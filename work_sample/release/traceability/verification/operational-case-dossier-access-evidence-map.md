# operational-case-dossier-access Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Dossier search supports pedido, DIM, carpeta, date, lot/chassis and purchase order. | `archivos.php`, `archivosquery.php` | High |
| Search is scoped by session customer and excludes cancelled cases. | `archivosquery.php` | High |
| Document listing reads filesystem and FTP locations under uploads. | `documentosotros.php` | High |
| Visibility rules depend on `tipo_usuario` and vehicle/correlativo conditions. | `documentosotros.php` | High |
| Generated invoice/planilla documents are exposed from dossier view. | `documentosotros.php` | High |
| Dispatch data report sums net/gross weights from partidas. | `datosdespachoquery.php` | High |
| FTP credentials and download path handling require security review. | `documentosotros.php`, `download.php` | High |

## Review Needed

- Confirm role/document visibility matrix.
- Confirm file storage and retention policy.
- Confirm secure download implementation.
- Confirm whether FTP path is still used in production.

