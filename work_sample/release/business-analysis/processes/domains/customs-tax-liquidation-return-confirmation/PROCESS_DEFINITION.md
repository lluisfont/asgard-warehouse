# Customs Tax Liquidation Return Confirmation - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Confirmar al equipo operativo que la revision de valores/liquidacion de tributos de un caso fue validada y que procede el pago de tributos, dejando marca de retorno de liquidacion en el caso.

## Alcance observado

- Pantalla Detalle de Items Pedido con desglose de liquidacion por item.
- Calculo/consulta de importes de GA, IVA, ICE, ICD, IEHD y gastos adicionales por concepto.
- Boton Confirmar disponible cuando `fechaenvioliquidacion` tiene valor y `fecharetornoliquidacion` esta vacia.
- Envio de correo de confirmacion a destinatarios configurados por cliente y ciudad.
- Asunto con numero de solicitud, confirmacion de aprobacion para pago de tributos y carpeta.
- Marcado de `dav_casos.fecharetornoliquidacion = CURRENT_TIMESTAMP()` si el envio de correo no devuelve error.
- Confirmacion heredada de pago de tributos desde `versolicitud.php?action=confirmarpago`, que envia correo y marca `dav_casosprevios.pagoconfirmado=1`.
- Exportacion Excel del detalle de items.

## Fuera de alcance observado

- Generacion inicial de la liquidacion.
- Edicion/aprobacion completa de importes tributarios.
- Flujo contable posterior de pago.
- Reglas de destinatarios de envio inicial de liquidacion.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario operativo | Revisa detalle de items y confirma retorno de liquidacion. |
| Equipo receptor Paceña / retorno liquidacion | Recibe correo para proceder con pago de tributos. |
| ASGARD | Calcula detalle, envia correo y marca fecha de retorno. |
| SendGrid | Canal tecnico de envio de correo. |

## Entradas

- `idcasos`.
- Accion `conf` para confirmar.
- Datos del caso: pedido, carpeta, `idcasosprevios`, `fechaenvioliquidacion`, `fecharetornoliquidacion`.
- Destinatarios desde `dav_retornomailsliquidacion` por cliente y ciudad.
- Liquidacion desde procedimiento `liquidacion(idcasos)` y tablas de pagos/facturas.

## Salidas

- Correo de confirmacion de aprobacion para pago de tributos.
- `dav_casos.fecharetornoliquidacion` informado.
- Mensaje de envio en pantalla.
- Excel de detalle de items cuando el usuario lo solicita.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/operativos/detalleitems.php:7-64` | Accion `conf`, arma correo, destinatarios y actualiza fecha retorno tras envio. |
| `index_archivos/operativos/detalleitems.php:67-71` | Lee pedido y fechas de envio/retorno de liquidacion. |
| `index_archivos/operativos/detalleitems.php:191-196` | Muestra boton Confirmar solo si ya fue enviada y no retorno. |
| `index_archivos/operativos/detalleitemsquery.php:1-90` | Arma detalle de liquidacion y llama `liquidacion(idcasos)`. |
| `index_archivos/logistica/EmbarqueClass.php:752-813` | Envio por SendGrid con destinatarios y BCC fijos. |
| `index_archivos/versolicitud.php:39-124` | Confirmacion heredada de pago de tributos y marca `pagoconfirmado`. |
| `.data_base/asgard.sql:1603-1604` | Campos de envio y retorno de liquidacion en `dav_casos`. |
| `.data_base/asgard.sql:9707-9714` | Tabla de destinatarios de retorno de liquidacion. |

## Criterios de aceptacion candidatos

- El boton Confirmar debe aparecer solo cuando la liquidacion ya fue enviada y aun no retorno.
- Confirmar debe enviar correo a los destinatarios configurados por cliente y ciudad.
- Si el envio de correo no reporta error, el caso debe quedar con `fecharetornoliquidacion` informada.
- El detalle debe mostrar importes por item y conceptos tributarios/gastos relacionados.
- El usuario debe poder exportar el detalle a Excel.
