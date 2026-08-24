# ASGARD-14 - Deuda tecnica, defectos y riesgos

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- Rutas PHP monoliticas y archivos muy grandes elevan el riesgo de cambio.
- Duplicacion de patrones en servicios Angular: token/manual headers, endpoint strings y manejo de errores disperso.
- Librerias embebidas y assets binarios versionados complican actualizaciones de seguridad.
- Cambios recientes de zona horaria y Azure Blob indican refactor activo con necesidad de caracterizacion.

## Evidencia

- `audit/evidence/source_inventory.csv`
- `audit/evidence/backend_routes.csv`
- `audit/evidence/frontend_service_calls.csv`
- `audit/registers/FINDINGS_REGISTER.md`

## Estado

`COMPLETED`: riesgos AS-IS documentados y priorizables.
