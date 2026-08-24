# Customs Request Intake - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| Tabla / Fuente | Uso candidato | Evidencia |
| --- | --- | --- |
| `dav_solicitudesprevias` | Staging de solicitudes importadas por cliente/usuario, validacion y errores. | `SolicitudClass.php:90-118`, `SolicitudClass.php:461-465` |
| `dav_casosprevios` | Cabecera de solicitud/caso previo. | `SolicitudClass.php:482-496`, `.data_base/asgard.sql:2214-2244` |
| `dav_documentosprevios` | Documentos iniciales o asociados al caso previo. | `SolicitudClass.php:502-505`, `.data_base/asgard.sql:5484-5510` |
| `dav_otrosdocumentosprevios` | Otros documentos de solicitud; envio y estado. | `finsolicitud.php:375`, `.data_base/asgard.sql:7839-7849` |
| `dav_tramites` | Tramites iniciales creados desde solicitud. | `SolicitudClass.php:509` |
| `dav_cliente`, `dav_proveedor`, `dav_transportista`, `dav_usuario`, `dav_regimen`, `dav_aduana` | Maestros de validacion y enriquecimiento. | `SolicitudClass.php:147-229` |
| `dav_mailsolicitudes`, `dav_transportistamail`, `dav_clienteusuarios` | Destinatarios de notificacion. | `finsolicitud.php:392-515` |

## Datos Criticos

- Pedido, orden de compra, fechas de embarque/llegada, proveedor, transportista, aduana, regimen.
- Correos de solicitante y destinatarios.
- Mensajes de error por fila importada.
