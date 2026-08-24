# Business Rules

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| ID | Rule | Evidence |
| --- | --- | --- |
| BR-CEC-001 | El codigo documental no puede repetirse si el documento no esta eliminado, excepto cuando el tipo documental es `3`. | `ControlCertificacionesController.php:11-13`, `ControlCertificacionesController.php:190-199` |
| BR-CEC-002 | El registro guarda fechas de emision/notificacion, vencimiento, plazo de alerta, unidad de alerta, extension y fecha de vencimiento extendida. | `ControlCertificacionesController.php:15-45`, `.data_base/asgard.sql:707-742` |
| BR-CEC-003 | Las mercancias pueden venir de datos manuales y/o de un Excel; ambos origenes se combinan antes de persistir. | `ControlCertificacionesController.php:57-107`, `ajax/registrar.php:10-29` |
| BR-CEC-004 | Los adjuntos se guardan por cliente bajo `control_certificaciones/{idcliente}` y se registran en `cc_archivos`. | `ControlCertificacionesController.php:168-187` |
| BR-CEC-005 | Al editar, se actualiza el registro, se reemplazan mercancias y, si hay archivos nuevos, se reemplazan archivos anteriores. | `ControlCertificacionesController.php:109-162`, `ajax/editar.php` |
| BR-CEC-006 | La consulta de control solo devuelve documentos del cliente, no eliminados, con filtros por codigo, tipo, estado calculado y mercancia. | `CommonController.php:57-91` |
| BR-CEC-007 | El estado documental se calcula como `S/N`, `VIGENTE`, `POR VENCER` o `VENCIDO` a partir de vencimiento, extension, plazo y unidad. | `.data_base/asgard.sql:39347-39399` |
| BR-CEC-008 | Si existe fecha de vencimiento extendida, esta reemplaza al vencimiento base para el calculo de estado. | `.data_base/asgard.sql:39356-39394` |
| BR-CEC-009 | El conteo de vencidos usa `fecha_vencimiento` si no hay extension y `fecha_vencimiento_extension` si existe. | `.data_base/asgard.sql:17722-17725` |
| BR-CEC-010 | Las notificaciones se envian a correos configurados para `tipo_notificacion_id = 1`. | `notificaciones/notificaciones.php:5-13` |
| BR-CEC-011 | La notificacion lista documentos cuyo estado no es `VIGENTE`; si el estado es `VENCIDO`, marca `notificacion_enviada = 1`. | `notificaciones/notificaciones.php:14-82` |
| BR-CEC-012 | Las autorizaciones previas vencen a los 180 dias de la fecha de emision para el control de AP. | `controllers/ControlAps.php:31-39`, `ajax/listaControlAps.php:20` |
| BR-CEC-013 | Los tipos documentales pueden ocultar el tipo `4` a usuarios que no esten en una lista explicita. | `CommonController.php:9-24` |
