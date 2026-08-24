# Customer Shipment Rating Feedback - State Model

| Estado | Condicion | Resultado |
| --- | --- | --- |
| SIN_RATING | No existe fila vigente para contexto/usuario. | Puede mostrarse formulario. |
| RATING_ENVIADO | Existe fila vigente. | UI muestra calificacion enviada. |
| MODAL_MENSUAL_PENDIENTE | No hay rating o pasaron 30 dias. | Puede mostrarse modal. |
| RATING_REGISTRADO | Insercion exitosa. | Se devuelve codigo `200`. |
| ERROR_REGISTRO | Insercion falla. | Se devuelve codigo `500`. |
