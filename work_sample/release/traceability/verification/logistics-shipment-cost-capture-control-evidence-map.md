# Logistics Shipment Cost Capture Control - Evidence Map

| Artefacto | Evidencia |
| --- | --- |
| Process definition | `frames/costos.php`, `componentes/costos/*.js`, `CostosClass.php` |
| Process flow | `costos.js::saveForm`, `CostosClass::guardarCostos` |
| Business rules | Permission/finalization gates, UI validations, soft-delete replacement |
| Data used | `.data_base/asgard.sql:11891-11990` |
| State model | `deleted_at`, `fecha_finalizacion`, `esAutomatico` |
| Use case | Costs tab interaction |
| OpenSpec | Derived from observed UI/backend/schema behavior |

## Limitaciones

- Catalog semantics for categories/concepts require business validation.
- Legacy operator-cost tables are related but not merged into this domain.
- No transaction boundary is visible in the save path.
