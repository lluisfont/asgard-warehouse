# Architecture Risks

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

| ID | Riesgo | Impacto |
| --- | --- | --- |
| AR-001 | Monolito PHP con includes globales y SQL directo. | Cambios con alto impacto lateral. |
| AR-002 | Mezcla de `mysql_*` legacy y PDO. | Seguridad y mantenibilidad inconsistentes. |
| AR-003 | Autorizacion distribuida por pantalla/endpoints. | Riesgo IDOR/tenant bypass. |
| AR-004 | Filesystem como parte central de estado. | Dificulta atomicidad, backup, seguridad y despliegue. |
| AR-005 | Procesos pesados en request web. | Timeouts y fallos parciales. |
| AR-006 | Secretos/constantes globales. | Exposicion y rotacion dificil. |
| AR-007 | Dependencias vendorizadas antiguas dentro del repo. | Vulnerabilidades y actualizacion compleja. |
| AR-008 | Tablas/reportes temporales y SQL derivado abundante. | Dificil razonamiento de consistencia. |
