# Technical Debt Register

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| Deuda | Prioridad |
| --- | --- |
| `mysql_*` y SQL concatenado. | Critica |
| Secretos hardcoded/globales. | Critica |
| Autorizacion/tenant guard distribuido. | Critica |
| Upload/download sin servicio central. | Alta |
| Dependencias antiguas vendorizadas. | Alta |
| Reglas por cliente hardcoded. | Alta |
| Falta de pruebas de caracterizacion. | Alta |
| Jobs pesados en request. | Media |
| Observabilidad limitada. | Media |
