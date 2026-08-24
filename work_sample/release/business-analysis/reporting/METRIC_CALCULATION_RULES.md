# Metric calculation rules

Estado: candidate_reconstruction  
Confianza: baja

Las reglas de calculo de metricas parecen residir en consultas SQL, dashboards y reportes PHP. No se ha observado catalogo central de formulas.

## Reglas candidatas a preservar

- Filtros por cliente, usuario, rol y rango de fechas.
- Exclusion/inclusion por estado operacional.
- Conteo de pendientes y cerrados por dominio.
- Agregacion de costos, gastos y facturacion.
- Transformaciones especificas por cliente o reporte.
- Uso de tablas temporales/vistas para preprocesar datos.
