# Customer Shipment Rating Feedback - Business Rules

| Rule ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-CSRF-001 | El rating se guarda con `tipo_usuario='CLIENTE'`. | `guardarRating` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CSRF-002 | La existencia de rating se consulta por cliente, embarque, solicitud, usuario y `deleted_at IS NULL`. | `getRating` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CSRF-003 | El modal mensual se muestra si no hay rating o si `created_at + 30 DAY` ya es anterior a la fecha actual. | `verificaMensual` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CSRF-004 | La UI permite incrementos de 0.5 estrellas. | `rating.js` usa `increment=0.5` | INFERRED_DRAFT_REVIEW_REQUIRED |
