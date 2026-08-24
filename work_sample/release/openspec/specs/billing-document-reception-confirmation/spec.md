# billing-document-reception-confirmation Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: List sent documents pending reception

ASGARD SHALL list sent billing/support documents that do not yet have reception confirmation.

#### Scenario: User opens sent tab

- GIVEN the user opens Recepcion de Planillas
- WHEN ASGARD loads Enviadas
- THEN Planilla/Factura, Nota de Cobranza and Cite documents with sent markers and without reception markers are displayed

### Requirement: Confirm individual reception

ASGARD SHALL allow reception confirmation for a single pending document.

#### Scenario: User clicks Recibido

- GIVEN a pending document is displayed
- WHEN the user confirms it as received
- THEN ASGARD updates the reception marker and reception timestamp for that document according to its document type

### Requirement: Confirm bulk reception

ASGARD SHALL allow reception confirmation for multiple selected pending documents.

#### Scenario: User receives marked documents

- GIVEN one or more pending documents are selected
- WHEN the user confirms Recibir Marcadas
- THEN ASGARD applies the corresponding reception update to each selected document

### Requirement: List received documents

ASGARD SHALL list documents already marked as received.

#### Scenario: User opens received tab

- GIVEN documents have reception markers
- WHEN ASGARD loads Recepcionadas
- THEN the received documents are displayed with their operational context and document type

### Requirement: Preserve document-type persistence rules

ASGARD SHALL persist reception using the table and fields that correspond to each document family.

#### Scenario: Document type determines update target

- GIVEN the document type is Planilla or Factura
- WHEN reception is confirmed
- THEN `dav_facturaplanilla.recepcionplanilla` and `fecharecepcionplanilla` are updated
- AND GIVEN the document type is Nota de Cobranza
- THEN `dav_notasdebito.estado_recepcionado` and `fecha_recepcionado` are updated
- AND GIVEN the document type is Cite
- THEN `dav_cite.fecharecepcion` is updated
