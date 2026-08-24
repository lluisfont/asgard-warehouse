# Technical component map: `index_archivos/ajax/DashboardGenerico.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N5ad9d6d3d5dc["dav_casos"]
  N1a79709569dd["dav_edp"]
  Nd1e1e810ab98["dav_estadoedp"]
  N0aceb5c55c71["dav_etapaedp"]
  Naaabdb2d9731["index_archivos/ajax/DashboardGenerico.php"]
  N68c66f06e546["CLI_COMMAND: index_archivos/ajax/DashboardGenerico.php"]
  Naaabdb2d9731 -- "reads" --> N5ad9d6d3d5dc
  Naaabdb2d9731 -- "reads" --> N5ad9d6d3d5dc
  Naaabdb2d9731 -- "reads" --> N1a79709569dd
  Naaabdb2d9731 -- "reads" --> Nd1e1e810ab98
  Naaabdb2d9731 -- "reads" --> N0aceb5c55c71
  N68c66f06e546 -- "handled_by" --> Naaabdb2d9731
```
