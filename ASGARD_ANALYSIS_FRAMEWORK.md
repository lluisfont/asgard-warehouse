# Marco comun de analisis ASGARD

Este marco define el vocabulario comun para comparar repositorios ASGARD durante la refactorizacion. Su objetivo es que todos los proyectos cierren la revision AS-IS con el mismo nivel minimo de evidencia antes de pasar a arquitectura TO-BE o roadmap.

## Criterio de cierre

La revision AS-IS se considera cerrada solo cuando `ASGARD-01` a `ASGARD-15` estan completadas, revisadas y trazadas a evidencias. `ASGARD-16` y `ASGARD-17` son fases posteriores: dependen del consolidado AS-IS y no deben usarse para rellenar lagunas del diagnostico.

Todas las afirmaciones funcionales o de negocio inferidas deben marcarse como `INFERRED_DRAFT_REVIEW_REQUIRED` hasta que existan evidencias cruzadas o validacion humana. El marco no convierte hipotesis en hechos.

## Fases comunes

| Fase | Objetivo | Necesaria para cerrar revision |
| --- | --- | --- |
| ASGARD-01 | Inventario del repositorio | Si |
| ASGARD-02 | Entry points, HTTP, AJAX, ciclo de request | Si |
| ASGARD-03 | Mapa funcional de modulos | Si |
| ASGARD-04 | Arquitectura PHP, clases, includes, dependencias | Si |
| ASGARD-05 | Modelo completo de base de datos | Si |
| ASGARD-06 | Matriz PHP <-> SQL | Si |
| ASGARD-07 | Stored procedures, funciones, vistas y reglas de negocio DB | Si |
| ASGARD-08 | Autenticacion, sesion, roles y permisos | Si |
| ASGARD-09 | Integraciones externas e internas | Si |
| ASGARD-10 | Documentos, OCR, PDF, Excel, Word | Si |
| ASGARD-11 | Contabilidad, OVP y logica critica relacionada | Si |
| ASGARD-12 | Cron, batch y procesamiento background | Si |
| ASGARD-13 | Seguridad tecnica | Si |
| ASGARD-14 | Deuda tecnica, defectos y riesgos | Si |
| ASGARD-15 | Consolidado arquitectonico AS-IS | Si, cierre |
| ASGARD-16 | Arquitectura TO-BE | Despues |
| ASGARD-17 | Roadmap de modernizacion | Despues |

## Entregables minimos por fase

| Fase | Entregables esperados |
| --- | --- |
| ASGARD-01 | Alcance analizado, inventario de ficheros, hashes, tecnologias, dependencias, exclusiones y limitaciones. |
| ASGARD-02 | Catalogo de entry points, rutas HTTP, controladores, endpoints AJAX, formularios, uploads/downloads y ciclo request-response. |
| ASGARD-03 | Catalogo de modulos funcionales, responsabilidad de cada modulo, pantallas asociadas, actores, reglas principales y dependencias funcionales. |
| ASGARD-04 | Mapa de arquitectura PHP: includes, clases, funciones compartidas, dependencias entre componentes, inicializacion, configuracion y puntos de acoplamiento. |
| ASGARD-05 | Diccionario de datos completo: tablas, columnas, claves, relaciones, indices, constraints, enums, tablas huerfanas y riesgos de migracion. |
| ASGARD-06 | Matriz PHP <-> SQL con lecturas, escrituras, objetos afectados, endpoints o procesos invocantes y trazabilidad a fuente. |
| ASGARD-07 | Catalogo de SP, funciones, vistas, triggers/eventos si existen, reglas implementadas en base de datos, efectos laterales y dependencias. |
| ASGARD-08 | Modelo de autenticacion, sesion, roles, permisos, matrices de autorizacion, recursos protegidos y gaps detectados. |
| ASGARD-09 | Catalogo de integraciones, contratos AS-IS, autenticacion por integracion, mapeos de campos, reintentos, timeouts, errores y riesgos. |
| ASGARD-10 | Catalogo documental: tipos de documento, generacion, OCR, import/export Office, PDF, almacenamiento, validacion, retencion y permisos. |
| ASGARD-11 | Analisis de contabilidad, OVP y logica critica: reglas, calculos, estados, transacciones, conciliaciones, invariantes y casos limite. |
| ASGARD-12 | Catalogo de cron, jobs, workers, comandos, colas o batches; frecuencia, entradas, salidas, dependencias, idempotencia y recuperacion ante fallos. |
| ASGARD-13 | Baseline de seguridad tecnica: secretos, SQL injection, CSRF, IDOR, control de acceso, subida de ficheros, aislamiento, abuse cases y hallazgos. |
| ASGARD-14 | Registro priorizado de deuda tecnica, defectos, duplicidades, codigo muerto, obsolescencia, observabilidad, rendimiento y riesgos de refactor. |
| ASGARD-15 | Consolidado AS-IS: arquitectura, dominios, flujos, datos, integraciones, seguridad, riesgos, preguntas abiertas y decision de cierre. |
| ASGARD-16 | Arquitectura TO-BE, principios, opciones, ADRs, componentes destino, migracion de datos, seguridad destino y estrategia de transicion. |
| ASGARD-17 | Roadmap de modernizacion, fases, dependencias, quick wins, riesgos, criterios de exito, plan de pruebas, rollout y rollback. |

## Correspondencia con Brownfield Refactor Kit

| Fase ASGARD | Comandos o artefactos del kit que la alimentan |
| --- | --- |
| ASGARD-01 | `verify-config`, `inventory`, `seed-baseline`, `engineering-analysis/baseline/*` |
| ASGARD-02 | `extract`, `process-map`, `engineering-analysis/interfaces/*`, `REQUEST_LIFECYCLE.md` |
| ASGARD-03 | `process-map`, `semantic-flows`, `business-analysis/processes/*`, `DOMAIN_FLOW_SEMANTIC_SUMMARY.csv` |
| ASGARD-04 | `extract`, `graphify-import`, `engineering-analysis/architecture/*` |
| ASGARD-05 | `data-dictionary`, `semantic-data`, `engineering-analysis/data/*` |
| ASGARD-06 | `process-map`, `semantic-flows`, `FLOW_TABLE_USAGE_MATRIX.csv`, `FLOW_FIELD_USAGE_MATRIX.csv` |
| ASGARD-07 | `data-dictionary`, `DATABASE_OBJECTS_CATALOG.md`, `BUSINESS_RULE_CATALOG.md` |
| ASGARD-08 | `extract`, `security-analyze`, `engineering-analysis/security/*` |
| ASGARD-09 | `extract`, `architecture-reconstruct`, `engineering-analysis/integrations/*` |
| ASGARD-10 | `process-map`, `business-reconstruct`, `business-analysis/documents/*`, `FILE_UPLOAD_DOWNLOAD_CATALOG.md` |
| ASGARD-11 | `business-rules-reconstruct`, `transaction-analyze`, `engineering-analysis/behavior/*` |
| ASGARD-12 | `process-map`, `JOB_AND_WORKER_CATALOG.md`, `SCHEDULED_TASKS_CATALOG.md`, `BACKGROUND_PROCESSING_ARCHITECTURE.md` |
| ASGARD-13 | `security-analyze`, `engineering-analysis/security/*`, `SECURITY_FINDINGS.md` |
| ASGARD-14 | `architecture-reconstruct`, `security-analyze`, `engineering-analysis/quality/*`, `REFACTORING_RISK_REGISTER.md` |
| ASGARD-15 | `verify-baseline`, `consolidate-release`, `ANALYSIS_COMPLETENESS_REPORT.md`, `VERIFICATION_REPORT.md` |
| ASGARD-16 | `openspec-baseline`, OpenSpec context/specs, TO-BE design artifacts |
| ASGARD-17 | OpenSpec changes, roadmap, migration, rollout, rollback and test plans |

## Estado recomendado

Usar estos valores de estado en todos los proyectos:

- `PENDING`: fase no iniciada.
- `IN_PROGRESS`: fase en reconstruccion.
- `INFERRED_DRAFT_REVIEW_REQUIRED`: existen hipotesis utiles, pero falta validacion.
- `BLOCKED`: no puede avanzar por falta de codigo, datos, credenciales, entorno o decision.
- `REVIEWED`: revisada por el equipo, con dudas abiertas documentadas.
- `COMPLETED`: evidencia suficiente y entregables minimos presentes.
- `NOT_APPLICABLE`: la fase no aplica al repositorio y la razon esta documentada.

## Plantilla de seguimiento

| Fase | Estado | Evidencias clave | Entregables | Bloqueos / preguntas |
| --- | --- | --- | --- | --- |
| ASGARD-01 | PENDING | | | |
| ASGARD-02 | PENDING | | | |
| ASGARD-03 | PENDING | | | |
| ASGARD-04 | PENDING | | | |
| ASGARD-05 | PENDING | | | |
| ASGARD-06 | PENDING | | | |
| ASGARD-07 | PENDING | | | |
| ASGARD-08 | PENDING | | | |
| ASGARD-09 | PENDING | | | |
| ASGARD-10 | PENDING | | | |
| ASGARD-11 | PENDING | | | |
| ASGARD-12 | PENDING | | | |
| ASGARD-13 | PENDING | | | |
| ASGARD-14 | PENDING | | | |
| ASGARD-15 | PENDING | | | |
| ASGARD-16 | PENDING | | | |
| ASGARD-17 | PENDING | | | |

## Reglas de comparabilidad

- Mantener la misma numeracion `ASGARD-01` a `ASGARD-17` en todos los repositorios.
- No cerrar `ASGARD-15` si alguna fase obligatoria `ASGARD-01` a `ASGARD-14` esta `PENDING`, `IN_PROGRESS` o `BLOCKED`.
- Permitir `NOT_APPLICABLE` solo con justificacion y evidencia negativa, por ejemplo ausencia verificada de jobs o integraciones.
- Separar hallazgos AS-IS de decisiones TO-BE. Las recomendaciones pueden registrarse, pero no sustituyen la reconstruccion del comportamiento actual.
- Trazar cada afirmacion relevante a fichero, linea, SQL, artefacto generado, entrevista o decision documentada.
- Registrar contradicciones y preguntas abiertas aunque el cierre sea posible; el cierre exige visibilidad, no perfeccion absoluta.
