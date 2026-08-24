# Technical component map: `index_archivos/logistica/ajax/fechaPickupNotificacion.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  Nf2761b1d8016["dav_clienteusuarios"]
  N09baa9a9cbda["logis_pedidos_detalle"]
  N73a5e4e8efaa["index_archivos/logistica/ajax/fechaPickupNotificacion.php"]
  N2388b2ec9161["CLI_COMMAND: index_archivos/logistica/ajax/fechaPickupNotificacion.php"]
  N73a5e4e8efaa -- "reads" --> Nf2761b1d8016
  N73a5e4e8efaa -- "reads" --> N09baa9a9cbda
  N2388b2ec9161 -- "handled_by" --> N73a5e4e8efaa
```
