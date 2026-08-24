# customs-dex-ocr-validation-update

## Purpose

Document the as-is DEX OCR validation and update behavior observed in ASGARD.

## Requirements

### Requirement: Resolve DEX context from exchange

The system SHALL resolve the customs request related to a DEX OCR read from `exchange_id`.

#### Scenario: Exchange belongs to shipment

- GIVEN `logis_embarques.idExchange` matches the input
- WHEN DEX OCR runs
- THEN related `dav_casosprevios` records are selected through the shipment.

#### Scenario: Exchange belongs to customs request

- GIVEN no shipment is found
- AND `dav_casosprevios.idExchange` matches the input
- WHEN DEX OCR runs
- THEN that request is used for validation.

### Requirement: Update customs declaration fields only for matching folder

The system SHALL update DUI/Sidunea fields only when the OCR folder matches the ASGARD folder.

#### Scenario: Folder matches

- GIVEN OCR field `carpeta` equals `dav_casos.carpeta`
- WHEN declaration, Sidunea or acceptance date fields are present
- THEN `dav_casos` is updated with the available values.

#### Scenario: Folder differs

- GIVEN OCR field `carpeta` differs from ASGARD
- WHEN OCR response is processed
- THEN the response indicates the DEX does not belong to the folder.

### Requirement: Return comparison observations

The system SHALL compare DEX OCR fields against ASGARD values and return differences for review.

#### Scenario: Field differs

- GIVEN OCR has a field that differs from ASGARD
- WHEN the response is built
- THEN the field label is added to the update message.
