# Document Exchange OCR Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Intercambio documental selects template by module and transaction attributes. | `PROCESS_DEFINITION.md`, `spec.md` | `iniciarIntercambio.php:15-116`, `IntercambioDocumentalClass.php:8-29` |
| Participants are assembled from company/contact sources. | `PROCESS_FLOW.md`, `UC-001.md`, `spec.md` | `iniciarIntercambio.php:118-253`, `IntercambioDocumentalClass.php:38-63` |
| Exchange can be linked to shipment. | `BUSINESS_RULES.md`, `DATA_USED.md`, `spec.md` | `IntercambioDocumentalClass.php:290-296` |
| Exchange can be linked to order. | `BUSINESS_RULES.md`, `DATA_USED.md`, `spec.md` | `IntercambioDocumentalClass.php:299-305` |
| OCR is performed through external document model API. | `PROCESS_FLOW.md`, `spec.md` | `OCRClass.php:108-206` |
| Contract must be loaded before transport invoice validation in observed IASA flow. | `BUSINESS_RULES.md`, `UC-001.md`, `spec.md` | `lectura_documentos_iasa.php:19-39`, `lectura_documentos_iasa.php:133-145` |
| Invoice is compared with contract values. | `BUSINESS_RULES.md`, `spec.md` | `lectura_documentos_iasa.php:219-252` |
| Packing list must be loaded before SCP validation. | `BUSINESS_RULES.md`, `spec.md` | `lectura_documentos_iasa.php:444-461` |
| SCP plate is compared with packing list plate. | `BUSINESS_RULES.md`, `spec.md` | `lectura_documentos_iasa.php:488-560` |
