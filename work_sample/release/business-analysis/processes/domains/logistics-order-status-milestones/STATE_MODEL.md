# Logistics Order Status Milestones - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Modelo observado

El estado no se guarda como un unico campo mutable del embarque, sino como historial append-only en `logis_edp`, con borrado logico. Algunos estados ademas actualizan el estado/finalizacion del embarque.

## Estados / hitos

| Elemento | Descripcion candidata |
| --- | --- |
| `logis_estados_edp.id` | Identificador tecnico de hito. |
| `logis_estados_edp.estado_edp` | Nombre del hito mostrado al usuario. |
| `logis_estados_edp.orden_etapa` | Orden de etapa o secuencia. |
| `logis_edp.fecha` | Fecha funcional del hito. |
| `logis_edp.created_at` | Fecha de registro del hito en sistema. |

## Transiciones observadas

| Transicion | Disparador | Resultado |
| --- | --- | --- |
| Embarque con historial | Nuevo hito | Inserta fila en `logis_edp`. |
| Embarque abierto | Estado `53`, `99` o `160` | Actualiza `logis_embarques.fecha_finalizacion`. |
| Hito activo | Eliminacion | Actualiza `logis_edp.deleted_at`. |
| Embarque finalizado | Apertura de UI | Se ocultan acciones de alta/guardado. |

## Comunicaciones observadas

| Condicion | Comunicacion |
| --- | --- |
| Cliente `429` o `755` y estado `58` | Email de actualizacion de fecha pick up y notificacion. |
| Otros casos | Notificacion persistida y Pusher. |

## Notas

- Los nombres de los estados finales no fueron inferidos porque dependen del catalogo.
- El hito `58` parece relacionado con fecha pick up por el texto del email, pero requiere validacion formal.

