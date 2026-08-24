# API Behavior Tests

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Area | Endpoints candidatos |
| --- | --- |
| MFA | `2fa/ajax/verificar-codigo.php`, `autenticar.php`, `reenviar-codigo.php`. |
| Logistica | `logistica/ajax/*`, componentes embarque, costos, EDP. |
| Solicitudes | `ajax/uploadExcelSolicitud.php`, `controllers/SolicitudClass.php` wrappers. |
| Documentos/OCR | `intercambioDocumental/ajax/*`, `download.php`. |
| Notificaciones | `servicioNotificaciones/ajax/*`. |
| Reportes | `operativos/*query.php`, `contables/*query.php`, `DashboardGenerico.php`. |
| Android legacy | `android/consulta.php`, `android/consultatodo.php`. |

Capturar status, JSON/HTML, DB diff y side effects por endpoint.
