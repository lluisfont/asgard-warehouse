# Technical component map: `index_archivos/operativos/LogReportes.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N938f146a74ad["log_asgard_ecosistema"]
  Nb4ffb6ffa514["index_archivos/operativos/LogReportes.php"]
  N562ed8fd5619["CLI_COMMAND: index_archivos/operativos/LogReportes.php"]
  Nb4ffb6ffa514 -- "writes" --> N938f146a74ad
  N562ed8fd5619 -- "handled_by" --> Nb4ffb6ffa514
```
