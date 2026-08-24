# Customs Request Intake - State Model

Estado: INFERRED_DRAFT_REVIEW_REQUIRED  
Idioma de negocio: Spanish

| Estado candidato | Descripcion | Evidencia |
| --- | --- | --- |
| Archivo recibido | Archivo de carga guardado en servidor. | `SolicitudClass.php:14-35` |
| Staging cargado | Filas insertadas en `dav_solicitudesprevias`. | `SolicitudClass.php:90-118` |
| Validado con errores | Fila conserva `error > 0` y `mensajeerror`. | `SolicitudClass.php:300-465` |
| Validado sin errores | Fila tiene ids maestros resueltos y puede crear caso previo. | `SolicitudClass.php:300-465` |
| Caso previo creado | Registro creado en `dav_casosprevios`. | `SolicitudClass.php:482-496` |
| Solicitud enviada/finalizada | `fechafin`, fecha DAM o `fechaaprobacion` actualizada. | `enviarsolicitud_ajax.php:107-114` |
| Notificada | Correos y notificaciones push emitidas. | `finsolicitud.php:392-515` |
