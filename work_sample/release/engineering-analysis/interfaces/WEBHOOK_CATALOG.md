# Webhook catalog

Estado: inferred_from_static_evidence  
Confianza: baja

No se ha confirmado una superficie formal de webhooks entrantes con firma, versionado y contrato documentado. La aplicacion si contiene endpoints HTTP que podrian recibir callbacks o integraciones, pero deben diferenciarse de AJAX interno y formularios web.

## Candidatos a revisar

| Area | Motivo |
|---|---|
| OCR/documentos | Posible callback o polling externo tras procesamiento |
| Notificaciones | Integracion con servicio realtime/correo |
| SFTP/API externas | Posibles confirmaciones de transferencia o estado |
| Power BI/reporteria | Embebidos o refresh externos no confirmados |

## Reglas para canonizar

Un endpoint solo debe clasificarse como webhook canonico si se confirma: origen externo, metodo HTTP, payload, autenticacion/firma, idempotencia, respuesta esperada, reintentos y logs.
