# Technical component map: `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N8ffd18898ae3["asgard.dav_casosprevios"]
  N55e8b019ddd4["dav_reporte_detalles_transportistas_iasa"]
  Nbae3ca09ca57["dav_reporte_transportistas_iasa"]
  Ne1f3a232987d["intercambiodocumental.exchange_documents"]
  N556a48ad883a["intercambiodocumental.exchanges"]
  Nd5ef87fbe4ef["index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php"]
  Nfee44c91faf1["CLI_COMMAND: index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php"]
  Nd5ef87fbe4ef -- "reads" --> N8ffd18898ae3
  Nd5ef87fbe4ef -- "reads" --> N55e8b019ddd4
  Nd5ef87fbe4ef -- "reads" --> N55e8b019ddd4
  Nd5ef87fbe4ef -- "writes" --> N55e8b019ddd4
  Nd5ef87fbe4ef -- "writes" --> N55e8b019ddd4
  Nd5ef87fbe4ef -- "writes" --> N55e8b019ddd4
  Nd5ef87fbe4ef -- "writes" --> Nbae3ca09ca57
  Nd5ef87fbe4ef -- "writes" --> Nbae3ca09ca57
  Nd5ef87fbe4ef -- "reads" --> Ne1f3a232987d
  Nd5ef87fbe4ef -- "reads" --> N556a48ad883a
  Nfee44c91faf1 -- "handled_by" --> Nd5ef87fbe4ef
```
