# Customer Password Recovery - Data Used

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Entidades y tablas observadas

| Tabla | Uso candidato | Campos observados |
| --- | --- | --- |
| `dav_clienteusuarios` | Usuario cliente que solicita recuperacion y recibe nueva contrasena. | `username`, `correo`, `activo`, `password`, `fechabloqueo`, `intentos`, `fechacontrasena`, `updated_at` |
| `dav_reseteos_passswords_clientes` | Registro de solicitudes/codigos de recuperacion de contrasena. | `id`, `correo`, `token`, `nombre_solicitante`, `apellido_solicitante`, `cliente_usuario`, `created_at`, `updated_at`, `deleted_at` |

## Mutaciones observadas

| Operacion | Tabla | Evidencia |
| --- | --- | --- |
| Insertar solicitud de recuperacion | `dav_reseteos_passswords_clientes` | `ResetPassword.php:19-21` |
| Invalidar solicitud validada | `dav_reseteos_passswords_clientes` | `ResetPassword.php:61` |
| Actualizar contrasena y desbloqueo | `dav_clienteusuarios` | `ResetPassword.php:79` |

## Entradas observadas

- `nombre`
- `apellido`
- `username`
- `token`
- `identifier`
- `password`

## Integraciones observadas

- SendGrid PHP SDK para envio de correo de verificacion.
