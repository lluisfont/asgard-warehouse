# Document Service Plan OCR Capture - Business Rules

| Rule ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-DSPC-001 | La lectura espera texto `SOLICITUD DE SERVICIO DE` y toma la linea siguiente como numero. | Parser de lineas OCR | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-DSPC-002 | La lectura espera `BL:` para obtener numero BL. | Parser de lineas OCR | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-DSPC-003 | La lectura espera `MONTO COTIZADO EN $us:` y toma la linea siguiente como monto. | Parser de lineas OCR | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-DSPC-004 | Se reemplaza la lectura vigente por `exchange_id` y `document_id` usando `deleted_at`. | Update previo a insert | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-DSPC-005 | Si faltan campos minimos, el documento se considera diferente al aceptado. | Rama de error | INFERRED_DRAFT_REVIEW_REQUIRED |
