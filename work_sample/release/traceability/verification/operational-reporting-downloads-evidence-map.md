# operational-reporting-downloads Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Operational reports use screen-specific filters and render tabular results. | `reporteseguimiento.php`, `reporte_documentos_cbn.php`, many files under `index_archivos/operativos` | High |
| Excel export is implemented by submitting query and metadata to `../reporteexcel/reporteexcel.php`. | `reporteseguimiento.php`, `reporte_documentos_cbn.php`, `rg` matches for `reporteexcel.php` | High |
| Report visualization/download logging is centralized in `LogReportes.php`. | `LogReportes.php`, repeated AJAX calls with `saveLogReporte` | High |
| `log_asgard_ecosistema` stores report name and download flag. | `.data_base/asgard.sql:11749-11779` | High |
| Report catalogs and permissions exist for client reports. | `.data_base/asgard.sql:9518-9591` | High |
| Download counters exist for visits and Excel exports. | `.data_base/asgard.sql:3690-3716`, `reportedetallevehiculosexcel.php` | Medium |
| Bulk document download resolves exchanges from shipments and customs requests. | `DescargaMasivaClass.php` | High |
| Bulk document download excludes deleted Intercambio Documental records. | Queries in `DescargaMasivaClass.php` with `ISNULL(ed.deletedAt)` | High |
| ZIP construction is delegated to an internal API. | `descargaMasivaDocumentos.php` function `construirZip` | High |

## Review Needed

- Confirm final catalog and ownership of all report ids in `dav_reportescliente`.
- Confirm whether every report must log both view and download actions.
- Confirm security model for passing raw query text into Excel generation.
- Confirm official retention policy for report usage logs.

