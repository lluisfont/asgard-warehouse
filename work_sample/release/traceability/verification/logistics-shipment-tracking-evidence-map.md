# Logistics Shipment Tracking Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Dashboards load customer providers/operators. | `PROCESS_DEFINITION.md`, `spec.md` | `DashboardCBN.php:7`, `DashboardAlicorp.php:7` |
| Dashboards load EDP states. | `BUSINESS_RULES.md`, `spec.md` | `DashboardCBN.php:9-33`, `DashboardAlicorp.php:9-33` |
| Orders are filtered by business dashboard criteria. | `PROCESS_FLOW.md`, `spec.md` | `DashboardCBN.php:169-275` |
| Shipments are filtered by ETA and modality criteria. | `PROCESS_FLOW.md`, `spec.md` | `DashboardCBN.php:330-380` |
| Shipments are stored in `logis_embarques`. | `DATA_USED.md`, `spec.md` | `CotizacionClass.php:423-471`, `.data_base/asgard.sql:12175-12210` |
| Shipment state can derive from EDP. | `STATE_MODEL.md`, `spec.md` | `EmbarqueClass.php:123-138` |
| Shipment state can derive from finalization/documents/cases/requests. | `STATE_MODEL.md`, `spec.md` | `EmbarqueClass.php:141-149` |
| Shipment-linked requests navigate to customs or services. | `UC-001.md`, `spec.md` | `solicitud.js:274-315` |
