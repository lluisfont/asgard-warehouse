# Design: Fecha/hora por ciudad en backend Slim 4

## Contexto actual
El backend PHP 7.4 con Slim 4 usa JWT (`firebase/php-jwt`) y rutas grandes bajo `app/routes`. El login en `usuarios.php` ya incluye `idciudad` en el payload. Existen usos de `date()`, `new DateTime()`, `CURRENT_TIMESTAMP()` y SQL con campos `fecha`, `hora`, `fechasistema`, `horasistema` y `datetime`.

Actualmente la diferencia Bolivia/Perú se resuelve por configuración del servidor (`UTC-04` vs `UTC-05`). Al fusionar servidores, esa estrategia deja de ser válida.

## Decisión principal
Usar la ciudad del usuario como fuente de zona horaria:

- `t_usuario.idciudad` -> `t_ciudad.idciudad`
- `t_ciudad.timezone_name`: nombre IANA (`America/La_Paz`, `America/Lima`)
- `t_ciudad.utc_offset_minutos`: offset estándar esperado (`-240`, `-300`)

`timezone_name` será la fuente principal porque maneja reglas reales de zona horaria. `utc_offset_minutos` se mantiene como dato simple para validación, filtros y compatibilidad con frontend.

## Cambios de base de datos

```sql
ALTER TABLE t_ciudad
  ADD COLUMN timezone_name VARCHAR(64) NOT NULL DEFAULT 'America/La_Paz' AFTER pais,
  ADD COLUMN utc_offset_minutos SMALLINT NOT NULL DEFAULT -240 AFTER timezone_name;

UPDATE t_ciudad
SET timezone_name = 'America/La_Paz', utc_offset_minutos = -240
WHERE pais IN ('BO', 'Bolivia', 'BOL') OR ciudad IN ('Santa Cruz', 'La Paz', 'Cochabamba');

UPDATE t_ciudad
SET timezone_name = 'America/Lima', utc_offset_minutos = -300
WHERE pais IN ('PE', 'Peru', 'Perú', 'PER') OR ciudad IN ('Lima', 'Callao');

CREATE INDEX idx_t_ciudad_timezone ON t_ciudad (timezone_name);
```

Para auditoría nueva o eventos técnicos, agregar columnas UTC solo donde se requiera trazabilidad de instante:

```sql
-- Ejemplo por tabla crítica, aplicar caso por caso.
ALTER TABLE t_ingreso
  ADD COLUMN fechasistema_utc DATETIME NULL AFTER fechasistema,
  ADD COLUMN timezone_name VARCHAR(64) NULL AFTER fechasistema_utc;
```

No se recomienda convertir masivamente todos los `datetime` existentes sin clasificar su significado.

## Utilidad backend propuesta
Crear `app/services/DateTimeService.php`:

```php
<?php

final class DateTimeService
{
    public static function timezoneFromJwt(array $claims): DateTimeZone
    {
        $name = $claims['timezone_name'] ?? 'America/La_Paz';
        return new DateTimeZone($name);
    }

    public static function nowLocal(array $claims): DateTimeImmutable
    {
        return new DateTimeImmutable('now', self::timezoneFromJwt($claims));
    }

    public static function nowUtc(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    public static function formatMysql(DateTimeImmutable $dateTime): string
    {
        return $dateTime->format('Y-m-d H:i:s');
    }

    public static function localMysqlNow(array $claims): string
    {
        return self::formatMysql(self::nowLocal($claims));
    }

    public static function utcMysqlNow(): string
    {
        return self::formatMysql(self::nowUtc());
    }
}
```

## Login / JWT
Modificar la consulta de login en `app/routes/usuarios.php` para unir `t_ciudad` y agregar al payload:

```php
'city_timezone_name' => $row['timezone_name'], // o timezone_name
'utc_offset_minutos' => (int) $row['utc_offset_minutos'],
```

Recomendación: usar nombres consistentes `timezone_name` y `utc_offset_minutos` para evitar lógica duplicada.

## Middleware opcional
Crear un middleware que decodifique el JWT una vez, valide la zona y agregue claims al request:

```php
$request = $request->withAttribute('auth', $decoded_array);
$request = $request->withAttribute('timezone', new DateTimeZone($decoded_array['timezone_name'] ?? 'America/La_Paz'));
```

Así las rutas no repiten `JWT::decode` ni dependen de `apache_request_headers()`.

## Reglas de guardado

1. Fechas de negocio visibles por ciudad: guardar como `DATETIME` local de la ciudad del usuario.
2. Auditoría técnica: guardar UTC en `DATETIME` o `TIMESTAMP` dedicado y, si aplica, el `timezone_name` usado.
3. Fechas calendario puras: guardar como `DATE`, no convertir por zona horaria.
4. Horas separadas (`hora`, `horasistema`): generar desde la misma instancia local para evitar fecha/hora inconsistentes.

## Reemplazos prioritarios

- `date("Y-m-d H:i:s")` -> `DateTimeService::localMysqlNow($claims)`
- `date("Y-m-d")` para fecha local -> `DateTimeService::nowLocal($claims)->format('Y-m-d')`
- `date("H:i:s")` para hora local -> `DateTimeService::nowLocal($claims)->format('H:i:s')`
- `new DateTime()` -> `DateTimeService::nowLocal($claims)` o `nowUtc()` según caso
- `CURRENT_TIMESTAMP()` / `NOW()` en inserts/updates -> parámetro generado en PHP o `UTC_TIMESTAMP()` para auditoría UTC

## Testing
- Login Bolivia emite `America/La_Paz` y `-240`.
- Login Perú emite `America/Lima` y `-300`.
- Un mismo servidor guarda la fecha local correcta para usuarios de ambas ciudades.
- Reportes por rango de fecha siguen filtrando por día local.
- No se altera la semántica de fechas históricas.
