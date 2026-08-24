# logistics-shipment-document-attachment-management Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for shipment document management using either Document Exchange or local attachments.

## Requirements

### Requirement: Select Document Channel

The system SHALL select Document Exchange when the shipment has complete exchange identifiers.

#### Scenario: Exchange exists

- GIVEN `idExchange_as` and `idExchange_id` are present
- WHEN the documents tab is opened
- THEN the system SHALL render the exchange document list.

### Requirement: Use Local Attachments When No Exchange

The system SHALL use local shipment attachments when no complete exchange exists.

#### Scenario: No exchange

- GIVEN the shipment lacks complete exchange identifiers
- WHEN the documents tab is opened
- THEN the system SHALL list `logis_embarquesdocumentos`
- AND SHALL allow local upload.

### Requirement: Store Local Attachment

The system SHALL store uploaded local shipment attachments in filesystem and database.

#### Scenario: Upload attachment

- GIVEN the user submits a file
- WHEN upload succeeds
- THEN the system SHALL move it to the shipment folder
- AND SHALL insert `logis_embarquesdocumentos`.

### Requirement: Delete Local Attachment

The system SHALL delete local attachment file and record.

#### Scenario: Delete attachment

- GIVEN a local document exists
- WHEN the user deletes it
- THEN the system SHALL remove the physical file
- AND SHALL delete the database row.
