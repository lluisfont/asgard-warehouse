# Advisory Management Services - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

## Estados de Solicitud Observados

| Estado candidato | Marcador / UI | Evidencia |
| --- | --- | --- |
| Pendiente | Pestaña `Pendientes`; `solicitudes.pendientes` habilita envio en la UI. | `tbl-estados.js:17-79`, `solicitud.js:61-80` |
| Enviado | Pestaña `Enviados`; `fecha_enviado` existe en schema. | `tbl-estados.js:17-79`, `.data_base/asgard.sql:390-436` |
| Recepcionado | Pestaña `Recepcionados`; fecha de recepcion aparece en reporte. | `tbl-estados.js:17-79`, `operativos/asesoria-gestion.php:67-212` |
| Asignado | Pestaña `Asignados`; `fecha_asignacion` y oficial asignado aparecen en reporte/carpeta. | `tbl-estados.js:17-79`, `.data_base/asgard.sql:285-324` |
| En revision | Pestaña `En Revision`; `fecha_inicio_revision` existe en schema. | `tbl-estados.js:17-79`, `.data_base/asgard.sql:390-436` |
| En proceso | Pestaña `En Proceso`; `fecha_inicio_proceso` existe en schema. | `tbl-estados.js:17-79`, `.data_base/asgard.sql:390-436` |
| Finalizado | Pestaña `Finalizado`; `fecha_finalizacion` existe en schema y reporte. | `tbl-estados.js:17-79`, `.data_base/asgard.sql:390-436` |
| Cerrado | `fecha_cierre` y `responsable_cierre_id` existen en schema/reporte. | `.data_base/asgard.sql:390-436`, `operativos/asesoria-gestion.php:67-212` |
| Anulado/eliminado | `deleted_at`, `motivo_anulacion` y `adjunto_anulacion`. | `.data_base/asgard.sql:390-436` |

## Observaciones

El catalogo exacto de valores numericos se infiere parcialmente desde UI y schema. El dump contiene una vista con mapeo de estados 1 a 8 que debe reconciliarse con `ages_estados`.
