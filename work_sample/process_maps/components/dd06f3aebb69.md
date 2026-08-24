# Technical component map: `index_archivos/servicioNotificaciones/ajax/enviarNotificacionesID.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  Nf2761b1d8016["dav_clienteusuarios"]
  Ndd06f3aebb69["index_archivos/servicioNotificaciones/ajax/enviarNotificacionesID.php"]
  Na51071b19560["CLI_COMMAND: index_archivos/servicioNotificaciones/ajax/enviarNotificacionesID.php"]
  Ndd06f3aebb69 -- "reads" --> Nf2761b1d8016
  Na51071b19560 -- "handled_by" --> Ndd06f3aebb69
```
