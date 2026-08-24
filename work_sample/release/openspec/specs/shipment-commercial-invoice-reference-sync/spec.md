# shipment-commercial-invoice-reference-sync Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for syncing commercial invoice document references into a logistics shipment.

## Requirements

### Requirement: Apply Only To Client 429

The system SHALL only update shipment commercial invoice references from this flow for client `429`.

#### Scenario: Different client

- GIVEN the session client is not `429`
- WHEN the endpoint receives document references
- THEN the system SHALL NOT update the shipment.

### Requirement: Extract Commercial Invoice References

The system SHALL collect reference codes from commercial invoice documents.

#### Scenario: Commercial invoice document with reference

- GIVEN a document name is `FACTURA COMERCIAL`
- AND its `reference_code` is not empty
- WHEN documents are processed
- THEN the system SHALL include that reference in the shipment reference list.

### Requirement: Persist Concatenated References

The system SHALL persist collected references in the shipment.

#### Scenario: One or more references collected

- GIVEN references were collected
- WHEN processing completes
- THEN the system SHALL update `logis_embarques.facturacomercial` for the shipment id.
