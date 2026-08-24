# Evidence Map - customer-password-recovery

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| Afirmacion | Evidencia | Confianza |
| --- | --- | --- |
| El login expone recuperacion de contrasena para usuarios. | `index_archivos/login.php:151` | High |
| El primer paso captura nombre, apellido y usuario/correo. | `index_archivos/resetpassword/ajax/generateToken.php:7-9` | High |
| ASGARD verifica usuario cliente activo por username o correo. | `index_archivos/resetpassword/ResetPassword.php:271-284` | High |
| ASGARD genera codigo de 6 caracteres y registra solicitud. | `index_archivos/resetpassword/ResetPassword.php:10-21`, `ResetPassword.php:95-99` | High |
| El correo se envia mediante SendGrid y requiere estado 202. | `index_archivos/resetpassword/ResetPassword.php:101-268` | High |
| El codigo se valida por usuario/correo, token y `deleted_at IS NULL`. | `index_archivos/resetpassword/ResetPassword.php:45-63` | High |
| El reset actualiza password bcrypt, desbloqueo e intentos. | `index_archivos/resetpassword/ResetPassword.php:76-88` | High |
| Existe tabla especifica para reseteos de clientes. | `.data_base/asgard.sql:9660-9676` | High |

## Riesgos candidatos

- API key de SendGrid hardcodeada en codigo fuente.
- SQL interpolado con parametros de usuario en verificacion y actualizacion.
- La expiracion compara dia calendario, no un TTL exacto por horas/minutos.
- El flujo devuelve identificador de reset consumido que luego autoriza el cambio de contrasena.
