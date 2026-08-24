# logistics-order-item-detail-maintenance Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for maintaining logistics order item detail fields.

## Requirements

### Requirement: Load Order Items

The system SHALL load order item positions for review.

#### Scenario: User opens item view

- GIVEN an order id
- WHEN the item view loads
- THEN the UI SHALL request `detalle-pedido/{idpedido}`
- AND SHALL render the returned positions.

### Requirement: Save Item Field Updates

The system SHALL persist submitted item field updates by position id.

#### Scenario: User saves item changes

- GIVEN the form contains field names with target column and position id
- WHEN the user saves
- THEN the backend SHALL update `logis_pedidos_detalle` for each submitted position field.

### Requirement: Client-Specific Columns

The system SHALL vary displayed item columns according to observed client conditions.

#### Scenario: Client-specific view

- GIVEN client `417`
- WHEN the item table is rendered
- THEN it SHALL show origin and destination columns instead of warehouse.
