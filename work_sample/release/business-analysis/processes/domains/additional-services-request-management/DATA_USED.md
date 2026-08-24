# Additional Services Request Management - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Uso de negocio | Evidencia |
| --- | --- | --- |
| `ages_solicitudes_asesoria_gestion.id` | Identificador de solicitud, presentado como `SOL-GE-*`. | `solicitud.js`, schema |
| `ages_solicitudes_asesoria_gestion.estado` | Estado de ciclo de vida de solicitud. | `tbl-estados.js`, views |
| `ages_solicitudes_asesoria_gestion.solicitante`, `e_mail`, `ciudad_id` | Datos minimos de solicitud. | `solicitud.js`, schema |
| `ages_solicitudes_asesoria_gestion.notas_adicionales` | Observaciones del solicitante. | `solicitud.js`, schema |
| `ages_solicitudes_asesoria_gestion.embarque_id` | Vinculo con embarque logistico. | `servicios-adicionales.php`, schema |
| `ages_solicitudes_asesoria_gestion.casos_previos_id`, `casos_id` | Vinculo con solicitud GA/caso. | `tbl-estados.js`, schema |
| `ages_solicitudes_asesoria_gestion.exchange_id` | Vinculo con intercambio documental. | `solicitud.js`, schema |
| `dav_tramites` | Tramites/servicios asociados a solicitud. | `servicios-adicionales.js`, views |
| `dav_entidademisora` | Entidad emisora seleccionable. | `/entidades-emisoras`, views |
| `dav_entidademisoratramite` | Area/tramite dependiente de entidad emisora. | `/tramites/{id}` |
| `dav_entidademisoratramitetipo` | Relacion entre tramite de entidad emisora y tipo de tramite disponible. | `SolicitudClass.php`, `tramites_json.php` |
| `dav_tipotramite` | Tipo de tramite y `hash_tramite`. | `/tipos-tramites/{id}` |
| `dav_clienteusuariospermisos` | Permiso de escritura para agregar/editar. | `servicios-adicionales.php` |
| `logis_embarques.fecha_finalizacion` | Bloquea alta de servicios en embarque finalizado. | `servicios-adicionales.php` |
| `v_solicitudes_asesoria_gestion` | Vista consolidada de solicitud, entidad, tipo, oficial y estado. | `.data_base/asgard.sql` |

## Observaciones de calidad de datos

- El frontend mezcla estado de solicitud con permisos y visibilidad; la autoridad final debe residir en API.
- El alta automatica hardcodea id de entidad, tramite, tipo y hashes para servicios concretos.
- Existen campos para cierre, reapertura, anulacion y facturacion en schema, pero no se reconstruyen aqui por falta de flujo frontend completo en esta evidencia.
