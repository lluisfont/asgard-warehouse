# Customs DAM Document Send Date Control - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Marcar la fecha de envio DAM de las facturas comerciales de una solicitud cuando desde intercambio documental se procesa el documento DAM y ya existe fecha de envio AP; si no existe AP, notificar que no se actualizo.

## Alcance observado

- Recepcion de `exchange_id` desde intercambio documental.
- Resolucion de `idcasosprevios` por intercambio directo o por embarque asociado.
- Validacion de existencia de al menos una `dav_facturacomercial.fechaenvioap`.
- Actualizacion masiva de `dav_facturacomercial.fechaenviodam=CURRENT_DATE()`.
- Envio de correo de alerta si no existe fecha AP previa.

## Fuera de alcance observado

- Carga del documento DAM al intercambio.
- Validacion del contenido del documento DAM.
- Generacion de DAM.
- Reversion de fecha DAM.
- Aprobacion manual del hito.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/intercambioDocumental/ajax/documento-dam.php:6-19` | Recibe `exchange_id` y resuelve `idcasosprevios`. |
| `index_archivos/intercambioDocumental/ajax/documento-dam.php:21-31` | Cuenta facturas comerciales con `fechaenvioap` informada. |
| `index_archivos/intercambioDocumental/ajax/documento-dam.php:38` | Actualiza `fechaenviodam` para casos de la solicitud. |
| `index_archivos/intercambioDocumental/ajax/documento-dam.php:40-46` | Envia correo si falta fecha AP. |

## Criterios de aceptacion candidatos

- La solicitud debe resolverse desde el intercambio documental.
- Solo se marca envio DAM si existe fecha AP previa.
- La fecha DAM se aplica a todas las facturas comerciales de casos asociados a la solicitud.
- Si no existe AP, debe notificarse por correo operativo.
