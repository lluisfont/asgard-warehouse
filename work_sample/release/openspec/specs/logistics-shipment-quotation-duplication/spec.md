# logistics-shipment-quotation-duplication Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for duplicating logistics quotations and shipments.

## Requirements

### Requirement: Duplicate Quotation

The system SHALL create a new quotation from an existing logistics record.

#### Scenario: User duplicates quotation

- GIVEN an origin logistics quotation/shipment id
- WHEN the user confirms duplication from the quotation list
- THEN the system SHALL insert a new `logis_embarques` row with `cotizacion = 1`
- AND SHALL copy magnitudes, containers, routes, operators and local documents when available.

### Requirement: Duplicate Shipment

The system SHALL create a new shipment from an existing shipment.

#### Scenario: User duplicates shipment

- GIVEN an origin shipment id
- WHEN the user confirms duplication from the shipment list
- THEN the system SHALL insert a new `logis_embarques` row with `cotizacion = 0`
- AND SHALL copy magnitudes, containers, routes and operator assignment.

### Requirement: Notify Duplicated Shipment

The system SHALL notify relevant parties when a new duplicated shipment is created.

#### Scenario: Shipment duplicated

- GIVEN the duplicated shipment insert succeeds
- WHEN the backend completes
- THEN the system SHALL register a notification
- AND SHALL emit the `duplicarEmbarque` Pusher event.

### Requirement: Start Exchange For Duplicated Shipment

The UI SHALL start and associate an exchange for the duplicated shipment.

#### Scenario: Backend returns duplicated shipment id

- GIVEN duplication succeeds
- WHEN the UI receives the new shipment id
- THEN it SHALL create exchange data
- AND SHALL save/edit the exchange for the new shipment.
