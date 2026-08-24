# Bulk Shipment Quotation Import - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Uso de negocio | Evidencia |
| --- | --- | --- |
| Excel columna A | Linea/rubro. | `uploadExcelCargaMasiva.php` |
| Excel columna B | Pedido/documento compra base. | `uploadExcelCargaMasiva.php` |
| Excel columna C | Complemento de orden compra. | `uploadExcelCargaMasiva.php` |
| Excel columna D | Cantidad pedido/bultos. | `uploadExcelCargaMasiva.php` |
| Excel columna E | Tipo de bulto/unidad. | `uploadExcelCargaMasiva.php` |
| Excel columna F | Peso neto. | `uploadExcelCargaMasiva.php` |
| Excel columna G | Proveedor de mercancia. | `uploadExcelCargaMasiva.php` |
| Excel columna I | Descripcion de mercancia. | `uploadExcelCargaMasiva.php` |
| `dav_clientelineas` | Resolucion de linea por cliente. | `obtenerIdlinea` |
| `dav_proveedor` | Resolucion de proveedor. | `obtenerIdProveedor` |
| `dav_unidad` | Resolucion de tipo bulto. | `obtenerIdTipoBulto` |
| `logis_embarques` | Cabecera de embarque/cotizacion. | `CotizacionClass::guardarCotizacionCliente`, schema |
| `logis_embarquesmagnitudes` | Cantidades, pesos, volumen y contenedores. | `CargaMasiva.php`, schema |
| `logis_embarquesdatos`, `logis_embarquesrutas` | Tramos/rutas del embarque. | POST `tramo`, schema |
| `logis_embarquesoperador` | Operador logistico asociado. | `CargaMasiva.php`, schema |

## Observaciones de calidad de datos

- Algunas variables de dimension se inicializan a cero incluso cuando hay valores POST, segun codigo observado.
- El flujo arma mensajes de error, pero devuelve `status=200` y continua con la llamada de guardado.
- La validacion no parece impedir crear registros con ids `0` para catalogos no encontrados.
