# Customs DEX OCR Validation Update - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia fuente

| Fuente | Evidencia |
| --- | --- |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:1-33` | Entrada de documento, `exchange_id` y llamada a `MODELO_DEX`. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:40-62` | Resolucion por `logis_embarques.idExchange` y `dav_casosprevios.idExchange`. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:83-101` | Consulta de datos ASGARD usados para contraste. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:106-139` | Actualizaciones de `dav_casos` para DUI, Sidunea y fecha. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:141-221` | Comparaciones OCR vs ASGARD. |
| `index_archivos/intercambioDocumental/ajax/documento-ocr-dex.php:230-243` | Respuesta JSON. |

## Cobertura

| Artefacto | Cobertura |
| --- | --- |
| Process definition | Cubierto |
| Process flow | Cubierto |
| Business rules | Cubierto |
| Data used | Cubierto |
| State model | Cubierto |
| Use case | Cubierto |
| OpenSpec | Cubierto |

## Riesgos de evidencia

- La intencion exacta de cada comparacion OCR necesita validacion funcional.
- No se observa persistencia de observaciones ni auditoria de lectura.
- Las actualizaciones se ejecutan antes de terminar todas las comparaciones.
