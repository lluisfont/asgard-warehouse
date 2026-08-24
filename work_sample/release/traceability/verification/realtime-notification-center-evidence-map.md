# realtime-notification-center Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Notification records are persisted before Pusher emission. | `ServicioNotificacionesClass.php` methods `guardarNotificacion`, `agregarDestinatarios`, `enviaNotificacion`; `ajax/enviarNotificacionesID.php` | High |
| Recipient verification is required before client-side toast display. | `ajax/verificarUsuario.php`, `ServicioNotificacionesClass.php` verification methods, `js/datos.js` `validarUsuario` | High |
| Read/unread state is stored per recipient notification. | `ajax/cambiarEstado.php`, `push_notificacionusuarios.idestado`, `cantidadNotificaciones*` | High |
| State `1` is treated as unread and state `3` as read. | `ajax/cambiarEstado.php`, `listaNotificaciones.php`, `cantidadNotificaciones*` | High |
| The official label of state `1` is ambiguous because creation comments call it sent/enviado. | `guardarNotificacion`, `agregarDestinatarios`, `cambiarEstado.php` | Medium |
| Destination URLs are hardcoded by event and recipient type. | `ServicioNotificacionesClass.php` method `generaUrl` and URL helper methods | High |
| Intercambio documental emits notification event 6 to multiple role-specific recipients. | `ajax/enviarNotificacionesID.php` | High |
| The schema supports event, state, notification type and user type catalogs. | `.data_base/asgard.sql:15128-15229` | High |
| Graphify includes `servicioNotificaciones` nodes and edges in the imported graph. | `.brownfield/work/release/traceability/verification/GRAPHIFY_GRAPH.json` entries for `index_archivos/servicioNotificaciones/*` | Medium |

## Review Needed

- Confirm catalog values in `push_eventos`, `push_estado`, `push_tiponotificacion` and `push_tipousuario`.
- Confirm authorization model for broad Pusher channel subscriptions.
- Confirm whether `push_notificacion.idestado` has business meaning after recipient states diverge.

