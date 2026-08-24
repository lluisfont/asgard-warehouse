# document-service-plan-ocr-capture

## Requirements

### Requirement: Replace OCR reading for document

The system SHALL keep one active OCR reading per exchange/document by soft deleting previous rows before inserting a new one.

#### Scenario: Accepted document

- GIVEN OCR extracts number, BL and quoted amount
- WHEN processed
- THEN prior `dav_planillasdp` rows are soft deleted
- AND a new row is inserted.
