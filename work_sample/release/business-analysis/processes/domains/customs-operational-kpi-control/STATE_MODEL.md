# Customs Operational KPI Control - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos de KPI

| Estado candidato | Condicion observada | Evidencia |
| --- | --- | --- |
| `CUMPLE` | Dias AP/DAM/REQ dentro del umbral por capacidad. | `kpisquery.php` |
| `NO CUMPLE` | Dias AP/DAM/REQ fuera del umbral por capacidad. | `kpisquery.php` |
| `positivo` | Indicador AD dentro de umbral observado. | `reportecontroladquery.php` |
| `negativo` | Indicador AD fuera de umbral observado. | `reportecontroladquery.php` |
| `CORRECTO` | Validacion/planillaje dentro de umbral. | `reporteseguimientoquery.php` |
| `ATRASADO` | Validacion/planillaje supera umbral. | `reporteseguimientoquery.php` |
| `EN TIEMPO` | Pago DUI dentro de umbral. | `reporteseguimientoquery.php` |
| `REINTEGRO` | Pago DUI supera umbral observado. | `reporteseguimientoquery.php` |

## Estado de embarque OL

El estado actual se infiere desde la maxima `orden_etapa` en `logis_estados_edp` para el embarque y luego se resuelve el nombre de etapa.

## Pendiente de validacion

- Confirmar nombres oficiales y si deben normalizarse mayusculas/minusculas.
- Confirmar umbrales por cliente, linea, regimen y fecha.
- Confirmar si `REINTEGRO` es realmente un estado KPI o una consecuencia financiera.

