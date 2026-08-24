# CLI command catalog

Estado: inferred_from_static_evidence  
Confianza: baja-media

No se ha identificado una interfaz CLI de negocio equivalente a un producto soportado para usuarios finales. La ejecucion principal observada corresponde a aplicacion web PHP, scripts heredados, cron/procesos programados y herramientas externas de analisis usadas durante esta reconstruccion.

## Comandos/herramientas relevantes

| Tipo | Evidencia | Uso |
|---|---|---|
| Scripts PHP programados | Carpetas y referencias `cron` | Automatizaciones periodicas, conciliaciones, avisos o tareas batch |
| Procedimientos SQL | Schema `.data_base/asgard.sql` | Logica batch o calculos ejecutados desde base de datos |
| Generadores documentales | PHP de reporteria, planillas, facturas y descargas | Produccion bajo demanda desde request web |
| Brownfield analyzer | `.brownfield/bin/brownfield.py` | Herramienta de analisis, no runtime de negocio |
| Graphify | `.brownfield/work/graphify` | Grafo de codigo usado para reconstruccion, no runtime de negocio |

## Riesgo

Los scripts programados pueden funcionar como interfaces operativas aunque no sean CLI publicas. Deben inventariarse por scheduler real antes de migrar infraestructura o separar servicios.
