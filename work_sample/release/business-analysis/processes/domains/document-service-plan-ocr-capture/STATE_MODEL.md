# Document Service Plan OCR Capture - State Model

| Estado | Condicion | Resultado |
| --- | --- | --- |
| OCR_SOLICITADO | Documento enviado a Azure OCR. | Espera resultado. |
| OCR_COMPLETADO | Estado `succeeded`. | Parser puede extraer campos. |
| DOCUMENTO_NO_ACEPTADO | Faltan numero, BL o monto. | No se inserta lectura. |
| LECTURA_REEMPLAZADA | Campos minimos existen. | Lecturas previas quedan con `deleted_at`. |
| LECTURA_VIGENTE | Nueva fila insertada. | Datos disponibles para consulta. |
