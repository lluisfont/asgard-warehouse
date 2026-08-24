# Customs Request Intake AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures the observed request intake behavior for customs/support/vehicle requests, including import staging, master-data validation, previous-case creation, submission and notification.

## Requirements

### CRI-REQ-001 - Import File Storage

The system shall store uploaded request files under a per-user folder with a generated filename prefix.

Evidence: `index_archivos/controllers/SolicitudClass.php:14-35`.

### CRI-REQ-002 - Request Staging

The system shall replace previous staged rows for the same customer and user before inserting imported request rows.

Evidence: `index_archivos/controllers/SolicitudClass.php:90-118`.

### CRI-REQ-003 - Master Data Validation

The system shall resolve request text fields to master-data identifiers and record validation errors per staged row.

Evidence: `index_archivos/controllers/SolicitudClass.php:122-465`.

### CRI-REQ-004 - Previous Case Creation

The system shall create `dav_casosprevios` from a valid request row.

Evidence: `index_archivos/controllers/SolicitudClass.php:482-496`.

### CRI-REQ-005 - Initial Documents And Procedures

The system shall create initial previous documents and procedures when the request conditions require them.

Evidence: `index_archivos/controllers/SolicitudClass.php:502-509`.

### CRI-REQ-006 - Submission Finalization

The system shall update finalization or approval dates when the request is sent.

Evidence: `index_archivos/enviarsolicitud_ajax.php:107-114`.

### CRI-REQ-007 - Notifications

The system shall notify configured recipients when a request is finalized.

Evidence: `index_archivos/finsolicitud.php:392-515`.

## Candidate Risks

- SQL interpolation is widespread in intake and submission flows.
- Uploaded file validation should be reviewed beyond `basename` and hash prefix.
- Master-data matching by display text may be fragile when names differ by spelling, accents or case.
