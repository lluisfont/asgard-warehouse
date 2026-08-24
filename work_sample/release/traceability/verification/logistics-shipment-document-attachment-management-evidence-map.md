# Logistics Shipment Document Attachment Management - Evidence Map

| Artefacto | Evidencia |
| --- | --- |
| Process definition | `frames/documentos.php`, `EmbarqueClass.php`, `embarquesController.php`, `carga-documentos-id.php` |
| Process flow | Channel selection, local upload/delete and exchange upload |
| Business rules | `idExchange` checks, filesystem path, `MAX(nrodocumento)+1`, physical delete |
| Data used | `.data_base/asgard.sql:12304-12316` |
| State model | Exchange/local mode, active/deleted local file |
| Use case | Document tab interaction |
| OpenSpec | Derived from observed PHP/JS/API behavior |

## Limitaciones

- Full exchange creation and approval lifecycle belongs to broader document-exchange domain.
- Local attachment authorization and finalization locks are not fully visible in the inspected upload/delete endpoints.
