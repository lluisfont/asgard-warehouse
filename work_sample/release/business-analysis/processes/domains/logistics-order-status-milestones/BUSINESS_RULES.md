# Logistics Order Status Milestones - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-LOS-001 | El historial de estados se filtra por `cliente_id` de sesion y `embarque_id`. | `getEstadosPedidos` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-002 | El catalogo de estados excluye `deleted_at` y se filtra por cliente si existen estados especificos para ese cliente. | `getEstados` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-003 | Estados con `idtipoproveedor` nulo o que contiene `0` son seleccionables en el flujo cliente observado. | `getEstados` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-004 | La escritura depende de `dav_clienteusuariospermisos.idreportescliente = 73` con `escritura = 1`. | `estado-pedidos.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-005 | Si `logis_embarques.fecha_finalizacion` tiene valor, no se muestran botones de alta/guardado. | `estado-pedidos.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-006 | Cada hito nuevo se inserta en `logis_edp` con `created_type = CLIENTE` en el flujo inspeccionado. | `agregarEdp` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-007 | Los estados `53`, `99` y `160` finalizan el embarque. | `agregarEdp` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-008 | Para clientes `429` y `755`, estado `58` dispara email de actualizacion de fecha pick up. | `agregarEdp`, `enviarMailEstado` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-009 | Para otros estados/clientes se persiste una notificacion de actualizacion de estado y se emite Pusher. | `enviarNotificacionEstado` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-010 | El borrado de un hito es logico mediante `logis_edp.deleted_at`. | `eliminar` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-LOS-011 | El campo `valor` solo se muestra para estado seleccionado con id `11` en la UI inspeccionada. | `estado-pedidos.php` | INFERRED_DRAFT_REVIEW_REQUIRED |

