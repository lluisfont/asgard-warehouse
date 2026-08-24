# customer-shipment-rating-feedback

## Requirements

### Requirement: Store customer rating

The system SHALL store customer rating and comment for the given shipment/request/case context.

#### Scenario: Rating submitted

- GIVEN customer submits rating data
- WHEN the request is processed
- THEN `dav_rating` is inserted with `tipo_usuario='CLIENTE'`.

### Requirement: Show monthly rating prompt

The system SHALL allow the prompt when no rating exists or 30 days have passed since the latest rating.
