# ASGARD-03 - Mapa funcional de modulos

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Hallazgos

- Los dominios funcionales candidatos se agrupan por rutas/servicios: almacenes, embarques, entidades, datos maestros, usuarios, contabilidad, empresa, common/asgard y ATE-GAS.
- El menu Angular expone operaciones de almacen, reportes, contabilidad, dashboards, embarques, salidas, timbrado, inventario fisico y ATE-GAS.
- El catalogo de frontend/backed permite trazar pantallas a servicios y servicios a endpoints.

## Concentracion observada

| Item | Count |
| --- | --- |
| AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 129 |
| AtlantesBE-main/AtlantesBE-main/app/routes/datosmaestro.php | 74 |
| AtlantesBE-main/AtlantesBE-main/app/routes/contabilidad.php | 55 |
| AtlantesBE-main/AtlantesBE-main/app/routes/embarques.php | 26 |
| AtlantesBE-main/AtlantesBE-main/app/routes/entidades.php | 19 |
| AtlantesBE-main/AtlantesBE-main/app/routes/usuarios.php | 19 |
| AtlantesBE-main/AtlantesBE-main/app/routes/asgard.php | 13 |
| AtlantesBE-main/AtlantesBE-main/app/routes/empresa.php | 3 |

| Item | Count |
| --- | --- |
| AtlantesFE-main/AtlantesFE-main/src/app/services/almacenes.service.ts | 120 |
| AtlantesFE-main/AtlantesFE-main/src/app/services/datomaestro.service.ts | 75 |
| AtlantesFE-main/AtlantesFE-main/src/app/services/contabilidad.service.ts | 55 |
| AtlantesFE-main/AtlantesFE-main/src/app/services/embarque.service.ts | 25 |
| AtlantesFE-main/AtlantesFE-main/src/app/services/usuario.service.ts | 19 |
| AtlantesFE-main/AtlantesFE-main/src/app/services/entidades.service.ts | 18 |
| AtlantesFE-main/AtlantesFE-main/src/app/services/asgard.service.ts | 13 |
| AtlantesFE-main/AtlantesFE-main/src/app/services/empresa.service.ts | 3 |

## Evidencias

- `audit/evidence/frontend_routes.csv`
- `audit/evidence/frontend_service_calls.csv`
- `audit/evidence/backend_routes.csv`
- `audit/evidence/php_sql_matrix.csv`

## Estado

`COMPLETED_WITH_REVIEW_REQUIRED`: mapa candidato completo; nombres de dominio y responsabilidades deben validarse con usuarios de negocio.
