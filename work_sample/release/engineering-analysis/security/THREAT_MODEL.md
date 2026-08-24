# Threat Model

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

## Activos

- Cuentas cliente/internas y sesiones PHP.
- JWT ASGARD/Atlantes.
- Datos aduaneros, logisticos, vehiculares, facturacion y documentos.
- Ficheros en `FILES_PATH` / `DOCUMENTS_FILES`.
- Secretos de correo, OCR, Pusher, DB, JWT y SFTP.
- Integraciones externas y canales realtime.

## Amenazas candidatas

| ID | Amenaza | Entrada | Impacto |
| --- | --- | --- | --- |
| TM-001 | SQL injection en endpoint legacy. | `$_GET`/`$_POST` + `mysql_query`. | Lectura/modificacion de datos tenant, credenciales, documentos. |
| TM-002 | Compromiso de secretos hardcoded. | Repo, backups, logs, artefactos. | Envio correo, JWT forjado, OCR/SFTP/Pusher comprometidos. |
| TM-003 | Bypass tenant por ID manipulable. | Parametros `idcasos`, `idcliente`, rutas de descarga. | Acceso a casos/documentos de otro cliente. |
| TM-004 | Upload malicioso. | Documentacion, Excel, OCR, ZIP/RAR. | Malware, overwrite, ejecucion indirecta, exfiltracion. |
| TM-005 | MFA bypass/tampering. | `u` base64, codigo no ligado a usuario. | Sesion no autorizada. |
| TM-006 | Session fixation/hijacking. | Login/MFA, cookies, headers proxy. | Toma de cuenta. |
| TM-007 | SSRF o lectura remota no controlada. | OCR/file_get_contents/curl. | Acceso a redes internas o ficheros sensibles. |
| TM-008 | Abuso realtime/notificaciones. | Pusher/endpoints notificacion. | Spam, phishing interno, fuga de enlaces. |
| TM-009 | Break-glass/master password abusado. | `master_pass`. | Acceso masivo no atribuido al usuario real. |

## Mitigaciones prioritarias

- Prepared statements y autorizacion por recurso.
- Vault/rotacion de secretos.
- Servicio central de ficheros seguro.
- MFA firmado y ligado a usuario.
- Matriz de permisos endpoint-recurso.
- Pruebas de caracterizacion de seguridad por flujo critico.
