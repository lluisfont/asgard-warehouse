# Sensitive Endpoints

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Endpoint | Accion sensible | Riesgo principal |
| --- | --- | --- |
| `index_archivos/veriflogin.php` | Autenticacion primaria, master password, sesion/JWT. | Bypass por master password, redireccion, session hardening. |
| `index_archivos/2fa.php` | Challenge MFA y payload de usuario. | Payload base64 sin firma visible. |
| `index_archivos/2fa/ajax/verificar-codigo.php` | Verifica codigo MFA. | Codigo no ligado visiblemente a usuario/correo. |
| `index_archivos/2fa/ajax/autenticar.php` | Crea sesion post-MFA. | Session fixation si no regenera id. |
| `index_archivos/2fa/ajax/reenviar-codigo.php` | Reenvia codigo MFA. | Rate limiting y abuso de correo. |
| `index_archivos/cambiocontrasena.php` | Cambio de password. | SQL legacy en update; requiere CSRF confirmado. |
| `index_archivos/download.php` | Descarga generica de ficheros. | Path traversal/tenant bypass por subruta. |
| `index_archivos/documentacion.php` | Alta/borrado documental de solicitud. | SQL injection, upload inseguro, borrado. |
| `index_archivos/documentacionaprobado.php` | Variante documental aprobada. | Upload/borrado y autorizacion. |
| `index_archivos/ajax/uploadExcelSolicitud.php` | Carga masiva de solicitudes. | Excel no confiable crea operaciones. |
| `index_archivos/vehiculosexcel/*` | Carga/migracion Excel vehicular. | Excel no confiable, escritura masiva. |
| `index_archivos/intercambioDocumental/ajax/OCRClass.php` | OCR remoto. | SSRF/secreto OCR/datos sensibles. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-ft.php` | Descompresion remota SSH y OCR. | Credenciales hardcoded y command/path injection. |
| `index_archivos/servicioNotificaciones/ajax/*` | Notificaciones persistidas/realtime. | Autorizacion de destinatarios/canales. |
| `index_archivos/android/consulta.php` | Login/API Android legacy. | Credenciales en SQL directo. |
| `index_archivos/android/consultatodo.php` | Consulta movil legacy por cliente/caso. | Tenant bypass si no hay auth fuerte. |
