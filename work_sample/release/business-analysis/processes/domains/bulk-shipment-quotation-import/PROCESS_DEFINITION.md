# Bulk Shipment Quotation Import - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Crear cotizaciones o embarques logisticos en lote desde un Excel, tomando datos repetitivos del formulario principal y datos variables por fila para linea, pedido, orden de compra, cantidad, tipo de bulto, proveedor y descripcion de carga.

## Alcance observado

- Carga de Excel desde modulo logistico.
- Lectura de filas desde la 2 hasta la ultima fila.
- Validacion/lookup parcial de linea, tipo de bulto y proveedor.
- Composicion de `ordencompra` y `doccompr` desde columnas B/C.
- Creacion repetida de cotizacion/embarque por fila via `CotizacionClass::guardarCotizacionCliente`.
- Persistencia relacionada en tablas de embarques, magnitudes, datos/rutas y operadores.
- Resultado JSON con ids de embarques/cotizaciones generados.
- Panel UI de resultados de carga masiva.

## Fuera de alcance observado

- Plantilla Excel especifica o generador de formato para esta carga.
- Validacion exhaustiva antes de crear embarques.
- Rechazo transaccional del lote si hay errores.
- Deteccion de duplicados por pedido/orden compra.
- Confirmacion de intercambio documental o envio posterior.

## Actores

| Actor | Rol observado |
| --- | --- |
| Cliente/usuario logistico | Carga Excel y datos comunes del embarque/cotizacion. |
| ASGARD Logistica | Lee filas, resuelve catalogos y crea cotizaciones/embarques. |
| CotizacionClass | Ejecuta persistencia de cotizacion/embarque, magnitudes, rutas y operador. |
| Maestros logisticos | Proveen linea, proveedor y unidad/bulto. |

## Entradas

- Archivo Excel `adjunto`.
- Datos comunes POST: tipo viaje, exportacion, prioridad, incoterm, factura comercial, contrato, exchange, tipo embarque, descripcion, mercancia, email, vigencia, agentes, operador, tramos y dimensiones.
- Columnas Excel: linea, pedido, orden/posicion, cantidad, tipo bulto, peso neto, proveedor, descripcion.

## Salidas

- Archivo guardado en categoria `cargamasiva`.
- Lista de ids devueltos por `guardarCotizacionCliente`.
- Cotizaciones o embarques creados/actualizados.
- Magnitudes por fila: bulto, cantidad, peso/volumen/contenedor.
- Mensaje de resultado/observaciones.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/logistica/ajax/uploadExcelCargaMasiva.php` | Upload y lectura de filas Excel para carga masiva logistica. |
| `index_archivos/logistica/controllers/CargaMasiva.php` | Construye datos por fila y llama `CotizacionClass::guardarCotizacionCliente`. |
| `index_archivos/logistica/index.php` | Panel de resultados de carga masiva en UI. |
| `index_archivos/logistica/js/datosEmbarques.js` | Invoca `uploadExcelCargaMasiva.php` y renderiza resultados. |
| `.data_base/asgard.sql` | Tablas `logis_embarques`, `logis_embarquesmagnitudes`, `logis_embarquesdatos`, `logis_embarquesoperador`, `logis_embarquesrutas`. |

## Criterios de aceptacion candidatos

- Cada fila del Excel debe transformarse en una solicitud de guardado de cotizacion/embarque.
- La linea debe resolverse contra `dav_clientelineas` del cliente.
- El proveedor debe resolverse contra `dav_proveedor`.
- El tipo de bulto debe resolverse contra `dav_unidad.codigo`.
- Los datos comunes del formulario deben aplicarse a todas las filas.
- El resultado debe exponer ids creados o advertencias por fila.
