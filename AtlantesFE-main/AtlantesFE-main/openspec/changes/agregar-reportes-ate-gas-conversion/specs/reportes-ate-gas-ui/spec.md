# Delta for Reportes ATE Gas UI

## ADDED Requirements

### Requirement: Enlaces de reportes en Conversión a Gas
El sistema SHALL mostrar los enlaces de Reporte de Status, Reporte de Ingresos y Reporte de Salidas dentro del submenú `Reportes -> Conversión a GAS`, respetando permisos por módulo.

#### Scenario: Usuario con permisos ve reportes nuevos
- GIVEN un usuario con permisos de lectura para los módulos `108`, `106` y `107`
- WHEN abre el menú lateral y navega a `Reportes -> Conversión a GAS`
- THEN el sistema muestra los enlaces `Reporte de Status`, `Reporte de Ingresos` y `Reporte de Salidas`
- AND cada enlace navega a su ruta Angular correspondiente.

#### Scenario: Usuario sin permiso no ve un reporte
- GIVEN un usuario sin permiso de lectura para el módulo `108`
- WHEN abre el submenú `Reportes -> Conversión a GAS`
- THEN el sistema no muestra el enlace `Reporte de Status`.

### Requirement: Reporte de Status
El sistema SHALL permitir generar el Reporte de Status usando el endpoint backend `/ate-gas/reporte-status/{idcliente}/{fecha_inicial}/{fecha_final}` y el permiso `108`.

#### Scenario: Generar Reporte de Status correctamente
- GIVEN un usuario con permiso `108`
- AND selecciona cliente, fecha inicial y fecha final
- WHEN presiona `Generar`
- THEN el frontend consulta el endpoint de Reporte de Status
- AND muestra los registros recibidos en una tabla paginada, filtrable y redimensionable
- AND permite exportar los datos visibles a Excel.

### Requirement: Reporte de Ingresos
El sistema SHALL permitir generar el Reporte de Ingresos usando el endpoint backend `/ate-gas/reporte-ingresos/{idcliente}/{fecha_inicial}/{fecha_final}` y el permiso `106`.

#### Scenario: Generar Reporte de Ingresos correctamente
- GIVEN un usuario con permiso `106`
- AND selecciona cliente, fecha inicial y fecha final
- WHEN presiona `Generar`
- THEN el frontend consulta el endpoint de Reporte de Ingresos
- AND muestra los registros recibidos en una tabla paginada, filtrable y redimensionable
- AND permite exportar los datos visibles a Excel.

### Requirement: Reporte de Salidas
El sistema SHALL permitir generar el Reporte de Salidas usando el endpoint backend `/ate-gas/reporte-salidas/{idcliente}/{fecha_inicial}/{fecha_final}` y el permiso `107`.

#### Scenario: Generar Reporte de Salidas correctamente
- GIVEN un usuario con permiso `107`
- AND selecciona cliente, fecha inicial y fecha final
- WHEN presiona `Generar`
- THEN el frontend consulta el endpoint de Reporte de Salidas
- AND muestra los registros recibidos en una tabla paginada, filtrable y redimensionable
- AND permite exportar los datos visibles a Excel.

### Requirement: Columnas dinámicas desde backend
El sistema SHALL construir las columnas de tabla y exportación Excel usando las llaves devueltas por el backend para cada reporte nuevo.

#### Scenario: Backend devuelve columnas variables
- GIVEN el backend responde con una lista de objetos para cualquiera de los reportes nuevos
- WHEN el frontend recibe la respuesta
- THEN usa las llaves del primer objeto como columnas de la tabla
- AND usa las mismas llaves para construir la cabecera y filas del Excel
- AND no requiere definir manualmente cada columna en el HTML.

### Requirement: Validación de filtros obligatorios
El sistema SHALL validar cliente, fecha inicial y fecha final antes de consultar cualquiera de los nuevos reportes.

#### Scenario: Faltan filtros requeridos
- GIVEN el usuario está en un reporte nuevo de Conversión a Gas
- AND no seleccionó cliente o fechas
- WHEN presiona `Generar`
- THEN el sistema muestra mensajes de campo requerido
- AND no llama al endpoint backend.
