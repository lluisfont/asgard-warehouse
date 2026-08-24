# Advisory Management Services - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| Entidad / Tabla | Uso candidato | Evidencia |
| --- | --- | --- |
| `ages_solicitudes_asesoria_gestion` | Cabecera de solicitud: solicitante, cliente, caso, caso previo, embarque, linea, notas, estado, exchange, fechas de ciclo y cierre. | `.data_base/asgard.sql:388-436`, `solicitud.js:150-260` |
| `ages_asesoria_gestion_carpetas` | Carpeta GE asociada: carpeta, DUI, recepcion, chasis, ciudad, cliente, agencia, propietario, estado, asignacion y facturacion. | `.data_base/asgard.sql:285-324`, `operativos/asesoria-gestion.php:67-212` |
| `ages_estados` | Catalogo de estados de solicitud. | `.data_base/asgard.sql:324-334` |
| `ages_etapa` | Catalogo de etapas de carpeta/tramite. | `.data_base/asgard.sql:334-344` |
| `dav_tramites` | Tramites asociados a solicitud/caso previo, entidad emisora, tipo y oficial. | `tramite.js:1-130`, `logistica/SolicitudesClass.php:714-850` |
| `ages_observaciones_tramites` | Observaciones de tramite con tipo, detalle, adjunto y auditoria. | `.data_base/asgard.sql:344-361` |
| `ages_pagos_detalle` | Detalle de pagos/costos vinculados a carpeta AGES. | `.data_base/asgard.sql:361-388` |
| `ages_valoraciones_costos` / `ages_valoracion_costo_parametros` | Parametros de costo de gestion por cliente, entidad, servicio y rango. | `.data_base/asgard.sql:461-505` |
| `dav_casosprevios` | Caso previo creado desde gestion aduanera masiva o logistica. | `SolicitudClass.php:481-520`, `logistica/SolicitudesClass.php:714-850` |
| `intercambiodocumental.exchanges` / `exchange_id` | Intercambio documental vinculado a solicitud de servicio adicional. | `tbl-estados.js:220-260`, `solicitud.js:150-260` |

## Datos de Reporte

El reporte operativo publica, entre otros: numero de solicitud, estado, solicitante, cliente, ciudad, entidad, tramite, tipo, notas, fechas de recepcion/asignacion/revision/proceso/finalizacion/cierre, responsable de cierre, solicitud GA asociada, embarque, factura comercial, monto facturado, costo de gestion y categoria.

Evidencia: `operativos/asesoria-gestion.php:67-212`.
