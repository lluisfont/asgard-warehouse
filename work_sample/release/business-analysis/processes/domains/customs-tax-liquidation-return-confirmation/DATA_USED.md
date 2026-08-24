# Customs Tax Liquidation Return Confirmation - Data Used

| Entidad / Tabla | Uso observado | Campos relevantes |
| --- | --- | --- |
| `dav_casos` | Caso confirmado y fechas de liquidacion. | `idcasos`, `pedido`, `carpeta`, `idcasosprevios`, `idcliente`, `idciudad`, `fechaenvioliquidacion`, `fecharetornoliquidacion`, `fob`, `tipocambio`, `formularioDUI` |
| `dav_retornomailsliquidacion` | Destinatarios de correo por cliente/ciudad. | `idcliente`, `idciudad`, `nombre`, `mail` |
| `dav_pagosdetalle` / `dav_concepto` | Conceptos adicionales usados en detalle. | `idconcepto`, `descripcion`, `monto`, `idcasos` |
| `dav_facturasdetalle` / `dav_facturaplanilla` | Conceptos facturados aprobados. | `idfacturaplanilla`, `idestadofactura`, `monto`, `idconcepto` |
| `dav_liquidacion` / `tmp_liquidacion` | Detalle tributario por item generado por procedimiento. | `descripciondetallada`, `partidaarancelaria`, `FOBbs`, `CIFbs`, `GA`, `IVA`, `ICE`, `ICD`, `IEHD` |
| `dav_casosprevios` | Numero de solicitud previa usado en asunto. | `idcasosprevios` |

## Persistencia observada

- `UPDATE dav_casos SET fecharetornoliquidacion=CURRENT_TIMESTAMP() WHERE idcasos=...`.
- Procedimiento `call liquidacion(idcasos)` para poblar/consultar `tmp_liquidacion`.
- Envio externo por SendGrid sin tabla local de bitacora observada en este flujo.
