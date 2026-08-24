# Evidence Map - certification-expiry-control

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| The domain registers certification/control documents with expiry, alert and extension fields. | `ControlCertificacionesController.php:15-45`, `.data_base/asgard.sql:707-742` | High |
| Duplicate code validation blocks repeated codes except document type `3`. | `ControlCertificacionesController.php:11-13`, `ControlCertificacionesController.php:190-199` | High |
| Merchandise can be loaded from Excel and merged with manually submitted merchandise. | `ControlCertificacionesController.php:57-107` | High |
| Attachments are stored under a customer-specific certification path and recorded in `cc_archivos`. | `ControlCertificacionesController.php:168-187` | High |
| Search uses customer scope, code/type filters, computed status and merchandise filters. | `CommonController.php:57-91`, `ajax/control-certificaciones.php:1-11` | High |
| Document state is calculated by SQL function `f_estado_documento`. | `.data_base/asgard.sql:39347-39399` | High |
| Expired count is provided by `v_certificados_vencidos`. | `.data_base/asgard.sql:17722-17725`, `ajax/cetificados-vencidos.php` | High |
| Notification process emails documents that are not `VIGENTE` and marks expired records as notified. | `notificaciones/notificaciones.php:5-82` | High |
| Additional scheduled notification variants exist for guarantee-bond, monthly and weekly certification alerts. | `notificaciones-boleta-garantia.php`, `notificaciones_mensuales.php`, `notificaciones_semanales.php` | Medium |
| Prior authorizations use `dav_autorizacionprevia` by chassis and calculate 180-day expiry. | `controllers/ControlAps.php:31-39`, `.data_base/asgard.sql:1288-1300` | High |
| Type-document visibility restricts type `4` for most users. | `CommonController.php:9-24` | Medium |

## Graphify Use

Graphify output is supporting context only. Direct PHP and SQL evidence is authoritative for this domain.
