# operational-case-dossier-access Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Search operational dossiers

ASGARD SHALL allow operational dossiers to be searched by multiple business identifiers.

#### Scenario: Search by business identifier

- GIVEN a search criterion such as order, DIM, folder, date, lot/chassis or purchase order
- WHEN the user generates the report
- THEN ASGARD returns non-cancelled matching cases for the session customer

### Requirement: Enforce document visibility

ASGARD SHALL apply user-type visibility rules before exposing dossier documents.

#### Scenario: Restricted user

- GIVEN a user type with restricted access
- WHEN documents are listed
- THEN only documents matching the observed prefix/type rules are shown

### Requirement: Provide dispatch summary data

ASGARD SHALL provide dispatch summary data for matching cases.

#### Scenario: Dispatch report

- GIVEN matching cases and partidas
- WHEN dispatch data report is generated
- THEN ASGARD returns weights, FOB, CIF, customs office and transport mode

