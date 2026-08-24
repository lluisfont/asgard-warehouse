# Billing Invoice Planilla Document Generation - State Model

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Estados candidatos

| Estado | Significado | Evidencia |
| --- | --- | --- |
| Factura-planilla registrada | Existe cabecera en `dav_facturaplanilla`. | Tabla y consultas |
| Documento generable | Existe informacion suficiente de caso, cliente, detalle y dosificacion. | `generarfacturaplanillacliente.php` |
| PDF fuente existente | Existe PDF fisico en ruta `/datadrive1`. | `file_exists(...)` |
| PDF membretado generado | ASGARD aplica imagen/membrete y guarda PDF derivado. | `descargarfactura.php`, `descargarplanilla.php` |
| PDF descargado | ASGARD entrega el PDF con headers HTTP. | `readfile(...)`, `mPDF->Output(..., 'D')` |
| Error de archivo | No existe factura o planilla esperada. | Mensajes de error/alerta |

## Transiciones candidatas

| Transicion | Desde | Hacia | Disparador | Persistencia |
| --- | --- | --- | --- | --- |
| Generar combinado | Factura-planilla registrada | PDF descargado | Enlace Factura & Planilla | Descarga directa mPDF |
| Membretar factura | PDF fuente existente | PDF membretado generado | Ver Factura antigua | Archivo en `/datadrive1/facturas/.../membretado` |
| Membretar planilla | PDF fuente existente | PDF membretado generado | Ver Planilla | Archivo en `/datadrive1/planillas/.../membretado` |
| Descargar membretado | PDF membretado generado | PDF descargado | Header PDF + `readfile` | No cambia DB |
| Fallar descarga | Documento generable | Error de archivo | Archivo fuente no existe | Mensaje al usuario |

## Estados no observados

- Enviado a autoridad fiscal.
- Aprobado por autoridad fiscal.
- Rechazado fiscalmente.
- Anulado con nota/causal fiscal.
- Regenerado con versionado.
