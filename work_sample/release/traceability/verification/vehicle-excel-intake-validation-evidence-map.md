# Vehicle Excel Intake Validation - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Artefacto | Evidencia |
| --- | --- |
| `PROCESS_DEFINITION.md` | `vehiculos.php`, `CargaVehiculos.php`, validations and updates. |
| `PROCESS_FLOW.md` | Upload, parse, persist, validate and complete. |
| `PROCESS_FLOW.md` | DAM Excel upload/validation variant in `index_archivos/vehiculosexcel/solicituddamajax.php`. |
| `BUSINESS_RULES.md` | Required fields, catalog checks, duplicate and chasis-year rules. |
| `DATA_USED.md` | `dav_casosprevios`, `dav_vehiculosprevios`, catalog tables and `dav_erroresexcel`. |
| `DATA_USED.md` | Error helper writes `dav_erroresexcel` from `index_archivos/vehiculosexcel/Funciones.php:377`. |
| `STATE_MODEL.md` | Upload/error/catalog/completed states. |
| `UC-001.md` | Vehicle Excel upload use case. |
| `openspec/spec.md` | AS-IS requirements. |

## Limitaciones

- No se ejecuto parser con plantillas reales.
- La semantica exacta de errores `1`, `2`, `3` debe validarse con usuarios.
- El dominio se relaciona con `vehicle-import-management`, pero se mantiene separado por densidad de flujo Excel.
