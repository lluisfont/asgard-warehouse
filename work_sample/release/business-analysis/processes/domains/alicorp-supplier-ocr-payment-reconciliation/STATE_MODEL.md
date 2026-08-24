# Alicorp Supplier OCR Payment Reconciliation - State Model

| Estado | Condicion | Resultado |
| --- | --- | --- |
| FACTURA_RECIBIDA | Documento SENAVEX/FDAB/Jennefer disponible. | OCR ejecutable. |
| CONCEPTO_DETERMINADO | Proveedor/documento mapea concepto. | Pago puede buscarse. |
| CONTEXTO_RESUELTO | `exchange_id` localiza embarque/GA/AGES. | Pago acotado. |
| PAGO_CANDIDATO | Concepto, monto y nro vacio coinciden. | Pago actualizable. |
| CIERRE_MARCADO | DIM coincide. | `alicorp_cierre_transito=1`. |
| PAGO_RECONCILIADO | Fecha valida. | Pago y nota actualizados. |
