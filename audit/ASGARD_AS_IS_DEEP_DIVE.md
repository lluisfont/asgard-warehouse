# ASGARD AS-IS Deep Dive

Estado: `COMPLETED_WITH_REVIEW_REQUIRED`

## Lectura ejecutiva

El repositorio implementa un sistema warehouse brownfield con backend PHP/Slim, frontend Angular y esquema MySQL versionado en `almacen.sql`. La superficie funcional observada esta concentrada en almacenes, datos maestros, contabilidad, embarques, usuarios, entidades, ATE-GAS, documentos/Excel/PDF y flujos OVP. Esta lectura es tecnica: cualquier denominacion funcional queda marcada como `INFERRED_DRAFT_REVIEW_REQUIRED` hasta validacion de negocio.

## Superficie principal

| Area | Evidencia | Lectura AS-IS |
| --- | --- | --- |
| Backend HTTP | `339` rutas Slim | API modular por archivos de rutas, con mayor peso en `almacenes.php`, `datosmaestro.php`, `contabilidad.php` y `embarques.php`. |
| Frontend | `99` rutas Angular y `328` llamadas HTTP | UI Angular con servicios por dominio; los servicios replican patrones de token, headers y endpoint strings. |
| Datos | `189` tablas, `1716` columnas | Modelo SQL fisico amplio; relaciones semanticas y ownership deben revisarse con base real y responsables. |
| SQL embebido | `2567` referencias | Logica de lectura/escritura repartida en PHP, especialmente rutas de almacen, contabilidad y OVP. |
| Integraciones | `1058` evidencias | Azure Blob, SendGrid, Freshchat/Freshservice, OVP/SOAP, cURL/API interna y filesystem local son candidatos relevantes. |
| Documentos | `4036` evidencias | Cargas, descargas, plantillas Excel, PDF/Word/QR/base64 e imagenes forman una superficie documental relevante. |

## Hotspots de codigo y operacion

### Rutas backend con mayor superficie

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
| AtlantesBE-main/AtlantesBE-main/app/routes/common.php | 1 |

### Servicios frontend con mayor acoplamiento HTTP

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

### Tablas mas referenciadas por SQL embebido

| Item | Count |
| --- | --- |
| t_cliente | 140 |
| t_tipocambio | 134 |
| t_embarque | 129 |
| t_usuario | 71 |
| t_factura | 58 |
| t_concepto | 56 |
| t_ciudad | 47 |
| t_cargo | 46 |
| t_salida | 46 |
| dav_pagosovp | 43 |
| t_ingreso | 42 |
| t_notadebito | 42 |
| t_ingresodetalle | 41 |
| t_embalaje | 37 |
| t_agentecarga | 36 |

### Tablas con mas operaciones de escritura observadas

| Tabla | Archivo | Insert | Update | Delete | Total |
| --- | --- | --- | --- | --- | --- |
| dav_pagosovp | AtlantesBE-main/AtlantesBE-main/app/functions/ovp.php | 7 | 20 | 0 | 43 |
| dav_cobros | AtlantesBE-main/AtlantesBE-main/app/functions/ovp.php | 0 | 18 | 0 | 18 |
| t_inventariofisicodetalle | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 5 | 7 | 2 | 27 |
| t_ingresodetalle | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 4 | 7 | 1 | 39 |
| t_pedidodetalle | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 4 | 2 | 5 | 36 |
| t_ubicacionitem | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 5 | 5 | 1 | 24 |
| t_pedidotienda | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 4 | 0 | 5 | 19 |
| t_pediodetalletienda | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 4 | 0 | 5 | 19 |
| t_cargo | AtlantesBE-main/AtlantesBE-main/app/routes/embarques.php | 4 | 2 | 2 | 11 |
| t_costo | AtlantesBE-main/AtlantesBE-main/app/routes/embarques.php | 4 | 2 | 2 | 11 |
| t_inventariofisico | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 2 | 6 | 0 | 16 |
| t_pedidodisponibilidad | AtlantesBE-main/AtlantesBE-main/app/routes/almacenes.php | 2 | 0 | 6 | 15 |

## Riesgos que condicionan refactorizacion

| Riesgo | Severidad | Evidencia | Impacto |
| --- | --- | --- | --- |
| SQL construido en PHP | High | `audit/evidence/sql_usage_refs.csv`, `FND-SEC-003` | Requiere caracterizacion y saneamiento antes de cambios profundos en rutas criticas. |
| Bootstrap expone CORS y errores amplios | High | `app/start.php`, `FND-SEC-001`, `FND-SEC-002` | Puede filtrar detalles o ampliar superficie si se despliega tal cual. |
| OVP/contabilidad centralizados | High | `app/functions/ovp.php`, `app/routes/contabilidad.php` | Riesgo alto de regresion si no se definen invariantes, conciliaciones y casos limite. |
| Archivos/documentos | Medium | `document_processing_catalog.csv` | Requiere politicas de MIME, path, autorizacion, retencion y almacenamiento. |
| Integraciones con secretos externos | Medium | `integration_catalog.csv`, `.env.example.php` local ignorado | Contratos, rotacion y owners deben formalizarse. |

## Evidencia Graphify

La ejecucion previa de Graphify sobre el repo real produjo `2932` nodos, `6227` edges y `229` comunidades. Los god nodes observados fueron consistentes con el inventario: `DatoMaestroService`, `UsuarioService`, `AlmacenesService`, `ContabilidadService`, `QRcode`, `ExcelModel`, `ExportExcelService`, `EntidadesService` y componentes de ingresos/salidas/inventario/ATE-GAS. Esta senal se usa como validacion arquitectonica secundaria; los CSV de `audit/evidence/` son la evidencia normativa del paquete.

## Cierre

`ASGARD-01` a `ASGARD-15` quedan cerradas como baseline tecnico reproducible. `ASGARD-16` y `ASGARD-17` no se generan porque dependen de validacion AS-IS y decisiones TO-BE posteriores.
