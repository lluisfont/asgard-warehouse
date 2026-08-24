# Alicorp OCR Bulk Shipment Intake - Business Rules

## Reglas candidatas

| ID | Regla | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-AOBSI-001 | La factura comercial masiva puede recibirse como ZIP con PDFs o PDF individual. | Extension `zip`/`pdf` en `get-ocr-alicorp-masivo.php`. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-002 | La lista de empaque masiva se procesa como ZIP con archivos XLSX. | Copia/extraccion de `listaEmpaque`. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-003 | El OCR Alicorp persiste cabecera, detalle e importes internacionales antes de crear embarque. | Inserts a `ocr_alicorp*`. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-004 | Factura, incoterm, ciudad/pais, contrato, pedido, proveedor, descripcion y tramos son datos minimos para crear GA automatica. | Variables que activan `sinData`. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-005 | Si `sinData` es verdadero, el embarque puede crearse pero la GA automatica no se crea. | `if(!$sinData)` envuelve preparacion GA. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-006 | Para transporte terrestre se crean dos tramos; para multimodal se crean cuatro tramos candidatos. | Switch por `idtipoembarque`. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-007 | La GA automatica usa regimen `4`, tipo declaracion `2`, ciudad `11` y llegada estimada a 10 dias. | Asignaciones directas antes de `guardarGestionAduanera.php`. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-008 | En linea `43` algunos proveedores disparan certificado de origen, inocuidad o fitosanitario. | Switch de linea/proveedor. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-009 | En linea `44` algunos productos y umbrales de peso disparan certificado de origen y fitosanitario. | Switch de linea/proveedor/producto/peso. | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-AOBSI-010 | La lista de empaque se empareja con la solicitud cuando el nombre del archivo contiene el pedido OCR. | `strpos($fileLE, pedido)`. | INFERRED_DRAFT_REVIEW_REQUIRED |

## Riesgos de regla

- Los codigos de linea, proveedor, ciudad, regimen, tipo declaracion y productos estan hardcodeados.
- Las reglas de servicios adicionales parecen informarse a la UI y completarse en llamadas posteriores, no en una transaccion backend unica.
- No se observa control de duplicado por factura/pedido antes de crear embarques.
- El proceso puede dejar embarques creados sin GA o sin exchange completo si fallan pasos posteriores.
