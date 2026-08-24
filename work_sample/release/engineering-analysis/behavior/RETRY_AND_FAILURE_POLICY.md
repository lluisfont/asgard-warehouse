# Retry And Failure Policy

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

No se observa politica transversal de retries/fallos.

| Operacion | Fallo observado/candidato | Politica candidata |
| --- | --- | --- |
| Correo | Envio falla o destinatario invalido. | Registrar intento, retry acotado, no duplicar negocio. |
| OCR | Timeout/error modelo. | Estado OCR fallido, retry manual/idempotente. |
| Upload | Archivo movido pero DB falla, o inverso. | Compensacion y limpieza. |
| Pusher | DB insert ok pero evento no emitido. | Reintento desde outbox. |
| Excel | Fila invalida. | Stage con errores, no mutar definitivo. |
| PDF/ZIP | Generacion parcial. | Archivo temporal y rename atomico. |
