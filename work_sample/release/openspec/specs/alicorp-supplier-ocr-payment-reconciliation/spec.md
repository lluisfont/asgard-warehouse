# alicorp-supplier-ocr-payment-reconciliation

## Requirements

### Requirement: Reconcile supplier OCR payment

The system SHALL reconcile SENAVEX, FDAB and Jennefer OCR invoices against pending payments scoped by exchange context.

#### Scenario: Payment found

- GIVEN OCR amount matches a pending payment
- AND concept matches supplier/document
- AND invoice date is valid
- WHEN processed
- THEN payment and debit note are updated.

### Requirement: Mark transit closure on DIM match

The system SHALL mark `alicorp_cierre_transito=1` when OCR DIM matches ASGARD DIM.
