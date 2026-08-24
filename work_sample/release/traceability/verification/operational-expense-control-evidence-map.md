# operational-expense-control Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Expense report builds dynamic columns from observed planilla/factura concepts. | `gastosquery.php` | High |
| Expense report includes total planilla, total factura, other invoices, debit notes and total. | `gastosquery.php` | High |
| Expense detail separates Planilla and Factura origins. | `detallegastosquery.php` | High |
| Pedido expense search is limited to the last three months. | `gastospedidoquery.php` | High |
| Item/SKU expenses are loaded from `url_pedidos` API. | `gastos-items.php` | High |
| Control gastos exportacion reports DEX, FOB, SENASAG/SENAVEX, fletes and extra costs. | `control-gastos.php`, `control-gastos.js` | High |
| Report SQL uses interpolated request/session values and generic Excel query metadata. | `gastos.php`, `gastosquery.php`, `reporte-gastos-skuquery.php` | High |

## Review Needed

- Confirm total formulas and treatment of notes/debit.
- Confirm API contracts and auth for `url_pedidos`.
- Confirm export permissions and report ownership.
- Confirm state catalogs for invoice, planilla and payment.

