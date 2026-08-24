# Identity Access / 2FA - Process Flow

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Flujo Principal Candidato

1. El usuario llega a `2fa.php` con un payload `u` codificado en base64.
2. `2fa.php` decodifica el usuario y obtiene su correo.
3. `TwoFaClass::enviarCorreo` genera un codigo numerico de seis digitos.
4. El sistema detecta IP del usuario e intenta resolver ubicacion con `ip-api.com`.
5. El sistema guarda el codigo en `dav_codigos_2fa`.
6. El sistema envia correo MFA al usuario.
7. El usuario introduce el codigo en pantalla.
8. La pantalla llama a `2fa/ajax/verificar-codigo.php`.
9. `TwoFaClass::verificarCodigo` busca el codigo activo para `tipo_usuario = 'CLIENTE'`.
10. Si el codigo existe y no han pasado mas de 600 segundos, el codigo queda invalidado con `deleted_at`.
11. La pantalla llama a `2fa/ajax/autenticar.php`.
12. `TwoFaClass::autenticar` crea variables de sesion y JWT.
13. El sistema actualiza intentos, bloqueo, ultima actividad y visitas.
14. El usuario es enviado a `principal.php` o a `cambiocontrasena.php`.

## Flujos Alternativos

- Reenvio de codigo: el boton de reenvio llama a `2fa/ajax/reenviar-codigo.php`, que decodifica el correo y vuelve a ejecutar `enviarCorreo`.
- Codigo expirado: si el codigo supera 600 segundos, se marca como eliminado y se devuelve error.
- Codigo invalido: se incrementa `$_SESSION['intentos_code']`.
- Usuario bloqueado: con mas de 3 intentos invalidos, se actualiza `dav_clienteusuarios.fechabloqueo` y `dav_clienteusuarios.intentos`.

## Evidencia

- `index_archivos/2fa.php:10-13`
- `index_archivos/2fa.php:61-118`
- `index_archivos/2fa/ajax/verificar-codigo.php:1-6`
- `index_archivos/2fa/ajax/autenticar.php:1-6`
- `index_archivos/2fa/ajax/reenviar-codigo.php:1-7`
- `index_archivos/2fa/TwoFaClass.php:34-38`
- `index_archivos/2fa/TwoFaClass.php:80-84`
- `index_archivos/2fa/TwoFaClass.php:86-130`
- `index_archivos/2fa/TwoFaClass.php:132-218`

## Riesgos Observados

- SQL construido por concatenacion/interpolacion en `TwoFaClass`.
- Claves JWT hardcodeadas en codigo.
- Uso de `file_get_contents` a `http://ip-api.com` sin TLS.
- El payload `u` viaja codificado en base64, no necesariamente firmado/cifrado en la evidencia observada.
