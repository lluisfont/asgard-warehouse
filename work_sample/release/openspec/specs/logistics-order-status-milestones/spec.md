# logistics-order-status-milestones Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: List shipment order status milestones

ASGARD SHALL list active order-status milestones for the active client and selected shipment.

#### Scenario: View history

- GIVEN a shipment id
- WHEN the status frame loads
- THEN ASGARD returns `logis_edp` rows for the active client and shipment
- AND excludes rows with `deleted_at`

### Requirement: Restrict milestone capture by permission and shipment finalization

ASGARD SHALL expose milestone capture only when the user has write permission and the shipment is not finalized.

#### Scenario: User can add milestone

- GIVEN the user has write permission for report id `73`
- AND the shipment has no finalization date
- WHEN the frame renders
- THEN add/save controls are available

#### Scenario: User cannot add milestone

- GIVEN the shipment has a finalization date
- WHEN the frame renders
- THEN add/save controls are not available

### Requirement: Persist new order status milestones

ASGARD SHALL persist submitted milestones in `logis_edp`.

#### Scenario: Save milestone

- GIVEN one or more new milestone rows
- WHEN the user saves
- THEN ASGARD inserts one `logis_edp` row per milestone
- AND records the active client user as creator

### Requirement: Finalize shipment on final milestone ids

ASGARD SHALL mark the shipment as finalized when a final milestone id is registered.

#### Scenario: Final state

- GIVEN a submitted milestone with state id `53`, `99` or `160`
- WHEN ASGARD saves the milestone
- THEN `logis_embarques.fecha_finalizacion` is set
- AND `fecha_finalizacion_usuario` is set to the active client user

### Requirement: Notify participants about status changes

ASGARD SHALL communicate order-status updates to relevant participants.

#### Scenario: Standard notification

- GIVEN a non-special order-status update
- WHEN ASGARD saves the milestone
- THEN ASGARD creates a notification
- AND emits a realtime event

#### Scenario: Pick-up email notification

- GIVEN client `429` or `755`
- AND state id `58`
- WHEN ASGARD saves the milestone
- THEN ASGARD sends an email about pick-up update

