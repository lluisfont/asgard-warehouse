# Evidence Map - vehicle-reception-part-ocr-document-generation

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| Afirmacion | Evidencia | Confianza |
| --- | --- | --- |
| El endpoint resuelve cliente desde embarque o caso previo por `exchange_id`. | `lectura-ocr-pr.php:13-18` | High |
| Solo clientes `417` y `755` activan el OCR PR observado. | `lectura-ocr-pr.php:20-21` | High |
| PDF se procesa por URL y ZIP se extrae para procesar archivos en base64. | `lectura-ocr-pr.php:22-65` | High |
| El OCR PR incrementa/crea conteo en `ocr_lecturas`. | `OcrUtil.php:48-62` | High |
| La lectura estructurada se inserta en `ocr_parte_recepcion`. | `OcrUtil.php:121-128`, `.data_base/asgard.sql:13045-13068` | High |
| ASGARD busca casos por chasis en `dav_partidas.otroparametro10` y mercancias `15`/`34`. | `OcrUtil.php:149-158` | High |
| Si no existe documento tipo `71`, ASGARD lo inserta en `dav_documentos`. | `OcrUtil.php:161-167`, `OcrUtil.php:181-187` | High |
| Diferencias de cantidad manifestada contra chasis leidos generan correo operativo. | `OcrUtil.php:175-219` | High |

## Riesgos candidatos

- Procesamiento restringido por ids de cliente hardcodeados.
- Sin transaccion entre lectura OCR y generacion de documentos.
- Parsing de chasis por separadores simples puede fallar con formatos no previstos.
- Credenciales OCR y SSL verification dependen de configuracion global con `verify=false`.
- Chasis no encontrados solo se comunican por correo, sin bandeja estructurada de excepciones.
