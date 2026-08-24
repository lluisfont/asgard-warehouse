# Technical component map: `index_archivos/controllers/GlobalClass.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N835b8ed490f8["ada_agentesaduana"]
  Nac61ed3adc65["ada_agentescontactos"]
  N540098db87c7["ada_clienteagenteaduana"]
  Nf4525b5ab829["dav_cliente"]
  N96b3ab8d7c8d["dav_clientelineas"]
  N3a02e6506044["dav_clienteproveedor"]
  Ncfbb8f884c4b["dav_clientereportescliente"]
  N8b5850f049d8["dav_condicion"]
  N22bc437ff3b2["dav_divisa"]
  N1a79709569dd["dav_edp"]
  N8b6f4abc9726["dav_localidad"]
  N06349ff4967d["dav_pais"]
  Nf838fcd7828f["dav_proveedor"]
  Nd23e86b31b2f["dav_tipodeclaracion"]
  N7e75b82f1ff0["dav_usuario"]
  Nced41184bbc7["logis_emailcliente"]
  N126a824012f3["logis_embarquereferencias"]
  N50876db04819["logis_pedidos"]
  N09baa9a9cbda["logis_pedidos_detalle"]
  Nab976e3475f2["logis_tipoembarque"]
  N0863c2994040["prov_usuarioaccesoproveedor"]
  Nebbaaee950a4["index_archivos/controllers/GlobalClass.php"]
  Nbe23c82d2384["CLI_COMMAND: index_archivos/controllers/GlobalClass.php"]
  Nebbaaee950a4 -- "reads" --> N835b8ed490f8
  Nebbaaee950a4 -- "reads" --> Nac61ed3adc65
  Nebbaaee950a4 -- "reads" --> N540098db87c7
  Nebbaaee950a4 -- "reads" --> Nf4525b5ab829
  Nebbaaee950a4 -- "reads" --> Nf4525b5ab829
  Nebbaaee950a4 -- "reads" --> Nf4525b5ab829
  Nebbaaee950a4 -- "reads" --> N96b3ab8d7c8d
  Nebbaaee950a4 -- "reads" --> N3a02e6506044
  Nebbaaee950a4 -- "reads" --> Ncfbb8f884c4b
  Nebbaaee950a4 -- "reads" --> N8b5850f049d8
  Nebbaaee950a4 -- "reads" --> N22bc437ff3b2
  Nebbaaee950a4 -- "writes" --> N1a79709569dd
  Nebbaaee950a4 -- "reads" --> N8b6f4abc9726
  Nebbaaee950a4 -- "reads" --> N06349ff4967d
  Nebbaaee950a4 -- "reads" --> Nf838fcd7828f
  Nebbaaee950a4 -- "reads" --> Nf838fcd7828f
  Nebbaaee950a4 -- "reads" --> Nd23e86b31b2f
  Nebbaaee950a4 -- "reads" --> N7e75b82f1ff0
  Nebbaaee950a4 -- "reads" --> Nced41184bbc7
  Nebbaaee950a4 -- "reads" --> N126a824012f3
  Nebbaaee950a4 -- "reads" --> N50876db04819
  Nebbaaee950a4 -- "reads" --> N09baa9a9cbda
  Nebbaaee950a4 -- "reads" --> Nab976e3475f2
  Nebbaaee950a4 -- "reads" --> N0863c2994040
  Nbe23c82d2384 -- "handled_by" --> Nebbaaee950a4
```
