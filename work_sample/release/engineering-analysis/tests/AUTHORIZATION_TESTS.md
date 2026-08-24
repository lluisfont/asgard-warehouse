# Authorization Tests

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| ID | Prueba | Resultado esperado |
| --- | --- | --- |
| AUTH-001 | Endpoint protegido sin sesion. | 401/redirect sin mutacion. |
| AUTH-002 | Usuario sin escritura intenta alta GA `65`. | 403/sin boton/sin mutacion server-side. |
| AUTH-003 | Usuario sin permiso servicios adicionales `49/66`. | 403/sin mutacion. |
| AUTH-004 | Descargar documento de otro cliente. | 403/no contenido. |
| AUTH-005 | Editar embarque/caso de otro cliente. | 403/sin update. |
| AUTH-006 | Proveedor accede a contexto cliente no asociado. | 403. |
| AUTH-007 | Notificacion para destinatario no asociado. | No visible/no link. |
