# third-party-token-document-onboarding

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirement: Relate third party to customer

ASGARD SHALL maintain customer-specific third-party relationships for operational agents and managers.

### Scenario: New third party created

- GIVEN a customer user submits third-party data
- WHEN ASGARD creates the third-party record
- THEN ASGARD also creates the customer-third-party relation
- AND stores provided contacts.

## Requirement: Invite third party by token

ASGARD SHALL generate or store a token when requesting third-party data completion by email.

### Scenario: Invitation email sent

- GIVEN an existing third-party record with contacts
- WHEN the user sends a completion request
- THEN ASGARD stores token, mail count and mail date
- AND sends a form link containing the token.

## Requirement: Store third-party documents

ASGARD SHALL store third-party documents with description and document type in the family-specific table.

### Scenario: Document uploaded

- GIVEN a valid third-party context
- WHEN a document is uploaded
- THEN ASGARD copies the file into the third-party folder
- AND inserts the document metadata row.
