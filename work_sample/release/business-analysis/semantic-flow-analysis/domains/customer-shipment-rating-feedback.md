# customer-shipment-rating-feedback - semantic flow usage

Estado: FLOW_SEMANTIC_INFERENCE_REVIEW_REQUIRED

## Resumen

- Tablas cruzadas: 1
- Campos cruzados: 11
- Tablas con mutacion observada: 1
- Riesgos candidatos: documentos/OCR; catalogos/semantica

## Tablas en el flujo

| Tabla | Uso | Rol semantico | Campos | Reglas/riesgos | Evidencias |
|---|---|---|---|---|---|
| `dav_rating` | CREATE | Entidad transaccional modificada por el flujo; sus cambios deben caracterizarse antes de refactor. | casos_id, cliente_id, comentario, created_at, created_by, deleted_at, embarque_id, rating, solicitud_id, tipo_usuario, updated_at | control de acceso/cliente; transicion o bloqueo por estado; regla documental/carga-descarga; persistencia/atomicidad/concurrencia; catalogo/semantica pendiente | index_archivos/modulos/rating/rating.js:1-153 \| index_archivos/modulos/rating/rating.php:2-13 \| index_archivos/modulos/rating/get-rating.php:2-10 \| index_archivos/modulos/rating/verifica-mensual.php:2-10 \| index_archivos/modulos/rating/RatingClass.php:8-19 \| index_archivos/modulos/rating/RatingClass.php:24-31 \| index_archivos/modulos/rating/RatingClass.php:43-67 \| rating.js:1-153 \| rating.php:2-13 \| get-rating.php:2-10 \| verifica-mensual.php:2-10 \| RatingClass.php:8-67 \| index_archivos/modulos/r |

## Campos con uso cruzado

| Tabla | Campo | Rol en flujo | Sensibilidad | Contexto |
|---|---|---|---|---|
| `dav_rating` | `casos_id` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
| `dav_rating` | `cliente_id` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
| `dav_rating` | `comentario` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | - Insercion de rating y comentario en `dav_rating`. \| - Catalogo formal de escala y obligatoriedad de comentario. \| El cliente puede enviar rating y comentario. \| Si corresponde mostrar, el usuario selecciona estrellas y comentario. \| ```mermaid flowchart TD A["Vista con rating"] --> B["Consultar rating vigente"] B -->\|Existe\| C["Mostrar enviado"] B -->\|No existe\| D["Permitir calificar"] D --> E["Enviar rating/comentario"] E --> F["Insertar dav_rating"] B --> G["Verificacion mensual"] G -->\|30 dias cumplidos\| D ``` |
| `dav_rating` | `created_at` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | Para modal mensual, ASGARD calcula `created_at + 30 DAY`. \| BR-CSRF-003 \| El modal mensual se muestra si no hay rating o si `created_at + 30 DAY` ya es anterior a la fecha actual. \| \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
| `dav_rating` | `created_by` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
| `dav_rating` | `deleted_at` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | BR-CSRF-002 \| La existencia de rating se consulta por cliente, embarque, solicitud, usuario y `deleted_at IS NULL`. \| \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
| `dav_rating` | `embarque_id` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
| `dav_rating` | `rating` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | # Customer Shipment Rating Feedback - Process Definition ## Estado INFERRED_DRAFT_REVIEW_REQUIRED ## Objetivo de negocio Permitir que el cliente califique y comente un embarque, solicitud o caso, mostrando el formulario solo cuando no existe calificacion vigente o cuando ya transcurrio el periodo mensual observado. \| Componente Vue de rating en vistas de solicitud, gestion aduanera y embarque. \| - Consulta de rating existente por cliente, embarque, solicitud, usuario y tipo `CLIENTE`. \| # Customer Shipment Rating Feedback - Process Flow 1. \| La vista carga el componente de rating con cliente,  |
| `dav_rating` | `solicitud_id` | Campo de soporte funcional mencionado en datos/reglas del flujo. | PERSONAL_OR_CONTACT_DATA | \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
| `dav_rating` | `tipo_usuario` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | - Las calificaciones observadas se guardan con `tipo_usuario='CLIENTE'`. \| BR-CSRF-001 \| El rating se guarda con `tipo_usuario='CLIENTE'`. \| \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
| `dav_rating` | `updated_at` | Campo de soporte funcional mencionado en datos/reglas del flujo. | BUSINESS_DATA | \| `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` \| ## Mutaciones observadas |
