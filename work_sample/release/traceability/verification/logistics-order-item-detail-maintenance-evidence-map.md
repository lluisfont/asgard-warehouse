# Logistics Order Item Detail Maintenance - Evidence Map

| Artefacto | Evidencia |
| --- | --- |
| Process definition | `items_pedido.php`, `datosEmbarques.js`, `saveDescripcionItems.php` |
| Process flow | UI detail load and save endpoint |
| Business rules | Client-specific columns and POST field parsing |
| Data used | `.data_base/asgard.sql:12567-12592` |
| State model | Loaded, edited and updated item states |
| Use case | Item detail maintenance |
| OpenSpec | Derived from observed JS/PHP/schema behavior |

## Limitaciones

- Upstream pedido import and grouping are outside this narrow domain.
- The endpoint exposes a broad column-update surface that requires technical review.
