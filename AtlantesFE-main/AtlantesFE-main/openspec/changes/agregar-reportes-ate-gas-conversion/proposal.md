# Proposal: Agregar reportes ATE Gas en frontend

## Intent
Agregar tres reportes de Conversión a Gas en el frontend Angular 17, reutilizando el comportamiento, estructura visual, permisos, filtros, tabla y exportación de Excel del Reporte Demanda existente.

## Scope
- Agregar Reporte de Status con código de permiso `108`.
- Agregar Reporte de Ingresos con código de permiso `106`.
- Agregar Reporte de Salidas con código de permiso `107`.
- Mostrar los enlaces dentro de `Reportes -> Conversión a GAS` en el componente `menulateral`.
- Consumir los endpoints backend existentes:
  - `/ate-gas/reporte-status/{idcliente}/{fecha_inicial}/{fecha_final}`
  - `/ate-gas/reporte-ingresos/{idcliente}/{fecha_inicial}/{fecha_final}`
  - `/ate-gas/reporte-salidas/{idcliente}/{fecha_inicial}/{fecha_final}`
- Construir columnas de tabla y Excel dinámicamente desde la respuesta del backend, salvo que se decida mapear columnas explícitas luego.

## Out of Scope
- Crear o modificar consultas backend.
- Cambiar permisos en base de datos.
- Cambiar el comportamiento existente del Reporte Demanda.

## Approach
Basarse completamente en `src/app/reporte-ate-gas-demanda/`, duplicando el patrón para tres nuevos componentes Angular, sus rutas, declaraciones en `app.module.ts`, métodos en `AlmacenesService`, flags de permisos en `menulateral.component.ts` y enlaces en `menulateral.component.html`.

Como los campos exactos de cada reporte vienen dados por la respuesta backend, la implementación debe renderizar columnas dinámicas tomando las llaves del primer registro recibido y usar esas mismas llaves para exportar a Excel.

## Risks and Open Questions
- Confirmar los nombres exactos de las propiedades de respuesta JSON para cada endpoint, por ejemplo `reporte_status`, `reporte_ingresos` o `reporte_salidas`.
- Si el backend devuelve fechas como string, validar si los filtros PrimeNG deben ser `text` o si se normalizarán a `Date`.
- Definir si los `menu_item` nuevos deben ser `13.4`, `13.5`, `13.6` o seguir otra convención interna.
