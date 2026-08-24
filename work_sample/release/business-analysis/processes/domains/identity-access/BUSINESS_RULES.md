# Identity Access / 2FA - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| IA-2FA-BR-001 | El codigo MFA para cliente es numerico y tiene 6 digitos. | `TwoFaClass.php:34-37`; `2fa.php:61-64` | INFERRED_DRAFT_REVIEW_REQUIRED |
| IA-2FA-BR-002 | El codigo MFA se considera vencido si transcurren mas de 600 segundos desde `created_at`. | `TwoFaClass.php:92-100` | INFERRED_DRAFT_REVIEW_REQUIRED |
| IA-2FA-BR-003 | Cada codigo validado o expirado se invalida estableciendo `deleted_at`. | `TwoFaClass.php:95-103` | INFERRED_DRAFT_REVIEW_REQUIRED |
| IA-2FA-BR-004 | Si hay mas de 3 intentos fallidos de codigo, se bloquea el usuario cliente. | `TwoFaClass.php:112-122` | INFERRED_DRAFT_REVIEW_REQUIRED |
| IA-2FA-BR-005 | El bloqueo tecnico escribe `fechabloqueo = CURRENT_TIMESTAMP()` e `intentos = 5` en `dav_clienteusuarios`. | `TwoFaClass.php:115-116` | INFERRED_DRAFT_REVIEW_REQUIRED |
| IA-2FA-BR-006 | Si `cambiarcontrasena == 1`, el login correcto deriva a cambio de password. | `TwoFaClass.php:204-210` | INFERRED_DRAFT_REVIEW_REQUIRED |
| IA-2FA-BR-007 | La nueva password debe tener minimo 8 caracteres, mayuscula, minuscula, numero y caracter especial. | `index_archivos/cambiocontrasena.php:78-117` | INFERRED_DRAFT_REVIEW_REQUIRED |
| IA-2FA-BR-008 | El cambio de password desde perfil requiere validar la password actual con `password_verify` antes de actualizar el hash bcrypt. | `usuario/editarperfil.php` | INFERRED_DRAFT_REVIEW_REQUIRED |

## Reglas Pendientes de Confirmar

- Politica de expiracion y limpieza historica de `dav_codigos_2fa`.
- Regla exacta para activar/desactivar `dav_clienteusuarios.2fa`.
- Proceso operativo para desbloquear usuarios.
- Si el limite de 3 intentos aplica por sesion, por codigo, por IP o por usuario en todos los casos.
