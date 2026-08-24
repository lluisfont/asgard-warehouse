# Alicorp OCR Bulk Shipment Intake - Evidence Map

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Evidencia fuente

| Artefacto | Lineas / patron | Uso |
| --- | --- | --- |
| `index_archivos/logistica/index.php` | Modal `Carga Masiva` | Entrada del usuario. |
| `index_archivos/logistica/js/datosEmbarques.js` | Submit `formOCRMasivo` y llamadas exchange | Orquestacion UI posterior. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php` | Copia/extraccion de ZIP/PDF/XLSX | Recepcion de archivos. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php` | `lecturaOCRAlicorp` | OCR por factura. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php` | Include `embarquesController.php` | Creacion de embarque. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php` | Include `guardarGestionAduanera.php` | Creacion de GA. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php` | `cargar_servicios` | Reglas de servicios adicionales. |
| `index_archivos/logistica/ajax/get-ocr-alicorp-masivo.php` | `guadarSolicitudListaEmpaque` | Asociacion lista de empaque. |
| `index_archivos/ocr/lectura_ocr.php` | Inserts OCR Alicorp | Persistencia OCR. |
| `.data_base/asgard.sql` | DDL `ocr_alicorp*` | Modelo fisico OCR. |

## Trazabilidad a artefactos

| Artefacto generado | Evidencia principal |
| --- | --- |
| `PROCESS_DEFINITION.md` | Modal, endpoint masivo, OCR, creacion embarque/GA, exchange. |
| `PROCESS_FLOW.md` | Secuencia de carga, OCR, creacion y asociacion documental. |
| `BUSINESS_RULES.md` | Reglas de datos minimos, tramos, servicios y LE. |
| `DATA_USED.md` | Tablas OCR, logistica, GA y exchange. |
| `STATE_MODEL.md` | Estados por archivo y por creacion parcial/completa. |
| `UC-001.md` | Caso de uso masivo Alicorp. |
| `openspec/spec.md` | Requisitos AS-IS candidatos. |

## Limitaciones

- No se ejecuto OCR real ni creacion de entidades.
- No se validaron catalogos de proveedores, lineas, regimen, ciudad o tipo de declaracion con negocio.
- La parte de exchange depende de JavaScript y servicios externos; se documenta como observada, no como confirmada end-to-end.
