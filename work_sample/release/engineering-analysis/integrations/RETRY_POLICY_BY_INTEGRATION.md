# Retry Policy By Integration

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

No se observa politica uniforme. Candidata:

- Mail/Pusher: outbox idempotente con retries acotados.
- OCR: retry manual/cola con idempotency key por documento/modelo.
- SFTP: no retry ciego de comandos mutadores; registrar fallo.
- Power BI/Freshservice: degradacion UI.
- ip-api: timeout corto y no bloquear MFA si falla ubicacion.
