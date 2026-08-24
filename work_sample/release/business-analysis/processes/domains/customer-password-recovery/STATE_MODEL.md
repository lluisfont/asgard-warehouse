# Customer Password Recovery - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Estados candidatos

| Estado | Significado candidato | Evidencia |
| --- | --- | --- |
| Sin solicitud | No hay recuperacion activa para el usuario/correo. | Ausencia de fila no eliminada. |
| Codigo generado | Existe fila en `dav_reseteos_passswords_clientes` con token y `deleted_at IS NULL`. | `generateToken` |
| Codigo enviado | SendGrid respondio `202`. | `sendEmailVerification` |
| Codigo validado | La fila se encontro por usuario/correo/token y no estaba eliminada. | `verifyToken` |
| Solicitud consumida | La fila fue marcada con `deleted_at`. | `verifyToken` |
| Contrasena actualizada | `dav_clienteusuarios` fue actualizado con bcrypt y desbloqueo. | `resetPasswordClient` |
| Solicitud rechazada | Usuario inexistente, envio fallido, token invalido o expirado. | Ramas de error observadas. |

## Transiciones observadas

| Desde | Hacia | Condicion |
| --- | --- | --- |
| Sin solicitud | Codigo generado | Usuario activo encontrado. |
| Codigo generado | Codigo enviado | SendGrid responde `202`. |
| Codigo generado | Solicitud rechazada | Error de envio o persistencia. |
| Codigo enviado | Codigo validado | Token coincide, no eliminado y vigente. |
| Codigo enviado | Solicitud rechazada | Token invalido, eliminado o expirado. |
| Codigo validado | Solicitud consumida | ASGARD actualiza `deleted_at`. |
| Solicitud consumida | Contrasena actualizada | Usuario define nueva contrasena y update exitoso. |
