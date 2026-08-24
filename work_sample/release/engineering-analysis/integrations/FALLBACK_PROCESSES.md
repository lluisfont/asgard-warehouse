# Fallback Processes

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Integracion | Fallback candidato |
| --- | --- |
| OCR | Captura manual/revision humana y no actualizar si campos faltan. |
| Mail | Reenvio manual desde logs/outbox futuro. |
| Pusher | Bandeja persistida `push_*` como fallback a toast. |
| Power BI | Reportes locales/Excel cuando existan. |
| SFTP/ZIP | Carga de PDF individual. |
| Freshservice | Canales soporte alternos fuera del repo. |
