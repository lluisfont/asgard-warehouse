# Evidence Map - alicorp-operational-document-package-dispatch

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| Afirmacion | Evidencia | Confianza |
| --- | --- | --- |
| El job se activa con `cron=1` y procesa clientes `775` y `755`. | `cron/documentacionAlicorp.php:24-29` | High |
| Casos elegibles requieren exchange de embarque, no anulados y sin marca de envio documental. | `SolicitudesClass.php:160-218` | High |
| Los documentos a incluir se obtienen desde parametrizacion concatenada/documental. | `EmbarqueClass.php:1552-1584`, `documentacionAlicorp.php:61-72` | High |
| El job consulta Document Exchange y descarga archivos parametrizados. | `documentacionAlicorp.php:75-96` | High |
| El ZIP se guarda mediante `GlobalClass::guardarArchivo` en `documentosOperativosAlicorp`. | `documentacionAlicorp.php:108-122` | High |
| El correo `TRAMITES IASA` se envia con adjuntos y tabla de carpetas/facturas. | `documentacionAlicorp.php:145-169` | High |
| Tras el envio se marca `dav_casos.embarque_documentos_enviados`. | `SolicitudesClass.php:1203-1206` | High |
| Existe una variante manual que descarga un ZIP documental operativo por embarque. | `index_archivos/logistica/ajax/downloadDocumentos.php` | Medium |

## Riesgos candidatos

- Token de Document Exchange hardcodeado.
- Verificacion SSL desactivada para descarga.
- La marca de enviado se actualiza despues de invocar correo, sin confirmacion estructurada de entrega.
- El filtro de carpetas usa concatenacion SQL sobre lista construida desde datos de casos.
