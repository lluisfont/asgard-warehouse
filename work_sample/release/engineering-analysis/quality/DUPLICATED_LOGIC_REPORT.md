# Duplicated Logic Report

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

| Area duplicada | Riesgo |
| --- | --- |
| Login/session en `veriflogin.php` y `TwoFaClass.php` | Divergencia de hardening. |
| Upload/borrado en `documentacion*` | Bugs de seguridad repetidos. |
| OCR por cliente/modelo | Secretos, parsing y errores divergentes. |
| Reportes `*query.php` | Filtros tenant/fecha/estado inconsistentes. |
| Excel vehiculos/DAM/solicitudes | Validaciones duplicadas. |
| Notificaciones wrappers logisticos | Destinatarios y payload duplicados. |
