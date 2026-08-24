# billing-invoice-planilla-document-generation Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Generate combined invoice-planilla PDF

ASGARD SHALL generate a combined Planilla de Despacho and Factura PDF for a given `idfacturaplanilla`.

#### Scenario: User requests combined document

- GIVEN a valid `idfacturaplanilla`
- WHEN the user opens Factura & Planilla
- THEN ASGARD composes planilla and factura pages
- AND downloads a PDF named with carpeta, `PF` and invoice number

### Requirement: Calculate document amounts

ASGARD SHALL calculate invoice and planilla amounts from their corresponding detail tables.

#### Scenario: Amounts are displayed

- GIVEN invoice detail rows exist
- WHEN ASGARD generates the document
- THEN invoice total is the sum of `dav_facturasdetalle.monto`
- AND planilla total is derived from applicable `dav_pagosdetalle.monto`

### Requirement: Include fiscal control data

ASGARD SHALL include fiscal authorization, control code, QR and emission deadline when generating the invoice page.

#### Scenario: Fiscal data is available

- GIVEN dosificacion data is linked
- WHEN ASGARD generates the invoice
- THEN the invoice includes authorization number, control code, QR and emission deadline

### Requirement: Download individual invoice PDF

ASGARD SHALL provide an individual invoice PDF when the file contract can be resolved.

#### Scenario: User opens Ver Factura

- GIVEN an invoice PDF exists or a membretado file exists
- WHEN the user requests invoice download
- THEN ASGARD returns the invoice PDF inline

### Requirement: Download individual planilla PDF

ASGARD SHALL provide an individual planilla PDF when the file contract can be resolved.

#### Scenario: User opens Ver Planilla

- GIVEN a planilla PDF exists
- WHEN the user requests planilla download
- THEN ASGARD returns the planilla PDF inline
