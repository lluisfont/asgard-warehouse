# alicorp-transit-deadline-control Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for Alicorp transit-deadline monitoring, automatic deadline seeding and transit-closure display.

## Requirements

### Requirement: Scope Alicorp Cases

The system SHALL scope the observed transit control to client `775`.

#### Scenario: Query Alicorp control

- GIVEN the user submits date filters
- WHEN the control query runs
- THEN the system SHALL include cases where `dav_casos.idcliente = 775`.

### Requirement: Seed Missing Transit Deadline

The system SHALL populate missing Alicorp transit deadline dates from DEX validation date.

#### Scenario: Deadline missing

- GIVEN an Alicorp case has `alicorp_vencimiento IS NULL`
- AND has a DEX validation date
- WHEN the control query runs for the case invoice date range
- THEN the system SHALL set `alicorp_vencimiento` to `fechavalidaciondui + 60 days`.

### Requirement: Flag Near Deadline

The system SHALL identify cases near transit deadline.

#### Scenario: Case has no DEX exit

- GIVEN an Alicorp case has no `fechapasesalida`
- AND `alicorp_vencimiento` is five days or less from the current date
- WHEN the control result is calculated
- THEN the system SHALL mark the case with the observed deadline-alert flag.

### Requirement: Show Transit Closure

The system SHALL display whether transit closure has been paid/closed according to the observed Alicorp flag.

#### Scenario: Closure flag set

- GIVEN `alicorp_cierre_transito = 1`
- WHEN the control result is displayed
- THEN the system SHALL show `PAGADO`.

### Requirement: Include Client-Cancelled Invoices

The system SHALL include client-cancelled invoices as informational rows.

#### Scenario: Cancelled invoice in range

- GIVEN a row exists in `dav_clientefacturaanulada` for client `775`
- AND the row date is in the query range
- WHEN the control result is generated
- THEN the system SHALL include the invoice with cancellation indicator.
