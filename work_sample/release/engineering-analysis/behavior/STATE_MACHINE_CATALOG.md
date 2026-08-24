# State Machine Catalog

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

Los modelos detallados estan en `STATE_MODEL.md` por dominio.

| Maquina / familia | Estados candidatos |
| --- | --- |
| Login/MFA | activo, bloqueado, MFA pendiente, codigo valido/caducado, cambio password requerido. |
| DAV/FDM cliente | para revision, aprobado, rechazado, finalizado. |
| Solicitud/GA | borrador/guardada, enviada (`fechafin`), aprobada/reducida, finalizada. |
| Embarque logistico | cotizacion, operador solicitado, costos cargados, aceptado/confirmado, finalizado. |
| EDP aduanero/logistico | historial por estado/etapa; vigente por ultimo registro. |
| Documentos | requerido, recibido, faltante, aprobado, observado, eliminado. |
| Vehiculo Excel | cargado, con error, completo, listo para DAM/AP/documento. |
| Factura/planilla | generada, pendiente pago, pagada, anulada/estado factura. |
| Notificacion | no leida, leida, eliminada. |
| MIC/DEX/SCP | pendiente, recibido, enviado, concluido. |
