# Bulk Shipment Quotation Import - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Elemento | Evidencia | Observacion |
| --- | --- | --- |
| Upload logistico | `logistica/ajax/uploadExcelCargaMasiva.php` | Guarda archivo y lee filas Excel. |
| Mapeo de columnas | `uploadExcelCargaMasiva.php` | Usa columnas A, B, C, D, E, F, G e I. |
| Resolucion de linea | `CargaMasiva::obtenerIdlinea` | Busca por primeros 3 caracteres y cliente. |
| Resolucion proveedor | `CargaMasiva::obtenerIdProveedor` | Busca proveedor por nombre exacto. |
| Resolucion bulto | `CargaMasiva::obtenerIdTipoBulto` | Busca `dav_unidad.codigo`. |
| Creacion por fila | `CargaMasiva::guadarSolicitudCargaMasiva` | Llama `CotizacionClass::guardarCotizacionCliente` por fila. |
| UI resultados | `logistica/index.php`, `logistica/js/datosEmbarques.js` | Muestra resultados de carga masiva. |
| Persistencia destino | `.data_base/asgard.sql` | Tablas `logis_embarques*`. |

## Cobertura

- Flujo principal reconstruido: si.
- Reglas de catalogos reconstruidas: si.
- Riesgos de validacion registrados: si.
- Validacion humana requerida: si.
