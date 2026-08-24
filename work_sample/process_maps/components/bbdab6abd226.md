# Technical component map: `index_archivos/js/spreadsjs/external/angular.1.2.22.js`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  Nbbdab6abd226["index_archivos/js/spreadsjs/external/angular.1.2.22.js"]
  Nb2b972168dd8["EVENT_CONSUMER: index_archivos/js/spreadsjs/external/angular.1.2.22.js"]
  Nb8d83dea9dec["-1"]
  Ne43c2a67c5a1["ABORTED"]
  N4659512f9485["Math"]
  N26b3b60b32d6["event"]
  N9a2d86a5d480["response"]
  N4dc0b7c401b8["status"]
  Nbbdab6abd226 -- "sets_state" --> Nb8d83dea9dec
  Nbbdab6abd226 -- "sets_state" --> Ne43c2a67c5a1
  Nbbdab6abd226 -- "sets_state" --> N4659512f9485
  Nbbdab6abd226 -- "sets_state" --> N26b3b60b32d6
  Nbbdab6abd226 -- "sets_state" --> N9a2d86a5d480
  Nbbdab6abd226 -- "sets_state" --> N4dc0b7c401b8
  Nbbdab6abd226 -- "sets_state" --> N4dc0b7c401b8
  Nb2b972168dd8 -- "handled_by" --> Nbbdab6abd226
```
