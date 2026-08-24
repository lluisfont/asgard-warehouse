# customs-guarantee-tax-control Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Guarantee report is filtered by session customer, year and declaration type. | `boletasgarantia.php`, `boletasgarantiaajax.php`, `ContabilidadClass.php` | High |
| Total guarantee amount comes from active document records of type `4`. | `ContabilidadClass.php` method `getSeguimientoTotal` | High |
| Units with DAM are derived from `dav_facturacomercial.fechaenviodam`. | `ContabilidadClass.php` methods `getSeguimientoMensual`, `getSeguimientoOperativo` | High |
| Extracted units are counted when exit pass or canal assignment exists. | `getSeguimientoMensual` | High |
| Operational categories use DAM, exit/canal, FRV and document-date conditions. | `getSeguimientoOperativo`, `getSeguimientoOperativoDesglosado` | High |
| UI constrains the desglosado report to a maximum date range of 90 days. | `boletasgarantia.php` function `validarRangoFechas` | High |
| Tax report calculates received amount, paid taxes, return/replacement, difference and party favored. | `tributosquery.php` | High |
| Imcruz legalized-planilla report is scoped to client `417`. | `planillaslegalizadasquery.php` | High |

## Review Needed

- Confirm official meaning of document type `4`.
- Confirm whether 90-day threshold is a legal, commercial or internal operational rule.
- Confirm whether fixed exchange rate `6.96` is intended.
- Confirm formula for guarantee amount in use and tax difference.
- Confirm if Imcruz legalized-planilla report belongs in this domain or a client-specific report pack.

