# Document Exchange OCR AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures the observed document exchange behavior, participant construction, exchange linkage, OCR execution, and document consistency checks.

## Requirements

### DEO-REQ-001 - Template Selection

The system shall select a document exchange template based on operational module and transaction attributes.

Evidence: `index_archivos/intercambioDocumental/ajax/iniciarIntercambio.php:15-116`, `index_archivos/intercambioDocumental/IntercambioDocumentalClass.php:8-29`.

### DEO-REQ-002 - Participant Construction

The system shall construct exchange participants from customer, provider, logistics operator, insurance agent, dispatcher, customs agent, and transport contacts when required by the selected template.

Evidence: `index_archivos/intercambioDocumental/ajax/iniciarIntercambio.php:118-253`, `index_archivos/intercambioDocumental/IntercambioDocumentalClass.php:38-63`.

### DEO-REQ-003 - Exchange Linkage

The system shall link an exchange id to a shipment or order when the exchange belongs to that object.

Evidence: `index_archivos/intercambioDocumental/IntercambioDocumentalClass.php:290-305`.

### DEO-REQ-004 - OCR Processing

The system shall call the configured OCR model for documents that require automated reading.

Evidence: `index_archivos/intercambioDocumental/ajax/OCRClass.php:108-206`.

### DEO-REQ-005 - IASA Contract Before Invoice

For the observed IASA flow, the transport invoice validation shall require a loaded contract document first.

Evidence: `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:19-39`, `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:133-145`.

### DEO-REQ-006 - Invoice Contract Consistency

For the observed IASA flow, the system shall compare invoice price, quantity/weight, and freight total against contract values.

Evidence: `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:219-252`.

### DEO-REQ-007 - Packing List Before SCP

For the observed IASA flow, the SCP report validation shall require a loaded packing list first.

Evidence: `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:444-461`.

### DEO-REQ-008 - SCP Packing List Consistency

For the observed IASA flow, the system shall compare vehicle plate data between the SCP report and the packing list by order.

Evidence: `index_archivos/intercambioDocumental/ajax/lectura_documentos_iasa.php:488-560`.

## Candidate Risks

- SQL interpolation is present in exchange and OCR flows; safety depends on escaping/binding in surrounding code.
- OCR credentials and model constants must be verified in runtime configuration.
- File paths and uploaded document names require validation of storage and access controls.
- Document ids are hardcoded UUIDs; the governing catalog should be reconstructed.
