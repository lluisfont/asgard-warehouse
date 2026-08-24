# Transaction Boundary Catalog

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Transaccion candidata | Tablas/efectos |
| --- | --- |
| Login exitoso | sesion, `dav_clienteusuarios`, `dav_cliente`, `log_asgard_ecosistema`, JWT. |
| MFA verificar/autenticar | `dav_codigos_2fa`, sesion, logs, visitas. |
| Crear GA embarque | `dav_casosprevios`, `dav_documentosprevios`, `logis_edp`, correo/opcional. |
| Carga Excel vehiculos | filesystem, `dav_vehiculosprevios`, errores, documentos previos. |
| Cierre DAV/FDM | `dav_dav`, EDP, correo. |
| Upload documental | `dav_documentosprevios`/otros, filesystem. |
| Factura/planilla | DB, PDF, QR, ZIP/descarga. |
| Notificacion | `push_notificacion`, `push_notificacionusuarios`, Pusher. |
| OCR documento | filesystem/remoto, OCR externo, DB destino. |
