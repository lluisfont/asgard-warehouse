# Security Findings

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| ID | Finding | Evidencia | Prioridad |
| --- | --- | --- | --- |
| SEC-001 | Rutas legacy interpolan parametros de usuario en SQL mediante `mysql_query`; riesgo SQL injection. | `documentacion.php`, `android/consulta.php`, multiples `*query.php` | Critica |
| SEC-002 | Secretos sensibles hardcoded: SendGrid, JWT y SFTP/SSH. | `cnfdb105.php`, `2fa/TwoFaClass.php`, `lectura-ocr-ft.php` | Critica |
| SEC-003 | MFA valida codigo por codigo/tipo y usa payload `base64` sin firma visible. | `2fa.php`, `2fa/TwoFaClass.php` | Alta |
| SEC-004 | Descarga generica permite subruta controlada por usuario y requiere canonizacion/tenant guard. | `download.php` | Alta |
| SEC-005 | Upload documental conserva nombre original y no muestra allowlist MIME/tamano en el bloque observado. | `documentacion.php`, `documentacionaprobado.php` | Alta |
| SEC-006 | Procesamiento ZIP/RAR por SSH usa comandos con rutas derivadas de ficheros. | `lectura-ocr-ft.php` | Alta |
| SEC-007 | IP de auditoria/MFA confia en `HTTP_X_FORWARDED_FOR` sin validacion de proxy confiable. | `veriflogin.php`, `TwoFaClass.php` | Media |
| SEC-008 | Login primario permite master password global (`master_pass`) que desbloquea cualquier usuario si coincide. | `veriflogin.php:39-47`, `veriflogin.php:79` | Alta |
| SEC-009 | Headers/CSP se configuran en login primario, pero no se observa aplicacion transversal en todas las pantallas. | `veriflogin.php`; ausencia en multiples endpoints | Media |
| SEC-010 | Aislamiento multi-tenant depende de filtros manuales `idcliente`; requiere matriz de recursos critica. | multiples dominios | Alta |

## Prioridad inmediata

1. Rotacion/remocion de secretos.
2. Bloqueo o hardening de rutas con SQL directo y ficheros.
3. Rediseño MFA con payload firmado, codigo ligado a usuario/correo y generador criptografico.
4. Revisión de master password y politica de break-glass.
