# Critical Path Tests

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Prioridad | Flujo | Pruebas candidatas |
| --- | --- | --- |
| P0 | Login/MFA | CSRF, password, bloqueo, MFA valido/caducado, sesion/JWT. |
| P0 | Tenant isolation | Recurso de otro cliente rechazado en casos/documentos/embarques/reportes. |
| P0 | Upload/download documental | Subir, listar, borrar, descargar, validar rutas y DB. |
| P0 | Carga Excel vehiculos | Archivo valido, errores catalogo, duplicado chasis, documentos tipo 19. |
| P0 | Factura/planilla | Generacion PDF/QR, dosificacion, descarga, estado. |
| P0 | EDP/casos | Ultimo estado por fecha/orden/id y reportes dependientes. |
| P1 | DAV/FDM cliente | Aprobar, rechazar, finalizar, EDP y correo. |
| P1 | Notificaciones | Crear, destinatarios, leer/no leer, Pusher. |
| P1 | OCR | Exito, error modelo, campos faltantes, no actualizacion parcial. |
