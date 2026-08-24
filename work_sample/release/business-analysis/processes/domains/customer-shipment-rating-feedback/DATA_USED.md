# Customer Shipment Rating Feedback - Data Used

| Tabla | Uso | Campos |
| --- | --- | --- |
| `dav_rating` | Calificaciones y comentarios de cliente. | `cliente_id`, `embarque_id`, `solicitud_id`, `casos_id`, `rating`, `comentario`, `created_by`, `tipo_usuario`, `created_at`, `updated_at`, `deleted_at` |

## Mutaciones observadas

- `INSERT INTO dav_rating (...)`.

## Lecturas observadas

- Rating vigente por contexto/usuario.
- Ultima fecha de rating para regla mensual.
