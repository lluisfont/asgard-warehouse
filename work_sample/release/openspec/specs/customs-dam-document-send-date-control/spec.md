# customs-dam-document-send-date-control

## Requirements

### Requirement: Mark DAM send date after AP

The system SHALL set `fechaenviodam` only when at least one related commercial invoice has `fechaenvioap`.

#### Scenario: AP exists

- GIVEN exchange resolves to a customs request
- AND related invoice has AP send date
- WHEN DAM document event is processed
- THEN related commercial invoices are marked with current DAM send date.

#### Scenario: AP missing

- GIVEN no related invoice has AP send date
- WHEN DAM document event is processed
- THEN no DAM date is updated
- AND an alert email is sent.
