# vehicle-invoice-data-bulk-update Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for bulk updating vehicle invoice data from an Excel file in the logistics customs-management context.

## Requirements

### Requirement: Generate Update Template

The system SHALL generate an Excel template for the selected shipment.

#### Scenario: User downloads template

- GIVEN a shipment id
- WHEN the user requests the update template
- THEN the system SHALL include vehicle rows linked to the shipment
- AND SHALL include partida, chasis, invoice date, invoice number, invoice amount and declaration type.

### Requirement: Block SUMA/DAM Rows

The system SHALL prevent updates for vehicle rows that already have SUMA/DAM evidence.

#### Scenario: Row has SUMA/DAM

- GIVEN the current row has a non-empty SUMA/DAM identifier
- WHEN an update Excel is processed
- THEN the system SHALL NOT update that row from this flow
- AND SHALL show a blocking observation.

### Requirement: Update Invoice Fields

The system SHALL update eligible vehicle invoice fields by matching chasis.

#### Scenario: Matching eligible chasis

- GIVEN a current vehicle row without SUMA/DAM
- AND the uploaded Excel contains the same chasis
- WHEN the update is processed
- THEN the system SHALL update invoice date and invoice number in the related vehicle, commercial invoice, document and DAM invoice records.

### Requirement: Recalculate Customs Values

The system SHALL recalculate customs financial values when a positive invoice amount is supplied.

#### Scenario: Positive invoice amount

- GIVEN the uploaded amount is greater than zero
- WHEN the matching vehicle row is updated
- THEN the system SHALL update FOB
- AND SHALL recalculate freight, insurance, total costs, CIF and CIF Bs using vehicle parameters
- AND SHALL propagate derived values to DAV, partidas, DAM items and DAM invoice.

### Requirement: Update Declaration Type

The system SHALL update the customs case declaration type from the Excel file.

#### Scenario: Declaration type supplied

- GIVEN the uploaded Excel contains declaration type id
- WHEN the matching vehicle row is processed
- THEN the system SHALL update `dav_casos.idtipodeclaracion` for the related customs case.
