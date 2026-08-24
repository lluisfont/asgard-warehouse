# Design: Fecha/hora por ciudad en Angular 17

## Contexto actual
La aplicación Angular 17 usa `jwt-decode` y muchos componentes leen `this._usuarioService.getTokenDetalle()`. La pantalla `ciudades` administra `codigo`, `ciudad`, `modotransporte` y `pais`, pero todavía no maneja zona horaria.

## Decisión principal
Crear un contexto horario frontend basado en el JWT:

```ts
export interface UserTimeContext {
  idciudad: number;
  timezone_name: string;
  utc_offset_minutos: number;
}
```

`UsuarioService.getTokenDetalle()` debe retornar estos campos cuando estén disponibles en el token. Además, se recomienda agregar métodos:

```ts
getTimezoneName(): string {
  return this.getTokenDetalle()?.timezone_name ?? 'America/La_Paz';
}

getUtcOffsetMinutes(): number {
  return Number(this.getTokenDetalle()?.utc_offset_minutos ?? -240);
}
```

## Servicio helper propuesto
Crear `src/app/services/date-time-context.service.ts` o ampliar `UsuarioService`:

```ts
@Injectable({ providedIn: 'root' })
export class DateTimeContextService {
  constructor(private usuarioService: UsuarioService) {}

  timezoneName(): string {
    return this.usuarioService.getTokenDetalle()?.timezone_name ?? 'America/La_Paz';
  }

  utcOffsetMinutes(): number {
    return Number(this.usuarioService.getTokenDetalle()?.utc_offset_minutos ?? -240);
  }

  formatDateOnly(value: string | null | undefined): string {
    if (!value) return '';
    return value.substring(0, 10);
  }

  toDateFilterValue(value: string | Date): string {
    if (typeof value === 'string') return value.substring(0, 10);
    const year = value.getFullYear();
    const month = String(value.getMonth() + 1).padStart(2, '0');
    const day = String(value.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }
}
```

Evitar convertir filtros de fecha a `toISOString()` porque eso usa UTC y puede mover el día.

## UI de ciudades
Actualizar `ciudades.component.ts` y `ciudades.component.html`:

- Agregar propiedades `timezone_name` y `utc_offset_minutos`.
- Cargar estos campos desde `response.ciudades`.
- En `prepararDatos(0)`, usar valores por defecto según país o `America/La_Paz` / `-240`.
- En `guardarDatos()`, enviar ambos campos al backend.
- Validar que `utc_offset_minutos` sea numérico.

Lista inicial sugerida:

```ts
zonasHorarias = [
  { label: 'Bolivia (UTC-04)', timezone_name: 'America/La_Paz', utc_offset_minutos: -240 },
  { label: 'Perú (UTC-05)', timezone_name: 'America/Lima', utc_offset_minutos: -300 }
];
```

## Reglas frontend

1. Fechas calendario: enviar `YYYY-MM-DD`.
2. Fechas con hora de negocio: enviar string local `YYYY-MM-DD HH:mm:ss` o `YYYY-MM-DDTHH:mm` según contrato actual del endpoint.
3. Fechas auditables UTC: solo mostrar convertidas cuando el backend indique que son UTC.
4. No inferir país por dominio o servidor; usar JWT/ciudad.

## Validación
- Login Bolivia y Perú muestran el mismo menú y permisos, pero distinto contexto horario.
- Ciudad permite guardar `America/Lima` y `America/La_Paz`.
- Reportes no pierden registros al consultar rangos alrededor de medianoche.
- Formularios que usan fecha no cambian de día por zona horaria del navegador.
