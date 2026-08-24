# Error Handling By Integration

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Integracion | Manejo candidato / gap |
| --- | --- |
| Mail | No se observa outbox/retry transversal. |
| OCR | Errores devueltos en arrays/mensajes, riesgo update parcial. |
| Pusher | Si falla emision tras DB insert, no se observa reintento. |
| SFTP/SSH | Riesgo fallo comando/timeout sin compensacion clara. |
| Power BI/Freshservice | Fallo queda en browser externo. |
| ip-api | Si falla, devuelve texto generico. |
