# Error response catalog

Estado: inferred_from_static_evidence  
Confianza: media

## Patrones observados

| Patron | Descripcion | Impacto |
|---|---|---|
| HTML inline | Mensajes insertados en la pantalla o fragmento AJAX | El consumidor depende de texto/markup historico |
| JSON parcial | Respuestas con banderas, codigos o mensajes no normalizados | Dificulta clientes genericos y pruebas contractuales |
| `die`/salida directa | Cortes de ejecucion con texto o error tecnico | Riesgo de fuga de informacion y comportamiento no uniforme |
| Redireccion | Errores de sesion/permisos que terminan en login o pantalla anterior | La API real queda mezclada con navegacion |
| Fichero inexistente/denegado | Descargas documentales con fallos por ruta, permiso o estado | Debe caracterizarse por endpoint y tipo documental |
| Error SQL/PHP | Fallos heredados de consulta, warning o include | Riesgo operativo y de exposicion tecnica |

## Contrato minimo a caracterizar

- Formato exacto ante sesion caducada.
- Formato exacto ante permiso insuficiente.
- Respuesta ante parametro obligatorio ausente.
- Respuesta ante entidad inexistente.
- Respuesta ante fichero no encontrado.
- Respuesta ante error de integracion externa.
- Respuesta ante duplicidad o transicion de estado invalida.

## Criterio de refactor

No sustituir mensajes ni codigos de forma global hasta disponer de golden masters por flujo critico. La normalizacion debe introducirse detras de adaptadores compatibles.
