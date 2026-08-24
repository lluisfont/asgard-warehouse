# certification-expiry-control

Status: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Certification Document Registration

The system shall allow authorized users to register certification/control documents with expiry dates, alert settings, vehicle/merchandise attributes and attachments.

#### Scenario: Register document

- Given a user submits document metadata, alert settings and files
- When the document code is not duplicated or the document type allows duplication
- Then the system stores the document, files and merchandise records

### Requirement: Certification Document Editing

The system shall allow authorized users to edit certification/control documents and replace merchandise or attachments.

#### Scenario: Edit document

- Given an existing non-deleted document for the customer
- When updated metadata and optional files are submitted
- Then the system updates the document and refreshes merchandise and file records as applicable

### Requirement: Expiry State Calculation

The system shall calculate document state from expiry date, optional extended expiry date, alert threshold and alert unit.

#### Scenario: Document is within alert threshold

- Given a document has not expired
- And the difference to expiry is less than or equal to the alert threshold
- When the state is calculated
- Then the state is `POR VENCER`

#### Scenario: Document is expired

- Given the effective expiry date is before the current date
- When the state is calculated
- Then the state is `VENCIDO`

### Requirement: Expiry Notifications

The system shall notify configured customer recipients about non-current certification documents.

#### Scenario: Notify expiring or expired documents

- Given configured notification recipients
- And a customer has documents whose state is not `VIGENTE`
- When the notification process runs
- Then the system sends an email with document, code, expiry and merchandise details

### Requirement: Prior Authorization Control

The system shall provide a control view for prior authorizations by chassis, calculating expiry at 180 days from issue date.

#### Scenario: List prior authorizations by expiry filter

- Given AP records linked to non-cancelled, non-liquidated cases
- When a user filters by AP status
- Then the system returns AP document, chassis, case and day-difference data

## Traceability

- Business process: `.brownfield/work/release/business-analysis/processes/domains/certification-expiry-control`
- Use case: `.brownfield/work/release/business-analysis/use-cases/domains/certification-expiry-control/UC-001.md`
- Evidence map: `.brownfield/work/release/traceability/verification/certification-expiry-control-evidence-map.md`
