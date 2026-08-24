# Billing Document Reception Confirmation - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos

| Estado | Significado | Evidencia |
| --- | --- | --- |
| No incluido | El documento no tiene marca de envio/salida o no cumple filtros de consulta. | Condiciones SQL de bandeja |
| Enviado pendiente de recepcion | El documento fue emitido/enviado y aun no tiene marca de recepcion. | `gettablaenviadas` |
| Recepcionado | El documento tiene bandera o fecha de recepcion registrada. | `gettablarecibidos` |

## Transiciones candidatas

| Transicion | Desde | Hacia | Disparador | Persistencia |
| --- | --- | --- | --- | --- |
| Enviar documento | No incluido | Enviado pendiente de recepcion | Flujo externo de envio/salida documental | `fechaenvioplanilla`, `estado_enviado`, `fechasalida` |
| Recibir Planilla/Factura | Enviado pendiente de recepcion | Recepcionado | Recibido individual o masivo | `recepcionplanilla=1`, `fecharecepcionplanilla=CURRENT_TIMESTAMP()` |
| Recibir Nota de Cobranza | Enviado pendiente de recepcion | Recepcionado | Recibido individual o masivo | `estado_recepcionado=1`, `fecha_recepcionado=CURRENT_TIMESTAMP()` |
| Recibir Cite | Enviado pendiente de recepcion | Recepcionado | Recibido individual o masivo | `fecharecepcion=CURRENT_TIMESTAMP()` |

## Estados no observados

- Recepcion rechazada.
- Recepcion parcial.
- Recepcion revertida.
- Recepcion con observacion.
- Recepcion validada por supervisor.
