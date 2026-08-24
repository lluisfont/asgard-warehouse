# Advisory Management Services Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| The domain has a dedicated services board grouped by state. | `PROCESS_DEFINITION.md`, `PROCESS_FLOW.md`, `STATE_MODEL.md`, `spec.md` | `tbl-estados.js:17-79`, `servicios-adicionales.php:35-61` |
| Request creation captures requester, email, city, line, notes and relations. | `PROCESS_FLOW.md`, `DATA_USED.md`, `UC-001.md`, `spec.md` | `solicitud.js:1-260`, `.data_base/asgard.sql:388-436` |
| Requests can contain multiple procedures by issuing entity, procedure and type. | `BUSINESS_RULES.md`, `DATA_USED.md`, `spec.md` | `tramite.js:1-130`, `tbl-estados.js:155-220` |
| Document exchange can be linked or created for additional services. | `PROCESS_FLOW.md`, `BUSINESS_RULES.md`, `spec.md` | `tbl-estados.js:220-260`, `solicitud.js:150-260` |
| Mass/logistics creation creates previous cases and previous documents. | `BUSINESS_RULES.md`, `DATA_USED.md`, `spec.md` | `SolicitudClass.php:481-520`, `SolicitudesClass.php:714-850` |
| Operational reporting exposes lifecycle dates, cost and responsible fields. | `PROCESS_DEFINITION.md`, `DATA_USED.md`, `STATE_MODEL.md`, `spec.md` | `operativos/asesoria-gestion.php:67-212`, `.data_base/asgard.sql:285-505` |
