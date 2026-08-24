# Evidence Map - customer-primary-login-session-audit

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| Afirmacion | Evidencia | Confianza |
| --- | --- | --- |
| El login primario valida CSRF antes de credenciales. | `index_archivos/veriflogin.php:13-18` | High |
| Se consulta `master_pass` y se acepta master password como alternativa. | `index_archivos/veriflogin.php:38-48`, `veriflogin.php:80` | High |
| El estado bloqueado depende de `intentos=5` y ventana menor a 24 horas. | `index_archivos/veriflogin.php:55-60` | High |
| Login exitoso sin 2FA crea sesion, JWT, reinicia intentos y suma visitas. | `index_archivos/veriflogin.php:100-120` | High |
| Login exitoso y fallido se insertan en `log_asgard_ecosistema`. | `index_archivos/veriflogin.php:139-160`, `veriflogin.php:183-202` | High |
| Fallos de credencial reinician bloqueo vencido y luego incrementan intentos. | `index_archivos/veriflogin.php:216-228` | High |
| Usuarios con 2FA son enviados a `2fa.php`. | `index_archivos/veriflogin.php:178-180` | High |
| Password vencida a 90 dias redirige a `cambiocontrasena.php`. | `index_archivos/veriflogin.php:59`, `veriflogin.php:166-173` | Medium |

## Riesgos candidatos

- Master password operativo requiere gobierno estricto y auditoria reforzada.
- La fecha de desbloqueo informada puede depender de datos leidos antes de incrementar intentos.
- Se usan cookies de latitud/longitud como parte del log sin validacion visible.
- Redireccion por `ultimoenlace` requiere validacion de destino para evitar abuso.
