# Alicorp Transit Deadline Control - Business Rules

## Reglas inferidas

| ID | Regla | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-ATDC-001 | El control observado aplica al cliente Alicorp identificado por `idcliente = 775`. | `control_alicorpquery.php` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ATDC-002 | El rango principal filtra por fecha de factura comercial. | `CAST(dav_facturacomercial.fechafactura as DATE) BETWEEN ...` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ATDC-003 | Si `alicorp_vencimiento` esta nulo, se calcula como `fechavalidaciondui + 60 dias`. | `UPDATE dav_casos ... DATE_ADD(a.fechavalidaciondui, INTERVAL 60 DAY)` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ATDC-004 | Un caso queda en alerta de vencimiento si faltan cinco dias o menos y no tiene pase de salida. | `DATEDIFF(...)<=5 AND fechapasesalida IS NULL` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ATDC-005 | Casos anulados se excluyen salvo cuando la anulacion cliente esta marcada. | `IFNULL(anulado,0) != 1 OR ... anuladocliente = 1` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ATDC-006 | Facturas anuladas del cliente se incorporan como filas informativas en el mismo resultado. | `UNION ALL dav_clientefacturaanulada` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-ATDC-007 | `alicorp_cierre_transito = 1` se presenta como `PAGADO`; cualquier otro valor como `SIN PAGAR`. | `CASE dav_casos.alicorp_cierre_transito` | INFERRED_DRAFT_REVIEW_REQUIRED |

## Riesgos / validaciones pendientes

- Confirmar si el plazo de 60 dias es contractual, aduanero o una regla operacional interna.
- Confirmar si la actualizacion automatica al consultar es deseada o deberia ejecutarse en un proceso controlado.
- Confirmar si el umbral de cinco dias representa alerta temprana oficial.
- Confirmar si el cierre de transito deberia tener usuario, fecha y fuente documental auditables aparte del flag.
