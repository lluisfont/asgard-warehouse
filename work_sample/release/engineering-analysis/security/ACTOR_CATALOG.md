# Actor Catalog

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Actor | Tipo | Capacidades observadas | Evidencia |
| --- | --- | --- | --- |
| Usuario cliente | Humano autenticado | Login, MFA, consulta/alta/edicion segun permisos, cargas documentales, reportes. | `veriflogin.php`, `permisos.php`, dominios |
| Usuario interno ASGARD | Humano autenticado | Operaciones aduaneras/logisticas, seguimiento, reportes, aprobaciones. | `dav_usuario`, dominios operativos |
| Proveedor/transportista | Humano/tercero | Acceso por contexto `PROVEEDORES`, notificaciones, tracking/costos/documentos. | `ASGARD_TYPE`, tracking, terceros |
| Agente de aduana/seguro/gestor | Tercero | Onboarding, contactos, documentos, solicitudes vinculadas. | `third-party-token-document-onboarding` |
| Sistema ASGARD | Aplicacion | Autenticacion, sesion, permisos, SQL, ficheros, notificaciones y OCR. | codigo PHP |
| Pusher | Servicio externo | Realtime notifications. | `servicioNotificaciones`, `cnfdb105.php` |
| SendGrid/SMTP | Servicio externo | Envio correo MFA, solicitudes y avisos. | `MailClass.php`, `cnfdb105.php` |
| OCR/Form Recognizer | Servicio externo | Lectura documental. | `OCRClass.php`, `OcrUtil.php` |
| Servidor SFTP/SSH | Servicio externo | Descompresion/lectura remota de documentos OCR. | `lectura-ocr-ft.php` |
| Power BI | Servicio externo | Dashboards embebidos. | `executive-powerbi-dashboard-portal` |
| Freshservice | Servicio externo | Widget soporte para clientes/proveedores observados. | `pusherlibs.php` |
