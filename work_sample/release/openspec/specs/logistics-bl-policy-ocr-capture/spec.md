# logistics-bl-policy-ocr-capture

## Requirements

### Requirement: Capture BL OCR data

The system SHALL persist BL number, issuer, date and quantity for the related shipment.

#### Scenario: BL document read

- GIVEN a BL document type
- WHEN OCR succeeds
- THEN `logis_lecturablpoliza` is inserted or updated with BL fields.

### Requirement: Capture policy OCR data

The system SHALL persist policy number, application, date, quantity and value.

#### Scenario: Policy document read

- GIVEN a policy document type
- WHEN OCR succeeds
- THEN `logis_lecturablpoliza` is inserted or updated with policy fields.

### Requirement: Return date comparisons

The system SHALL return BL/policy date differences when both document sides exist.
