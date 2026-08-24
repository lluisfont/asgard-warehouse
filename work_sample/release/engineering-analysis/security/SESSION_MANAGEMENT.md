# Session Management

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Creacion de sesion observada

| Flujo | Evidencia | Observacion |
| --- | --- | --- |
| Login primario sin MFA | `veriflogin.php:89-116` | Regenera session id, setea `idcliente`, `idclienteusuarios`, tipos y JWT. |
| Login con MFA | `2fa/TwoFaClass.php:132-218` | Hace `session_start`, setea contexto y JWT, pero no se observa `session_regenerate_id(true)` en ese metodo. |
| Gate general | `permisos.php` | Redirige si no existe `$_SESSION['idcliente']`. |
| Cambio password | `cambiocontrasena.php` | Requiere sesion y password actual para actualizar hash. |

## Valores sensibles de sesion

- `idcliente`, `idclienteusuarios`.
- `tipo_usuario`, `tipo_usuario_mejora_continua`.
- `tokenJWT`, `tokenJWT_Atlantes`.
- `idalmacen_atlantes`, `idcliente_almacen`.
- `intentos_code` para MFA.

## Riesgos candidatos

- En MFA no se observa regeneracion de id de sesion despues del exito.
- `session.cookie_lifetime` se configura despues de `session_start` en MFA.
- La IP de auditoria confia en `HTTP_X_FORWARDED_FOR`.
- El control de sesion duplicada se apoya en datos de sesion/navegador, no en un token server-side unico observado.
- Los tokens JWT quedan en sesion PHP y usan secretos hardcoded observados.

## Controles recomendados

- Regenerar session id en todos los caminos de autenticacion exitosa.
- Centralizar configuracion de cookie antes de `session_start`.
- Validar proxy confiable antes de usar `X-Forwarded-For`.
- Definir idle timeout server-side y revocacion de sesion.
- Evitar master password global salvo mecanismo break-glass auditado.
