# Customs Document Approval - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| CDA-BR-001 | Un documento previo registra tipo, emisor, formato, numero, fecha, importe, divisa y opcionalmente adjunto. | `documentacion.php:86-109`, `.data_base/asgard.sql:5484-5502` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CDA-BR-002 | Otros documentos se registran en `dav_otrosdocumentosprevios` con descripcion y adjunto opcional. | `documentacion.php:118-140`, `.data_base/asgard.sql:7839-7848` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CDA-BR-003 | La importacion temporal reemplaza documentos previos del caso para los tipos incluidos en la carga. | `documentacion.php:234-271` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CDA-BR-004 | Documentos intermedios se convierten en documentos previos y luego se ocultan con `ocultar = 1`. | `documentacionaprobado.php:196-241`, `.data_base/asgard.sql:6893-6898` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CDA-BR-005 | Documentos con `aceptar = 1` no se muestran en ciertos flujos de aprobacion pendiente. | `documentacionaprobado.php:970-999` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CDA-BR-006 | Documentos con datos de emisor y `aceptar` 0, 2 o 3 pueden marcarse como `aceptar = 4` para envio. | `documentacionaprobado.php:316` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CDA-BR-007 | Otros documentos pendientes de envio usan `estado` 0 o 3 y `transportista = 0`. | `documentacionaprobado.php:442-476` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CDA-BR-008 | Al finalizar/envio general, otros documentos se marcan `enviado = 1`, `estado = 1`. | `finsolicitud.php:375` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CDA-BR-009 | Se permite eliminar documentos, duplicar documentos y quitar adjuntos desde acciones por GET. | `documentacion.php:330-357`, `documentacionaprobado.php:281-312` | INFERRED_DRAFT_REVIEW_REQUIRED |

## Pendiente de Confirmar

- Semantica exacta de valores `aceptar` 0, 1, 2, 3 y 4.
- Semantica exacta de valores `estado` en otros documentos.
- Politica de versionado de adjuntos reemplazados o eliminados.
