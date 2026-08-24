# Customer Primary Login Session Audit - Process Definition

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Proceso Candidato

Nombre: Inicio de sesion primario de usuario cliente con control de intentos y auditoria.

Objetivo de negocio candidato: validar credenciales iniciales de usuario cliente, aplicar bloqueo por intentos, abrir sesion o derivar a MFA, registrar actividad de acceso y mantener visitas/ultima actividad para trazabilidad operativa.

## Trigger

El proceso inicia cuando el formulario de login envia `username`, `contrasena`, `csrf_token` y opcionalmente `ultimoenlace` a `veriflogin.php`.

Evidencia:

- `index_archivos/login.php`
- `index_archivos/veriflogin.php:1-31`

## Actores

- Usuario cliente: ingresa credenciales y puede continuar a MFA o pantalla principal.
- Sistema ASGARD: valida CSRF, credenciales, bloqueo, sesion existente, 2FA y politica de cambio de password.
- Master password: mecanismo observado de acceso alternativo contra tabla `master_pass`.
- Registro de auditoria: `log_asgard_ecosistema` captura exito/fallo con IP, navegador, sistema operativo, plataforma y geolocalizacion de cookies.

## Alcance

Incluye:

- Protecciones iniciales de sesion/cabeceras observadas.
- Validacion CSRF.
- Verificacion de password bcrypt y master password.
- Validacion de usuario activo.
- Bloqueo por intentos y ventana de 24 horas.
- Prevencion de sesion cruzada de otro cliente en el mismo explorador.
- Creacion de sesion, JWT y conteo de visitas cuando no aplica MFA.
- Redireccion a MFA cuando `2fa` esta activo.
- Log de inicio exitoso y fallido.
- Redireccion a cambio de contrasena si vencio la politica de 90 dias.

Fuera de alcance:

- Validacion del codigo MFA, cubierta por `identity-access`.
- Recuperacion de contrasena, cubierta por `customer-password-recovery`.
- Cambio de contrasena posterior a vencimiento, salvo la redireccion observada.

## Resultado Esperado

- Login exitoso sin MFA crea sesion y redirige a `principal.php` o `ultimoenlace`.
- Login exitoso con MFA redirige a `2fa.php`.
- Credencial incorrecta incrementa intentos, registra fallo y puede bloquear al usuario.
- Usuario bloqueado es informado con fecha/hora candidata de desbloqueo.

## Estado de Validacion

Reconstruccion candidata desde codigo y SQL. La revision humana se difiere hasta completar el baseline completo.
