# Customs Document Approval AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures observed customs previous-document management: document creation, attachment handling, intermediate document conversion, approval/send markers, and notification of pending/new documents.

## Requirements

### CDA-REQ-001 - Previous Document Management

The system shall create and update previous documents with type, issuer, format, number, date, amount, currency and optional attachment.

Evidence: `index_archivos/documentacion.php:86-109`, `index_archivos/documentacion.php:292-323`.

### CDA-REQ-002 - Other Document Management

The system shall create and update other previous documents with description and optional attachment.

Evidence: `index_archivos/documentacion.php:118-165`.

### CDA-REQ-003 - Bulk Document Import

The system shall import previous documents through temporary staging and replace existing documents for the imported document types.

Evidence: `index_archivos/documentacion.php:234-271`.

### CDA-REQ-004 - Intermediate Document Conversion

The system shall convert intermediate case documents into previous documents and hide the intermediate record after conversion.

Evidence: `index_archivos/documentacionaprobado.php:196-241`.

### CDA-REQ-005 - Approval Pending List

The system shall list previous documents whose `aceptar` value is not `1` in approval flows.

Evidence: `index_archivos/documentacionaprobado.php:970-999`.

### CDA-REQ-006 - Mark Documents For Send

The system shall mark eligible documents as `aceptar = 4` before sending newly registered documents.

Evidence: `index_archivos/documentacionaprobado.php:316`, `index_archivos/documentacionaprobado.php:421-426`.

### CDA-REQ-007 - Send Other Documents

The system shall notify other pending documents and mark them as sent with state `1`.

Evidence: `index_archivos/documentacionaprobado.php:442-476`, `index_archivos/finsolicitud.php:375`.

## Candidate Risks

- File upload validation and path handling need review.
- SQL uses interpolated request values.
- Meaning of `aceptar`/`estado` codes must be formalized.
