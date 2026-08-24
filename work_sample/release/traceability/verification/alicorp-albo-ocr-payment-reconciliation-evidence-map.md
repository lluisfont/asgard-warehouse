# Alicorp Albo OCR Payment Reconciliation - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia fuente

| Fuente | Evidencia |
| --- | --- |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:1-45` | Inicializacion, entradas y concepto `272`. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:51-82` | Descompresion remota ZIP/RAR y recorrido de PDFs. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:91-162` | Resolucion de contexto y consulta de pago pendiente en rama paquete. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:171-177` | Marcado de cierre de transito y vinculacion AGES. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:183-195` | Actualizacion de pago, metadata OCR y nota de debito en rama paquete. |
| `index_archivos/intercambioDocumental/ajax/lectura-ocr-falbo.php:225-347` | Rama PDF directa para OCR, contexto, cierre y pago/nota. |

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

- La rama ZIP/RAR y la rama PDF no persisten exactamente la misma metadata OCR.
- El matching de pago depende de monto exacto y numero vacio.
- Se observaron credenciales y comando remoto hardcodeados en fuente.
