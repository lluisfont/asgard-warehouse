# Data Lineage

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Origen | Transformacion | Destino |
| --- | --- | --- |
| Formularios PHP/JS | SQL directo/controladores | Tablas `dav_*`, `logis_*`, `tck_*`, `ages_*`. |
| Excel | Parsers PHPExcel + validacion | Staging/vehiculos/solicitudes/reportes. |
| Documentos PDF/ZIP | Upload/OCR/parser | Documentos, facturas, SOAT, IASA/Alicorp. |
| Estados operativos | Insercion EDP/historial | Dashboards/reportes/seguimiento. |
| DB transaccional | Procedimientos/temporales | Reportes Excel/PDF/Power BI. |
