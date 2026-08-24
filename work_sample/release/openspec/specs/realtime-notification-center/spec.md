# realtime-notification-center Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Persist operational notifications before realtime delivery

ASGARD SHALL create a notification header and recipient assignment records before emitting a realtime event.

#### Scenario: Store notification with recipients

- GIVEN a business event with title, message, event id, operational identifier and recipients
- WHEN the notification is created
- THEN `push_notificacion` contains the notification header
- AND `push_notificacionusuarios` contains one row per recipient
- AND the initial recipient state is `1`

### Requirement: Deliver realtime alerts only to valid recipients

ASGARD SHALL verify the current session as a notification recipient before the browser displays the realtime alert.

#### Scenario: Valid recipient receives toast

- GIVEN the browser receives a Pusher event
- WHEN `verificarUsuario.php` confirms exactly one matching recipient
- THEN the browser shows the notification
- AND the response includes the generated destination URL

#### Scenario: Non-recipient does not receive toast

- GIVEN the browser receives a Pusher event
- WHEN recipient verification does not find a valid match
- THEN the browser does not show the operational notification

### Requirement: Provide a notification inbox by user context

ASGARD SHALL list notifications according to the active `ASGARD_TYPE` session context.

#### Scenario: List internal notifications

- GIVEN an internal user session
- WHEN the user opens the notification menu
- THEN notifications are filtered for the internal user and `idtipousuario = 1`

#### Scenario: List customer notifications

- GIVEN a customer session
- WHEN the user opens the notification menu
- THEN notifications include customer-specific or customer-context rows according to the inspected logic

#### Scenario: List provider notifications

- GIVEN a provider session
- WHEN the user opens the notification menu
- THEN notifications are filtered by provider user and provider type mapping where available

### Requirement: Manage read state per recipient

ASGARD SHALL maintain read/unread state at recipient-notification level.

#### Scenario: Mark as read

- GIVEN a recipient notification row
- WHEN the user marks it as read
- THEN `push_notificacionusuarios.idestado` is set to `3`

#### Scenario: Mark as unread

- GIVEN a recipient notification row
- WHEN the user marks it as unread
- THEN `push_notificacionusuarios.idestado` is set to `1`

#### Scenario: Mark all as read

- GIVEN a user with multiple unread notifications
- WHEN the user marks all notifications as read
- THEN all applicable recipient rows are set to `3`

