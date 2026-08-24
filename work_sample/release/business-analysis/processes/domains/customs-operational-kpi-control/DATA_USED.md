# Customs Operational KPI Control - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Descripcion candidata | Fuente |
| --- | --- | --- |
| `dav_casosprevios.fechasolicitud` | Fecha de solicitud/asignacion base. | `kpisquery.php` |
| `dav_facturacomercial.fechaenvioap` | Fecha de confirmacion/envio AP. | `kpisquery.php` |
| `dav_facturacomercial.fechaenviodam` | Fecha de envio DAM. | `kpisquery.php` |
| `dav_facturacomercial.fechadocreq` / `fechaenvioreq` | Fechas para requerimiento documental. | `kpisquery.php` |
| `logis_edp.fecha` y `estado_edp_id` | Hitos operativos/aduaneros. | `reportecontroladquery.php`, `reportecontrololquery.php` |
| `logis_estados_edp.orden_etapa` | Orden para estado actual de embarque. | `reportecontrololquery.php` |
| `dav_casos.fechapagodui` | Fecha pago DIM/DUI. | `reporteseguimientoquery.php` |
| `dav_casos.fechavalidaciondui` | Fecha validacion DUI. | `reporteseguimientoquery.php` |
| `dav_facturaplanilla.fecha` | Fecha planilla/facturacion usada para KPI planilla. | `reporteseguimientoquery.php` |
| `dav_casosprevios.cif_bs`, porcentajes y gastos | Base de prevision de tributos. | `reportecontroladquery.php` |
| `tck_asignacion_viaje` | Cantidad/asignacion de viajes en control OL. | `reportecontrololquery.php` |

