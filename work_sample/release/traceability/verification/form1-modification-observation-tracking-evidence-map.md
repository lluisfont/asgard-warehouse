# form1-modification-observation-tracking Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Form1 modifications are reported with case/AGES context, DIM, chasis and plate. | `modificacionesquery.php` | High |
| Visible modification text is built from subcontravention plus `dondedice` and `debedecir`. | `modificacionesquery.php`, `dav_casossubcontravencion` | High |
| Observation, ingress and conclusion dates are inferred from Form1 EDP states `1`, `3` and `7`. | `modificacionesquery.php`, `dav_form1edp` | Medium |
| Current procedure state is obtained from latest Form1 EDP state. | `modificacionesquery.php`, function `ultimoidform1edp` | Medium |
| Call history is grouped by Form1 and includes attachments. | `historial_llamadas.php`, `dav_form1llamadas` | High |
| Observed folders report unresolved missing documents from `dav_faltadocumentos` and legacy `dav_casos` fields. | `observadasquery.php`, `dav_faltadocumentos` | High |
| SQL and download paths use interpolated runtime values and raw attachment path patterns. | `modificacionesquery.php`, `observadasquery.php`, `historial_llamadas.php` | High |

## Review Needed

- Confirm official Form1 state catalog and labels.
- Confirm visibility semantics of `permisoclientes`.
- Confirm responsibility semantics for missing documents.
- Confirm attachment storage, authorization and retention rules.
