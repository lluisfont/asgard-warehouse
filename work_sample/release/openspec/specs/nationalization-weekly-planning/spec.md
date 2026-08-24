# nationalization-weekly-planning Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: List planned chassis

ASGARD SHALL list chassis with nationalization planning data.

#### Scenario: User opens planning page

- GIVEN the user opens nationalization planning
- WHEN the page initializes
- THEN ASGARD requests `lista-chasis` and displays planning rows

### Requirement: Upload weekly planning

ASGARD SHALL upload a selected planning file to the planning API.

#### Scenario: File selected

- GIVEN the user selected a file
- WHEN the user uploads the planning
- THEN ASGARD sends the file to `cargar-planificacion`
- AND displays returned chassis rows

### Requirement: Warn about nationalized chassis

ASGARD SHALL warn when uploaded planning includes already-nationalized chassis.

#### Scenario: API returns nationalized chassis

- GIVEN the upload response includes `chasis_nacionalizados`
- WHEN ASGARD processes the response
- THEN it shows a modal with chassis, partida, validation date and payment date

### Requirement: Confirm planning

ASGARD SHALL confirm the currently loaded planning list.

#### Scenario: User confirms planning

- GIVEN a loaded planning list
- WHEN the user confirms
- THEN ASGARD sends the list to `confirmar-planificacion`
- AND reloads the chassis list on success
