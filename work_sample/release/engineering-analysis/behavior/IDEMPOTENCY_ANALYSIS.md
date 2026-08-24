# Idempotency Analysis

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

No se observa un patron transversal de idempotency key.

| Operacion | Riesgo si se reintenta |
| --- | --- |
| Crear solicitud/GA/embarque | Duplicado de cabecera y documentos. |
| Registrar EDP | Historial duplicado o estado vigente alterado. |
| Enviar notificacion/correo | Duplicado de aviso y filas `push_*`. |
| Cargar Excel | Doble insert/reemplazo parcial. |
| Generar factura/planilla/PDF | Archivo/contador/motivo inconsistente. |
| OCR | Doble actualizacion documental o costos. |

Recomendacion: definir idempotency key por recurso+accion para endpoints mutadores criticos.
