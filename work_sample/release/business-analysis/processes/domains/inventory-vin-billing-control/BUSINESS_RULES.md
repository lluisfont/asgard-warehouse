# Inventory VIN Billing Control - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Regla | Descripcion | Evidencia |
| --- | --- | --- |
| BR-IVBC-001 | El periodo operativo de facturacion va del dia 21 al dia 20 del mes siguiente. | `facturacion-inventario.php` |
| BR-IVBC-002 | Si el dia actual es 21 o mayor, la fecha inicio maxima es el dia 21 del mes actual; si no, el 21 del mes anterior. | `obtenerMaxFechaInicio` |
| BR-IVBC-003 | La fecha inicio seleccionada se normaliza al dia 21. | `validarFechaInicio` |
| BR-IVBC-004 | El precalculo debe ejecutarse antes de mostrar Confirmar Facturacion. | `mostrarPrecalculo` |
| BR-IVBC-005 | Los KPIs observados son internacional, nacional/local, unicos y facturables. | `kpi` en Vue |
| BR-IVBC-006 | La confirmacion se envia con fecha inicio y fecha fin. | `confirmarFacturacion` |
| BR-IVBC-007 | Periodos y chasis facturados se registran en tablas de inventario facturacion. | Schema `inventario_facturacion_*` |
| BR-IVBC-008 | El Excel de detalle debe ser base64 valido antes de descargar. | `generarExcel` |

## Riesgos de regla pendientes

- Confirmar definicion oficial de VIN facturable.
- Confirmar tarifa y tipo de cambio usados para total USD/Bs.
- Confirmar si un chasis puede facturarse en mas de un periodo.
