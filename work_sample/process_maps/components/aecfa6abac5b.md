# Technical component map: `index_archivos/versolicituddetalle.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N2e62e50d2f5d["dav_division"]
  N69231407d2f6["dav_proveedorcoordinador"]
  N7e75b82f1ff0["dav_usuario"]
  Naecfa6abac5b["index_archivos/versolicituddetalle.php"]
  Ned46a903cd64["CLI_COMMAND: index_archivos/versolicituddetalle.php"]
  Naecfa6abac5b -- "reads" --> N2e62e50d2f5d
  Naecfa6abac5b -- "reads" --> N69231407d2f6
  Naecfa6abac5b -- "reads" --> N7e75b82f1ff0
  Naecfa6abac5b -- "reads" --> N7e75b82f1ff0
  Ned46a903cd64 -- "handled_by" --> Naecfa6abac5b
```
