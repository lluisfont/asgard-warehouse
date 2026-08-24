# Technical component map: `index_archivos/jqGrid/js/jquery.jqGrid.src.js`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  Nfa23c35efca7["index_archivos/jqGrid/js/jquery.jqGrid.src.js"]
  N0cd3039f58f6["QUEUE_WORKER: index_archivos/jqGrid/js/jquery.jqGrid.src.js"]
  N9be6cbbf6ee1["200"]
  Nfa23c35efca7 -- "sets_state" --> N9be6cbbf6ee1
  Nfa23c35efca7 -- "sets_state" --> N9be6cbbf6ee1
  N0cd3039f58f6 -- "handled_by" --> Nfa23c35efca7
```
