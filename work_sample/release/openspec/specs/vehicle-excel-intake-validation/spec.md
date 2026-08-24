# vehicle-excel-intake-validation Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for uploading, completing and validating vehicle data from Excel files.

## Requirements

### Requirement: Upload Vehicle Excel

The system SHALL store the uploaded vehicle Excel file and associate it with the customs previous request.

#### Scenario: Main upload

- GIVEN a previous request id
- AND a vehicle Excel file
- WHEN the user uploads the file
- THEN the system SHALL copy the file to the customer vehicle-load folder
- AND SHALL update the corresponding file/date fields in `dav_casosprevios`.

### Requirement: Persist Vehicle Rows

The system SHALL persist vehicle rows from Excel.

#### Scenario: Rows parsed

- GIVEN the Excel contains vehicle rows
- WHEN the upload is processed
- THEN the system SHALL create or update `dav_vehiculosprevios` rows for the request.

### Requirement: Validate Vehicle Data

The system SHALL validate vehicle rows against required fields and catalogs.

#### Scenario: Invalid row

- GIVEN a vehicle row misses required data or conflicts with catalogs
- WHEN validation runs
- THEN the system SHALL set `dav_vehiculosprevios.error`
- AND SHALL store a business-readable `mensajeerror`.

### Requirement: Complete Existing Vehicles

The system SHALL allow completing selected fields of existing vehicles by chasis.

#### Scenario: Completion upload

- GIVEN a completion Excel contains a chasis already linked to the request
- WHEN the completion file is processed
- THEN the system SHALL update missing/complementary fields for that chasis
- AND SHALL mark the row as complemented.

### Requirement: Mark Complete Information

The system SHALL mark vehicle information as complete after successful validation.

#### Scenario: No blocking errors

- GIVEN vehicle rows have no active blocking errors
- WHEN the user completes the flow
- THEN the system SHALL set `fecha_info_completa` for accepted vehicles.
