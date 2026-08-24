# vehicle-transitory-depot-compliance Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Payment report calculates expected taxes from ASGARD case/partida values. | `PagoTributosTransitorioImcruz.php` method `getReportePagoTributos` | High |
| Payment timing is classified using 60-day threshold from depot entry to tax payment. | `reporte-pago-tributos-transitorio.js` | High |
| Deferred days are permanence days over 60. | `reporte-pago-tributos-transitorio.js` | High |
| Depot report consolidates accessories, damages, contamination and mileage at port/depot stages. | `PagoTributosTransitorioImcruz.php` method `getDataDepositoTransitorio` | High |
| Atlantes provides ingress and egress movement dates. | JS/PHP calls to `/ingresos/...` and `/salidas/...` | High |
| Trip state mapping is reconstructed from CTE values. | `getDataDepositoTransitorio` CTE `tmp_estado_viaje` | High |
| Client scoping is hardcoded to `417`. | `PagoTributosTransitorioImcruz.php` filters | High |

## Review Needed

- Confirm if client `417` is intentional and whether the domain is Imcruz-specific.
- Confirm official legal/commercial source for 60-day threshold.
- Confirm official formula for `tributos_previstos`.
- Confirm Atlantes endpoint contract, token ownership and timezone.
- Confirm `tipo_inventario_id` catalog for port vs transitory depot.

