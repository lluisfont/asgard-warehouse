# Logistics Order Status Milestones - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Registrar y consultar hitos de estado de pedido asociados a un embarque logistico, permitiendo a usuarios autorizados dejar fecha, estado, cantidad opcional y comentario, comunicar cambios a participantes y bloquear nuevas capturas cuando el embarque esta finalizado.

## Alcance observado

- Consulta historica de estados de pedido por embarque.
- Catalogo de estados EDP filtrado por cliente y tipo de proveedor.
- Captura manual de nuevos estados desde frame logistico.
- Registro en `logis_edp` con embarque, cliente, estado, fecha, comentario, valor opcional, usuario y tipo de creador.
- Borrado logico de estados registrados.
- Exportacion Excel del historial mostrado.
- Control de escritura por permiso `dav_clienteusuariospermisos.idreportescliente = 73`.
- Bloqueo de captura cuando `logis_embarques.fecha_finalizacion` ya existe.
- Finalizacion automatica de embarque para estados observados `53`, `99` y `160`.
- Comunicacion por email para estado `58` en clientes `429` y `755`; en otros casos, notificacion realtime.

## Fuera de alcance observado

- Catalogo funcional completo de `logis_estados_edp`.
- Significado de los ids finales `53`, `99`, `160`.
- Significado de estado `58`.
- Reglas de visibilidad completa del frame donde se embebe estado de pedidos.
- Correccion de posibles defectos tecnicos en rutinas de notificacion.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario cliente | Consulta y, con permiso, registra nuevos estados de pedido. |
| Usuario interno / operador | Puede aparecer como creador segun `created_type`. |
| ASGARD | Persiste hitos, consulta catalogos, finaliza embarque y dispara comunicaciones. |
| Participantes logisticos | Reciben notificaciones: cliente, usuario cliente especifico, gestor transporte, agente aduana, transportista. |

## Entradas

- `embarque_id`.
- Estado seleccionado de `logis_estados_edp`.
- Fecha de estado.
- Comentario/descripcion `edp`.
- Valor opcional, usado para cantidad cuando aplica.
- Sesion: `idcliente`, `idclienteusuarios`.

## Salidas

- Nueva fila en `logis_edp`.
- `logis_embarques.fecha_finalizacion` y `fecha_finalizacion_usuario` cuando el estado es final.
- Email de actualizacion de fecha pick up para casos observados.
- Notificacion persistida y evento Pusher para actualizacion de estado.
- Historial visible/exportable de estados de pedido.
- Borrado logico de un hito si se invoca eliminacion.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/logistica/frames/estado-pedidos.php` | UI Vue de consulta, alta, exportacion, permiso de escritura y bloqueo por finalizacion. |
| `index_archivos/logistica/ajax/estado-pedidos/*.php` | Endpoints de consulta, catalogo, guardado y borrado. |
| `index_archivos/logistica/ajax/estado-pedidos/guardar-edp.php` | Wrapper JSON directo para guardar un estado de pedido mediante `EstadoPedidosClass::guardarEstadoPedido`. |
| `index_archivos/logistica/enviarCorreoEstadoPedido.php` | Variante auxiliar de comunicacion por email asociada a cambios de estado de pedido. |
| `index_archivos/logistica/estado_pedidos/EstadoPedidosClass.php` | Insercion en `logis_edp`, finalizacion de embarque, email/notificacion y borrado logico. |
| `.data_base/asgard.sql:12088-12115` | Esquema `logis_edp`. |
| `.data_base/asgard.sql:12422-12442` | Esquema `logis_estados_edp`. |

## Criterios de aceptacion candidatos

- El historial debe consultarse por cliente de sesion y embarque.
- Solo usuarios con escritura en permiso `73` deben ver alta/guardado.
- No debe permitirse alta si el embarque ya tiene `fecha_finalizacion`.
- Cada nuevo hito debe registrar `created_by` y `created_type = CLIENTE` en el flujo inspeccionado.
- Los estados finales observados deben fijar `fecha_finalizacion` del embarque.
- El catalogo de estados debe excluir registros con `deleted_at`.
- Los estados borrados logicamente no deben aparecer en historial.
