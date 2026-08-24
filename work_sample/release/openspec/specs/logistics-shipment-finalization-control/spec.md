# logistics-shipment-finalization-control Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for manual shipment finalization and post-finalization write blocking.

## Requirements

### Requirement: Show Finalization Action Only For Open Shipments

The system SHALL show the finalization action only when the shipment has no `fecha_finalizacion` and the user has write permission.

#### Scenario: Open shipment with write permission

- GIVEN a shipment has empty `fecha_finalizacion`
- AND the user has `escritura = 1`
- WHEN the shipment detail is opened
- THEN the system SHALL show the Finalizar Embarque action.

### Requirement: Confirm Irreversible Closure

The system SHALL require user confirmation before finalizing a shipment.

#### Scenario: User cancels

- GIVEN the confirmation dialog is shown
- WHEN the user cancels
- THEN the system SHALL NOT submit the finalization request.

### Requirement: Validate Client-Specific Prerequisites

For client `429`, the system SHALL validate required EDP states, cost concepts and customs-management fields before finalizing.

#### Scenario: Missing prerequisite

- GIVEN the shipment belongs to client `429`
- AND one or more required prerequisites are missing
- WHEN finalization is requested
- THEN the system SHALL return a warning grouped by missing area
- AND SHALL NOT set `fecha_finalizacion`.

### Requirement: Persist Finalization

The system SHALL persist shipment finalization with timestamp, user and EDP event.

#### Scenario: Valid finalization

- GIVEN all applicable validations pass
- WHEN finalization is requested
- THEN the system SHALL set `logis_embarques.fecha_finalizacion`
- AND SHALL set `logis_embarques.fecha_finalizacion_usuario`
- AND SHALL insert a `logis_edp` event with `created_type = CLIENTE`.

### Requirement: Block Subsequent Modification

The system SHALL hide or disable operational write actions after finalization.

#### Scenario: Finalized shipment

- GIVEN a shipment has `fecha_finalizacion`
- WHEN dependent screens are opened
- THEN the system SHALL NOT show add/save actions controlled by open-shipment checks.

