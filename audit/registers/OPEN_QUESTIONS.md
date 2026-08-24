# Open Questions

| ID | Fase | Pregunta | Motivo |
| --- | --- | --- | --- |
| OQ-DB-001 | ASGARD-07 | Existen stored procedures, triggers, eventos o vistas en la base real que no esten en `almacen.sql`? | El dump versionado no permite confirmar objetos runtime externos. |
| OQ-AUTH-001 | ASGARD-08 | Cual es la matriz oficial rol/permiso/endpoints? | El frontend usa `tokenDetalle.permisos`, pero falta matriz revisada endpoint por endpoint. |
| OQ-INT-001 | ASGARD-09 | Cuales son los contratos, timeouts, reintentos y owners de Azure Blob, SendGrid, Freshchat, OVP y APIs internas? | El codigo muestra llamadas/configuracion, no los acuerdos operativos. |
| OQ-DOC-001 | ASGARD-10 | Que politicas de retencion y acceso aplican a imagenes, Excel, PDF y archivos generados? | La evidencia tecnica no define politica documental. |
| OQ-BATCH-001 | ASGARD-12 | Hay cron jobs o tareas programadas fuera del repositorio en WAMP/servidor/Windows Task Scheduler? | No hay scheduler versionado. |
| OQ-SEC-001 | ASGARD-13 | El entorno productivo deshabilita error details y restringe CORS? | El bootstrap versionado no diferencia entorno. |
| OQ-ACC-001 | ASGARD-11 | Cuales invariantes contables/OVP son obligatorios y cuales son historicos/legacy? | `ovp.php` concentra reglas criticas extensas. |
