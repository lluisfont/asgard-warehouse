# Customs Request Intake Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Uploaded request files are stored per user. | `PROCESS_FLOW.md`, `spec.md` | `SolicitudClass.php:14-35` |
| Imported rows replace prior staging rows for customer/user. | `BUSINESS_RULES.md`, `spec.md` | `SolicitudClass.php:90-118` |
| Request type catalog includes Despacho Aduanero, Gestion Soporte and Vehiculos. | `BUSINESS_RULES.md` | `SolicitudClass.php:122-140` |
| Master data validation updates row errors and messages. | `PROCESS_FLOW.md`, `spec.md` | `SolicitudClass.php:300-465` |
| Valid request creates `dav_casosprevios`. | `DATA_USED.md`, `spec.md` | `SolicitudClass.php:482-496`, `.data_base/asgard.sql:2214-2244` |
| Legacy direct intake creates `dav_casosprevios` and base previous documents when no exchange exists. | `PROCESS_DEFINITION.md`, `DATA_USED.md` | `index_archivos/nuevoinsert.php:59-86` |
| Legacy request view supports permanent deletion of request and child rows. | `PROCESS_DEFINITION.md`, `FINDINGS_REGISTER.md` | `index_archivos/versolicitud.php:7-13` |
| Request print/PDF view renders submitted request data. | `PROCESS_DEFINITION.md` | `index_archivos/impresion.php` |
| Initial documents/tramites can be created after case creation. | `PROCESS_FLOW.md`, `spec.md` | `SolicitudClass.php:502-509` |
| Request submission updates finalization/approval dates. | `STATE_MODEL.md`, `spec.md` | `enviarsolicitud_ajax.php:107-114` |
| Finalization sends email/push notifications. | `UC-001.md`, `spec.md` | `finsolicitud.php:392-515` |
