# customs-guarantee-tax-control Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Calculate guarantee usage by month

ASGARD SHALL calculate monthly guarantee usage for the session customer, selected year and declaration type.

#### Scenario: Monthly summary

- GIVEN non-cancelled customs cases with sent DAM date
- WHEN the guarantee report is generated
- THEN ASGARD reports units with DAM, extracted units, paid taxes, guarantee amount in use and extraction percentage

### Requirement: Calculate available guarantee amount

ASGARD SHALL calculate available guarantee amount from active guarantee-document records and current usage.

#### Scenario: Available amount

- GIVEN active document records of observed type `4`
- AND guarantee amount in use
- WHEN the report is generated
- THEN available amount equals total guarantee amount minus amount in use

### Requirement: Classify operational guarantee exposure

ASGARD SHALL classify units into operational exposure categories.

#### Scenario: Unit without nationalization within threshold

- GIVEN a unit without exit/canal assignment
- AND a document date not older than 90 days
- WHEN the operational summary is generated
- THEN the unit is classified as without nationalization

#### Scenario: Unit without nationalization over threshold

- GIVEN a unit without exit/canal assignment
- AND a document date older than 90 days
- WHEN the operational summary is generated
- THEN the unit is classified as without nationalization over threshold

### Requirement: Reconcile tax differences

ASGARD SHALL report tax differences between required/received funds, paid taxes and returns/replacements.

#### Scenario: Difference favors a party

- GIVEN a customs case with received funds and paid taxes
- WHEN the tax report is generated
- THEN ASGARD calculates the difference
- AND reports whether the difference favors the customer or the agency

