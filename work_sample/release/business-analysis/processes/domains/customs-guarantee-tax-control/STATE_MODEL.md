# Customs Guarantee Tax Control - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos de unidad para uso de boleta

| Estado candidato | Condicion observada | Evidencia |
| --- | --- | --- |
| `DAM ACEPTADA` | Unidad con DAM enviada, aun sin salida/canal/verificacion completa y sin documento observado. | `getSeguimientoOperativoDesglosado` |
| `SIN NACIONALIZAR` | Unidad sin salida/canal, con verificacion FRV y hasta 90 dias desde documento observado. | `getSeguimientoOperativo` |
| `SIN NACIONALIZAR (POR VENCER)` | Unidad sin salida/canal, con verificacion FRV y mas de 90 dias desde documento observado. | `getSeguimientoOperativo` |
| `DAM NO ACEPTADA` | Unidad en transito sin fecha de envio DAM, con avance incompleto. | `getSeguimientoOperativoDesglosado` |
| `EXTRAIDA` | Unidad con pase de salida o asignacion de canal. | `getSeguimientoMensual` |

## Estados candidatos de tributos

| Estado candidato | Condicion observada | Evidencia |
| --- | --- | --- |
| `Pendiente` | `fechapagodui` vacia. | `tributosquery.php` |
| `Nacionalizado` | `fechapagodui` informada. | `tributosquery.php` |
| `A favor CLIENTE` | Diferencia calculada mayor que cero segun formula de reporte. | `tributosquery.php` |
| `A favor PACEÑA` | Diferencia calculada menor que cero segun formula de reporte. | `tributosquery.php` |
| `Sin diferencia` | Diferencia calculada igual a cero. | `tributosquery.php` |

## Pendiente de validacion

- Confirmar si `DAM ACEPTADA` representa una aprobacion formal o una situacion operacional derivada.
- Confirmar si `SIN NACIONALIZAR (POR VENCER)` significa vencida, por vencer o alerta interna.
- Confirmar catalogo oficial de estados contables y tratamiento de saldos.

