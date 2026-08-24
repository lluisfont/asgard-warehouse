# vehicle-transitory-depot-compliance Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Calculate transitory-depot tax timing

ASGARD SHALL calculate tax-payment timing for vehicle customs cases using ASGARD case data and Atlantes depot movement dates.

#### Scenario: Payment before threshold

- GIVEN a vehicle case with depot entry date and tax payment date
- WHEN the difference is less than 60 days
- THEN payment timing is `ANTES DE 60`

#### Scenario: Payment after threshold

- GIVEN a vehicle case with depot entry date and tax payment date
- WHEN the difference is greater than or equal to 60 days
- THEN payment timing is `DESPUES DE 60`

### Requirement: Calculate deferred days and amount

ASGARD SHALL calculate deferred days when transitory-depot permanence exceeds 60 days.

#### Scenario: Deferred days exist

- GIVEN permanence days greater than 60
- WHEN the report is generated
- THEN deferred days equal permanence days minus 60
- AND deferred amount candidate equals expected taxes

### Requirement: Consolidate depot inventory evidence

ASGARD SHALL consolidate vehicle inventory evidence from port and transitory-depot stages.

#### Scenario: Vehicle inventory evidence

- GIVEN a vehicle chasis
- WHEN the depot report is generated
- THEN the report includes accessory, damage, contamination and mileage indicators for port and transitory depot

### Requirement: Include shipment and trip context

ASGARD SHALL include shipment/trip context when available.

#### Scenario: Trip assigned

- GIVEN a vehicle shipment with trip assignment
- WHEN the depot report is generated
- THEN the report includes trip number, trip creation date, assigned driver and trip state

