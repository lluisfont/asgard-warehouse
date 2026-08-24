# Rollback And Compensation

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Mutacion | Compensacion candidata |
| --- | --- |
| Insert DB + upload filesystem | Si falla DB, borrar archivo; si falla archivo, revertir/invalidar registro. |
| Crear solicitud + documentos + EDP | Transaccion o saga con estado `ERROR/PENDIENTE_REVISION`. |
| OCR + update documental | Guardar resultado bruto y aplicar update en paso separado validable. |
| Factura/planilla + PDF/QR | Generar temporal, persistir solo si todos los artefactos existen. |
| Notificacion DB + Pusher | Outbox para reemitir eventos no entregados. |
| Carga Excel masiva | Staging completo antes de mutacion definitiva. |
