# Migration Risks

Estado: INFERRED_RISK_REVIEW_REQUIRED
Idioma: Spanish

- Encoding mixto.
- Tablas legacy con nombres duplicados/copy.
- Dependencia de procedimientos/temporales/reportes.
- Ficheros historicos referenciados por DB.
- Magic values sin FK/catalogo confirmado.
- SQL-only externo no incluido.
- PII/secreto en dumps o artefactos.
