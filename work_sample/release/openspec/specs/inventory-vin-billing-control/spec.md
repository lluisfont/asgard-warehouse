# inventory-vin-billing-control Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Calculate billing period

ASGARD SHALL calculate VIN billing periods from day 21 to day 20 of the following month.

#### Scenario: Default period

- GIVEN the user opens the page
- WHEN ASGARD initializes dates
- THEN fechaInicio is the 21st of the previous applicable month
- AND fechaFin is the 20th of the following month

### Requirement: Precalculate billable VINs

ASGARD SHALL request a billing precalculation for the selected period.

#### Scenario: Precalculation succeeds

- GIVEN fechaInicio and fechaFin
- WHEN the user generates precalculation
- THEN ASGARD displays international, national/local, unique and billable VIN counts

### Requirement: Confirm VIN billing

ASGARD SHALL confirm VIN billing for the selected period.

#### Scenario: User confirms

- GIVEN precalculation was displayed
- WHEN the user confirms billing
- THEN ASGARD sends the period to the confirmation endpoint

### Requirement: Export billing detail

ASGARD SHALL export period billing detail as an Excel file.

#### Scenario: Period has id

- GIVEN a listed billing period has an id
- WHEN the user requests Excel
- THEN ASGARD downloads the base64 Excel response if valid
