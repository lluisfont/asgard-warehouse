# Document Service Plan OCR Capture - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Leer una planilla/solicitud de servicio mediante OCR general, extraer numero, BL, monto cotizado y fechas, y registrar una version vigente en `dav_planillasdp` para el documento de intercambio.

## Alcance observado

- OCR contra Azure Read API con credencial directa en codigo.
- Extraccion por posicion/texto de numero de solicitud, BL, monto cotizado, fecha impresion, registro y validacion.
- Soft delete de lecturas anteriores para el mismo `exchange_id` y `document_id`.
- Insercion de nueva lectura en `dav_planillasdp`.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr.php:13-35` | Llama Azure OCR con endpoint y subscription key. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr.php:73-142` | Extrae campos desde lineas OCR. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr.php:144-156` | Reemplaza lectura vigente en `dav_planillasdp`. |

## Criterios de aceptacion candidatos

- El documento debe contener numero de solicitud, BL y monto cotizado para aceptarse.
- Una nueva lectura invalida lecturas anteriores del mismo documento/intercambio.
- Las fechas se convierten a formato persistible antes de insertar.
