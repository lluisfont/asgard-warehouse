# alicorp-albo-ocr-payment-reconciliation

## Purpose

Document the as-is OCR-driven ALBO/FALBO payment reconciliation for Alicorp operations.

## Requirements

### Requirement: Read ALBO/FALBO invoice by OCR

The system SHALL process PDF invoices directly and ZIP/RAR packages through OCR model `MODELO_FACTUTA_ALBO`.

#### Scenario: PDF invoice

- GIVEN a PDF file
- WHEN OCR is requested
- THEN the file is sent directly to the OCR model.

#### Scenario: Compressed package

- GIVEN a ZIP or RAR file
- WHEN OCR is requested
- THEN files are extracted and PDFs are processed.

### Requirement: Resolve payment context from exchange

The system SHALL resolve the business context from `exchange_id`.

#### Scenario: Shipment exchange

- GIVEN `logis_embarques.idExchange` matches
- WHEN searching payment
- THEN payments are scoped by cases belonging to that shipment.

#### Scenario: Customs or AGES exchange

- GIVEN no shipment match exists
- WHEN searching payment
- THEN ASGARD tries `dav_casosprevios.idExchange` and AGES `exchange_id`.

### Requirement: Reconcile pending payment

The system SHALL update a pending concept `272` payment when OCR amount and date are valid.

#### Scenario: Payment found

- GIVEN a payment with empty `nro`
- AND concept `272`
- AND amount equals OCR total
- AND OCR date can be converted
- WHEN OCR response is processed
- THEN payment and debit note are updated with invoice number and date.

### Requirement: Mark transit closure on matching DIM

The system SHALL mark Alicorp transit closure when OCR DIM matches the ASGARD DIM.

#### Scenario: DIM matches

- GIVEN OCR `dim` equals `DS-gestiondui-aduanaCodigo-nodui`
- WHEN OCR response is processed
- THEN `dav_casos.alicorp_cierre_transito` is set to `1`.
