# Logistics Order Item Detail Maintenance - Data Used

| Entidad / Tabla | Uso observado | Campos relevantes |
| --- | --- | --- |
| `logis_pedidos_detalle` | Posiciones de pedido mantenidas. | `id`, `pedido_id`, `pedido`, `cliente_lineas_id`, `posicion`, `cantidad_pedido`, `material`, `texto_breve`, `ce`, `origen`, `destino`, `deleted_at` |
| API `detalle-pedido/{idpedido}` | Fuente de detalle inicial. | `detalle_pedido`, `detalle_pedido_otro` |
| Catalogo almacenes | Apoya seleccion visual de almacen. | Datos retornados por `CotizacionClass::listaAlmacenes` |

## Persistencia observada

- `UPDATE logis_pedidos_detalle SET {campo}='{valor}' WHERE id={id}`.
