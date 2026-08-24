# System Context AS-IS

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

ASGARD Clientes es una aplicacion PHP legacy/monolitica que concentra operaciones aduaneras, logisticas, documentales, vehiculares, contables, reporting, OCR, notificaciones y dashboards para multiples clientes.

## Contexto

| Elemento | Rol AS-IS |
| --- | --- |
| Usuarios cliente | Acceden por login/MFA, ejecutan solicitudes, cargas, reportes, documentos y dashboards. |
| Usuarios internos | Operan casos, DAV/DAM/DEX, EDP, reportes, aprobaciones y seguimiento. |
| Proveedores/transportistas/agentes | Participan por contexto proveedor, tokens, documentos, costos, tracking y notificaciones. |
| Base MySQL ASGARD | Persistencia principal de casos, logistica, vehiculos, facturacion, usuarios, permisos y reporting. |
| Filesystem | Almacena documentos, OCR, Excel, PDF, ZIP/RAR y adjuntos por rutas bajo constantes. |
| Servicios externos | SendGrid/SMTP, Pusher, OCR/Form Recognizer, Power BI, Freshservice, SFTP/SSH, APIs internas. |

## Observaciones

- El repo contiene mezcla de codigo de negocio, vistas PHP, AJAX, clases, librerias vendorizadas y assets.
- La separacion modular existe por directorios, no como servicios desplegados independientes.
- Graphify reporta 32896 nodos y 53235 aristas en el grafo importado.
