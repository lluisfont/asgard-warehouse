# Delta for Fecha/Hora Backend

## ADDED Requirements

### Requirement: Zona horaria por ciudad
El sistema SHALL almacenar para cada ciudad una zona horaria operativa mediante `timezone_name` IANA y `utc_offset_minutos`.

#### Scenario: Usuario de Bolivia inicia sesión
- GIVEN un usuario relacionado a una ciudad con `timezone_name = "America/La_Paz"` y `utc_offset_minutos = -240`
- WHEN el usuario inicia sesión correctamente
- THEN el JWT SHALL incluir `idciudad`, `timezone_name` y `utc_offset_minutos`.

#### Scenario: Usuario de Perú inicia sesión
- GIVEN un usuario relacionado a una ciudad con `timezone_name = "America/Lima"` y `utc_offset_minutos = -300`
- WHEN el usuario inicia sesión correctamente
- THEN las fechas generadas por backend SHALL usar la zona horaria de esa ciudad para valores locales de negocio.

### Requirement: Utilidad central de fecha/hora
El backend SHALL usar una utilidad central para obtener fecha/hora local del usuario, UTC y conversiones entre ambas.

#### Scenario: Endpoint autenticado genera una fecha de sistema
- GIVEN un request autenticado con JWT que contiene `timezone_name`
- WHEN el endpoint necesita registrar fecha/hora actual
- THEN SHALL llamar a la utilidad central y SHALL NOT depender de `date_default_timezone_set`, `date()` directo, `new DateTime()` sin zona, `NOW()` ni `CURRENT_TIMESTAMP()`.

### Requirement: Compatibilidad con columnas datetime existentes
El sistema SHALL mantener compatibilidad con columnas `datetime` actuales y SHALL documentar cuáles representan fecha local de negocio y cuáles representan instantes auditables.

#### Scenario: Campo de documento operativo
- GIVEN un campo `datetime` que representa la fecha local visible de una operación o documento
- WHEN se guarde el valor
- THEN se SHALL guardar en hora local de la ciudad del usuario para conservar reportes y cortes diarios.

#### Scenario: Campo de auditoría nuevo
- GIVEN un nuevo evento de auditoría o trazabilidad
- WHEN se guarde el valor
- THEN el sistema SHOULD guardar UTC en un campo `*_utc` o equivalente junto con la zona horaria usada.

## MODIFIED Requirements

### Requirement: Login y payload JWT
El login SHALL enriquecer el payload JWT con datos de zona horaria obtenidos desde `t_ciudad`.
(Previously: el JWT incluía `idciudad`, pero no garantizaba zona horaria ni offset.)

#### Scenario: Login exitoso
- GIVEN credenciales válidas
- WHEN se construye el payload JWT
- THEN la consulta de usuario SHALL unir `t_ciudad` y retornar `timezone_name` y `utc_offset_minutos`.

### Requirement: Administración de ciudades
Los endpoints de ciudades SHALL permitir consultar, crear y actualizar `timezone_name` y `utc_offset_minutos`.
(Previously: ciudades manejaba `codigo`, `ciudad`, `modotransporte`, `pais`, `parametrizacion` e `idaduana`.)

#### Scenario: Crear ciudad
- GIVEN un administrador crea una ciudad
- WHEN envía los datos de ciudad
- THEN el backend SHALL validar que `timezone_name` sea un identificador permitido y que `utc_offset_minutos` sea coherente.
