# Performance Risks

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Riesgo | Motivo |
| --- | --- |
| Reportes pesados en request | Queries temporales, Excel/PDF y procedimientos. |
| OCR/PDF/ZIP en request | Procesamiento externo y de ficheros bloqueante. |
| SQL sin indices confirmados | Muchas consultas complejas legacy. |
| N+1/loops SQL | Pantallas y clases mezclan loops + queries. |
| Filesystem remoto/local | Descargas/uploads grandes. |
| Pusher/correo sincronico | Side effects en request. |
