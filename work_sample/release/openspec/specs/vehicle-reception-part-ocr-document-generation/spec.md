# vehicle-reception-part-ocr-document-generation

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirement: Process eligible reception-part documents

ASGARD SHALL process OCR reception-part documents for eligible customers resolved from Document Exchange context.

### Scenario: Eligible PDF document

- GIVEN a document with an `exchange_id` resolving to customer `417` or `755`
- WHEN the document extension is PDF
- THEN ASGARD submits the document to the configured PR OCR model
- AND records the OCR result.

## Requirement: Persist reception-part OCR reading

ASGARD SHALL store structured reception-part data after successful OCR.

### Scenario: OCR result succeeded

- GIVEN OCR result fields for reception part number, dates, manifest, BL/DAM and chassis detail
- WHEN `saveResult` runs
- THEN ASGARD inserts a row in `ocr_parte_recepcion`.

## Requirement: Generate document 901 for matched vehicle cases

ASGARD SHALL create document type `71` for matched non-annulled vehicle cases when the document is not already present.

### Scenario: Chassis matches a vehicle customs case

- GIVEN a chassis from OCR detail
- AND a matching non-annulled case through `dav_partidas`
- AND no existing `dav_documentos` row of type `71`
- WHEN ASGARD creates the reception document
- THEN `dav_documentos` receives the new row with reception number and date.
