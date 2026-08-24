# vehicle-soat-pdf-ocr-splitting

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirement: Process supported SOAT documents

ASGARD SHALL only run SOAT PDF splitting for supported document identifiers.

### Scenario: Supported document id

- GIVEN a PDF upload linked to a supported exchange document id
- WHEN the split endpoint runs
- THEN ASGARD prepares the PDF for OCR.

## Requirement: Extract vehicle identifiers by OCR

ASGARD SHALL use the SOAT OCR model to extract chassis and related fields.

### Scenario: OCR returns vehicle table

- GIVEN OCR result includes vehicle rows
- WHEN ASGARD processes the result
- THEN it extracts chassis, motor, comprobante, factura and roseta.

## Requirement: Generate one PDF per chassis

ASGARD SHALL generate individual PDF files named by chassis when a corresponding page exists.

### Scenario: Chassis with available page

- GIVEN a chassis value and matching page index
- WHEN PDF generation runs
- THEN ASGARD writes `/datadrive1/temporales/soat/{chasis}.pdf`.
