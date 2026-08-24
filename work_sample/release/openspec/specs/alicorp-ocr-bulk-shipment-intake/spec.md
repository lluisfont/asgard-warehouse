# alicorp-ocr-bulk-shipment-intake Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for creating Alicorp logistics shipments and customs-management requests from bulk OCR upload.

## Requirements

### Requirement: Accept Bulk OCR Files

The system SHALL accept commercial invoice files for OCR processing.

#### Scenario: User uploads ZIP of invoices

- GIVEN the uploaded commercial invoice file has ZIP extension
- WHEN the upload is processed
- THEN the system SHALL copy and extract the ZIP
- AND SHALL process PDF files found in the extracted folder.

### Requirement: Persist Alicorp OCR Reading

The system SHALL persist Alicorp OCR results before downstream creation.

#### Scenario: OCR succeeds

- GIVEN a PDF invoice can be read by OCR
- WHEN the OCR method completes
- THEN the system SHALL persist OCR header, item detail and international value rows
- AND SHALL return `idocr_alicorp`.

### Requirement: Create Shipment From OCR

The system SHALL create a logistics shipment from valid OCR data.

#### Scenario: OCR data available

- GIVEN OCR has returned invoice, order, route, provider and cargo data
- WHEN the backend maps OCR fields to shipment fields
- THEN the system SHALL create a logistics shipment linked to `idocr_alicorp`.

### Requirement: Create Customs Request When Data Complete

The system SHALL create a customs-management request only when required OCR data is complete.

#### Scenario: Required data is complete

- GIVEN required fields do not trigger `sinData`
- WHEN the shipment has been created
- THEN the system SHALL create a Gestion Aduanera request linked to the shipment.

### Requirement: Identify Additional Services

The system SHALL identify candidate additional services based on line, provider, product text and weight.

#### Scenario: Service rule matches

- GIVEN the OCR data matches a hardcoded line/provider/product/weight rule
- WHEN the request is created
- THEN the system SHALL include the matching service codes in `cargar_servicios`.

### Requirement: Associate Packing List By Order

The system SHALL associate packing-list files when their file name contains the OCR order.

#### Scenario: Matching packing list file

- GIVEN extracted XLSX packing-list files exist
- AND a file path contains the OCR order value
- WHEN the customs request is created
- THEN the system SHALL attach that packing list to the request.
