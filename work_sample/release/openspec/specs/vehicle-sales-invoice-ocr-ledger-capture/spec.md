# vehicle-sales-invoice-ocr-ledger-capture

## Requirements

### Requirement: Insert non-duplicate OCR sales invoice

The system SHALL insert a sales invoice from OCR only when same date and invoice number are not already registered and amount is positive.

#### Scenario: Valid invoice

- GIVEN OCR returns invoice data
- AND no same date/invoice exists
- AND amount is positive
- WHEN processed
- THEN `logis_libroventas` is inserted.
