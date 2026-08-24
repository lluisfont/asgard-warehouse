# Integration Architecture

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Integracion | Uso | Evidencia |
| --- | --- | --- |
| SendGrid / SMTP | MFA, solicitudes, notificaciones, documentos. | `MailClass.php`, `cnfdb105.php` |
| Pusher | Notificaciones realtime. | `servicioNotificaciones`, `cnfdb105.php` |
| OCR / Form Recognizer | Extraccion de DEX, BL, facturas, SOAT, partes, IASA, Alicorp. | `OCRClass.php`, `intercambioDocumental/ajax/*` |
| SFTP/SSH | Descompresion/lectura remota OCR ZIP/RAR. | `lectura-ocr-ft.php` y variantes |
| Power BI | Dashboards embebidos/genericos. | `dashboard*.php`, `DashboardGenerico.php` |
| Freshservice | Widget soporte para clientes/proveedores observados. | `pusherlibs.php` |
| ASESORIA_GESTION_API | Servicios adicionales/asesoria. | dominios asesoria/servicios adicionales |
| Atlantes/JWT | Token y contexto almacen/cliente. | `veriflogin.php`, `TwoFaClass.php` |

## Riesgos

- Secretos y endpoints externos aparecen como constantes globales.
- No se observa patron uniforme de retries, timeouts, circuit breakers o colas.
- OCR y SFTP manejan documentos sensibles; requieren controles de ruta, secreto y auditoria.
