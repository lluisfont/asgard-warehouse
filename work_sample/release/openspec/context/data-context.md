# Data context

Estado: candidate_reconstruction  
Fuente: engineering-analysis/data

La base de datos es el nucleo del comportamiento legacy. Se detectaron 1432 tablas y 13149 columnas, con fuerte presencia de catalogos, tablas operativas, temporales/vistas y familias `dav_*`, `logis_*`, `tck_*`, `ada_*`, entre otras.

Se genero una capa explicita de ingenieria inversa semantica para tablas y campos. Esta capa explica dominio candidato, lifecycle, sensibilidad y significado funcional probable de cada elemento, manteniendo el estado `SEMANTIC_INFERENCE_REVIEW_REQUIRED`.

## Consideraciones

- Muchas relaciones no estan garantizadas como foreign keys declaradas.
- Tablas temporales y vistas participan en reporteria/procesos.
- Datos personales aparecen en usuarios, contactos, telefonos, correos y tokens.
- Los catalogos y magic values condicionan estados, permisos y reglas.

## Regla OpenSpec

No cambiar schema, claves, estados o catalogos sin caracterizar consultas y flujos consumidores.
