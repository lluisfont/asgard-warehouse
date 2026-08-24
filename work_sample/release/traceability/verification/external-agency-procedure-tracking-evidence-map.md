# external-agency-procedure-tracking Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| SENASAG report is scoped to observed `identidademisora=2`. | `senasag.php` | High |
| Stage columns are dynamic from `dav_etapasentidademisora`. | `senasagquery.php` | High |
| Stage states are dynamic when `tieneestado=1`. | `senasagquery.php` | High |
| Stage filter includes selected stage and excludes next stage when applicable. | `senasagquery.php` | High |
| Procedure metadata is maintained in `tramites.php`. | `tramites.php`, `tramites_json.php` | Medium |
| SQL uses interpolated runtime/filter values and dynamic table names for state catalogs. | `senasagquery.php`, `tramites.php` | High |

## Review Needed

- Confirm official entity id for SENASAG and other entities.
- Confirm correct stage progression logic.
- Confirm permissions and audit for procedure maintenance.
- Confirm state catalog conventions.

