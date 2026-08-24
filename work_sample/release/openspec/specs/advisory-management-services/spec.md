# Advisory Management Services AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures observed advisory/support service management: additional service request creation, procedure assignment, state-board navigation, document-exchange integration, mass customs-management creation, and operational reporting.

## Requirements

### AMS-REQ-001 - State Board

The system shall display advisory/service requests grouped by operational states: pending, sent, received, assigned, in review, in process and finalized.

Evidence: `index_archivos/asesoria-gestion/components/tbl-estados.js:17-79`.

### AMS-REQ-002 - Request Creation

The system shall allow creating a request with requester, email, city, line, notes, shipment id, previous-case id and optional exchange id.

Evidence: `index_archivos/asesoria-gestion/components/solicitud.js:1-260`, `.data_base/asgard.sql:388-436`.

### AMS-REQ-003 - Procedure Management

The system shall allow adding/editing/removing procedures with issuing entity, procedure and procedure type.

Evidence: `index_archivos/asesoria-gestion/components/tramite.js:1-130`, `index_archivos/asesoria-gestion/components/tbl-estados.js:155-220`.

### AMS-REQ-004 - Document Exchange Link

The system shall link an existing document exchange or create a new one for an additional-service request.

Evidence: `index_archivos/asesoria-gestion/components/tbl-estados.js:220-260`, `index_archivos/asesoria-gestion/components/solicitud.js:150-260`.

### AMS-REQ-005 - Massive Customs Management Creation

The system shall create previous cases and default previous documents when creating customs/support management records from mass or logistics flows.

Evidence: `index_archivos/controllers/SolicitudClass.php:481-520`, `index_archivos/logistica/SolicitudesClass.php:714-850`.

### AMS-REQ-006 - Operational Report

The system shall provide a general advisory-management report with filters and export covering lifecycle dates, responsible users, related shipment/case, cost and category data.

Evidence: `index_archivos/operativos/asesoria-gestion.php:67-212`.

## Candidate Risks

- Backend endpoints behind the Vue components must be located and reconstructed in a later API pass.
- State values must be reconciled with `ages_estados` and report mappings.
- SQL creation paths use interpolated runtime values in observed legacy PHP code.
