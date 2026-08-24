# Customer Primary Login Session Audit - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Estados candidatos

| Estado | Significado candidato | Evidencia |
| --- | --- | --- |
| Solicitud recibida | Login POST recibido con CSRF y credenciales. | `veriflogin.php:13-31` |
| Usuario no encontrado | No existe username en `dav_clienteusuarios`. | `veriflogin.php:240-243` |
| Usuario bloqueado | `intentos=5` y no pasaron 24 horas desde `fechabloqueo`. | `veriflogin.php:58-75` |
| Credencial fallida | Password no coincide con usuario ni master password. | `veriflogin.php:181-237` |
| Usuario inactivo | Credencial coincide pero `activo=0`. | `veriflogin.php:81-84` |
| Sesion cruzada | Ya existe sesion de otro cliente en el navegador. | `veriflogin.php:87-98` |
| Requiere MFA | Credencial valida y flag `2fa` activo. | `veriflogin.php:178-180` |
| Autenticado | Sesion y JWT creados. | `veriflogin.php:100-113` |
| Password vencida | `fechacontrasena + 90 dias` superado. | `veriflogin.php:59`, `veriflogin.php:166-173` |

## Transiciones observadas

| Desde | Hacia | Condicion |
| --- | --- | --- |
| Solicitud recibida | Usuario no encontrado | Username no existe. |
| Solicitud recibida | Usuario bloqueado | `bloqueado=1`. |
| Solicitud recibida | Credencial fallida | Hash y master password no validan. |
| Credencial fallida | Usuario bloqueado | Intentos llega a `5`. |
| Solicitud recibida | Usuario inactivo | Credencial valida pero `activo=0`. |
| Solicitud recibida | Sesion cruzada | Sesion existente pertenece a otro cliente. |
| Solicitud recibida | Requiere MFA | Credencial valida y `2fa` activo. |
| Solicitud recibida | Autenticado | Credencial valida, usuario activo y sin 2FA. |
| Autenticado | Password vencida | `cambiarcontrasena=1`. |
