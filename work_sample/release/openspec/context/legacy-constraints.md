# Legacy constraints

Estado: candidate_reconstruction

- PHP legacy con mezcla de controlador, vista, validacion y SQL.
- `mysql_query` y consultas directas en rutas criticas.
- Sesion y permisos distribuidos.
- Respuestas HTTP heterogeneas.
- Documentos y rutas de archivo sensibles.
- Variantes por cliente embebidas.
- Reporteria dependiente de SQL historico.
- Scheduler real pendiente de confirmar.
- Base de datos como contrato de facto.

## Implicacion

El refactor debe avanzar por estrangulamiento/adaptadores, no por reemplazo masivo de comportamiento.
