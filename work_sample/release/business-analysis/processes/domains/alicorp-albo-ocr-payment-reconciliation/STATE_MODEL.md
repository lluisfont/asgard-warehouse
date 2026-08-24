# Alicorp Albo OCR Payment Reconciliation - State Model

## Estados candidatos

| Estado | Condicion observada | Resultado |
| --- | --- | --- |
| FACTURA_RECIBIDA | Se recibe PDF/ZIP/RAR desde intercambio. | Lista para OCR. |
| PAQUETE_DESCOMPRIMIDO | Archivo comprimido fue extraido remotamente. | PDFs listos para lectura. |
| OCR_LEIDO | OCR devuelve total, DIM, numero y fecha. | Puede buscar contexto/pago. |
| CONTEXTO_RESUELTO | `exchange_id` localiza embarque, GA o AGES. | Se construye consulta de pago. |
| PAGO_CANDIDATO_ENCONTRADO | Existe pago concepto `272`, sin `nro`, por monto. | Puede actualizarse. |
| CIERRE_TRANSITO_MARCADO | DIM OCR coincide con caso ASGARD. | `alicorp_cierre_transito=1`. |
| PAGO_RECONCILIADO | Fecha valida y pago encontrado. | Pago y nota tienen numero/fecha. |
| NO_RECONCILIADO | Falta contexto, pago o fecha valida. | Se informa motivo. |

## Transiciones

| Transicion | Disparador | Efecto |
| --- | --- | --- |
| Leer factura | Solicitud OCR | Ejecuta OCR ALBO/FALBO. |
| Resolver contexto | OCR sin error | Busca por `exchange_id`. |
| Marcar cierre | DIM coincidente | Actualiza `dav_casos`. |
| Reconciliar pago | Pago y fecha validos | Actualiza pago y nota. |
| Rechazar actualizacion | Sin pago/fecha/contexto | Devuelve mensaje funcional. |
