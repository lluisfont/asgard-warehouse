# scp-reception-control Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| SCP supports massive XLSX upload. | `scp.php`, `exportaciones/js/scp.js`, `uploadDatosSCP.php` | High |
| XLSX rows are read from columns B-S starting at row 3. | `uploadDatosSCP.php` | High |
| Existing records are matched by order, note, plate and material number. | `uploadDatosSCP.php`, `guardarRecepcionSCP` | High |
| New records are inserted into `dav_reporte_recepcion_scp`. | `reporte-scp.php` method `guardarRecepcionSCP` | High |
| Existing records are updated field-by-field. | `reporte-scp.php` method `guardarRecepcionSCP` | High |
| State is set to `Recibido` when reception date is present. | `reporte-scp.php` method `guardarRecepcionSCP` | High |
| Report calculation compares sent quantity to packing-list net weight. | `reporte-scp.php` method `getReporteSCP` | High |
| Schema contains SCP reception and IASA packing-list/report detail tables. | `.data_base/asgard.sql:9388-9459` | High |
| Evidence extractor found reads/writes for IASA detail and SCP reception tables. | `.brownfield/work/evidence/evidence.jsonl` entries for `dav_reporte_detalles_transportistas_iasa` and `dav_reporte_recepcion_scp` | Medium |

## Review Needed

- Confirm whether `idcliente = 775` in report query is intentional.
- Confirm official SCP state catalog and transition policy.
- Confirm whether `cantidad_enviada` and `peso_neto_lista_empaque` are comparable without unit conversion/tolerance.
- Confirm duplicate handling when `orden + nota + placa` maps to multiple materials.

