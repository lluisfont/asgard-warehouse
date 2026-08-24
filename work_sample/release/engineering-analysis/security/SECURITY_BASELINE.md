# Security Baseline

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Superficie observada

- Aplicacion PHP legacy bajo `index_archivos`.
- Autenticacion cliente en `login.php`, `veriflogin.php` y MFA en `2fa.php`.
- Sesion PHP con variables `idcliente`, `idclienteusuarios`, `tipo_usuario` y tokens JWT.
- Autorizacion transversal mediante `permisos.php`, `dav_clienteusuariospermisos`, `dav_clientereportescliente` y checks puntuales por `idreportescliente`.
- Acceso multi-tenant principalmente por filtros `idcliente` / `idclienteusuarios` en SQL.
- Integraciones externas: correo SendGrid/SMTP, Pusher, OCR/Form Recognizer, Freshservice, SFTP/SSH, Power BI y APIs internas.
- Gestion de ficheros en `FILES_PATH`, `DOCUMENTS_FILES`, OCR, ZIP/RAR, Excel, PDF y descargas.

## Controles positivos observados

| Control | Evidencia | Estado |
| --- | --- | --- |
| Login primario usa PDO prepared statement para buscar usuario por username. | `index_archivos/veriflogin.php:63-72` | OBSERVED |
| Login primario valida CSRF antes de autenticar. | `index_archivos/veriflogin.php:15-19` | OBSERVED |
| Login primario configura cookies `httponly`, `secure`, `samesite` y headers HSTS/X-Frame/CSP. | `index_archivos/veriflogin.php:2-13` | OBSERVED |
| Password moderno con `password_verify` y `password_hash`. | `veriflogin.php`, `cambiocontrasena.php` | OBSERVED |
| MFA email con caducidad de 10 minutos e intentos limitados. | `2fa/TwoFaClass.php:86-130` | OBSERVED |
| Bloqueo de usuario tras intentos fallidos. | `veriflogin.php`, `2fa/TwoFaClass.php:113-130` | OBSERVED |

## Riesgos transversales candidatos

| Area | Riesgo | Evidencia |
| --- | --- | --- |
| SQL injection | Muchas rutas legacy interpolan `$_GET`, `$_POST` o sesion en `mysql_query`; algunas sin cast ni binding visible. | `documentacion.php`, `download.php`, multiples `*query.php` |
| Secretos | Tokens SendGrid/JWT/SFTP aparecen hardcoded o cargados globalmente desde repo/env local. | `cnfdb105.php`, `TwoFaClass.php`, `lectura-ocr-ft.php` |
| MFA | El payload `u` es `base64(json)` sin firma visible y el codigo se valida por codigo/tipo, no por usuario/correo. | `2fa.php`, `TwoFaClass.php` |
| Ficheros | Uploads y descargas usan nombres/rutas de usuario con validacion limitada y `FILES_PATH`. | `documentacion.php`, `download.php`, `OCRClass.php` |
| SSRF/path abuse | OCR y lectura remota consumen URLs/rutas derivadas de parametros o ficheros externos. | `OCRClass.php`, `lectura-ocr-pr.php`, `lectura-ocr-ft.php` |
| Tenant isolation | La separacion por cliente depende de filtros manuales repetidos; no se observa guard central obligatorio por recurso. | multiples queries con `idcliente` |
| Sesion/IP | IP derivada de `HTTP_X_FORWARDED_FOR` sin lista de proxies confiables. | `veriflogin.php`, `TwoFaClass.php` |

## Veredicto

La seguridad debe tratarse como `INFERRED_RISK_REVIEW_REQUIRED`. Hay controles modernos en login primario, pero conviven con rutas legacy de alto riesgo. Antes de baseline formal se requiere hardening de secretos, SQL, ficheros, MFA y autorizacion por recurso.
