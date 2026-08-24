# vehicle-chassis-timeline-trace Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Search vehicle by chassis

ASGARD SHALL validate a vehicle chassis before rendering its inventory timeline.

#### Scenario: Chassis exists

- GIVEN a user enters a chassis
- WHEN ASGARD calls the chassis search endpoint successfully
- THEN ASGARD displays the vehicle timeline area

### Requirement: Render inventory timeline

ASGARD SHALL render the six observed inventory milestones for the chassis.

#### Scenario: Milestone has records

- GIVEN the API returns records for a milestone
- WHEN the user selects that milestone
- THEN ASGARD shows responsible user, date/time, location, code, signer and available actions

### Requirement: Highlight damage and latest record

ASGARD SHALL visually flag milestones with damage and the latest inventory record.

#### Scenario: Damage exists

- GIVEN a milestone record has `cantidad_con_desperfecto > 0`
- WHEN the timeline is built
- THEN ASGARD marks that milestone with a damage warning

### Requirement: Show inventory evidence

ASGARD SHALL show detail evidence for accessories, damage and contamination, including photos when present.

#### Scenario: Photo exists

- GIVEN an evidence row has an image filename
- WHEN the user requests the photo
- THEN ASGARD downloads it from `file/download` and shows it in a modal
