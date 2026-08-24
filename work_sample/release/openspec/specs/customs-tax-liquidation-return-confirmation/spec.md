# customs-tax-liquidation-return-confirmation Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for confirming tax-liquidation return and notifying that tax payment may proceed.

## Requirements

### Requirement: Gate Return Confirmation

The system SHALL show return confirmation only when liquidation was sent and has not returned.

#### Scenario: Confirmation pending

- GIVEN `fechaenvioliquidacion` is present
- AND `fecharetornoliquidacion` is empty
- WHEN the detail page is rendered
- THEN the system SHALL show the Confirm action.

### Requirement: Notify Recipients

The system SHALL notify configured return-liquidation recipients by client and city.

#### Scenario: Confirm action submitted

- GIVEN the user submits confirmation
- WHEN recipients exist in `dav_retornomailsliquidacion`
- THEN the system SHALL send an email using those addresses.

### Requirement: Mark Return Date

The system SHALL mark the case return date after successful mail send.

#### Scenario: Mail send does not report error

- GIVEN the confirmation email was sent without an observed error response
- WHEN confirmation completes
- THEN the system SHALL update `dav_casos.fecharetornoliquidacion` with current timestamp.

### Requirement: Present Liquidation Detail

The system SHALL present liquidation detail by item and export it to Excel.

#### Scenario: User opens detail

- GIVEN a case id
- WHEN the detail page loads
- THEN the system SHALL run the observed liquidation query/procedure
- AND SHALL render item-level fiscal values and totals.
