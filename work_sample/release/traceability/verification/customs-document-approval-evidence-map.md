# Customs Document Approval Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Previous documents store business document metadata and attachments. | `PROCESS_DEFINITION.md`, `DATA_USED.md`, `spec.md` | `documentacion.php:86-109`, `.data_base/asgard.sql:5484-5502` |
| Other documents store additional document descriptions and attachments. | `PROCESS_FLOW.md`, `DATA_USED.md`, `spec.md` | `documentacion.php:118-165`, `.data_base/asgard.sql:7839-7848` |
| Bulk import replaces documents by imported types. | `BUSINESS_RULES.md`, `spec.md` | `documentacion.php:234-271` |
| Intermediate documents convert into previous documents. | `BUSINESS_RULES.md`, `STATE_MODEL.md`, `spec.md` | `documentacionaprobado.php:196-241` |
| `aceptar = 1` is excluded from approval pending list. | `BUSINESS_RULES.md`, `STATE_MODEL.md`, `spec.md` | `documentacionaprobado.php:970-999` |
| `aceptar = 4` marks documents for send. | `STATE_MODEL.md`, `spec.md` | `documentacionaprobado.php:316`, `documentacionaprobado.php:421-426` |
| Other documents are marked sent with `enviado = 1`, `estado = 1`. | `STATE_MODEL.md`, `spec.md` | `documentacionaprobado.php:476`, `finsolicitud.php:375` |
