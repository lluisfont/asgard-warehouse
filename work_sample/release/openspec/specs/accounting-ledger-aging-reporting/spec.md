# accounting-ledger-aging-reporting Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Maintain monthly aging amounts

ASGARD SHALL allow monthly aging amounts to be inserted or updated by month and year.

#### Scenario: Existing aging amount

- GIVEN a submitted month and year with an existing record
- WHEN the user saves the matrix
- THEN ASGARD updates the amount

#### Scenario: New aging amount

- GIVEN a submitted month and year without an existing record
- WHEN the user saves the matrix
- THEN ASGARD inserts the amount

### Requirement: Generate accounting concept report

ASGARD SHALL generate accounting concept rows split by invoice and planilla.

#### Scenario: Active invoice-planilla

- GIVEN active invoice/planilla rows for non-cancelled cases
- WHEN the report is generated
- THEN ASGARD returns financial concepts grouped by case/document

### Requirement: Generate purchase ledger

ASGARD SHALL generate purchase-ledger rows with fiscal base and tax credit.

#### Scenario: Purchase ledger row

- GIVEN reportable invoice/payment data
- WHEN the purchase ledger is generated
- THEN ASGARD includes invoice/DIM identifiers, purchase amount, taxable base and fiscal credit

