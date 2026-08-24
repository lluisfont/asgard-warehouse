# Customs Operational KPI Control - Business Rules

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

| ID | Regla candidata | Evidencia |
| --- | --- | --- |
| BR-COKC-001 | KPI AP/DAM/REQ usa capacidad base de 200 unidades. | `kpisquery.php` |
| BR-COKC-002 | Para cantidad menor o igual a capacidad, AP/DAM/REQ cumple si los dias son menores o iguales a 1. | `kpisquery.php` |
| BR-COKC-003 | Para el tramo superior observado, AP/DAM/REQ cumple si los dias son menores o iguales a 2. | `kpisquery.php` |
| BR-COKC-004 | El calculo usa dias laborales. | `DiasLaborales`, `sp_workdaydiff` |
| BR-COKC-005 | KPI vehicular base usa clientes observados `417`, `452` y `471` para construir temporales. | `kpisquery.php` |
| BR-COKC-006 | Control AD calcula indicador de prevision positivo si la diferencia absoluta esta dentro del 5% de costos reales. | `reportecontroladquery.php` |
| BR-COKC-007 | KPI envio planilla observado es positivo entre 0 y 2 dias laborales. | `reportecontroladquery.php` |
| BR-COKC-008 | KPI nacionalizacion IM4 observado es positivo entre 0 y 3 dias laborales. | `reportecontroladquery.php` |
| BR-COKC-009 | Estado OL del embarque se deriva por maxima `orden_etapa` de EDP. | `reportecontrololquery.php` |
| BR-COKC-010 | Seguimiento aduanero marca validacion como atrasada si supera 2 dias laborales. | `reporteseguimientoquery.php` |
| BR-COKC-011 | Pago DUI usa umbral 4 dias antes de 2023-10-01 y 5 dias desde esa fecha observada. | `reporteseguimientoquery.php` |

## Riesgos de regla pendientes

- Confirmar catalogo oficial de SLA y capacidad.
- Confirmar por que los temporales KPI base usan clientes hardcodeados.
- Confirmar la regla del 5% en prevision de tributos.
- Confirmar cambio de umbral de pago DUI desde 2023-10-01.

