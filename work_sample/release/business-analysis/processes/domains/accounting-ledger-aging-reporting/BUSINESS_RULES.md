# Accounting Ledger Aging Reporting - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| BR-ALAR-001 | Aging muestra meses y columnas anuales desde 2020 hasta el anio actual. | `agig.php` |
| BR-ALAR-002 | Aging se identifica por mes y anio. | `agig_ajax.php` |
| BR-ALAR-003 | Si existe `dav_aging` por mes/anio se actualiza; si no existe se inserta. | `agig_ajax.php` |
| BR-ALAR-004 | Estado de cuentas/comision se filtra por cliente de sesion, linea opcional y fecha de pago DIM. | `comision.php`, `comisionquery.php` |
| BR-ALAR-005 | Solo facturas/planillas activas con `idestadofactura=1` participan en el reporte de comision. | `comisionquery.php` |
| BR-ALAR-006 | El reporte separa conceptos de factura y planilla mediante `UNION ALL`. | `comisionquery.php` |
| BR-ALAR-007 | Libro de compras excluye casos anulados y filtra por cliente de sesion. | `librocomprasquery.php` |
| BR-ALAR-008 | Credito fiscal se calcula como 13% del monto/base observado. | `librocomprasquery.php` |
| BR-ALAR-009 | El proveedor observado en libro de compras se fija como NIT `1020511026` y razon social `PACEÑA SRL.`. | `librocomprasquery.php` |

## Riesgos de regla pendientes

- Confirmar si `agig` significa aging, ahorro o ambos.
- Confirmar proveedor fiscal fijo y su vigencia.
- Confirmar reglas fiscales para credito fiscal y compras por DIM.
- Confirmar significado oficial de `idestadofactura=1`.

