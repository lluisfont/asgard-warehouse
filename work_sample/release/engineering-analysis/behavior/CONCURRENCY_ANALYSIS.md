# Concurrency Analysis

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Area | Riesgo de concurrencia |
| --- | --- |
| Cargas Excel vehiculos/solicitudes | Reemplazos masivos e inserts multiples sin transaccion visible pueden dejar estados parciales. |
| Documentos | Upload + insert/update SQL + filesystem no es atomico. |
| EDP/notificaciones | Doble click/reintento puede crear eventos duplicados. |
| Factura/planilla | Generacion PDF/QR/ZIP durante request puede solaparse por mismo id. |
| Temporales/reportes | Procedimientos y tablas temporales dependen del alcance de conexion y limpieza. |
| MFA/login | Codigos y contadores por sesion/usuario pueden competir con reenvios. |

Mitigacion candidata: transacciones DB, idempotency keys, locks por recurso critico y separacion de side effects.
