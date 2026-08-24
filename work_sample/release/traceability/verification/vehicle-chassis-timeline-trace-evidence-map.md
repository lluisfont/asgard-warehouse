# vehicle-chassis-timeline-trace Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| The user searches the timeline by chassis. | `bitacora_chasis.php` | High |
| The timeline has six fixed observed milestones. | `revisionData` in `bitacora_chasis.php` | High |
| ASGARD delegates inventory data to `url_pedidos` API endpoints. | `bitacora_chasis.php` | High |
| Damage indicator is based on `cantidad_con_desperfecto > 0`. | `bitacora_chasis.php` | High |
| Latest milestone is based on maximum `created_at`. | `bitacora_chasis.php` | Medium |
| Detail modal includes accessories, damage and contamination. | `bitacora_chasis.php` | High |
| Photos are downloaded through `file/download` using path and filename. | `bitacora_chasis.php` | High |
| Stored procedure `sp_reporte_chasis_inventario` provides related local reporting evidence for chassis inventory. | `.data_base/asgard.sql` | Medium |

## Review Needed

- Confirm API contracts and ownership.
- Confirm catalog ids for inventory milestones.
- Confirm authorization for cross-chassis evidence lookup.
- Confirm whether `created_at` is the correct date for latest operational hito.
