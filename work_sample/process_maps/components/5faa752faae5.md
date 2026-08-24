# Technical component map: `index_archivos/operativos/getIHAgencia.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N835b8ed490f8["ada_agentesaduana"]
  Nac61ed3adc65["ada_agentescontactos"]
  N540098db87c7["ada_clienteagenteaduana"]
  N5faa752faae5["index_archivos/operativos/getIHAgencia.php"]
  N4d9f33b6cfa1["CLI_COMMAND: index_archivos/operativos/getIHAgencia.php"]
  N5faa752faae5 -- "reads" --> N835b8ed490f8
  N5faa752faae5 -- "reads" --> Nac61ed3adc65
  N5faa752faae5 -- "reads" --> N540098db87c7
  N4d9f33b6cfa1 -- "handled_by" --> N5faa752faae5
```
