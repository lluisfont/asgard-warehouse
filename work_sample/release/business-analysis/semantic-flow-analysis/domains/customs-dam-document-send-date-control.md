# customs-dam-document-send-date-control - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 1
- Campos cruzados: 2
- Tablas con mutacion observada: 1
- Riesgos candidatos: documentos/OCR

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `dav_facturacomercial` | UPDATE | Entidad transaccional modificada por el flujo; sus cambios deben caracterizarse antes de refactor. | fechaenvioap, fechaenviodam | transicion o bloqueo por estado; regla documental/carga-descarga; calculo financiero/impositivo; persistencia/atomicidad/concurrencia; notificacion o acceso externo | index_archivos/intercambioDocumental/ajax/documento-dam.php:6-19 \| index_archivos/intercambioDocumental/ajax/documento-dam.php:21-31 \| index_archivos/intercambioDocumental/ajax/documento-dam.php:38 \| index_archivos/intercambioDocumental/ajax/documento-dam.php:40-46 \| documento-dam.php:6-19 \| documento-dam.php:21-31 \| documento-dam.php:38 \| documento-dam.php:40-46 \| EV-SQL_QUERY-EA5CECD49E45DC .data_base/asgard.sql:17749 READS access to dav_facturacomercial \| EV-SQL_QUERY-E0B4CE612BC841 .data_bas |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `dav_facturacomercial` | `fechaenvioap` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | BUSINESS_DATA | - Validacion de existencia de al menos una `dav_facturacomercial.fechaenvioap`. \| `index_archivos/intercambioDocumental/ajax/documento-dam.php:21-31` \| Cuenta facturas comerciales con `fechaenvioap` informada. \| BR-CDDSDC-002 \| La fecha DAM solo se actualiza si existe `fechaenvioap` distinta de `0000-00-00`. \| \| `idfacturacomercial`, `idcasos`, `fechaenvioap`, `fechaenviodam` \| ## Mutaciones observadas \| Correo de alerta cuando falta `fechaenvioap`. |
| `dav_facturacomercial` | `fechaenviodam` | Fecha/hora de evento del flujo; sirve para orden, plazo, vencimiento o auditoria. | BUSINESS_DATA | - Actualizacion masiva de `dav_facturacomercial.fechaenviodam=CURRENT_DATE()`. \| `index_archivos/intercambioDocumental/ajax/documento-dam.php:38` \| Actualiza `fechaenviodam` para casos de la solicitud. \| Si existe AP, actualiza `fechaenviodam` con la fecha actual. \| ```mermaid flowchart TD A["DAM desde intercambio"] --> B["Resolver solicitud"] B --> C["Verificar fecha envio AP"] C -->\|Existe AP\| D["Actualizar fechaenviodam"] C -->\|Sin AP\| E["Enviar correo de alerta"] ``` \| \| `idfacturacomercial`, `idcasos`, `fechaenvioap`, `fechaenviodam` \| ## Mutaciones observadas |
