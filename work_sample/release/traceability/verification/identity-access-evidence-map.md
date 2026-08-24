# Identity Access Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Customer MFA starts at `2fa.php` with encoded user payload. | `PROCESS_DEFINITION.md`, `UC-001.md`, `spec.md` | `index_archivos/2fa.php:10-13` |
| MFA code is six numeric digits. | `BUSINESS_RULES.md`, `spec.md` | `index_archivos/2fa/TwoFaClass.php:34-37`, `index_archivos/2fa.php:61-64` |
| MFA code is stored in `dav_codigos_2fa`. | `DATA_USED.md`, `spec.md` | `index_archivos/2fa/TwoFaClass.php:80-84`, `.data_base/asgard.sql:3422-3433` |
| MFA code expires after 600 seconds. | `BUSINESS_RULES.md`, `STATE_MODEL.md`, `spec.md` | `index_archivos/2fa/TwoFaClass.php:92-100` |
| Valid or expired code is soft-deleted. | `BUSINESS_RULES.md`, `STATE_MODEL.md`, `spec.md` | `index_archivos/2fa/TwoFaClass.php:95-103` |
| More than 3 failed attempts blocks customer user. | `BUSINESS_RULES.md`, `UC-001.md`, `spec.md` | `index_archivos/2fa/TwoFaClass.php:112-122` |
| Successful MFA creates authenticated session and JWT values. | `AUTHENTICATION_FLOWS.md`, `SESSION_MANAGEMENT.md`, `spec.md` | `index_archivos/2fa/TwoFaClass.php:132-218` |
| Password-change flag redirects to `cambiocontrasena.php`. | `BUSINESS_RULES.md`, `UC-001.md`, `spec.md` | `index_archivos/2fa/TwoFaClass.php:204-210` |
| Password policy enforces minimum complexity. | `BUSINESS_RULES.md` | `index_archivos/cambiocontrasena.php:78-117` |
| Graphify confirms `TwoFaClass` methods and extracted code graph context. | `AUTHENTICATION_FLOWS.md` | `graphify-out/GRAPH_REPORT.md`, `.brownfield/work/graphify/GRAPHIFY_IMPORT_MANIFEST.json` |
