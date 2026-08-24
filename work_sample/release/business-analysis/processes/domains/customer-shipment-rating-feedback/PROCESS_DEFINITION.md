# Customer Shipment Rating Feedback - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Permitir que el cliente califique y comente un embarque, solicitud o caso, mostrando el formulario solo cuando no existe calificacion vigente o cuando ya transcurrio el periodo mensual observado.

## Alcance observado

- Componente Vue de rating en vistas de solicitud, gestion aduanera y embarque.
- Consulta de rating existente por cliente, embarque, solicitud, usuario y tipo `CLIENTE`.
- Insercion de rating y comentario en `dav_rating`.
- Verificacion mensual para decidir si se muestra modal de calificacion.

## Fuera de alcance observado

- Analitica posterior de satisfaccion.
- Edicion o borrado de calificaciones.
- Calificaciones de usuarios internos u operadores.
- Catalogo formal de escala y obligatoriedad de comentario.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/modulos/rating/rating.js:1-153` | Componente UI, consulta y envio de rating. |
| `index_archivos/modulos/rating/rating.php:2-13` | Endpoint de guardado. |
| `index_archivos/modulos/rating/get-rating.php:2-10` | Endpoint de consulta. |
| `index_archivos/modulos/rating/verifica-mensual.php:2-10` | Endpoint de regla mensual. |
| `index_archivos/modulos/rating/RatingClass.php:8-19` | Inserta `dav_rating`. |
| `index_archivos/modulos/rating/RatingClass.php:24-31` | Consulta rating vigente. |
| `index_archivos/modulos/rating/RatingClass.php:43-67` | Evalua si corresponde mostrar modal mensual. |

## Criterios de aceptacion candidatos

- El cliente puede enviar rating y comentario.
- Si existe rating vigente del mismo usuario/contexto, la UI lo muestra como enviado.
- Si no hay rating o pasaron 30 dias desde el ultimo, puede mostrarse el modal mensual.
- Las calificaciones observadas se guardan con `tipo_usuario='CLIENTE'`.
