# Logging Analysis

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Logs observados

- `log_asgard_ecosistema` para login/sesion.
- `logsAsgard.php` como helper de auditoria.
- Contadores de reportes en `dav_contadorreportesclientes`.
- Logs/errores puntuales via `error_log`.
- Notificaciones persistidas `push_*`.

## Gaps

- No se observa correlation id transversal.
- No hay politica uniforme de niveles, estructura y retencion.
- Riesgo de loggear datos sensibles OCR/documentos/PII.
- Fallos de servicios externos no siempre quedan persistidos.
