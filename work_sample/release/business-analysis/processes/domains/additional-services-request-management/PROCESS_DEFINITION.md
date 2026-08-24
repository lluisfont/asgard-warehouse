# Additional Services Request Management - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Solicitar, registrar y dar seguimiento a servicios adicionales de asesoria/gestion vinculados a embarques, solicitudes GA o casos, seleccionando entidad emisora, tramite y tipo de tramite, integrando el intercambio documental y avanzando la solicitud por estados operativos.

## Alcance observado

- Alta de nueva solicitud de servicio adicional.
- Captura de solicitante, correo, ciudad, linea y notas adicionales.
- Seleccion de entidad emisora, area/tramite y tipo de servicio.
- Edicion o eliminacion de tramites mientras el estado lo permite.
- Bandejas por estado: Pendientes, Enviados, Recepcionados, Asignados, En Revision, En Proceso y Finalizado.
- Creacion desde pestaña de embarque `Servicios Adicionales`.
- Creacion desde Asesoria/Gestion standalone.
- Creacion automatica de servicios predefinidos al actualizar intercambio documental.
- Vinculo con Intercambio Documental para crear exchange, agregar documentos y participantes.
- Control de boton/agregado por permiso de escritura y por finalizacion de embarque.

## Fuera de alcance observado

- Implementacion backend completa del API `ASESORIA_GESTION_API`.
- Reglas internas de asignacion de oficiales.
- Cierre/facturacion final del servicio adicional.
- Valorizacion/costos detallados de cada servicio.
- SLA formal por tipo de tramite.

## Actores

| Actor | Rol observado |
| --- | --- |
| Solicitante cliente/operativo | Registra solicitud y servicios requeridos. |
| Coordinador/usuario ASGARD | Edita, envia, revisa o da seguimiento a la solicitud. |
| Oficial asignado | Atiende el tramite cuando la solicitud esta asignada. |
| ASGARD | Gestiona bandejas, formularios, permisos y llamadas API. |
| ASESORIA_GESTION_API | Persiste solicitud/tramites y cambios de estado. |
| Intercambio Documental | Abre exchange, documentos, participantes y aprobadores. |

## Entradas

- Solicitante, correo, ciudad y linea.
- Notas adicionales.
- Embarque, caso previo o caso relacionado.
- Entidad emisora.
- Tramite/area.
- Tipo de tramite y `hash_tramite`.
- Exchange documental existente o nuevo.
- Permiso `dav_clienteusuariospermisos.idreportescliente=66`.

## Salidas

- Solicitud `SOL-GE-*`.
- Tramites adicionales vinculados a la solicitud.
- Exchange documental asociado.
- Documentos requeridos agregados al exchange.
- Bandeja actualizada por estado.
- Notificaciones de nueva solicitud.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/logistica/frames/servicios-adicionales.php` | Integra servicios adicionales dentro de embarque y controla agregar por finalizacion/permiso. |
| `index_archivos/asesoria-gestion/components/tbl-estados.js` | Bandejas por estado, modal de nueva solicitud y alta desde GA/embarque. |
| `index_archivos/asesoria-gestion/components/solicitud.js` | Formulario de solicitud, guardar, editar, enviar y crear intercambio documental. |
| `index_archivos/asesoria-gestion/components/servicios-adicionales.js` | Seleccion entidad/tramite/tipo, alta/edicion/eliminacion de tramites y documentos de exchange. |
| `index_archivos/asesoria-gestion/components/tramite.js` | Edicion/eliminacion de tramite por fila. |
| `index_archivos/logistica/ajax/actualizaridexchange.php` | Creacion automatica de servicios certificado origen, fitosanitario e inocuidad. |
| `.data_base/asgard.sql` | Tablas/vistas `ages_solicitudes_asesoria_gestion`, `dav_tramites`, `v_solicitudes_asesoria_gestion`, `v_reporte_general_asesoria_gestion`. |

## Criterios de aceptacion candidatos

- El usuario solo puede agregar servicios si tiene escritura y el embarque no esta finalizado.
- Una solicitud nueva debe tener solicitante, email, ciudad y al menos un tramite.
- La lista de tramites depende de la entidad emisora seleccionada.
- La lista de tipos depende del tramite seleccionado.
- Una solicitud pendiente puede enviarse.
- La edicion de tramites queda bloqueada al llegar a estados de recepcion/asignacion/revision/proceso/finalizacion.
- Si existe exchange, los documentos del tramite se agregan al exchange; si no, se crea uno nuevo.
