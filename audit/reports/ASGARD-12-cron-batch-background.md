# ASGARD-12 - Cron, batch y procesamiento background

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- No se observaron workers/colas/cron versionados como scheduler formal.
- Existen procesos tipo batch/importacion masiva desde endpoints y servicios (Excel, timbrado, inventario, ATE-GAS).
- Existe script auxiliar `scripts/strip-phpunit-eager-load.php` para entorno de pruebas/migracion.

## Evidencias

- `audit/evidence/frontend_service_calls.csv`
- `audit/evidence/document_processing_catalog.csv`
- `AtlantesBE-main/AtlantesBE-main/scripts/strip-phpunit-eager-load.php`

## Estado

`COMPLETED_WITH_OPEN_QUESTIONS`: ausencia de cron versionado documentada; falta confirmar schedulers externos en servidor.
