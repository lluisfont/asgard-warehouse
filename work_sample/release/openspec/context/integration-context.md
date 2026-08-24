# Integration context

Estado: candidate_reconstruction  
Fuente: engineering-analysis/integrations

Integraciones candidatas: correo/SendGrid, notificaciones realtime/Pusher, OCR/procesamiento documental, SFTP/API externas, Power BI, descargas/cargas de ficheros y posiblemente procesos batch.

## Riesgos

- Secretos hardcodeados o gestionados fuera de vault.
- Contratos externos no documentados.
- Politicas de timeout, rate limit y retry no uniformes.
- Fallback manual probable ante fallos.
- Webhooks entrantes no confirmados como superficie formal.

## Regla OpenSpec

Cada integracion debe tener contrato AS-IS, credenciales gestionadas, logs, reintentos y prueba de caracterizacion antes de tocarla.
