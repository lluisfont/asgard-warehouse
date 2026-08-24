# bulk-request-excel-import-validation Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Provide controlled Excel template

ASGARD SHALL provide a bulk request Excel template enriched with session-client master data.

#### Scenario: User downloads template

- GIVEN the user is authorized for bulk upload
- WHEN the user downloads the template
- THEN ASGARD returns an Excel file with validation lists for the required catalogs

### Requirement: Stage uploaded rows

ASGARD SHALL stage uploaded Excel rows before creating operational requests.

#### Scenario: Excel is uploaded

- GIVEN an Excel file is submitted
- WHEN ASGARD reads columns A-Y
- THEN ASGARD stores the rows in `dav_solicitudesprevias` scoped to client and user

### Requirement: Validate all rows

ASGARD SHALL validate each staged row against required master data.

#### Scenario: Validation runs

- GIVEN staged rows exist
- WHEN ASGARD validates the upload
- THEN each row receives converted ids, error flag and validation message

### Requirement: Create requests only if the lot has no blocking errors

ASGARD SHALL create operational previous requests only when every row is valid.

#### Scenario: No blocking errors

- GIVEN validation found zero blocking errors
- WHEN ASGARD commits the upload
- THEN ASGARD creates `dav_casosprevios` rows and related initial documents/tramites

#### Scenario: Blocking errors exist

- GIVEN at least one row has blocking errors
- WHEN ASGARD completes validation
- THEN ASGARD does not create any operational requests
