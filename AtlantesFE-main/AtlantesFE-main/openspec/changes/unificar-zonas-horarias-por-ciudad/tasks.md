# Tasks

## 1. Contexto de usuario
- [x] 1.1 Actualizar modelo/uso de `getTokenDetalle()` para incluir `timezone_name` y `utc_offset_minutos`.
- [x] 1.2 Crear helper o servicio central para formato y envío de fechas.
- [x] 1.3 Reemplazar conversiones con `toISOString()` en filtros de fecha si existen.

## 2. Pantalla de ciudades
- [x] 2.1 Agregar propiedades `timezone_name` y `utc_offset_minutos` a `ciudades.component.ts`.
- [x] 2.2 Agregar controles en `ciudades.component.html` para seleccionar zona horaria.
- [x] 2.3 Enviar los nuevos campos en `addciudad` y `saveciudad`.
- [x] 2.4 Mostrar zona horaria en la tabla/listado de ciudades.

## 3. Componentes con fechas
- [x] 3.1 Inventariar componentes que usan `new Date`, `Date`, `toLocale*`, `substring` de fechas o inputs `datetime-local`.
- [x] 3.2 Priorizar ingresos, salidas, reportes de almacén, facturas, cobros y pagos.
- [x] 3.3 Normalizar envío de filtros por día como `YYYY-MM-DD`.
- [ ] 3.4 Normalizar presentación de fecha/hora con el helper central.

## 4. Validación
- [ ] 4.1 Probar sesión Bolivia y Perú contra el mismo backend.
- [ ] 4.2 Probar creación/edición de ciudades con ambas zonas.
- [ ] 4.3 Probar reportes con rangos de fecha cerca de medianoche.
- [x] 4.4 Ejecutar `npm test` o validación manual si las pruebas no están configuradas.
- [ ] 4.5 Validar artefactos OpenSpec si el CLI está disponible.
