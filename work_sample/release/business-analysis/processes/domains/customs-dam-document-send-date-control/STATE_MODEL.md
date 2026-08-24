# Customs DAM Document Send Date Control - State Model

| Estado | Condicion | Resultado |
| --- | --- | --- |
| DAM_EVENTO_RECIBIDO | Llega `exchange_id`. | Se busca solicitud. |
| SOLICITUD_RESUELTA | Existe `idcasosprevios`. | Se verifica AP. |
| AP_PREVIA_EXISTE | Hay factura con `fechaenvioap`. | DAM puede marcarse enviada. |
| DAM_ENVIADA | Se actualiza `fechaenviodam`. | Hito disponible para reportes/controles. |
| DAM_NO_ACTUALIZADA | No hay AP previa. | Se envia alerta por correo. |
