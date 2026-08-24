# Customs Tax Liquidation Return Confirmation - State Model

## Estados inferidos

| Estado candidato | Condicion observada | Accion disponible |
| --- | --- | --- |
| SIN_ENVIO_LIQUIDACION | `fechaenvioliquidacion` vacia | Consultar detalle / Excel. |
| ENVIADA_PENDIENTE_RETORNO | `fechaenvioliquidacion` informada y `fecharetornoliquidacion` vacia | Confirmar retorno. |
| RETORNO_CONFIRMADO | `fecharetornoliquidacion` informada | Consultar detalle / Excel. |

## Transiciones observadas

| Transicion | Disparador | Cambio |
| --- | --- | --- |
| Confirmar retorno | Usuario ejecuta `accion=conf` y correo no reporta error | `fecharetornoliquidacion = CURRENT_TIMESTAMP()` |

## Pendiente de validar

- Si existe reversa o anulacion de retorno confirmado.
- Si la fecha de retorno debe representar envio de correo, aprobacion recibida o autorizacion de pago.
- Si se requiere separar estado de envio de correo y estado de aprobacion.
