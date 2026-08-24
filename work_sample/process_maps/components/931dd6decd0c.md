# Technical component map: `index_archivos/controllers/SolicitudClass.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  Neb9ead6b5fcc["dav_aduana"]
  Nf600c3b4ee21["dav_casosprevios"]
  Ne63b5a1fd7b6["dav_ciudad"]
  Nf2b04933f0f0["dav_clientedeclarante"]
  N96b3ab8d7c8d["dav_clientelineas"]
  N3a02e6506044["dav_clienteproveedor"]
  N3bdb417b6f2e["dav_clientetransportista"]
  N3750c9307382["dav_entidademisora"]
  N3a14a6ce832a["dav_entidademisoratramite"]
  N62cece95b095["dav_entidademisoratramitetipo"]
  N8b51ca9ed1e5["dav_modotransporte"]
  Nf838fcd7828f["dav_proveedor"]
  Nf11b58db47e2["dav_regimen"]
  N5d515933bd30["dav_solicitudesprevias"]
  Nd23e86b31b2f["dav_tipodeclaracion"]
  N9f2028e70c28["dav_tipotramite"]
  N116086725bcc["dav_transportista"]
  N7e75b82f1ff0["dav_usuario"]
  Nd10ca78f5ee4["tmp_tiposolicitud"]
  N931dd6decd0c["index_archivos/controllers/SolicitudClass.php"]
  N8e5bb72e148e["CLI_COMMAND: index_archivos/controllers/SolicitudClass.php"]
  N931dd6decd0c -- "reads" --> Neb9ead6b5fcc
  N931dd6decd0c -- "writes" --> Nf600c3b4ee21
  N931dd6decd0c -- "reads" --> Ne63b5a1fd7b6
  N931dd6decd0c -- "reads" --> Nf2b04933f0f0
  N931dd6decd0c -- "reads" --> Nf2b04933f0f0
  N931dd6decd0c -- "reads" --> N96b3ab8d7c8d
  N931dd6decd0c -- "reads" --> N3a02e6506044
  N931dd6decd0c -- "reads" --> N3bdb417b6f2e
  N931dd6decd0c -- "reads" --> N3750c9307382
  N931dd6decd0c -- "reads" --> N3a14a6ce832a
  N931dd6decd0c -- "reads" --> N62cece95b095
  N931dd6decd0c -- "reads" --> N8b51ca9ed1e5
  N931dd6decd0c -- "reads" --> Nf838fcd7828f
  N931dd6decd0c -- "reads" --> Nf838fcd7828f
  N931dd6decd0c -- "reads" --> Nf11b58db47e2
  N931dd6decd0c -- "reads" --> N5d515933bd30
  N931dd6decd0c -- "reads" --> N5d515933bd30
  N931dd6decd0c -- "reads" --> Nd23e86b31b2f
  N931dd6decd0c -- "reads" --> N9f2028e70c28
  N931dd6decd0c -- "reads" --> N116086725bcc
  N931dd6decd0c -- "reads" --> N7e75b82f1ff0
  N931dd6decd0c -- "reads" --> Nd10ca78f5ee4
  N8e5bb72e148e -- "handled_by" --> N931dd6decd0c
```
