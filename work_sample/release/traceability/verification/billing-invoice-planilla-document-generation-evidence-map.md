# Billing Invoice Planilla Document Generation - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Elemento | Evidencia | Observacion |
| --- | --- | --- |
| Entrada por factura-planilla | `generarfacturaplanillacliente.php` | Usa `$_GET["idfacturaplanilla"]`. |
| Datos de cabecera/caso | `generarfacturaplanillacliente.php` | Consulta `dav_facturaplanilla`, `dav_casos`, cliente, ciudad, regimen, proveedor y dosificacion. |
| Calculo planilla | `generarfacturaplanillacliente.php` | Suma `dav_pagosdetalle` por caso con filtros de prepagado/anulado/ND. |
| Calculo factura | `generarfacturaplanillacliente.php` | Suma `dav_facturasdetalle.monto`. |
| Codigo de control | `generarfacturaplanillacliente.php`, `generadorcodigocontrol.php` | Llama `generaFactura(...)`. |
| QR fiscal | `generarfacturaplanillacliente.php` | Genera PNG temporal con `phpqrcode`. |
| PDF combinado | `generarfacturaplanillacliente.php` | Usa mPDF y descarga `carpeta-PF-nro.pdf`. |
| Factura individual | `descargarfactura.php` | Busca/genera PDF membretado y devuelve inline. |
| Planilla individual | `descargarplanilla.php` | Busca/genera PDF membretado y devuelve inline. |
| Reporte/enlaces | `facpla.php`, `facplaquery.php` | Presenta enlaces Ver Factura, Ver Planilla y Ver Docs. |

## Cobertura

- Flujo principal reconstruido: si.
- Reglas de datos/importes reconstruidas: si.
- Reglas fiscales candidatas reconstruidas: si.
- Reglas de almacenamiento reconstruidas: si.
- Validacion humana requerida: si.
