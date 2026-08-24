# Alicorp Supplier OCR Payment Reconciliation - Business Rules

| Rule ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-ASOPR-001 | SENAVEX deriva el concepto desde UUID documental: `208`, `270`, `256` o `271`. | Switch `$iddcoumento` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ASOPR-002 | FDAB usa concepto fijo `273`; Jennefer usa `274`. | Variables `$idconcepto` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ASOPR-003 | El contexto se busca por embarque, solicitud aduanera y AGES. | Consultas por `exchange_id` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ASOPR-004 | El pago candidato debe tener concepto esperado, `nro` vacio y monto igual al OCR. | Consultas a `dav_pagosdetalle` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ASOPR-005 | La reconciliacion actualiza pago y nota de debito con numero y fecha de factura. | Updates a `dav_pagosdetalle` y `dav_notasdebitodetalle` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ASOPR-006 | El cierre `alicorp_cierre_transito=1` se marca cuando el DIM OCR coincide con el DIM construido en ASGARD. | Updates a `dav_casos` | INFERRED_DRAFT_REVIEW_REQUIRED |
