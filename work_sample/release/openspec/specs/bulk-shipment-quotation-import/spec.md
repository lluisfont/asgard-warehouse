# bulk-shipment-quotation-import Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Read shipment import rows

ASGARD SHALL read each Excel row as a candidate shipment quotation/import line.

#### Scenario: User uploads logistics Excel

- GIVEN an Excel file is submitted
- WHEN ASGARD reads the workbook
- THEN rows from row 2 onward are mapped to line, order, purchase order, quantity, package type, weight, provider and description

### Requirement: Resolve logistics catalogs

ASGARD SHALL resolve row values against logistics master data.

#### Scenario: Catalog values are present

- GIVEN row values for line, provider and package type
- WHEN ASGARD processes the row
- THEN it resolves line, provider and package type ids where matching records exist

### Requirement: Create shipment quotation records per row

ASGARD SHALL call the shipment quotation persistence flow for each imported row.

#### Scenario: Row is processed

- GIVEN row and common form data are available
- WHEN ASGARD processes the import
- THEN it calls `CotizacionClass::guardarCotizacionCliente` for that row

### Requirement: Return generated ids

ASGARD SHALL return the generated shipment/quotation ids to the UI.

#### Scenario: Import completes

- GIVEN the import processing finishes
- WHEN ASGARD responds
- THEN the response includes generated ids and a success message
