# operational-case-dossier-access - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 1
- Campos cruzados: 4
- Tablas con mutacion observada: 0
- Riesgos candidatos: permisos/autorizacion; documentos/OCR; catalogos/semantica

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `dav_facturaplanilla` | REPORTING_READ_MODEL | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | cliente, fecha, idtipodoc, nit | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; variante cliente pendiente de confirmar; catalogo/semantica pendiente; seguridad/autorizacion sensible | EV-SQL_QUERY-7A8D0839363E40 .data_base/asgard.sql:17695 READS access to dav_facturaplanilla \| EV-SQL_QUERY-FE2F4B4D50414D .data_base/asgard.sql:17701 READS access to dav_facturaplanilla \| EV-SQL_QUERY-380B7E65D6B753 .data_base/asgard.sql:17761 READS access to dav_facturaplanilla \| EV-SQL_QUERY-B852A980F6E3B6 .data_base/asgard.sql:17785 READS access to dav_facturaplanilla \| EV-SQL_QUERY-0E7A5CD6CC7AB3 .data_base/asgard.sql:17821 READS access to dav_facturaplanilla \| EV-SQL_QUERY-CA801278D001D7 .d |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `dav_facturaplanilla` | `cliente` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | Usuario cliente/proveedor \| Accede a subconjuntos de documentos segun tipo de usuario. \| Los expedientes se filtran por cliente de sesion y excluyen casos anulados. \| - Usuario cliente tipo `2` en vehiculos ve documentos filtrados por reglas observadas. \| ASGARD consulta casos no anulados del cliente y aplica filtros. \| BR-OCDA-001 \| Busqueda de expedientes se limita a cliente de sesion. |
| `dav_facturaplanilla` | `fecha` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | BUSINESS_DATA | Busqueda de archivos y documentos por pedido, DIM, carpeta, fecha, lote/chasis u orden de compra. \| - Consolidacion de factura comercial, planilla, lote/chasis, fechas de validacion, pago DIM y facturacion. \| - Reporte de envio/entrega de planillas vehiculares por chasis, agencia, partida, DIM, FRV, fecha de nacionalizacion y fecha de entrega. \| El usuario elige criterio: pedido, DIM, carpeta, fecha, lote/chasis u orden de compra. \| El usuario consulta datos de despacho con filtros de fecha/proveedor/linea. |
| `dav_facturaplanilla` | `idtipodoc` | Referencia funcional que vincula el flujo con otra entidad/catalogo. | BUSINESS_DATA | `dav_documentos.idtipodocumento=19` \| Factura comercial. |
| `dav_facturaplanilla` | `nit` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | PERSONAL_OR_CONTACT_DATA | # Operational Case Dossier Access - Process Definition ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## Objetivo de negocio Permitir la busqueda de expedientes operativos por caso/carpeta y el acceso a documentos asociados, incluyendo archivos subidos, facturas/planillas generadas y datos resumidos de despacho. |
