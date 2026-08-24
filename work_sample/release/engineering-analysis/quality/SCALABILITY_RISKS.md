# Scalability Risks

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

- Monolito web ejecuta trabajos pesados sin workers separados observados.
- Filesystem compartido limita escalado horizontal si no esta montado/consistente.
- Sesiones PHP pueden depender de almacenamiento local si no hay session store central.
- Reportes/temporales y OCR pueden saturar DB/web.
- Pusher/correo/OCR externos requieren rate limits y backpressure.
