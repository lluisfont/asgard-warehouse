# Container Diagram AS-IS

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

```mermaid
flowchart LR
  U["Usuarios cliente / internos / proveedores"] --> W["Servidor web PHP ASGARD"]
  W --> PHP["Monolito PHP index_archivos"]
  PHP --> DB["MySQL ASGARD"]
  PHP --> FS["Filesystem documentos/Excel/PDF/OCR"]
  PHP --> MAIL["SendGrid / SMTP"]
  PHP --> PUSH["Pusher"]
  PHP --> OCR["OCR / Form Recognizer"]
  PHP --> PBI["Power BI"]
  PHP --> SSH["SFTP/SSH documentos"]
  PHP --> API["APIs internas/externas"]
```

## Contenedores candidatos

| Contenedor | Tecnologia | Responsabilidad |
| --- | --- | --- |
| Web/PHP | PHP legacy, includes, AJAX | Render UI, ejecutar controladores, sesiones, permisos, SQL y ficheros. |
| Base de datos | MySQL | Persistencia transaccional/reporting. |
| Filesystem | Directorios bajo constantes | Documentos, adjuntos, OCR, Excel, PDFs y temporales. |
| Librerias locales | PHPMailer, SendGrid, mPDF, TCPDF, PHPExcel, JWT | Soporte transversal. |
| Servicios externos | HTTP/SFTP/Pusher/Power BI/OCR | Integraciones. |
