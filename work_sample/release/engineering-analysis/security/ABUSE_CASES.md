# Abuse Cases

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| ID | Caso de abuso | Objetivo atacante | Evidencia / Superficie |
| --- | --- | --- | --- |
| AB-001 | Manipular `idcasosprevios` en documentacion para ver/subir/borrar documentos ajenos. | Exfiltrar o alterar documentos. | `documentacion.php`, `documentacionaprobado.php` |
| AB-002 | Usar `download.php?p=...&f=...` para descargar ficheros fuera del contexto permitido. | Exfiltrar archivos de otro cliente. | `download.php` |
| AB-003 | Enviar payload `u` MFA modificado. | Crear sesion como otro usuario si no hay firma. | `2fa.php`, `TwoFaClass::decodeUsuario` |
| AB-004 | Fuerza bruta/replay de codigo MFA. | Completar MFA sin correo. | `verificarCodigo` consulta solo codigo/tipo. |
| AB-005 | Subir ZIP/RAR con nombres/rutas maliciosas. | Escribir o leer ficheros inesperados. | `lectura-ocr-ft.php`, `lectura-ocr-pr.php` |
| AB-006 | Inyectar SQL en endpoint legacy de reportes o Android. | Leer/modificar base de datos. | `android/consulta.php`, multiples `*query.php` |
| AB-007 | Usar master password para suplantar usuarios. | Acceso masivo o no repudiable. | `veriflogin.php`, `master_pass` |
| AB-008 | Abusar endpoint de notificaciones. | Spam o enlaces falsos a usuarios. | `servicioNotificaciones/ajax/*` |
| AB-009 | Forjar JWT si se conoce key hardcoded. | Acceso a servicios integrados. | `TwoFaClass.php` |
| AB-010 | Reutilizar credencial SFTP hardcoded. | Acceso a servidor remoto/documentos. | `lectura-ocr-ft.php` |
