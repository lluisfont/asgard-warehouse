# Authentication Architecture

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Componentes

- `login.php`: formulario inicial y CSRF.
- `veriflogin.php`: autenticacion primaria con PDO, password hash, bloqueo y sesion.
- `2fa.php`: pantalla MFA basada en payload `u`.
- `2fa/TwoFaClass.php`: envio/verificacion codigo, sesion post-MFA y JWT.
- `cambiocontrasena.php`: cambio forzado o manual de password.
- `resetpassword`: recuperacion de password.

## Datos

- `dav_clienteusuarios`: usuario, password hash, estado, intentos, 2FA, actividad.
- `dav_codigos_2fa`: codigos MFA.
- `master_pass`: password global/break-glass.
- `log_asgard_ecosistema`: auditoria de login.

## Riesgos clave

- Master password global.
- MFA con payload base64 sin firma visible.
- Secretos JWT hardcoded.
- Diferencia de controles entre login primario y MFA.
