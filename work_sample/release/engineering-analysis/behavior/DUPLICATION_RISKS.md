# Duplication Risks

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Duplicacion | Riesgo |
| --- | --- |
| Endpoints legacy `query.php` y variantes Excel/PDF | Reglas parecidas divergentes por modulo. |
| OCR por cliente/modelo | Credenciales, parsing y errores repetidos en varios scripts. |
| Notificaciones logisticas | Wrappers parecidos con riesgo de variables reutilizadas. |
| Documentacion/documentacionaprobado | Upload/borrado duplicados con diferencias sutiles. |
| Login primario vs MFA | Sesion/JWT/logs implementados dos veces. |
| DAV/DAM/vehiculos | Validaciones y catalogos repetidos en controladores y vistas. |

Accion candidata: extraer servicios comunes solo despues de pruebas de caracterizacion.
