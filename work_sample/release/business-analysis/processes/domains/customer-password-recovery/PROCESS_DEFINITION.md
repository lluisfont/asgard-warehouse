# Customer Password Recovery - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Recuperacion de contrasena de usuario cliente mediante codigo de verificacion.

Objetivo de negocio candidato: permitir que un usuario cliente recupere acceso a ASGARD cuando olvida su contrasena, generando un codigo de un solo uso, enviandolo por correo y habilitando el cambio de contrasena si el codigo es valido.

## Trigger

El proceso inicia cuando el usuario selecciona "olvide mi contrasena" desde `login.php` y completa el formulario de recuperacion.

Evidencia:

- `index_archivos/login.php:151`
- `index_archivos/resetpassword/ajax/generateToken.php`
- `index_archivos/resetpassword/ResetPassword.php:10-42`

## Actores

- Usuario cliente: solicita el codigo, lo introduce y define nueva contrasena.
- Sistema ASGARD: verifica usuario activo, genera codigo, guarda solicitud, valida vigencia y actualiza credencial.
- Servicio SendGrid: envia el correo con codigo de verificacion.
- Responsable tecnico en copia oculta: recibe BCC observado en el envio.

## Precondiciones

- Existe un usuario activo en `dav_clienteusuarios` con `username` o `correo` coincidente.
- La tabla `dav_reseteos_passswords_clientes` existe y acepta correo, token, solicitante, usuario cliente y timestamps.
- El servicio de correo esta disponible y acepta la API key configurada.

## Resultado Esperado

- ASGARD registra una solicitud de recuperacion con codigo alfanumerico de 6 caracteres.
- El usuario recibe el codigo por correo.
- Si el codigo coincide y no esta eliminado, ASGARD invalida la solicitud marcando `deleted_at`.
- Con identificador validado, ASGARD actualiza `dav_clienteusuarios.password` usando bcrypt, limpia bloqueo, reinicia intentos y actualiza `fechacontrasena`.

## Excepciones

- Usuario/correo inexistente o inactivo.
- Error al registrar la solicitud.
- Error al enviar correo.
- Codigo no verificable, ya eliminado o no coincidente.
- Codigo expirado por cambio de dia calendario segun comparacion observada.
- Error al actualizar contrasena.

## Estado de Validacion

Reconstruccion candidata desde codigo y SQL. La revision humana se difiere hasta completar el baseline completo.
