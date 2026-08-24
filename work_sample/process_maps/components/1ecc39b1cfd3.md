# Technical component map: `index_archivos/intercambioDocumental/ajax/dividir-pdf-soat.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N8ffd18898ae3["asgard.dav_casosprevios"]
  Ne1f3a232987d["intercambiodocumental.exchange_documents"]
  N556a48ad883a["intercambiodocumental.exchanges"]
  N1ecc39b1cfd3["index_archivos/intercambioDocumental/ajax/dividir-pdf-soat.php"]
  N1ecc39b1cfd3 -- "reads" --> N8ffd18898ae3
  N1ecc39b1cfd3 -- "reads" --> Ne1f3a232987d
  N1ecc39b1cfd3 -- "reads" --> N556a48ad883a
```
