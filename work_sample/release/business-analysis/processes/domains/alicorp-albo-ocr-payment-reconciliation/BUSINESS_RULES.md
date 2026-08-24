# Alicorp Albo OCR Payment Reconciliation - Business Rules

| Rule ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-AAOPR-001 | La reconciliacion usa el modelo OCR `MODELO_FACTUTA_ALBO`. | `lecturaOCRModelo(..., MODELO_FACTUTA_ALBO)` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AAOPR-002 | El concepto de pago tratado por este flujo es `272`. | `$idconcepto=272` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AAOPR-003 | El contexto puede resolverse desde embarque, solicitud aduanera o AGES mediante `exchange_id`. | Consultas por `idExchange` / `exchange_id` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AAOPR-004 | El pago candidato debe tener `nro` vacio y monto igual al total OCR. | Filtros `IFNULL(nro,'')=''` y `monto=$monto` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AAOPR-005 | Si el DIM OCR coincide con `DS-gestiondui-codigoAduana-nodui`, se marca `alicorp_cierre_transito=1`. | Consulta por DIM y `UPDATE dav_casos` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AAOPR-006 | Si el contexto es AGES y el DIM coincide, se vincula la factura comercial con `ages_id`. | `UPDATE dav_facturacomercial SET ages_id=$idages` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AAOPR-007 | La fecha OCR debe estar en tres partes separadas por `/` para actualizar pago y nota. | `explode("/", $fechafact)` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AAOPR-008 | La rama ZIP/RAR guarda metadata OCR en `dav_pagosdetalle`; la rama PDF directa observada actualiza solo numero y fecha. | Updates lineas 193-194 vs 346-347 | INFERRED_DRAFT_REVIEW_REQUIRED |
