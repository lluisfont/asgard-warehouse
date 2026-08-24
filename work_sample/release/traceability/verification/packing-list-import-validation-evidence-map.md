# packing-list-import-validation Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Packing list import is initiated from a frame by `idcasosprevios`. | `frames/listaempaque.php` | High |
| Import requires brand and Excel attachment. | `frames/listaempaque.php`, `js/listaEmpaque.js` | High |
| Imported rows are grouped by `idcargado`. | `uploadExcelListaEmpaque.php`, `EmbarqueClass.php`, schema tables | High |
| Three persisted sections exist: general, specific and items. | `dav_listaempaque_general`, `dav_listaempaque_especifico`, `dav_listaempaque_items` | High |
| Validation compares dates and weights and returns observations. | `uploadExcelListaEmpaque.php` | High |
| PDF generation reads saved rows and calls `armarLEparaPDF`. | `exportarListaEmpaque.php`, `EmbarqueClass.php` | High |
| SQL/parser methods use interpolated ids and file-derived data; detailed parser validation should be reviewed. | `EmbarqueClass.php`, upload/export endpoints | High |

## Review Needed

- Confirm Excel parser rules and accepted template version.
- Confirm weight tolerance and blocking behavior.
- Confirm replacement/history behavior for multiple loads.
- Confirm PDF storage/download lifecycle.
