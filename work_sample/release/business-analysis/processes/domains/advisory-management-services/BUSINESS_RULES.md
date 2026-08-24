# Advisory Management Services - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| AMS-BR-001 | El tablero organiza solicitudes por estados operativos: pendientes, enviados, recepcionados, asignados, en revision, en proceso y finalizado. | `tbl-estados.js:17-79` |
| AMS-BR-002 | La creacion de solicitud requiere solicitante, email y ciudad en la UI. | `solicitud.js:1-80` |
| AMS-BR-003 | Una solicitud puede contener multiples tramites, cada uno con entidad emisora, tramite y tipo de tramite. | `tramite.js:1-130`, `tbl-estados.js:155-220` |
| AMS-BR-004 | La solicitud puede asociarse a embarque, caso previo, linea e intercambio documental. | `solicitud.js:150-260`, `.data_base/asgard.sql:390-436` |
| AMS-BR-005 | Si la solicitud se crea desde un contexto de intercambio documental existente, se guarda el `exchange_id`; si no existe, puede crearse uno nuevo. | `tbl-estados.js:220-260`, `solicitud.js:150-260` |
| AMS-BR-006 | Para creacion masiva de gestion aduanera se insertan documentos previos requeridos por modo de transporte. | `SolicitudClass.php:481-520`, `logistica/SolicitudesClass.php:714-850` |
| AMS-BR-007 | Para ciertos clientes/tipos de solicitud se genera solicitud de gestion/SOAT automaticamente tras crear caso previo. | `logistica/SolicitudesClass.php:805-824` |
| AMS-BR-008 | El reporte general expone fechas de solicitud, recepcion, asignacion, revision, proceso, finalizacion y cierre. | `operativos/asesoria-gestion.php:67-212`, `.data_base/asgard.sql:390-436` |
| AMS-BR-009 | Las solicitudes eliminadas se excluyen mediante `deleted_at IS NULL` en vistas/reportes observados. | `.data_base/asgard.sql:17821-17827`, report queries referenced in schema views |
