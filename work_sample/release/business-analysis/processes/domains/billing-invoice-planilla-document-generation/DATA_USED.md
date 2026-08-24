# Billing Invoice Planilla Document Generation - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Uso de negocio | Evidencia |
| --- | --- | --- |
| `dav_facturaplanilla.idfacturaplanilla` | Identificador principal de generacion y descarga. | `generarfacturaplanillacliente.php` |
| `dav_facturaplanilla.nro` | Numero de factura/planilla mostrado y usado en QR/rutas. | `generarfacturaplanillacliente.php` |
| `dav_facturaplanilla.fecha` | Fecha de emision y fecha fiscal. | `generarfacturaplanillacliente.php` |
| `dav_facturaplanilla.iddosificacion` | Decide contrato antiguo/en linea y enlaza dosificacion. | `descargarfactura.php` |
| `dav_facturaplanilla.NombreDocumentoXML` | Nombre base para documentos en linea. | `descargarfactura.php` |
| `dav_facturasdetalle.monto` | Conceptos e importes facturados. | `generarfacturaplanillacliente.php` |
| `dav_pagosdetalle.monto` | Gastos de planilla y pagos directos/prepagados. | `generarfacturaplanillacliente.php` |
| `dav_dosificacion.nroautorizacion` | Autorizacion fiscal de factura. | `generarfacturaplanillacliente.php` |
| `dav_dosificacion.llave` | Llave para codigo de control. | `generarfacturaplanillacliente.php` |
| `dav_dosificacion.fechalimite` | Fecha limite de emision. | `generarfacturaplanillacliente.php` |
| `dav_dosificacion.actividadeconomica`, `leyenda` | Texto fiscal impreso en factura. | `generarfacturaplanillacliente.php` |
| `dav_casos` | Pedido, carpeta, DIM/DEX, CIF, tipo de cambio, regimen y proveedor. | `generarfacturaplanillacliente.php` |
| `dav_ciudad` | Ciudad, razon social y NIT empresa. | `generarfacturaplanillacliente.php` |

## Observaciones de calidad de datos

- La generacion actualiza `motivo` con `$_POST["motivo"]`, aunque el flujo principal de descarga puede no enviar ese dato.
- El QR se escribe como archivo temporal local con nombre basado en carpeta y numero.
- Hay bifurcacion por cliente para mostrar datos adicionales de descripcion, chasis o localidad.
