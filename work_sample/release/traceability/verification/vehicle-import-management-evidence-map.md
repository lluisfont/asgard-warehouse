# Vehicle Import Management Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Vehicle validation is part of request/document send flows. | `PROCESS_DEFINITION.md`, `PROCESS_FLOW.md`, `spec.md` | `documentacion.php:31-41`, `documentacion.php:381-435`, `enviarsolicitud_ajax.php:30-114` |
| Chassis is the operational identifier for vehicle updates. | `PROCESS_DEFINITION.md`, `BUSINESS_RULES.md`, `DATA_USED.md` | `VehiculosClass.php:84-105`, `uploadExcelSolicitud.php:31-39` |
| Bulk Excel rows are staged before confirmation. | `PROCESS_FLOW.md`, `DATA_USED.md`, `STATE_MODEL.md`, `spec.md` | `uploadExcelSolicitud.php:16-123`, `VehiculosClass.php:15-57`, `.data_base/asgard.sql:6711-6726` |
| Rows with validation messages are shown as observations. | `PROCESS_FLOW.md`, `BUSINESS_RULES.md`, `UC-001.md` | `VehiculosClass.php:26-52`, `uploadExcelSolicitud.php:86-123` |
| Economic and item updates are selected by `camposmodificar`. | `BUSINESS_RULES.md`, `spec.md` | `VehiculosClass.php:136-154` |
| Movement between previous requests requires destination and AP-related validations. | `BUSINESS_RULES.md`, `STATE_MODEL.md`, `spec.md` | `VehiculosClass.php:156-205`, `VehiculosClass.php:223-280` |
| DAM markers and chassis-document mappings are updated during request/document progression. | `DATA_USED.md`, `STATE_MODEL.md`, `spec.md` | `documentacion.php:445-512`, `enviarsolicitud_ajax.php:90-114`, `.data_base/asgard.sql:680-689` |
| SOAT lots are created/reused and item records reassigned during vehicle movement. | `DATA_USED.md`, `STATE_MODEL.md`, `spec.md` | `VehiculosClass.php:290-334`, `.data_base/asgard.sql:16007-16033` |
