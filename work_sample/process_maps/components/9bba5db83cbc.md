# Technical component map: `index_archivos/enviarsolicitud_ajax.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N8683c9bf7808["cc_chasis_codigo_documento"]
  N206450188091["dav_vehiculosprevios"]
  N9bba5db83cbc["index_archivos/enviarsolicitud_ajax.php"]
  Ne1091b0797e6["CLI_COMMAND: index_archivos/enviarsolicitud_ajax.php"]
  N9bba5db83cbc -- "writes" --> N8683c9bf7808
  N9bba5db83cbc -- "reads" --> N206450188091
  Ne1091b0797e6 -- "handled_by" --> N9bba5db83cbc
```
