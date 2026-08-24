# Inventory VIN Billing Control - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato / Tabla | Uso observado |
| --- | --- |
| `inventario_facturacion_periodo` | Periodo de facturacion, cliente, fechas, confirmado/historico y auditoria. |
| `inventario_facturacion_chasis` | Chasis/VIN asociados a periodo de facturacion. |
| `fechaInicio` | Inicio del periodo, normalizado a dia 21. |
| `fechaFin` | Fin del periodo, dia 20 del mes siguiente. |
| `internacional` | Conteo de VIN internacional. |
| `nacional_local` | Conteo de VIN nacional/local. |
| `unicos` | Conteo de VIN unicos. |
| `facturables` | Conteo de VIN para facturar. |
| `tarifa` | Tarifa USD por unidad en consolidado. |
| `total_usd` | Total mensual en USD. |
| `total_bs` | Total mensual en bolivianos. |

## Endpoints API observados

| Endpoint | Uso |
| --- | --- |
| `inventario/reportes/lista-facturacion-chasis` | Lista consolidado mensual. |
| `inventario/reportes/facturacion-chasis` | Genera precalculo de VIN facturables. |
| `inventario/reportes/confirmar-facturacion-chasis` | Confirma facturacion del periodo. |
| `inventario/reportes/info-facturacion-excel/{id}` | Genera Excel de detalle del periodo. |
