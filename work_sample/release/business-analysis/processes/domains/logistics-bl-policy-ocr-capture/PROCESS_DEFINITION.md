# Logistics BL Policy OCR Capture - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Capturar por OCR datos de Bill of Lading y poliza de seguro asociados a un embarque, consolidarlos en `logis_lecturablpoliza` y detectar diferencias de fechas entre BL y poliza.

## Alcance observado

- Lectura OCR segun tipo documental: `MODELO_BL` o `MODELO_SEGURO`.
- Resolucion de embarque por `logis_embarques.idExchange` o `dav_casosprevios.idExchange`.
- Insercion o actualizacion de datos BL: ubicacion, numero, emisor, fecha y cantidad.
- Insercion o actualizacion de datos de poliza: ubicacion, numero, aplicacion, fecha, cantidad y valor.
- Comparacion de BL y poliza cuando ambas ubicaciones existen.

## Fuera de alcance observado

- Carga del documento en intercambio.
- Aprobacion documental formal.
- Correccion manual de diferencias.
- Sincronizacion con costos o facturacion.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:10-18` | El tipo documental decide el modelo OCR. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:35-44` | Resuelve embarque por `exchange_id`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:52-141` | Extrae y persiste datos BL. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:143-235` | Extrae y persiste datos de poliza. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-bl.php:240-250` | Compara fechas BL/poliza. |

## Criterios de aceptacion candidatos

- El documento BL debe actualizar o crear una lectura por embarque.
- La poliza debe actualizar o crear una lectura compatible por embarque/cantidad.
- La fecha OCR debe normalizarse antes de guardarse.
- Si BL y poliza quedan relacionados, se deben devolver diferencias de fecha negativas.
