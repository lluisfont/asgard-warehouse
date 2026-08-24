# export-mic-dex-physical-reception-control Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for MIC/DEX physical document reception control in export operations.

## Requirements

### Requirement: Query MIC DEX Records

The system SHALL list MIC/DEX records with verification departure date and support filtering by operational fields.

#### Scenario: Generate report

- GIVEN filter values are submitted
- WHEN the report is generated
- THEN the system SHALL return matching `dex_suma` records
- AND SHALL derive each document state from date fields.

### Requirement: Enforce Same-State Bulk Selection

The system SHALL allow bulk marking only for records sharing the same current state.

#### Scenario: Mixed states selected

- GIVEN a record is selected
- WHEN other records have different state
- THEN those other records SHALL be disabled for the same bulk action.

### Requirement: Mark Or Revert State

The system SHALL update the appropriate state date when records are marked or reverted.

#### Scenario: Mark records

- GIVEN selected records share a state
- WHEN the user marks records
- THEN the system SHALL update the corresponding date field
- AND SHALL insert history rows.

#### Scenario: Revert records

- GIVEN selected records share a state
- WHEN the user confirms revert
- THEN the system SHALL clear the corresponding date field
- AND SHALL insert history rows.

### Requirement: Show History

The system SHALL show state change history for a MIC/DEX record.

#### Scenario: Open history

- GIVEN a MIC/DEX record exists
- WHEN the user opens history
- THEN the system SHALL show document header and history rows with date, state and user.

