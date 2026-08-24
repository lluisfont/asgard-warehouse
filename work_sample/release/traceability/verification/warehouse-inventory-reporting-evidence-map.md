# Evidence Map - warehouse-inventory-reporting

Status: INFERRED_DRAFT_REVIEW_REQUIRED

| Claim | Evidence | Confidence |
| --- | --- | --- |
| ASGARD reports Warehouse ingresos through Atlantes API. | `index_archivos/warehouse/ingresosquery.php:17-39`, `warehouse/ingresos.php` | High |
| ASGARD reports Warehouse salidas through Atlantes API. | `index_archivos/warehouse/salidasquery.php:17-39`, `warehouse/salidas.php` | High |
| Inventory report uses Atlantes inventory endpoint and filters positive quantity. | `index_archivos/warehouse/inventarioquery.php:14-39` | High |
| Timbrado report uses Atlantes endpoint and local SKU/factura filters. | `index_archivos/warehouse/timbradoquery.php:18-55` | High |
| Movement detail report uses Atlantes endpoint and local lote/codigo filters. | `index_archivos/warehouse/reporte-movimiento-detallequery.php:18-55` | High |
| Warehouse selector is scoped to `idalmacen_atlantes` in session. | `index_archivos/warehouse/parametros/almacenes.php:6-32` | High |
| jqGrid report screens prepare column headers/data for Excel export. | `index_archivos/warehouse/ingresos.php`, `salidas.php`, `inventario.php` | Medium |
| Vehicle inventory reports consume `url_pedidos` endpoints with tokenJWT. | `index_archivos/warehouse/reporte-inventario-chasis.php:150-220`, `reporte-inventario-chasis-reportados.php` | High |
| Inventory schema stores chasis, accessories, parts, damage, contamination, locations, reported shipments and billing periods. | `.data_base/asgard.sql:11289-11709` | High |

## Graphify Use

Graphify output is supporting context only. Direct PHP report code, API calls and SQL schema are authoritative for this domain.
