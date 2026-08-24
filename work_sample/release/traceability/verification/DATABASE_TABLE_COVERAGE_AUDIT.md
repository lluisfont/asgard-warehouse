# Database Table Coverage Audit

Estado: IN_PROGRESS
Idioma: Spanish

## Criterio

Se compararon tablas `CREATE TABLE` de `.data_base/asgard.sql` contra los dominios reconstruidos y la cobertura de infraestructura. La revision no convierte una familia de tablas en dominio si no hay evidencia de flujo de aplicacion, endpoint, UI, cron o procedimiento operacional explotado por el sistema.

## Familias detectadas

| Familia | Clasificacion candidata | Accion |
| --- | --- | --- |
| `pbi_*` | Tablas derivadas/BI generadas desde SQL y procedimientos. | Cubiertas como soporte de dashboards/PowerBI; no son dominio transaccional primario. |
| `rnc_*` | Mejora continua / no conformidad. | Cubiertas por `continuous-improvement-nonconformity`; tablas restantes son catalogos/migraciones/vistas. |
| `soat_*` | SOAT vehicular. | Cubiertas parcialmente por `vehicle-import-management`, `vehicle-request-bulk-update` y `vehicle-soat-pdf-ocr-splitting`; tablas restantes sin uso PHP directo quedan pendientes de validacion si aparece UI/proceso adicional. |
| `con_*` | Contratos/documentos de contrato en SQL. | Sin evidencia PHP funcional localizada en este barrido; clasificar como SQL-only pendiente hasta encontrar interfaz/endpoints. |
| `serv_*` | Servicios tercerizados en SQL. | Sin evidencia PHP funcional localizada en este barrido; posible maestro/servicio no expuesto. Pendiente si aparece uso. |
| `cn_*` | Concesionarios/contactos en SQL. | Sin evidencia PHP funcional localizada en este barrido; posible maestro no expuesto. |
| `bot_*` | Tablas bot DEX/SUMA. | Sin evidencia PHP funcional localizada en `index_archivos`; posible proceso externo/robot. |
| `tmp_*`, `f_*` | Temporales/materializaciones/reportes. | Cubiertas por reporting o SQL derivado, no dominio propio. |
| `un*tab`, `untartab`, `dex_*` catalogos | Catalogos arancelarios/aduaneros. | Datos de soporte para calculos aduaneros, no flujo independiente. |

## Resultado del barrido de codigo

- `rg` sobre `index_archivos` para familias `pbi_`, `con_`, `soat_`, `serv_`, `cn_`, `bot_`, `rnc_` encontro uso funcional PHP directo principalmente para:
  - `soat_lotes` y `soat_loteitems` en `VehiculosClass.php`, ya documentado;
  - `rnc_*`, ya documentado;
  - `pbi_*` como BI derivado y procedimientos SQL, no como flujo PHP transaccional.
- No se localizaron endpoints PHP funcionales para `con_*`, `serv_*`, `cn_*` o `bot_*` durante este barrido.

## Barrido incremental desde evidencia PHP

Se extrajeron tablas con evidencia PHP directa que todavia no aparecian como tokens en la documentacion funcional. La accion aplicada fue ampliar `DATA_USED` en dominios existentes cuando la tabla era soporte claro del flujo:

- `customs-dav-client-review-approval`: catalogos y detalle DAV/FDM (`dav_partidas`, `dav_mercancia`, `dav_estadopartida`, `dav_unidadfactura`, `dav_acuerdo`, `dav_parametro`, `dav_dato`).
- `logistics-quotation-costing`: catalogos de tramo/carga/mercancia y parametros de gestion (`dav_puerto`, `tck_lugaresentregacarga`, `logis_tipocarga`, `logis_tipomercancia*`, `dav_gestlogistica`, `dav_gestion_aduanera*`, `dav_clientealmacen`, `dav_region`).
- `logistics-shipment-tracking`: datos de viaje/carga/evidencia (`tck_carga`, `tck_orden_viaje`, `v_tck_infoviajecliente`, `tck_imagenes`) y catalogos logisticos compartidos.
- `shipment-customs-request-management`: coordinadores, division, agentes/contactos IH y parametros de nueva gestion.

Los hits restantes de una sola fuente corresponden principalmente a catalogos maestros, tablas temporales/vistas de reporte o dependencias externas; no justifican dominio independiente sin un trigger/flujo observado.

En una segunda absorcion se documentaron los remanentes de soporte mas claros:

- tipos de contacto de terceros (`ada_tipocontactos`, `ads_tipocontactos`, `ges_tipo_contactos`, `dav_proveedortipocontactos`);
- relacion `dav_entidademisoratramitetipo` para servicios adicionales/tramites;
- tablas/vistas temporales de exportacion, tracking y solicitud;
- catalogos logisticos auxiliares (`logis_aeropuertos`, `logis_contenedor`);
- `dav_estadoaps` para control AP.

`TIMEDIFF`, `getContaminacionInfo` y `getKilometrajeInfo` se tratan como falsos positivos del extractor SQL porque son funciones/llamadas, no tablas de dominio detectadas. Los ultimos catalogos residuales (`dav_clientereportescliente`, `dav_paises_codigo_telefonico`, `prov_clientes_proveedores`) quedaron clasificados como soporte transversal o datos de tracking, no como dominios independientes.

## Pendiente

Si aparece codigo externo al repo cliente, cron externo, robot o API no incluida que use `con_*`, `serv_*`, `cn_*` o `bot_*`, debe abrirse un dominio candidato separado con evidencia nueva.
