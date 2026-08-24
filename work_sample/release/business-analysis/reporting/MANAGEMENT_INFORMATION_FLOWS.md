# Management information flows

Estado: candidate_reconstruction  
Confianza: media

```mermaid
flowchart LR
  Operacion[Datos operativos] --> SQL[Consultas SQL/vistas/temporales]
  SQL --> Reportes[Reportes web y exportaciones]
  SQL --> Dashboard[Dashboard generico]
  SQL --> PowerBI[Power BI]
  Reportes --> Cliente[Cliente]
  Dashboard --> Gerencia[Gerencia]
  PowerBI --> Gerencia
  Reportes --> Finanzas[Finanzas/backoffice]
```

## Controles necesarios

Definir ownership de metricas, filtros obligatorios, periodicidad, calidad de datos y reconciliacion entre reportes.
