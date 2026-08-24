# Technical component map: `index_archivos/parametros/operadortransporte/OperadorTransporte.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  Nf4525b5ab829["dav_cliente"]
  N3bdb417b6f2e["dav_clientetransportista"]
  N116086725bcc["dav_transportista"]
  Nb455429ee9d4["logis_contactos_operadores"]
  N605c6d893805["logis_documentosclienteoperador"]
  Na8484ce145e4["logis_tipodocumento"]
  N0b05f969dccc["logis_tipoviaje"]
  N0863c2994040["prov_usuarioaccesoproveedor"]
  Nddc8db6ef9a2["index_archivos/parametros/operadortransporte/OperadorTransporte.php"]
  N0770faf90081["CLI_COMMAND: index_archivos/parametros/operadortransporte/OperadorTransporte.php"]
  Nddc8db6ef9a2 -- "reads" --> Nf4525b5ab829
  Nddc8db6ef9a2 -- "reads" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "reads" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "reads" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "reads" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "reads" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "writes" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "writes" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "writes" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "writes" --> N3bdb417b6f2e
  Nddc8db6ef9a2 -- "reads" --> N116086725bcc
  Nddc8db6ef9a2 -- "reads" --> N116086725bcc
  Nddc8db6ef9a2 -- "reads" --> N116086725bcc
  Nddc8db6ef9a2 -- "reads" --> Nb455429ee9d4
  Nddc8db6ef9a2 -- "reads" --> N605c6d893805
  Nddc8db6ef9a2 -- "reads" --> N605c6d893805
  Nddc8db6ef9a2 -- "reads" --> N605c6d893805
  Nddc8db6ef9a2 -- "writes" --> N605c6d893805
  Nddc8db6ef9a2 -- "writes" --> N605c6d893805
  Nddc8db6ef9a2 -- "writes" --> N605c6d893805
  Nddc8db6ef9a2 -- "reads" --> Na8484ce145e4
  Nddc8db6ef9a2 -- "reads" --> Na8484ce145e4
  Nddc8db6ef9a2 -- "reads" --> Na8484ce145e4
  Nddc8db6ef9a2 -- "reads" --> N0b05f969dccc
  Nddc8db6ef9a2 -- "reads" --> N0b05f969dccc
  Nddc8db6ef9a2 -- "reads" --> N0863c2994040
  Nddc8db6ef9a2 -- "reads" --> N0863c2994040
  Nddc8db6ef9a2 -- "writes" --> N0863c2994040
  Nddc8db6ef9a2 -- "writes" --> N0863c2994040
  Nddc8db6ef9a2 -- "writes" --> N0863c2994040
  N0770faf90081 -- "handled_by" --> Nddc8db6ef9a2
```
