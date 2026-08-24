# Accounting Ledger Aging Reporting - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos

| Estado candidato | Condicion observada | Evidencia |
| --- | --- | --- |
| `Aging pendiente de crear` | No existe `dav_aging` para mes/anio recibido. | `agig_ajax.php` |
| `Aging actualizado` | Existe `dav_aging` para mes/anio recibido. | `agig_ajax.php` |
| `Factura activa para reporte` | `dav_facturaplanilla.idestadofactura=1`. | `comisionquery.php`, `librocomprasquery.php` |
| `Caso excluido` | Caso anulado o fuera del cliente/filtro. | Queries contables |
| `Libro de compras reportable` | Caso/factura o pago IVA cumple filtro de cliente, ciudad y fecha. | `librocomprasquery.php` |

## Pendiente de validacion

- Confirmar catalogo de estado de factura.
- Confirmar si aging requiere cierre/aprobacion.
- Confirmar si libro de compras genera una salida formal o solo reporte operativo.

