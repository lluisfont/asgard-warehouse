# Code Smell Catalog

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Smell | Evidencia candidata |
| --- | --- |
| SQL concatenado en PHP legacy | Uso amplio de `mysql_query` con parametros. |
| Includes globales | `cnfdb105.php`, `permisos.php`, menus y libs en casi todas las pantallas. |
| Mezcla vista/controlador/modelo | Pantallas PHP con HTML, SQL y side effects. |
| Magic numbers | Clientes, permisos, estados, tipos documento. |
| Dependencias vendorizadas antiguas | `PHPExcel`, `MPDF57`, `PDFMerger`, `phpMailer`, `sendgrid_php`. |
| Copia/pega por cliente/modelo | OCR, reportes, dashboards, documentos. |
| Filesystem como estado | Upload/download/documentos/PDF/Excel. |
| Scripts largos | Reportes y clases con muchas responsabilidades. |
