# nationalization-weekly-planning Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Nationalization planning is managed from a logistics page. | `planificacion-nacionalizacion.php` | High |
| The UI lists chasis, partida, carpeta, declaration type, arrival date and planned nationalization date. | `planificacion-nacionalizacion.php` | High |
| Weekly planning upload is delegated to `url_pedidos` endpoint `cargar-planificacion`. | `planificacion-nacionalizacion.php` | High |
| Already-nationalized chassis are returned separately and displayed in a modal warning. | `planificacion-nacionalizacion.php` | High |
| Confirmation sends the loaded chassis list to `confirmar-planificacion`. | `planificacion-nacionalizacion.php` | High |
| Planned nationalization date exists in `dav_casos`. | `.data_base/asgard.sql` | Medium |
| Planning/reprogramming table exists for partida-level planning. | `.data_base/asgard.sql` `part_planificacion_partida` | Medium |

## Review Needed

- Confirm API implementation and database writes.
- Confirm Excel file format.
- Confirm rule for already-nationalized chassis.
- Confirm reprogramming and audit behavior.
