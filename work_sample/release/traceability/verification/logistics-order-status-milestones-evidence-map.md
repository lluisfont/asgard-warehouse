# logistics-order-status-milestones Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Order-status milestones are stored in `logis_edp`. | `EstadoPedidosClass.php` method `agregarEdp`; `.data_base/asgard.sql:12088-12115` | High |
| The UI blocks capture unless the user has permission `73` with write access. | `frames/estado-pedidos.php` | High |
| The UI blocks capture when `logis_embarques.fecha_finalizacion` is present. | `frames/estado-pedidos.php` | High |
| States `53`, `99`, `160` finalize the shipment. | `EstadoPedidosClass.php` method `agregarEdp` | High |
| State `58` for clients `429`/`755` sends pick-up email. | `EstadoPedidosClass.php` methods `agregarEdp`, `enviarMailEstado` | High |
| Other state updates create realtime notifications. | `EstadoPedidosClass.php` method `enviarNotificacionEstado` | High |
| States catalog is client-aware and provider-type filtered. | `EstadoPedidosClass.php` method `getEstados`; `.data_base/asgard.sql:12422-12442` | High |
| Milestone deletion is logical. | `EstadoPedidosClass.php` method `eliminar` | High |

## Review Needed

- Confirm names and business meaning of state ids `11`, `53`, `58`, `99`, `160`.
- Confirm whether Pusher event `crearSolicitud` is correct for order-status updates.
- Review notification method `enviarNotificacion` for variable-scope defects before refactor.
- Confirm if clients/providers/internal users can all create milestones and expected `created_type` values.

