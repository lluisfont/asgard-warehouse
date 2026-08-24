# Logistics Order Status Milestones - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo principal

1. El usuario abre el frame Estado de Pedidos para un embarque.
2. ASGARD carga historial de `logis_edp` para cliente y embarque.
3. ASGARD carga catalogo de estados desde `logis_estados_edp`.
4. La UI comprueba permiso de escritura y si el embarque esta finalizado.
5. Si puede escribir, el usuario agrega una o mas filas nuevas.
6. El usuario informa fecha, estado, valor opcional y comentario.
7. ASGARD recibe la lista de nuevos hitos.
8. ASGARD inserta una fila en `logis_edp` por cada hito.
9. Si el estado es `53`, `99` o `160`, ASGARD marca el embarque como finalizado.
10. ASGARD envia email o notificacion segun cliente/estado.
11. La UI recarga historial y limpia filas nuevas.

## Flujo alternativo - Eliminacion

1. Se invoca endpoint de eliminacion con id de estado pedido.
2. ASGARD actualiza `logis_edp.deleted_at`.
3. El registro deja de aparecer en consultas posteriores.

## Flujo alternativo - Solo consulta

1. Si el usuario no tiene permiso de escritura, solo ve historial y exportacion.
2. Si el embarque esta finalizado, no se muestran acciones de alta aunque tenga permiso.

## Evidencia

- `estado-pedidos.php`: metodos Vue `getEstadoPedido`, `getEstados`, `guardarEdp`, `agregarNuevo`.
- `EstadoPedidosClass.php`: `guardarEstadoPedido`, `agregarEdp`, `eliminar`, `enviarMailEstado`, `enviarNotificacionEstado`.

