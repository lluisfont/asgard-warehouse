# logistics-shipment-cost-capture-control Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for capturing active shipment costs by category and concept.

## Requirements

### Requirement: Gate Cost Editing

The system SHALL allow cost editing only for open shipments and users with write permission.

#### Scenario: Open shipment with permission

- GIVEN the shipment has no `fecha_finalizacion`
- AND the user has write permission `70`
- WHEN the costs tab is rendered
- THEN the system SHALL allow adding categories/concepts and saving.

### Requirement: Replace Active Costs

The system SHALL replace active shipment costs when saving a new cost set.

#### Scenario: Save costs

- GIVEN the user submits valid cost data
- WHEN the save endpoint runs
- THEN the system SHALL mark existing shipment costs and details as deleted
- AND SHALL insert the submitted cost headers and details.

### Requirement: Maintain Category Detail

The system SHALL store cost category totals and detail concepts.

#### Scenario: Cost category submitted

- GIVEN a submitted category has concepts
- WHEN it is saved
- THEN the system SHALL insert a `logis_costos` row
- AND SHALL insert related `logis_costos_detalle` rows.

### Requirement: Seed Merchandise Cost

The system SHALL seed an automatic merchandise cost when applicable source documents exist.

#### Scenario: Merchandise documents exist

- GIVEN documents type `19` exist for the shipment previous case
- WHEN costs are loaded
- THEN the system SHALL create or refresh the automatic merchandise concept in category `6`.
