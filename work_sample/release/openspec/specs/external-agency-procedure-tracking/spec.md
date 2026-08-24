# external-agency-procedure-tracking Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Build dynamic stage report

ASGARD SHALL build the procedure report columns from configured external-agency stages.

#### Scenario: Stage has state

- GIVEN a configured stage with `tieneestado=1`
- WHEN the report is generated
- THEN ASGARD includes both stage date and stage state columns

### Requirement: Filter by current stage

ASGARD SHALL filter procedures by selected current stage using observed stage-date progression.

#### Scenario: Procedure at selected stage

- GIVEN a selected stage
- AND the procedure has a date for that stage
- AND the following stage has no date
- WHEN the report is generated
- THEN the procedure is included

### Requirement: Maintain procedure metadata

ASGARD SHALL store agency, agency procedure and procedure type for a case/request procedure.

#### Scenario: New procedure

- GIVEN a case/request and selected procedure metadata
- WHEN the user saves
- THEN ASGARD creates a procedure record

