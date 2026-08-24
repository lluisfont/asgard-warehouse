# Evidence Map - continuous-improvement-nonconformity

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| The module manages continuous improvement/nonconformity cases. | `index_archivos/mejora-continua/views/*`, `.data_base/asgard.sql:15468-15531` | High |
| Case states are explicitly defined in frontend constants. | `index_archivos/mejora-continua/js/constantes.js:1-11` | High |
| User roles are administrator, analyst and regular. | `index_archivos/mejora-continua/js/constantes.js:13-15` | High |
| API calls use `X-CREDENTIALS` with user and customer context. | `index_archivos/mejora-continua/js/config.js:1-14` | High |
| Case registration payload includes classification, impact, responsible, dates, descriptions and attachments. | `index_archivos/mejora-continua/views/formulario-caso.php:153-218` | High |
| Impact selection drives assignment and attention deadlines. | `index_archivos/mejora-continua/components/casos/detalle-hallazgo.js:547-571` | High |
| Administrators can open sent cases for assignment. | `index_archivos/mejora-continua/components/commons/tbl-hallazgos.js:45-55` | High |
| Assignment records normative/requisite context and analyst/deadline data. | `index_archivos/mejora-continua/components/casos/asignacion-analista.js:1-251`, `views/asignacion-analista.php:100-165` | High |
| Cause analysis records action plan, expected results, responsible users and dates. | `index_archivos/mejora-continua/components/casos/analisis.js:1-180`, `.data_base/asgard.sql:15248-15276` | High |
| Verification captures notes, evidence files and verified flags per action. | `index_archivos/mejora-continua/components/casos/verificacion.js:1-151`, `views/verificacion.php:180-244` | High |
| Closure submits verification result. | `index_archivos/mejora-continua/views/cerrar-caso.php:160-229` | High |
| Reopening is supported and schema stores previous/new case linkage. | `index_archivos/mejora-continua/views/re-apertura.php:120-199`, `.data_base/asgard.sql:15516-15518` | Medium |

## Graphify Use

Graphify output is supporting context only. Frontend/API call evidence and SQL schema are authoritative for this candidate domain.
