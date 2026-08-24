# logistics-route-trip-assignment-management Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is route and trip assignment behavior for logistics shipments.

## Requirements

### Requirement: List Shipment Routes

The system SHALL list non-deleted routes associated with a shipment.

#### Scenario: Routes are available

- GIVEN a shipment has active route records
- WHEN the routes view is opened
- THEN the system SHALL display origin, departure, dates, destination, arrival, transit time, transport mode and carrier.

### Requirement: Add Route For Open Shipment

The system SHALL allow users with write permission to add a route while the shipment is not finalized.

#### Scenario: Add route

- GIVEN the user has write permission
- AND the shipment has empty `fecha_finalizacion`
- WHEN route data is submitted
- THEN the system SHALL insert a row in `logis_embarquesrutas`.

### Requirement: Delete Route Logically

The system SHALL delete a route logically rather than physically removing it.

#### Scenario: Delete route

- GIVEN a route is active
- WHEN the user confirms deletion
- THEN the system SHALL set `deleted_at` for the route
- AND SHALL exclude it from subsequent route lists.

### Requirement: Assign Trip To Shipment

The system SHALL support assigning a TCK trip to a shipment.

#### Scenario: Assign recovered trip

- GIVEN a recoverable trip exists
- WHEN the user assigns it to a shipment
- THEN the system SHALL set `tck_asignacion_viaje.embarque_id` to the shipment id.

### Requirement: Remove Assigned Trip

The system SHALL remove an assigned trip logically.

#### Scenario: Remove trip

- GIVEN a trip is associated with a shipment
- WHEN the user confirms removal
- THEN the system SHALL set `deleted_at` and `deleted_by` on `tck_asignacion_viaje`.

### Requirement: Block Modifications After Finalization

The system SHALL hide route/trip write actions when the shipment is finalized.

#### Scenario: Finalized shipment

- GIVEN `logis_embarques.fecha_finalizacion` is not null
- WHEN the routes view is opened
- THEN the system SHALL not show add/delete route actions.

