# Accounting Ledger Aging Reporting - Data Used

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| Dato | Descripcion candidata | Fuente |
| --- | --- | --- |
| `dav_aging.mes` | Mes del registro aging/ahorro. | `agig.php`, `agig_ajax.php` |
| `dav_aging.anio` | Anio del registro aging/ahorro. | `agig.php`, `agig_ajax.php` |
| `dav_aging.monto` | Monto editable por mes/anio. | `agig_ajax.php` |
| `dav_meses` | Catalogo de meses para la matriz. | `agig.php` |
| `dav_facturaplanilla` | Facturas/planillas activas y numeros fiscales. | `comisionquery.php`, `librocomprasquery.php` |
| `dav_facturasdetalle` | Detalles facturados por concepto. | `comisionquery.php`, `librocomprasquery.php` |
| `dav_concepto` | Clasificadores de GDE, tributos y otros conceptos. | `comisionquery.php` |
| `dav_pagosdetalle` | Pagos usados para IVA/DIM y componentes de planilla. | `librocomprasquery.php`, `comisionquery.php` |
| `dav_casos` | Caso base, cliente, linea, ciudad, fecha pago DIM y anulacion. | Queries contables |
| `dav_dosificacion` | Nro autorizacion y codigo de control de factura. | `librocomprasquery.php` |

