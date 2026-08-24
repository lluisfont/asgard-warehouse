# Logistics Shipment Quotation Duplication - Evidence Map

| Artefacto | Evidencia |
| --- | --- |
| Process definition | `duplicarCotizacion.php`, `duplicarEmbarque.php`, `CotizacionClass.php` |
| Process flow | UI confirmation and backend duplication paths |
| Business rules | `cotizacion` flag, copied child records, notifications, exchange creation |
| Data used | `logis_embarques`, `logis_tramos`, `logis_embarquesmagnitudes`, `logis_embarquesoperador`, `logis_embarquesdocumentos` |
| State model | New duplicated quotation/shipment and exchange handoff |
| Use case | Duplicate action in quotation/shipment lists |
| OpenSpec | Derived from observed JS/PHP persistence |

## Limitaciones

- The exact user permission for duplicate actions was not isolated in these files.
- No explicit transaction boundary was observed for multi-table duplication.
