# State Model

Status: INFERRED_DRAFT_REVIEW_REQUIRED

## Document State

| State | Meaning Candidate | Rule |
| --- | --- | --- |
| `S/N` | Sin estado calculable. | Fecha de vencimiento nula o invalida. |
| `VIGENTE` | Documento vigente fuera del plazo de alerta. | Vencimiento o extension mayor/igual a fecha actual y diferencia mayor que plazo. |
| `POR VENCER` | Documento vigente pero dentro del plazo de alerta. | Vencimiento o extension mayor/igual a fecha actual y diferencia menor/igual al plazo. |
| `VENCIDO` | Documento vencido. | Vencimiento o extension menor que fecha actual. |

## Notification State

| State | Meaning Candidate | Evidence |
| --- | --- | --- |
| `notificacion_enviada = 0` | Documento aun candidato a notificacion. | `notificaciones.php:14-27` |
| `notificacion_enviada = 1` | Documento vencido ya notificado. | `notificaciones.php:76-82` |

## AP State Candidate

| State | Meaning Candidate | Evidence |
| --- | --- | --- |
| Vencida | AP con fecha de emision + 180 dias menor que fecha actual. | `listaControlAps.php:20`, `ControlAps.php:31-39` |
| Por vencer | AP con diferencia menor a 14 dias o dentro del filtro configurado. | `listaControlAps.php:20` |
| Vigente | AP con diferencia mayor o igual al umbral. | `listaControlAps.php:20` |

## Validation Required

- Confirmar si `tipo_documento_id = 3` corresponde a AP madre/modelo y por eso permite codigo repetido.
- Confirmar la semantica oficial de unidades `M` y `Y` dentro de `f_estado_documento`.
- Confirmar si `notificacion_enviada` debe reiniciarse cuando se edita fecha de vencimiento o extension.
