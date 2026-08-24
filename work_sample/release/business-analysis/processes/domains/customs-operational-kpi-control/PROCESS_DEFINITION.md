# Customs Operational KPI Control - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Medir el cumplimiento operacional aduanero y logistico mediante reportes de seguimiento, controles AD/OL y KPIs de tiempos entre hitos: solicitud, envio AP, envio DAM, documentacion requerida, pago DIM, validacion, planillaje, nacionalizacion y entrega.

## Alcance observado

- Reporte KPI por fecha de asignacion/solicitud para clientes vehiculares observados.
- Calculo de dias laborales con `DiasLaborales` y `sp_workdaydiff`.
- Evaluacion `CUMPLE` / `NO CUMPLE` para envio AP, envio DAM y requerimientos.
- Capacidades fijas observadas: `200` unidades para AP, DAM y requerimientos.
- Control AD con EDP, costos, prevision de tributos e indicadores de nacionalizacion.
- Control OL con EDP, operador, proveedor, agente, inhouse, estados y asignaciones de viaje.
- Reporte de seguimiento aduanero con costos, tributos, planilla, tiempos y KPI.
- Registro de visualizacion/descarga mediante `LogReportes.php` en reportes operativos.

## Fuera de alcance observado

- Creacion primaria de embarques y estados EDP.
- Ejecucion de viajes, documentada en dominios de tracking/export.
- Facturacion/cobranza y costos contables detallados.
- Catalogo oficial de umbrales y SLA.
- Validacion de dashboards embebidos por cliente.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario operativo | Consulta cumplimiento, atrasos y tiempos de procesos. |
| Coordinador/Inhouse | Filtra y analiza embarques/casos bajo responsabilidad. |
| ASGARD | Calcula KPI, estados, diferencias de dias y reportes. |
| Cliente | Limita el alcance de reportes por sesion y filtros. |

## Entradas

- Cliente de sesion.
- Rango de fechas.
- Filtros de proveedor, agente, linea, modalidad, coordinador, operador, regimen, almacen, aduana, tipo producto y estado.
- Hitos EDP en `logis_edp`.
- Fechas de caso: solicitud, validacion DUI, pago DUI, documentacion completa, canal, entrega almacen, planilla.
- Datos de costos, tributos y prevision.

## Salidas

- Reporte KPI de solicitud/AP/DAM/requerimiento.
- Control AD con hitos aduaneros, costos, prevision e indicadores.
- Control OL con estado de embarque, operador y viajes.
- Reporte de seguimiento con costos, tiempos y KPI.
- Excel de reportes.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/operativos/kpis.php` | UI KPI y logging de visualizacion/exportacion. |
| `index_archivos/operativos/kpisquery.php` | Calcula AP, DAM y requerimientos con capacidad y CUMPLE/NO CUMPLE. |
| `index_archivos/operativos/reportecontrolad.php` y `reportecontroladquery.php` | Control aduanero con EDP, prevision tributos e indicadores. |
| `index_archivos/operativos/reportecontrolol.php` y `reportecontrololquery.php` | Control logistico/operador con estados EDP y viajes. |
| `index_archivos/operativos/reporteseguimiento.php` y `reporteseguimientoquery.php` | Seguimiento aduanero con costos y tiempos de servicio. |
| `.data_base/asgard.sql` | Funciones/procedimientos de dias laborales, vistas y tablas de casos/EDP/costos. |

## Criterios de aceptacion candidatos

- Los reportes deben limitarse al cliente de sesion cuando aplica.
- Casos anulados y embarques eliminados se excluyen.
- KPI AP/DAM/REQ usa capacidad observada de 200 unidades.
- Para cantidades hasta capacidad, el umbral observado es 1 dia; para el siguiente tramo, 2 dias.
- Los KPI se calculan en dias laborales.
- Control AD/OL debe derivar fechas desde EDP y aplicar filtros de negocio seleccionados.
- Reportes deben registrar uso cuando el codigo llama a `LogReportes.php`.

