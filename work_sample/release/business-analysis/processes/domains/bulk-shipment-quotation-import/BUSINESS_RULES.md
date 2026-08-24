# Bulk Shipment Quotation Import - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Regla | Descripcion | Evidencia |
| --- | --- | --- |
| BR-BSQI-001 | El archivo se guarda bajo categoria `cargamasiva` por cliente. | `uploadExcelCargaMasiva.php` |
| BR-BSQI-002 | Cada fila Excel genera una llamada a `guardarCotizacionCliente`. | `CargaMasiva::guadarSolicitudCargaMasiva` |
| BR-BSQI-003 | La linea se resuelve comparando los primeros tres caracteres del nombre con `dav_clientelineas.linea` y cliente. | `obtenerIdlinea` |
| BR-BSQI-004 | El proveedor se resuelve por igualdad exacta de nombre en `dav_proveedor`. | `obtenerIdProveedor` |
| BR-BSQI-005 | El tipo de bulto se resuelve por igualdad exacta de `dav_unidad.codigo`. | `obtenerIdTipoBulto` |
| BR-BSQI-006 | `doccompr` toma columna B y `ordencompra` concatena B+C. | `uploadExcelCargaMasiva.php` |
| BR-BSQI-007 | `ordencompraini` puede tomar la orden de la fila si POST `ordencompraini` esta presente. | `CargaMasiva.php` |
| BR-BSQI-008 | `ordencompra` puede tomar el pedido/doccompr de la fila si POST `ordencompra` esta presente. | `CargaMasiva.php` |
| BR-BSQI-009 | La decision cotizacion/embarque depende de POST `guardarCotizacion` o `guardarEmbarque`. | `CargaMasiva.php` |
| BR-BSQI-010 | Los datos comunes del formulario se aplican a cada fila importada. | `CargaMasiva.php` |

## Riesgos de regla pendientes

- Confirmar si debe bloquearse la creacion cuando linea, bulto o proveedor no se resuelven.
- Confirmar si la comparacion de linea por primeros tres caracteres es intencional.
- Confirmar plantilla Excel oficial y significado exacto de columnas B/C.
- Confirmar control de duplicados por pedido/orden de compra.
