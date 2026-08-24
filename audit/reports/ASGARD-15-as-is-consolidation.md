# ASGARD-15 - Consolidado arquitectonico AS-IS

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`


## Veredicto

`COMPLETED_WITH_REVIEW_REQUIRED`.

El sistema AS-IS queda reconstruido a nivel tecnico suficiente para iniciar planificacion de refactorizacion: backend PHP/Slim, frontend Angular, SQL fisico, rutas, servicios HTTP, integraciones, documentos, seguridad y matriz PHP-SQL. Las afirmaciones de negocio permanecen candidatas hasta validacion humana.

## Baseline de arquitectura

| Capa | Evidencia | Lectura |
| --- | --- | --- |
| Frontend | Angular, `app.routing.ts`, servicios en `src/app/services` | Navegacion amplia por dominios; consumo HTTP distribuido por servicios. |
| API | Slim/PHP en `public/index.php`, `app/start.php`, `app/routes/*.php` | Bootstrap central, rutas por modulo, SQL embebido y servicios auxiliares. |
| Datos | `almacen.sql`, `database_*.csv`, `php_sql_matrix.csv` | Esquema MySQL amplio con tablas operativas, maestras, contables/documentales. |
| Integraciones | `integration_catalog.csv` | Azure Blob, SendGrid, OVP/SOAP, Freshchat/Freshservice, cURL/API interna. |
| Documentos | `document_processing_catalog.csv` | Excel/PDF/Word/QR/base64/uploads/downloads e imagenes ATE-GAS. |

## Riesgos de cierre

| Prioridad | Riesgo | Registro |
| --- | --- | --- |
| Alta | SQL interpolation/concatenacion en rutas y funciones PHP. | `FND-SEC-003` |
| Alta | CORS abierto y error middleware verboso. | `FND-SEC-001`, `FND-SEC-002` |
| Alta | OVP/contabilidad sin invariantes revisadas. | `FND-ACC-001`, `OQ-ACC-001` |
| Media | Politicas documentales y almacenamiento pendientes. | `OQ-DOC-001` |
| Media | Schedulers/objetos DB externos no confirmados. | `OQ-BATCH-001`, `OQ-DB-001` |

## Evidencias de cierre

- `audit/README.md`
- `audit/ASGARD_AS_IS_DEEP_DIVE.md`
- `audit/verification/ANALYSIS_COMPLETENESS_REPORT.md`
- `audit/verification/VERIFICATION_REPORT.md`
- `audit/registers/FINDINGS_REGISTER.md`
- `audit/registers/OPEN_QUESTIONS.md`
- `audit/evidence/*.csv`

## Condiciones para pasar a TO-BE

1. Validar dominios y reglas con responsables de operacion.
2. Revisar hallazgos High de seguridad y contabilidad/OVP.
3. Confirmar schedulers, objetos DB fuera del dump y contratos de integracion reales.
4. Definir suite de caracterizacion para rutas criticas antes de modernizar.
