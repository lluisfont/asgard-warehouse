# vehicle-cost-accounting-reporting Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Generate vehicle expense report

ASGARD SHALL generate a vehicle expense report for non-cancelled cases owned by the session customer.

#### Scenario: Expense report by date

- GIVEN a city, date range and date-filter type
- WHEN the report is generated
- THEN ASGARD returns vehicle, customs, tax, reintegration and invoice components

### Requirement: Generate vehicle logistics cost report

ASGARD SHALL calculate logistics cost per vehicle using customs, transport, invoice and warehouse data.

#### Scenario: Cost components

- GIVEN a reportable vehicle case
- WHEN logistics costs are calculated
- THEN ASGARD includes FOB, freight, insurance, ASPB, taxes, DUI, AP, invoiced services, warehouse and PRM/KPO values
- AND calculates component percentages and total cost factor

### Requirement: Generate ZDAM accounting output

ASGARD SHALL generate ZDAM report data through the observed stored procedure.

#### Scenario: ZDAM generation

- GIVEN filters for reportable nationalized vehicle cases
- WHEN ZDAM report is requested
- THEN ASGARD calls `sp_reportezdam`
- AND returns rows from `tmp_reportezdam`

