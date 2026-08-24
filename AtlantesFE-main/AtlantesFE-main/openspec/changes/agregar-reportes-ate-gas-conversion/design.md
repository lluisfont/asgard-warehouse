# Design: Reportes ATE Gas en Angular 17

## Current Architecture Context
El frontend ya tiene un patrón funcional para reportes de ATE Gas mediante `ReporteAteGasDemandaComponent`:

- Ruta: `/reporte-ate-gas-demanda`
- Permiso: módulo `105`
- Menú: `Reportes -> Conversión a GAS`
- Servicio: `AlmacenesService.reporteategasdemanda(...)`
- Filtros: cliente, fecha inicial, fecha final
- Vista: PrimeNG `p-table`
- Exportación: `ExportExcelService` con `ExcelModel`

## Proposed Approach
Crear tres componentes nuevos basados en Reporte Demanda:

- `reporte-ate-gas-status`
  - Clase: `ReporteAteGasStatusComponent`
  - Ruta: `/reporte-ate-gas-status`
  - Permiso: `108`
  - Endpoint: `ate-gas/reporte-status/{idcliente}/{fecha_inicial}/{fecha_final}`

- `reporte-ate-gas-ingresos`
  - Clase: `ReporteAteGasIngresosComponent`
  - Ruta: `/reporte-ate-gas-ingresos`
  - Permiso: `106`
  - Endpoint: `ate-gas/reporte-ingresos/{idcliente}/{fecha_inicial}/{fecha_final}`

- `reporte-ate-gas-salidas`
  - Clase: `ReporteAteGasSalidasComponent`
  - Ruta: `/reporte-ate-gas-salidas`
  - Permiso: `107`
  - Endpoint: `ate-gas/reporte-salidas/{idcliente}/{fecha_inicial}/{fecha_final}`

## Files to Update

### `src/app/services/almacenes.service.ts`
Agregar métodos:

- `reporteategasstatus(token, idcliente, fechainicial, fechafinal)`
- `reporteategasingresos(token, idcliente, fechainicial, fechafinal)`
- `reporteategassalidas(token, idcliente, fechainicial, fechafinal)`

Cada método debe seguir el mismo patrón de headers que `reporteategasdemanda`.

### `src/app/menulateral/menulateral.component.ts`
Agregar flags de permisos:

- `ver_reporte_ate_gas_status` con ids `[108]`
- `ver_reporte_ate_gas_ingresos` con ids `[106]`
- `ver_reporte_ate_gas_salidas` con ids `[107]`

### `src/app/menulateral/menulateral.component.html`
Dentro de `reportesAteGasSubmenu`, agregar enlaces para los tres reportes.

Sugerencia de `menu_item`:

- Demanda: `13.3` existente
- Status: `13.4`
- Ingresos: `13.5`
- Salidas: `13.6`

Actualizar `aria-expanded` y `ngClass` del submenú para contemplar estos nuevos valores o usar `parte_entera == 13` si está disponible y es consistente.

### `src/app/app.routing.ts`
Importar y registrar las rutas de los tres componentes.

### `src/app/app.module.ts`
Importar y declarar los tres componentes.

## Dynamic Columns
Como las columnas deben venir desde la respuesta del backend, cada componente debería mantener:

- `public columnas: Array<{field: string; header: string; tipo: string}> = [];`
- `public reporte: Array<any> = [];`

Al recibir respuesta:

1. Resolver el array de datos desde la propiedad esperada del response.
2. Si no se conoce la propiedad exacta, usar fallback al primer array encontrado en el objeto response.
3. Generar columnas con `Object.keys(this.reporte[0])`.
4. Convertir cada key a título legible reemplazando `_` por espacio y capitalizando.
5. Construir `reportexlsx` con esas columnas y datos.

## Response Resolution Recommendation
Para minimizar acoplamiento a nombres de propiedades backend:

```ts
private resolverDataReporte(response: any, propiedadEsperada: string): Array<any> {
  if (response && Array.isArray(response[propiedadEsperada])) {
    return response[propiedadEsperada];
  }

  if (Array.isArray(response)) {
    return response;
  }

  if (response && typeof response === 'object') {
    const key = Object.keys(response).find(k => Array.isArray(response[k]));
    return key ? response[key] : [];
  }

  return [];
}
```

## UI Recommendation
Para evitar duplicar HTML muy extenso, los nuevos componentes pueden usar un HTML dinámico:

- Header de tabla con `*ngFor="let col of columnas"`.
- Body con `*ngFor="let col of columnas"`.
- `p-columnFilter` tipo `text` por defecto.
- Para fechas, detectar campos que contengan `fecha`, `created_at`, `updated_at`, `inicio` o `fin` y formatear con `date:'dd/MM/yyyy HH:mm'` si el valor es parseable.

## Testing and Validation
Validar manualmente:

- Los tres enlaces aparecen solo con permisos correctos.
- Cada ruta carga sin error.
- Los filtros obligatorios funcionan.
- Cada reporte llama al endpoint correcto.
- Las columnas se generan desde la respuesta real.
- La exportación Excel incluye las mismas columnas y filas mostradas.

## Alternatives Considered
- Crear un único componente parametrizable para los tres reportes. Es más limpio, pero representa un refactor mayor. Para mantener bajo riesgo y seguir el patrón existente, se recomienda duplicar el patrón de Demanda primero.
