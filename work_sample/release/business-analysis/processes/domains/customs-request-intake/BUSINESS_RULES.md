# Customs Request Intake - Business Rules

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| CRI-BR-001 | El archivo importado se guarda por usuario en `cargasolicitudes/{idusuario}` con prefijo hash. | `SolicitudClass.php:14-35` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-002 | La importacion reemplaza solicitudes previas del mismo cliente y usuario antes de insertar nuevas filas. | `SolicitudClass.php:90-93` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-003 | Tipos de solicitud observados: Despacho Aduanero, Gestion Soporte y Vehiculos. | `SolicitudClass.php:122-140` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-004 | Proveedor y transportista se validan contra maestros relacionados al cliente. | `SolicitudClass.php:147-174` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-005 | Ciudad queda restringida a ids 4 y 11 en la busqueda observada. | `SolicitudClass.php:178-181` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-006 | La validacion acumula errores y mensaje por fila en `dav_solicitudesprevias`. | `SolicitudClass.php:300-465` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-007 | La solicitud validada crea un registro en `dav_casosprevios` con fechas, cliente, proveedor, transportista, regimen, aduana, pedido y condiciones logisticas. | `SolicitudClass.php:482-483` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-008 | El envio actualiza `fechafin` o fecha DAM segun condiciones de vehiculos/DAM. | `enviarsolicitud_ajax.php:107-114` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-009 | Documentos adicionales se marcan como enviados y estado 1 al finalizar. | `finsolicitud.php:375` | INFERRED_DRAFT_REVIEW_REQUIRED |
| CRI-BR-010 | La finalizacion genera correos y notificaciones push. | `finsolicitud.php:392-515` | INFERRED_DRAFT_REVIEW_REQUIRED |
