# Integration Risks

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

- Secretos hardcoded o globales.
- Sin retry/outbox/circuit breaker transversal.
- OCR/SFTP manejan documentos sensibles.
- Power BI/Freshservice dependen de browser y permisos externos.
- `ip-api.com` usa HTTP en MFA.
- Pusher puede exponer eventos si canal/destinatario se valida mal.
