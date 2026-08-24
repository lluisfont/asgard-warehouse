# Logistics Order Item Detail Maintenance - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Mantener campos operativos de posiciones de pedido logistico, especialmente datos de origen/destino, almacen, SKU/marca y cantidad visible, para que el detalle de pedido usado por embarques y agrupaciones quede actualizado.

## Alcance observado

- Vista Items dentro del contexto logistico de embarque/pedido.
- Carga de detalle de posiciones desde API `detalle-pedido/{idpedido}`.
- Presentacion de pedido, posicion, descripcion, SKU/marca, cantidad para cliente `802`, almacen u origen/destino segun cliente.
- Guardado masivo de campos del formulario `descripcion_items`.
- Actualizacion directa de `logis_pedidos_detalle` por id de posicion.

## Fuera de alcance observado

- Alta inicial del pedido.
- Agrupacion de posiciones en embarques.
- Validacion de catalogos de origen/destino/almacen.
- Auditoria formal de cambios por posicion.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario logistico | Revisa y corrige datos de detalle de pedido. |
| ASGARD | Actualiza campos enviados en `logis_pedidos_detalle`. |
| API pedidos | Entrega detalle inicial de posiciones. |

## Entradas

- Id de pedido.
- Campos serializados con nombre compuesto `{prefijo}_{campo}_{id}`.
- Valores de campos editables.
- Sesion de usuario/cliente.

## Salidas

- `UPDATE logis_pedidos_detalle SET {campo} = {valor} WHERE id = {id}`.
- Mensaje de exito en UI.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/logistica/frames/items_pedido.php:17-64` | Abre vista de items y define columnas segun cliente. |
| `index_archivos/logistica/frames/items_pedido.php:116-118` | Carga detalle de posiciones del pedido. |
| `index_archivos/logistica/js/datosEmbarques.js:5-95` | Construye tabla de posiciones desde API. |
| `index_archivos/logistica/js/datosEmbarques.js:399-425` | Serializa formulario y llama guardado. |
| `index_archivos/logistica/ajax/saveDescripcionItems.php:5-10` | Actualiza columnas de `logis_pedidos_detalle`. |
| `.data_base/asgard.sql:12567-12592` | DDL de `logis_pedidos_detalle`. |

## Criterios de aceptacion candidatos

- La vista debe listar posiciones del pedido seleccionado.
- Las columnas visibles deben variar segun reglas observadas por cliente.
- Guardar debe aplicar los cambios por posicion.
- La UI debe informar exito o error de guardado.
