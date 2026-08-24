# File Processing Tests

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| ID | Prueba |
| --- | --- |
| FILE-T-001 | Upload documental valido crea DB + archivo descargable. |
| FILE-T-002 | Upload con extension/MIME no permitido se rechaza. |
| FILE-T-003 | `download.php` no permite traversal ni tenant ajeno. |
| FILE-T-004 | Excel vehicular valido genera staging/vehiculos esperados. |
| FILE-T-005 | Excel corrupto/no plantilla se rechaza sin mutacion parcial. |
| FILE-T-006 | ZIP/RAR con paths peligrosos se rechaza. |
| FILE-T-007 | PDF/QR factura se genera de forma reproducible. |
| FILE-T-008 | OCR error no deja archivos temporales ni updates parciales. |
