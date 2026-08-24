# alicorp-operational-document-package-dispatch

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirement: Select pending Alicorp document packages

ASGARD SHALL select eligible Alicorp cases with exchange documents that have not yet been marked as sent.

### Scenario: Pending case exists

- GIVEN a non-annulled case for customer `755` or `775`
- AND an exchange id on the related shipment
- AND `embarque_documentos_enviados` is null
- WHEN the document cron runs
- THEN ASGARD includes the case for package evaluation.

## Requirement: Generate and send configured document ZIPs

ASGARD SHALL package configured exchange documents into ZIP files and send them by email.

### Scenario: Documents configured and available

- GIVEN configured document ids for the case context
- WHEN matching exchange documents are found
- THEN ASGARD downloads them
- AND creates a ZIP
- AND sends the ZIP as an email attachment.

## Requirement: Mark sent folders

ASGARD SHALL mark sent folders after package dispatch.

### Scenario: Email dispatch attempted with packages

- GIVEN one or more generated packages
- WHEN ASGARD sends the email
- THEN ASGARD updates `dav_casos.embarque_documentos_enviados`.
