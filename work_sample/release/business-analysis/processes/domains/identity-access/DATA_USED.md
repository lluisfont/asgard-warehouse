# Identity Access / 2FA - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Entidades Principales

| Tabla | Uso candidato | Evidencia |
| --- | --- | --- |
| `dav_clienteusuarios` | Usuario cliente, credenciales, correo, estado de bloqueo, contador de intentos, visitas, ultima actividad y flag `2fa`. | `.data_base/asgard.sql:3305-3333`; `TwoFaClass.php:115-116`; `TwoFaClass.php:189` |
| `dav_codigos_2fa` | Codigos MFA emitidos por correo, IP solicitante, tipo de usuario y soft delete por `deleted_at`. | `.data_base/asgard.sql:3422-3433`; `TwoFaClass.php:80-103` |
| `dav_cliente` | Datos del cliente usados para construir sesion y JWT. | `TwoFaClass.php:142-153`; `TwoFaClass.php:156-181` |
| `dav_clienteusuariospermisos` | Permisos por usuario cliente sobre reportes/funciones, con lectura/escritura. | `.data_base/asgard.sql:3340-3348` |
| `dav_clientereportescliente` | Reportes/funciones habilitados por cliente, soporte de menu y autorizacion de usuario. | `GlobalClass.php`, menu/permisos |
| `dav_permisos` | Permisos por usuario/modulo interno con lectura/escritura. | `.data_base/asgard.sql:8700-8708` |
| `dav_token` | Tokens historicos/sesion por usuario interno o legacy. | `.data_base/asgard.sql:10595-10605` |
| `personal_access_tokens` | Tokens personales estilo framework/API. | `.data_base/asgard.sql:14979-14992` |

## Campos Sensibles

- `dav_clienteusuarios.password`
- `dav_clienteusuarios.contrasena`
- `dav_clienteusuarios.correo`
- `dav_clienteusuarios.telefono`
- `dav_codigos_2fa.codigo`
- `dav_codigos_2fa.user_ip`
- `dav_token.token`
- `personal_access_tokens.token`
- `dav_usuario.contrasena`
- `dav_usuario.pass_suma`

Clasificacion candidata: datos de autenticacion, identificadores de usuario, trazas de acceso y datos personales. Requiere confirmacion con politica de privacidad/seguridad.
