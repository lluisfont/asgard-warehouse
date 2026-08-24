# Technical component map: `index_archivos/parametros/usuarioFirmante/UsuarioFirmante.php`

Status: `CANDIDATE_AS_IS`

> Relationships are observed or candidate-level in the same component and are not yet a confirmed execution sequence.

```mermaid
flowchart LR
  N9b1b856cd896["dav_cliente_usuarios_firmantes"]
  Nafcb9efa5fc3["index_archivos/parametros/usuarioFirmante/UsuarioFirmante.php"]
  N5ee5acaca1b2["CLI_COMMAND: index_archivos/parametros/usuarioFirmante/UsuarioFirmante.php"]
  Nafcb9efa5fc3 -- "reads" --> N9b1b856cd896
  Nafcb9efa5fc3 -- "writes" --> N9b1b856cd896
  N5ee5acaca1b2 -- "handled_by" --> Nafcb9efa5fc3
```
