# Technical component map: `index_archivos/js/spreadsjs/external/angular.1.0.8.js`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N1eee7bb7f97c["index_archivos/js/spreadsjs/external/angular.1.0.8.js"]
  N38b90d0b0adb["EVENT_CONSUMER: index_archivos/js/spreadsjs/external/angular.1.0.8.js"]
  Nb8d83dea9dec["-1"]
  N4659512f9485["Math"]
  N4dc0b7c401b8["status"]
  N1eee7bb7f97c -- "sets_state" --> Nb8d83dea9dec
  N1eee7bb7f97c -- "sets_state" --> N4659512f9485
  N1eee7bb7f97c -- "sets_state" --> N4dc0b7c401b8
  N1eee7bb7f97c -- "sets_state" --> N4dc0b7c401b8
  N38b90d0b0adb -- "handled_by" --> N1eee7bb7f97c
```
