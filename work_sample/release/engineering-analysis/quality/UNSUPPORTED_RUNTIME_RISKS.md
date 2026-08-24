# Unsupported Runtime Risks

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Riesgo | Motivo |
| --- | --- |
| Extension `mysql_*` | Obsoleta/no soportada en PHP moderno. |
| Librerias PDF/Excel antiguas | Compatibilidad y CVEs posibles. |
| Sintaxis PHP legacy | Puede depender de versiones antiguas. |
| Encoding mixto | Mojibake observado en textos; riesgo migracion UTF-8. |
| `allow_url_fopen=1` | Superficie SSRF/lectura remota si no se controla. |
| Cron/servidor no documentado | Riesgo al reinstalar o mover entorno. |
