# Technical component map: `index_archivos/js/spreadsjs/external/jquery-2.0.2.js`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  Nb7c86dcaecd1["index_archivos/js/spreadsjs/external/jquery-2.0.2.js"]
  N2af3962c9439["EVENT_CONSUMER: index_archivos/js/spreadsjs/external/jquery-2.0.2.js"]
  N1e9863485105["0"]
  Nad634ffbc354["1"]
  N183da03e3fbf["2"]
  N2ff892ca6441["function"]
  N538f3cb70d34["isBool"]
  Nc50ee6c42db8["isHidden"]
  Nf44dcf1e273d["parsererror"]
  N61447b7404fc["pending"]
  N8ea1c072da1d["stateString"]
  N7ffba14f0c28["stateVal"]
  N4dc0b7c401b8["status"]
  N887d2f4256a8["success"]
  Nb7c86dcaecd1 -- "sets_state" --> N1e9863485105
  Nb7c86dcaecd1 -- "sets_state" --> N1e9863485105
  Nb7c86dcaecd1 -- "sets_state" --> Nad634ffbc354
  Nb7c86dcaecd1 -- "sets_state" --> N183da03e3fbf
  Nb7c86dcaecd1 -- "sets_state" --> N2ff892ca6441
  Nb7c86dcaecd1 -- "sets_state" --> N538f3cb70d34
  Nb7c86dcaecd1 -- "sets_state" --> Nc50ee6c42db8
  Nb7c86dcaecd1 -- "sets_state" --> Nf44dcf1e273d
  Nb7c86dcaecd1 -- "sets_state" --> N61447b7404fc
  Nb7c86dcaecd1 -- "sets_state" --> N8ea1c072da1d
  Nb7c86dcaecd1 -- "sets_state" --> N7ffba14f0c28
  Nb7c86dcaecd1 -- "sets_state" --> N4dc0b7c401b8
  Nb7c86dcaecd1 -- "sets_state" --> N887d2f4256a8
  N2af3962c9439 -- "handled_by" --> Nb7c86dcaecd1
```
