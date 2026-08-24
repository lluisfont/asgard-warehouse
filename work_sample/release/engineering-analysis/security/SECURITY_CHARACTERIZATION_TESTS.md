# Security Characterization Tests

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

Estas pruebas son candidatas para un entorno controlado. No deben ejecutarse contra produccion sin autorizacion.

| ID | Prueba candidata | Resultado esperado |
| --- | --- | --- |
| SCT-001 | Login primario con CSRF ausente o invalido. | HTTP 403 y sin cambios de sesion. |
| SCT-002 | Login primario exitoso. | `session_regenerate_id`, cookies seguras y sesion con tenant correcto. |
| SCT-003 | MFA con payload `u` alterado. | Rechazo por firma/integridad; si no existe, registrar defecto. |
| SCT-004 | MFA con codigo valido de otro correo/usuario. | Rechazo por binding usuario/correo. |
| SCT-005 | MFA con mas de 3 intentos. | Bloqueo consistente por usuario y auditoria. |
| SCT-006 | `download.php` con `p=../...`. | Rechazo por ruta no permitida. |
| SCT-007 | Upload documental con extension ejecutable y MIME falso. | Rechazo y no persistencia. |
| SCT-008 | Upload ZIP con paths `../`. | Rechazo/extraccion segura. |
| SCT-009 | Endpoint AJAX mutador sin `permisos.php`/sesion. | 401/403 sin mutacion. |
| SCT-010 | Endpoint mutador con recurso de otro `idcliente`. | 403 sin lectura ni mutacion. |
| SCT-011 | SQL payload en parametros legacy comunes. | Sin error SQL ni efecto; consultas parametrizadas. |
| SCT-012 | Redireccion `ultimoenlace` externa. | Rechazo o normalizacion a ruta interna. |
| SCT-013 | Token SendGrid/JWT/SFTP inexistente en repo/artefactos. | Secret scan limpio. |
| SCT-014 | Pusher/notificacion para destinatario no autorizado. | No se entrega toast ni enlace. |
