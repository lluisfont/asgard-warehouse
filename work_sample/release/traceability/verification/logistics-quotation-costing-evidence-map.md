# Logistics Quotation Costing Evidence Map

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Artifact | Evidence |
| --- | --- | --- |
| Quotation creation stores shipment and operator data. | `PROCESS_DEFINITION.md`, `spec.md` | `embarquesController.php:94-178`, `CotizacionClass.php:423-627` |
| Sending quotation emails iterates operators. | `PROCESS_FLOW.md`, `spec.md` | `embarquesController.php:238-274` |
| Operator cost access is token-based. | `BUSINESS_RULES.md`, `spec.md` | `CostosClass.php:14-24`, `costosController.php:9-14` |
| Cost structure depends on incoterm and shipment type. | `BUSINESS_RULES.md`, `spec.md` | `CostosClass.php:412-427` |
| TT is calculated from ETA and ETD. | `BUSINESS_RULES.md`, `spec.md` | `CostosClass.php:431-463` |
| Submitted costs nullify token and mark filled. | `STATE_MODEL.md`, `spec.md` | `CostosClass.php:467-480` |
| Evaluation compares operators and groups. | `UC-001.md`, `spec.md` | `evaluarcosto.php:16-180` |
| Acceptance/confirmation update operator state. | `STATE_MODEL.md`, `spec.md` | `embarquesController.php:276-313` |
