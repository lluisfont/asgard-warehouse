# Evidence Map - master-data-configuration

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| The domain administers customer users. | `index_archivos/parametros/usuarios/Usuarios.php:86-144` | High |
| Customer users are logically deleted, not physically removed. | `index_archivos/parametros/usuarios/Usuarios.php:35-48` | High |
| Customer user creation uses client-prefixed username, generated password and optional 2FA. | `index_archivos/parametros/usuarios/ajax/validarUsuario.php:13-29` | High |
| Permissions are based on enabled customer reports and per-user permission rows. | `index_archivos/parametros/usuarios/Usuarios.php:184-203`, `index_archivos/parametros/usuarios/ajax/guardarPermisos.php:1-40` | High |
| Providers/consignees are maintained with contacts, documents and approval-like states. | `index_archivos/parametros/proveedormercancia/ProveedorMercancia.php:78-220`, `listaProveedores.php:13-52` | High |
| Provider states are displayed as pendiente, por aprobar, guardado and por aprobar modificacion. | `index_archivos/parametros/proveedormercancia/ajax/listaProveedores.php:27-30` | High |
| Provider duplicates are checked by name, country and locality. | `index_archivos/parametros/proveedormercancia/ProveedorMercancia.php:721-741` | Medium |
| Provider modification requests are based on differences from temporary provider data. | `index_archivos/parametros/proveedormercancia/ProveedorMercancia.php:750-825` | High |
| Transport operators are maintained per customer with contacts and documents. | `index_archivos/parametros/operadortransporte/OperadorTransporte.php:31-166`, `OperadorTransporte.php:168-223` | High |
| Tracking operator registration can create `tck_operadorestransporte` and linked `dav_transportista` credentials. | `index_archivos/tracking/OperadorTransporteClass.php:26-76` | High |
| Signing users are maintained as active non-deleted records. | `index_archivos/parametros/usuarioFirmante/UsuarioFirmante.php:8-38` | High |
| Schema supports the domain with customer, user, provider, transport and document tables. | `.data_base/asgard.sql:2600-3354`, `.data_base/asgard.sql:9016-9135`, `.data_base/asgard.sql:12056-12075` | High |

## Graphify Use

Graphify output is treated as supporting context only. Direct source code and database schema evidence remain authoritative for this domain.
