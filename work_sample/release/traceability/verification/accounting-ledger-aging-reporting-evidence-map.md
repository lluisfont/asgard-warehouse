# accounting-ledger-aging-reporting Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Aging matrix is dynamic from 2020 to current year. | `agig.php` | High |
| Aging save performs update or insert by mes/anio. | `agig_ajax.php` | High |
| Comision report separates invoice and planilla rows. | `comisionquery.php` | High |
| Comision report filters by session customer, optional line and payment DIM date. | `comision.php`, `comisionquery.php` | High |
| Purchase ledger calculates fiscal credit at 13%. | `librocomprasquery.php` | High |
| Purchase ledger uses fixed observed provider values. | `librocomprasquery.php` | High |
| Reports use generic Excel export with query metadata. | `comision.php`, `librocompras.php` | High |

## Review Needed

- Confirm business meaning of `agig`.
- Confirm fiscal provider constants and purchase-ledger formula.
- Confirm whether `idestadofactura=1` is the only reportable state.
- Confirm access control and audit for aging edits.

