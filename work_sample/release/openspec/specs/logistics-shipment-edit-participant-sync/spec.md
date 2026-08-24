# logistics-shipment-edit-participant-sync Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for editing logistics shipment/quotation data and syncing document-exchange participants.

## Requirements

### Requirement: Update Shipment Header

The system SHALL update shipment header fields from the submitted edit form.

#### Scenario: Valid edit submitted

- GIVEN a shipment id and edit fields
- WHEN the edit controller processes the request
- THEN the system SHALL update `logis_embarques`
- AND SHALL set `updated_at`.

### Requirement: Replace Shipment Children

The system SHALL replace submitted shipment magnitudes, containers and route legs.

#### Scenario: Edit includes child records

- GIVEN magnitudes, containers and route legs are submitted
- WHEN the header update succeeds
- THEN the system SHALL replace `logis_embarquesmagnitudes`, `logis_embarquescontenedor` and `logis_tramos`.

### Requirement: Update Operator According To Mode

The system SHALL handle operators differently for quotation and shipment edits.

#### Scenario: Quotation edit

- GIVEN the edit is not `actualizarEmbarque`
- WHEN operators are submitted
- THEN the system SHALL replace candidate operators.

#### Scenario: Shipment edit

- GIVEN the edit is `actualizarEmbarque`
- WHEN an operator is submitted
- THEN the system SHALL update or insert the operator as accepted.

### Requirement: Sync Document Participants

The system SHALL send updated shipment participants to the document API.

#### Scenario: Participants available

- GIVEN the shipment has an exchange id
- AND operator, customs agent, insurance agent or supplier is provided
- WHEN the edit succeeds locally
- THEN the system SHALL call the document API participants endpoint.
