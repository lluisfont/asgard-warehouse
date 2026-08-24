# OpenSpec glossary

Estado: candidate_reconstruction
Idioma: Spanish

Glosario operativo para interpretar el baseline OpenSpec y los cambios futuros.

| Termino | Significado candidato | Uso en OpenSpec | Estado |
|---|---|---|---|
| `Baseline candidato` | Documentacion AS-IS inferida desde codigo, schema y evidencia; no confirmada aun. | Punto de partida para validacion y cambios. | candidate |
| `Dominio candidato` | Agrupacion funcional reconstruida desde componentes, flujos, tablas y reglas. | Unidad de analisis/especificacion. | candidate |
| `Evidencia determinista` | Dato observado sin interpretacion: archivo, linea, tabla, columna, ruta, consulta. | Soporte minimo de trazabilidad. | observed |
| `Evidencia cruzada` | Relacion entre regla, flujo, tabla, campo, endpoint y fuente. | Soporte de inferencias funcionales. | candidate |
| `Inferencia funcional` | Interpretacion razonada del comportamiento AS-IS marcada como pendiente de validar. | Redaccion de reglas/procesos. | candidate |
| `Inferencia semantica` | Significado probable de datos o terminos deducido por nombre, uso, flujo y evidencia. | Diccionario y glosario. | candidate |
| `Graphify` | Grafo de codigo utilizado para comunidades, dependencias y cobertura estructural. | Evidencia complementaria. | observed |
| `Golden master` | Prueba de caracterizacion que preserva comportamiento legacy observable. | Requisito antes de refactor sensible. | candidate |
| `OpenSpec` | Estructura de especificacion de baseline, cambios, riesgos, tareas y pruebas. | Gobierno del refactor. | candidate |
| `PASS` | Baseline confirmado, sin blockers y con aprobacion humana requerida por politica. | Estado final canonico. | governed |
| `CONDITIONAL_PASS` | Resultado verificable suficiente para revision, pero no canonico. | Estado actual de verificacion candidata. | governed |
| `IN_PROGRESS` | Proyecto aun no canonizado ni confirmado. | Estado actual del proyecto. | governed |
| `Template` | Plantilla deliberada para futuros dominios/cambios, no evidencia del sistema analizado. | No debe confundirse con placeholder real. | governed |
| `Placeholder real` | Artefacto requerido que quedo sin contenido util fuera de carpetas template. | Debe ser 0 antes de entregar. | governed |
