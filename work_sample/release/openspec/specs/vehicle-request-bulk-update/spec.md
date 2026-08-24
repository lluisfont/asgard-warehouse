# vehicle-request-bulk-update Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Stage vehicle updates from Excel

ASGARD SHALL stage vehicle update rows from an uploaded Excel file before applying changes.

#### Scenario: Excel uploaded

- GIVEN the user selected an update mode and Excel file
- WHEN ASGARD processes the file
- THEN it stores rows under an `idcargado`
- AND returns a preview with messages per row

### Requirement: Block confirmation when row errors exist

ASGARD SHALL prevent confirmation when the upload contains validation errors.

#### Scenario: Chassis does not exist

- GIVEN an uploaded row has a chassis not found in active vehicle case folders
- WHEN the preview is returned
- THEN the row contains an error message
- AND confirmation is disabled

### Requirement: Apply updates by selected mode

ASGARD SHALL apply only the field groups selected by `camposmodificar`.

#### Scenario: FOB/freight mode

- GIVEN `camposmodificar=1`
- WHEN the user confirms
- THEN ASGARD updates report FOB and maritime freight values for valid rows

### Requirement: Export applied-change history

ASGARD SHALL export applied vehicle modifications for the current user.

#### Scenario: User opens history

- GIVEN applied rows exist for the user
- WHEN the history export is requested
- THEN ASGARD generates an Excel file with values, date, user and message
