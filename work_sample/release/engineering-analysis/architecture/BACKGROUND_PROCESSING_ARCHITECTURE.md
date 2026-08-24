# Background Processing Architecture

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Procesos observados

- Directorio `cron` para procesos documentales, Alicorp y reportes.
- Procedimientos SQL como `cobros`, `cobros2` y temporales de reportes.
- OCR con polling de resultados externos.
- Generacion de ZIP/PDF/Excel durante requests web.
- Notificaciones Pusher enviadas desde endpoints sin cola visible.

## Riesgos

- Trabajos pesados se ejecutan durante request web.
- No se observa cola central, reintentos, idempotencia o dead-letter.
- Temporales SQL/reportes pueden competir si no estan aislados por conexion/sesion.
- Cron/procesos externos no incluidos pueden consumir tablas SQL-only.
