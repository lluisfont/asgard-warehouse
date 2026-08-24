# Technical component map: `index_archivos/libs/fileinput/js/fileinput.js`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  Ndac55dccf907["index_archivos/libs/fileinput/js/fileinput.js"]
  N5b18acb89016["EVENT_CONSUMER: index_archivos/libs/fileinput/js/fileinput.js"]
  N75b5aaf365fd["div"]
  N98c3d7dfcb34["self"]
  Ndac55dccf907 -- "sets_state" --> N75b5aaf365fd
  Ndac55dccf907 -- "sets_state" --> N98c3d7dfcb34
  Ndac55dccf907 -- "sets_state" --> N98c3d7dfcb34
  N5b18acb89016 -- "handled_by" --> Ndac55dccf907
```
