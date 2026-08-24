# Billing Payments Receivables Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Invoice-planilla listing is filtered by session customer and operational criteria. | `PROCESS_DEFINITION.md`, `PROCESS_FLOW.md`, `spec.md` | `facplaquery.php:31-83` |
| Invoice amount is derived from `dav_facturasdetalle`. | `BUSINESS_RULES.md`, `DATA_USED.md`, `spec.md` | `facplaquery.php:86-89`, `.data_base/asgard.sql:6266-6282` |
| Planilla amount is derived from `dav_pagosdetalle`. | `BUSINESS_RULES.md`, `DATA_USED.md`, `spec.md` | `facplaquery.php:91-112`, `generarfacturaplanillacliente.php:126-136` |
| PDF documents are generated or downloaded from invoice-planilla metadata. | `PROCESS_FLOW.md`, `spec.md` | `generarfacturaplanillacliente.php:12-170`, `descargarfactura.php:12-26`, `descargarplanilla.php:11-29` |
| Debit notes are reported as charge/debit documents with detail concepts and amount. | `PROCESS_FLOW.md`, `DATA_USED.md`, `spec.md` | `notasdebitoquery.php:10-91`, `.data_base/asgard.sql:7473-7531` |
| Received payments show applied, returned and remaining balances. | `PROCESS_FLOW.md`, `DATA_USED.md`, `spec.md` | `pagosrecibidosquery.php:7-43` |
| Sent document receipt updates different tables by document type. | `BUSINESS_RULES.md`, `STATE_MODEL.md`, `UC-001.md` | `recepcionplanillas_ajax.php:321-352` |
| Account statement aging uses `cobros2` and customer credit days. | `BUSINESS_RULES.md`, `STATE_MODEL.md`, `spec.md` | `estadocuentasquery.php:1-75` |
