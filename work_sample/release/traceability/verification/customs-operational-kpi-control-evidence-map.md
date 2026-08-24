# customs-operational-kpi-control Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| AP/DAM/REQ KPIs use capacity 200 and 1/2 day thresholds. | `kpisquery.php` | High |
| KPI calculations use working-day functions. | `kpisquery.php`, `reportecontroladquery.php`, `reporteseguimientoquery.php` | High |
| Control AD calculates tax forecast and 5% difference indicator. | `reportecontroladquery.php` | High |
| Control OL derives shipment state from maximum EDP stage order. | `reportecontrololquery.php` | High |
| Tracking/logistics assignment count comes from `tck_asignacion_viaje`. | `reportecontrololquery.php` | High |
| Seguimiento report calculates payment, validation and planilla KPIs. | `reporteseguimientoquery.php` | High |
| Some KPI seed queries use hardcoded client ids `417`, `452`, `471`. | `kpisquery.php` | High |

## Review Needed

- Confirm official KPI thresholds and capacity.
- Confirm hardcoded client-id rationale.
- Confirm semantics of positive/negative, correcto/atrasado, en tiempo/reintegro.
- Confirm report ownership and access controls.

