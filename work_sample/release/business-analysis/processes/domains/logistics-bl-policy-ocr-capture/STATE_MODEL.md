# Logistics BL Policy OCR Capture - State Model

| Estado | Condicion | Resultado |
| --- | --- | --- |
| DOCUMENTO_RECIBIDO | Llega path, name, exchange y `tipodoc`. | Se puede ejecutar OCR. |
| EMBARQUE_RESUELTO | `exchange_id` localiza embarque. | Se puede persistir lectura. |
| BL_CAPTURADO | OCR BL con campos utilizables. | Datos BL guardados. |
| POLIZA_CAPTURADA | OCR poliza con campos utilizables. | Datos poliza guardados. |
| COMPARADO | BL y poliza tienen ubicacion. | Diferencias devueltas. |
