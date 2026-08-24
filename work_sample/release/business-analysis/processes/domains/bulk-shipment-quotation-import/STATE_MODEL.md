# Bulk Shipment Quotation Import - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos

| Estado | Significado | Evidencia |
| --- | --- | --- |
| Archivo recibido | El Excel fue subido por el usuario. | `uploadExcelCargaMasiva.php` |
| Archivo guardado | El archivo fue persistido en categoria `cargamasiva`. | `GlobalClass::guardarArchivo` |
| Filas leidas | ASGARD recorrio filas Excel y armo arreglo de datos. | `uploadExcelCargaMasiva.php` |
| Catalogos resueltos parcialmente | Linea/proveedor/bulto fueron buscados y pueden quedar en 0. | `CargaMasiva.php` |
| Cotizacion/embarque creado | `guardarCotizacionCliente` devolvio id. | `CargaMasiva.php` |
| Resultado mostrado | UI muestra ids o advertencias. | `datosEmbarques.js`, `index.php` |

## Transiciones candidatas

| Transicion | Desde | Hacia | Disparador |
| --- | --- | --- | --- |
| Subir Excel | - | Archivo recibido | Upload adjunto |
| Guardar archivo | Archivo recibido | Archivo guardado | `guardarArchivo` |
| Leer filas | Archivo guardado | Filas leidas | PHPExcel |
| Resolver catalogos | Filas leidas | Catalogos resueltos parcialmente | Lookup linea/proveedor/bulto |
| Crear registro | Catalogos resueltos parcialmente | Cotizacion/embarque creado | `guardarCotizacionCliente` |
| Devolver respuesta | Cotizacion/embarque creado | Resultado mostrado | JSON frontend |

## Estados no observados

- Validado sin errores.
- Rechazado por errores.
- Commit transaccional de lote.
- Reversion de registros creados.
