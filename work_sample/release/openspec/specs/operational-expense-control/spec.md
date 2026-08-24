# operational-expense-control Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Generate dynamic expense report

ASGARD SHALL generate expense reports with dynamic columns for invoice and planilla concepts.

#### Scenario: Concepts found

- GIVEN report filters with matching invoice and planilla concepts
- WHEN the expense report is generated
- THEN ASGARD creates columns for each observed concept
- AND calculates totals by concept and document origin

### Requirement: Show expense detail by case

ASGARD SHALL show expense detail split by planilla and invoice.

#### Scenario: Case detail

- GIVEN a case id
- WHEN expense detail is requested
- THEN ASGARD lists concept and amount rows by origin

### Requirement: Report expense by item

ASGARD SHALL report item-level expenses and derived unit prices.

#### Scenario: Item expense

- GIVEN API report rows with total expense, invoice value and quantity
- WHEN the UI displays the report
- THEN it shows unit expense and warehouse price candidates

