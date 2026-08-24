# State Transition Tests

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Maquina | Transiciones candidatas |
| --- | --- |
| MFA | emitido -> validado/caducado/bloqueado. |
| Usuario | activo -> bloqueado -> desbloqueable/cambio password. |
| DAV/FDM | para revision -> aprobado/rechazado -> finalizado. |
| GA | guardada -> enviada -> editada/aprobada. |
| Embarque | cotizacion -> operador aceptado/confirmado -> finalizado. |
| Documento | requerido -> recibido -> aprobado/observado/eliminado. |
| Vehiculo Excel | cargado -> con errores/completo -> documentos generados. |
| Notificacion | no leida -> leida -> no leida/eliminada. |
