# Customs DEX OCR Validation Update - Business Rules

| Rule ID | Regla candidata | Evidencia | Estado |
| --- | --- | --- | --- |
| BR-CDOVU-001 | La lectura usa el modelo OCR DEX para documentos de intercambio documental. | `lecturaOCRModelo($url, MODELO_DEX)` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDOVU-002 | `exchange_id` puede pertenecer a un embarque logistico o directamente a una solicitud aduanera previa. | Consultas a `logis_embarques` y `dav_casosprevios` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDOVU-003 | La actualizacion aduanera solo procede cuando la carpeta OCR coincide con `dav_casos.carpeta`. | Comparacion `carpeta` vs OCR | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDOVU-004 | El campo OCR `declaracion` se interpreta como compuesto y actualiza `gestiondui` y `nodui`. | `explode("-", declaracion)` y `UPDATE dav_casos` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDOVU-005 | `sidunea` OCR actualiza `dav_casos.nosidunea` cuando viene informado. | `UPDATE dav_casos SET nosidunea` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDOVU-006 | `fecha_aceptacion` OCR se convierte de `dd/mm/yyyy` a `yyyy-mm-dd` y actualiza `fechavalidaciondui`. | Conversion y `UPDATE dav_casos` | INFERRED_DRAFT_REVIEW_REQUIRED |
| BR-CDOVU-007 | Las diferencias entre OCR y datos internos no bloquean por si mismas la respuesta; se devuelven como mensaje de observacion. | Construccion de `mensajeerroractualziacion` | INFERRED_DRAFT_REVIEW_REQUIRED |
