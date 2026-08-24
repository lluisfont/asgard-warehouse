# customs-operational-kpi-control - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 3
- Campos cruzados: 5
- Tablas con mutacion observada: 0
- Riesgos candidatos: documentos/OCR; catalogos/semantica

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `logis_edp` | REPORTING_READ_MODEL | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | estado_edp_id | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; variante cliente pendiente de confirmar; catalogo/semantica pendiente | EV-SQL_QUERY-8F50B1D477DA3F .data_base/asgard.sql:17893 READS access to logis_edp \| EV-SQL_QUERY-F1C32982155A33 .data_base/asgard.sql:27604 READS access to logis_edp \| EV-SQL_QUERY-5B1AFC5002DA46 .data_base/asgard.sql:27616 READS access to logis_edp \| EV-SQL_QUERY-AFFC120BA34789 .data_base/asgard.sql:27851 READS access to logis_edp \| EV-SQL_QUERY-EE0833B6DDF8B2 .data_base/asgard.sql:27852 READS access to logis_edp \| EV-SQL_QUERY-3620ACCA479AFA .data_base/asgard.sql:27853 READS access to logis_ed |
| `logis_estados_edp` | REFERENCE_OR_STATE_CATALOG | Modelo de lectura o fuente de reporteria del flujo. | orden_etapa | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; variante cliente pendiente de confirmar; catalogo/semantica pendiente | EV-SQL_QUERY-0AFC222770F4D3 .data_base/asgard.sql:27607 READS access to logis_estados_edp \| EV-SQL_QUERY-4CD594FDD0D5FD .data_base/asgard.sql:27616 READS access to logis_estados_edp \| EV-SQL_QUERY-5AA64A39F8EEE1 .data_base/asgard.sql:27945 READS access to logis_estados_edp \| EV-SQL_QUERY-6056AB9D8D1DB4 .data_base/asgard.sql:27964 READS access to logis_estados_edp \| EV-SQL_QUERY-89714B754A69E1 .data_base/asgard.sql:39268 READS access to logis_estados_edp \| EV-SQL_QUERY-218F8B7BF2ED4D .data_base/a |
| `tck_asignacion_viaje` | REPORTING_READ_MODEL | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | alcance, estado, modalidad | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; variante cliente pendiente de confirmar; catalogo/semantica pendiente | EV-SQL_QUERY-756BC6D2A38BD4 .data_base/asgard.sql:27863 READS access to tck_asignacion_viaje \| EV-SQL_QUERY-98E05511405205 .data_base/asgard.sql:28674 READS access to tck_asignacion_viaje \| EV-SQL_QUERY-185076336AB6CF .data_base/asgard.sql:39522 READS access to tck_asignacion_viaje \| EV-SQL_QUERY-ACEB2EC9D2A1D2 .data_base/asgard.sql:44345 READS access to tck_asignacion_viaje \| EV-SQL_QUERY-F9A156D66479B2 .data_base/asgard.sql:44345 WRITES access to tck_asignacion_viaje \| EV-SQL_QUERY-A307570423D |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `logis_edp` | `estado_edp_id` | Campo de estado o hito usado para permitir, bloquear o reportar avance. | BUSINESS_DATA | `logis_edp.fecha` y `estado_edp_id` \| Hitos operativos/aduaneros. |
| `logis_estados_edp` | `orden_etapa` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | BR-COKC-009 \| Estado OL del embarque se deriva por maxima `orden_etapa` de EDP. \| `logis_estados_edp.orden_etapa` \| Orden para estado actual de embarque. \| \| `reporteseguimientoquery.php` \| ## Estado de embarque OL El estado actual se infiere desde la maxima `orden_etapa` en `logis_estados_edp` para el embarque y luego se resuelve el nombre de etapa. |
| `tck_asignacion_viaje` | `alcance` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | ## Alcance observado \| ## Fuera de alcance observado \| Cliente \| Limita el alcance de reportes por sesion y filtros. |
| `tck_asignacion_viaje` | `estado` | Campo de estado o hito usado para permitir, bloquear o reportar avance. | BUSINESS_DATA | # Customs Operational KPI Control - Process Definition ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## Objetivo de negocio Medir el cumplimiento operacional aduanero y logistico mediante reportes de seguimiento, controles AD/OL y KPIs de tiempos entre hitos: solicitud, envio AP, envio DAM, documentacion requerida, pago DIM, validacion, planillaje, nacionaliza... \| - Control OL con EDP, operador, proveedor, agente, inhouse, estados y asignaciones de viaje. \| Creacion primaria de embarques y estados EDP. \| # Customs Operational KPI Control - Process Flow ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## F |
| `tck_asignacion_viaje` | `modalidad` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | - Filtros de proveedor, agente, linea, modalidad, coordinador, operador, regimen, almacen, aduana, tipo producto y estado. |
