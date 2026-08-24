# nationalization-weekly-planning - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 2
- Campos cruzados: 5
- Tablas con mutacion observada: 0
- Riesgos candidatos: documentos/OCR

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `dav_casos` | READ_OR_CONTEXT | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | carpeta, fecha_planificacion_nacionalizacion, pedido | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; notificacion o acceso externo; seguridad/autorizacion sensible | EV-SQL_QUERY-1E16A453C0FC22 .data_base/asgard.sql:17707 READS access to dav_casos \| EV-SQL_QUERY-C8AB19C9274D78 .data_base/asgard.sql:17755 READS access to dav_casos \| EV-SQL_QUERY-85B23A1F6B1886 .data_base/asgard.sql:17773 READS access to dav_casosprevios \| EV-SQL_QUERY-A3D8E3282D4ABA .data_base/asgard.sql:17773 READS access to dav_casos \| EV-SQL_QUERY-1A08C59D5D207E .data_base/asgard.sql:17815 READS access to dav_casostarea \| EV-SQL_QUERY-F5AD1D6FB7CE71 .data_base/asgard.sql:17821 READS access |
| `part_planificacion_partida` | READ_OR_CONTEXT | Entidad de contexto usada por el flujo para consultar o relacionar informacion de negocio. | fecha_planificacion, partida | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; notificacion o acceso externo; seguridad/autorizacion sensible |  |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `dav_casos` | `carpeta` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | - Busqueda/listado de chasis con partida, carpeta, tipo de declaracion, fecha de arribo y fecha planificada. \| ASGARD muestra partida, chasis, carpeta, tipo de declaracion, fecha de arribo y fecha planificada. \| BR-NWP-002 \| El listado muestra partida, chasis, carpeta, tipo de declaracion, fecha de arribo y fecha planificada. \| `carpeta` \| Carpeta/caso relacionado. \| The UI lists chasis, partida, carpeta, declaration type, arrival date and planned nationalization date. |
| `dav_casos` | `fecha_planificacion_nacionalizacion` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | PERSONAL_OR_CONTACT_DATA | - Persistencia candidata en `dav_casos.fecha_planificacion_nacionalizacion`. \| `.data_base/asgard.sql` \| Campo `dav_casos.fecha_planificacion_nacionalizacion`. \| BR-NWP-008 \| La fecha planificada candidata se almacena en `dav_casos.fecha_planificacion_nacionalizacion`. \| `fecha_planificacion_nacionalizacion` \| Fecha planificada de nacionalizacion. \| `dav_casos.fecha_planificacion_nacionalizacion` \| Campo candidato de persistencia en ASGARD. |
| `dav_casos` | `pedido` | Dato documental o referencia a soporte/carga/descarga dentro del flujo. | BUSINESS_DATA | Implementacion backend de `url_pedidos` para cargar/confirmar/exportar. \| API `url_pedidos` \| Procesa archivo, valida nacionalizados, confirma y exporta. \| `pedido` / partida \| Partida asociada al chasis. \| Weekly planning upload is delegated to `url_pedidos` endpoint `cargar-planificacion`. |
| `part_planificacion_partida` | `fecha_planificacion` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | PERSONAL_OR_CONTACT_DATA | - Persistencia candidata en `dav_casos.fecha_planificacion_nacionalizacion`. \| `.data_base/asgard.sql` \| Campo `dav_casos.fecha_planificacion_nacionalizacion`. \| BR-NWP-008 \| La fecha planificada candidata se almacena en `dav_casos.fecha_planificacion_nacionalizacion`. \| `fecha_planificacion_nacionalizacion` \| Fecha planificada de nacionalizacion. \| `dav_casos.fecha_planificacion_nacionalizacion` \| Campo candidato de persistencia en ASGARD. |
| `part_planificacion_partida` | `partida` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | - Busqueda/listado de chasis con partida, carpeta, tipo de declaracion, fecha de arribo y fecha planificada. \| - Reprogramacion completa por motivo, aunque existe tabla relacionada `part_planificacion_partida`. \| `.data_base/asgard.sql` \| Tabla `part_planificacion_partida` con fecha de planificacion y motivo de reprogramacion. \| ASGARD muestra partida, chasis, carpeta, tipo de declaracion, fecha de arribo y fecha planificada. \| BR-NWP-002 \| El listado muestra partida, chasis, carpeta, tipo de declaracion, fecha de arribo y fecha planificada. |
