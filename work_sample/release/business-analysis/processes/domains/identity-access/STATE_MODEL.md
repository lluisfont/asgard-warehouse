# Identity Access / 2FA - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma de negocio: Spanish

## Estados Candidatos del Codigo 2FA

| Estado | Condicion observada | Evidencia |
| --- | --- | --- |
| Emitido | Registro insertado en `dav_codigos_2fa` con `created_at` y `deleted_at` nulo. | `TwoFaClass.php:80-84`; `.data_base/asgard.sql:3424-3431` |
| Valido | Existe codigo con `tipo_usuario = 'CLIENTE'` y `deleted_at IS NULL`, y no supera 600 segundos. | `TwoFaClass.php:88-103` |
| Expirado | Diferencia entre hora actual y `created_at` supera 600 segundos. | `TwoFaClass.php:92-100` |
| Consumido/invalidado | Se actualiza `deleted_at = CURRENT_TIMESTAMP()`. | `TwoFaClass.php:95-103` |

## Estados Candidatos del Usuario Cliente

| Estado | Condicion observada | Evidencia |
| --- | --- | --- |
| Autenticado | Variables `$_SESSION` y JWT creados tras `autenticar`. | `TwoFaClass.php:132-187` |
| Bloqueado por intentos MFA | `fechabloqueo` actualizado e `intentos = 5`. | `TwoFaClass.php:112-122` |
| Requiere cambio de password | `cambiarcontrasena == 1` deriva a `cambiocontrasena.php`. | `TwoFaClass.php:204-210` |
| Actividad registrada | `ultimaactividad`, `visitas` de usuario y cliente actualizadas. | `TwoFaClass.php:189-190` |

## Ambiguedades

- No se observa en este dominio como se desbloquea un usuario.
- No se confirma si `intentos` y `intentos_code` comparten regla de negocio o solo implementacion parcial.
- No se confirma el origen ni la integridad criptografica del parametro `u`.
