# Shipment Customs Request Management - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia por capacidad

| Capacidad | Evidencia | Inferencia |
| --- | --- | --- |
| Gate de alta | `index_archivos/logistica/componentes/embarques_ver_gestion_aduanera.php:1-40` | Alta GA depende de permiso, cierre y regla cliente. |
| Guardar / guardar y enviar | `index_archivos/logistica/js/datosEmbarques.js:2827-2847` | La UI diferencia guardado simple de envio inmediato. |
| Normalizar formulario | `index_archivos/logistica/ajax/guardarGestionAduanera.php:1-132` | El endpoint convierte fechas/flags y decide accion. |
| Crear GA | `index_archivos/logistica/SolicitudesClass.php:714-869` | Inserta solicitud en `dav_casosprevios`, crea documentos y EDP. |
| Editar GA | `index_archivos/logistica/SolicitudesClass.php:871-1043` | Actualiza campos segun tipo de solicitud y estado final. |
| Editar GA aprobada | `index_archivos/logistica/SolicitudesClass.php:1054-1082` | Edicion reducida para solicitud con caso/aprobacion. |
| Enviar solicitud | `index_archivos/logistica/ajax/enviarSolicitudExterna.php:1-18`, `index_archivos/logistica/SolicitudesClass.php:1084-1098` | Enviar marca `fechafin` y devuelve confirmacion. |
| Schema | `.data_base/asgard.sql:2276-2292` | `dav_casosprevios` contiene campos clave de GA vinculada al embarque. |

## Brechas

- Catalogos de tipo de solicitud, estados y reglas cliente pendientes.
- Atomicidad, concurrencia y SQL interpolado requieren revision tecnica.
- API de asesoria/gestion adicional queda fuera del repo inspeccionado.

