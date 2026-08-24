# Identity Access / 2FA - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Inicio de sesion de cliente con verificacion MFA por correo.

Objetivo de negocio candidato: permitir que un usuario cliente complete el acceso a ASGARD despues de validar un codigo de seis digitos enviado a su correo electronico. Esta inferencia se apoya en la pantalla `index_archivos/2fa.php`, que muestra "Ingrese el codigo de verificacion", y en `TwoFaClass::enviarCorreo`, que envia un correo con asunto de autenticacion MFA.

## Trigger

El proceso se inicia cuando `index_archivos/2fa.php` recibe el parametro `u`, decodifica datos del usuario y llama a `TwoFaClass::enviarCorreo($user->correo)`.

Evidencia:

- `index_archivos/2fa.php:10-13`
- `index_archivos/2fa/TwoFaClass.php:10-19`

## Actores

- Usuario cliente: introduce el codigo recibido y puede solicitar reenvio.
- Sistema ASGARD: genera, persiste, valida e invalida codigos 2FA.
- Servicio de correo: envia el codigo al correo del usuario.
- Servicio externo `ip-api.com`: usado para enriquecer el correo con ubicacion aproximada por IP.

Estado: INFERRED_DRAFT_REVIEW_REQUIRED.

## Precondiciones

- El usuario fue identificado antes de llegar a `2fa.php`; los datos viajan codificados en el parametro `u`.
- Existe correo del usuario en el payload decodificado.
- La tabla `dav_codigos_2fa` existe y permite guardar codigo, IP, correo, tipo de usuario y timestamps.
- La sesion PHP puede almacenar variables de autenticacion.

## Resultado Esperado

Si el codigo es valido y no esta expirado:

- el codigo se invalida con `deleted_at`;
- se reinicia `$_SESSION['intentos_code']`;
- se llama a `autenticar`;
- se crean variables de sesion y tokens JWT;
- se actualiza actividad/visitas del usuario y cliente;
- el usuario es redirigido a `principal.php` o a `cambiocontrasena.php` si debe cambiar password.

Evidencia:

- `index_archivos/2fa.php:61-90`
- `index_archivos/2fa/TwoFaClass.php:86-130`
- `index_archivos/2fa/TwoFaClass.php:132-218`

## Excepciones

- Codigo expirado tras mas de 600 segundos.
- Codigo invalido.
- Mas de 3 intentos invalidos: se bloquea el usuario en `dav_clienteusuarios` con `fechabloqueo` y `intentos = 5`.
- Si el usuario requiere cambio de password, el login devuelve `cambiocontrasena.php`.
- El usuario autenticado puede cambiar su password desde perfil validando la password actual y reutilizando la politica de complejidad observada.
- El usuario autenticado puede consultar el historial reciente de sesiones con fecha, estado, ubicacion, dispositivo/SO e IP.

Evidencia complementaria:

- `index_archivos/cambiocontrasena.php`
- `index_archivos/usuario/editarperfil.php`
- `index_archivos/usuario/historial.php`

## Estado de Validacion

Este proceso es una reconstruccion candidata desde codigo y SQL. Requiere validacion funcional para confirmar:

- si aplica a todos los clientes o solo a usuarios tipo `CLIENTE`;
- como se genera inicialmente el parametro `u`;
- si existen controles adicionales antes o despues de `2fa.php`;
- si el bloqueo por intentos se desbloquea manualmente o por proceso automatico.
