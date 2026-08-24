# packing-list-import-validation Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Import packing list Excel

ASGARD SHALL import a packing list Excel for a previous case/request.

#### Scenario: Valid upload request

- GIVEN a user provides `idcasosprevios`, brand and Excel file
- WHEN the user submits the import
- THEN ASGARD processes the file and persists general, specific and item rows under one `idcargado`

### Requirement: Validate packing list consistency

ASGARD SHALL report packing list observations for invalid dates and inconsistent weights.

#### Scenario: Weight mismatch

- GIVEN calculated gross or net weights differ between sections or from shipment magnitudes
- WHEN ASGARD builds the preview
- THEN it includes an observation alert

### Requirement: Generate packing list PDF

ASGARD SHALL generate a PDF from a persisted packing list load.

#### Scenario: PDF requested

- GIVEN `idcasosprevios` and `idcargado`
- WHEN the user opens the PDF link
- THEN ASGARD retrieves the persisted rows and builds the PDF
