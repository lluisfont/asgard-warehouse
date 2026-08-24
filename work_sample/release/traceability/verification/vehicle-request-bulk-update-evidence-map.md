# vehicle-request-bulk-update Evidence Map

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| Vehicle request updates are uploaded from an Excel template. | `updateSolicitudVehiculos.php`, `uploadExcelSolicitud.php` | High |
| Upload rows are staged in `dav_historialmodificacionvehiculos` by `idcargado`. | `VehiculosClass.php`, schema | High |
| Confirmation is disabled when upload has row errors. | `datosSolicitud.js` | High |
| Modes `0/1/2/3` control which fields can be updated. | `updateSolicitudVehiculos.php`, `datosSolicitud.js`, `VehiculosClass.php` | High |
| Chassis existence is validated against active vehicle case folders. | `VehiculosClass::buscarChasisEnCarpeta` | High |
| Changing request number has additional validation for sent request, AP/info completeness and SOAT lot/case updates. | `VehiculosClass.php` | Medium |
| Applied changes are exported from history for current user. | `historialModificaciones.php` | High |
| SQL uses interpolated Excel/session values and broad updates by chassis; authorization and audit need review. | `VehiculosClass.php`, AJAX endpoints | High |

## Review Needed

- Confirm permitted roles and approval requirements.
- Confirm exact meaning of `camposmodificar`.
- Confirm vehicle merchandise id `34`.
- Confirm whether previous values must be stored.
