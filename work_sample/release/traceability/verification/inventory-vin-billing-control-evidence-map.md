# inventory-vin-billing-control Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| VIN billing is controlled from a contable module page. | `facturacion-inventario.php` | High |
| Billing period uses day 21 through day 20. | `actualizarIntervaloFechas`, `actualizarFechaFinDesdeInicio` | High |
| Precalculation returns international, national/local, unique and billable VIN counts. | `facturacion-inventario.php` | High |
| Confirmation is delegated to `confirmar-facturacion-chasis`. | `facturacion-inventario.php` | High |
| Period and chassis tables exist for confirmed billing. | `inventario_facturacion_periodo`, `inventario_facturacion_chasis` | High |
| API implementation and billing formula are external to inspected PHP. | `url_pedidos` endpoints | High |

## Review Needed

- Confirm billable VIN formula.
- Confirm tariff and exchange-rate source.
- Confirm duplicate billing prevention.
- Confirm period reopen/delete behavior.
