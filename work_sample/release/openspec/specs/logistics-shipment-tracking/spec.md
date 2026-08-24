# Logistics Shipment Tracking AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures observed logistics tracking for customer orders and shipments, including dashboards, filters, EDP states, shipment creation, operator status, and links to document exchange/request flows.

## Requirements

### LST-REQ-001 - Customer Dashboard Data

The system shall load customer providers/operators and applicable EDP states when rendering logistics dashboards.

Evidence: `index_archivos/DashboardCBN.php:7-33`, `index_archivos/DashboardAlicorp.php:7-33`.

### LST-REQ-002 - Order Tracking Filters

The system shall allow filtering orders by order number, line, creation dates, EDP dates, time ranges, EDP states and provider.

Evidence: `index_archivos/DashboardCBN.php:169-275`.

### LST-REQ-003 - Shipment Tracking Filters

The system shall allow filtering shipments by shipment number, line, real ETA, theoretical ETA and transport modality.

Evidence: `index_archivos/DashboardCBN.php:330-380`.

### LST-REQ-004 - Shipment Creation

The system shall create logistics shipments in `logis_embarques` with customer, route, provider, modality, order, cargo, operator and exchange-related data when supplied.

Evidence: `index_archivos/logistica/CotizacionClass.php:423-471`, `.data_base/asgard.sql:12175-12210`.

### LST-REQ-005 - Shipment State Derivation

The system shall derive shipment state from EDP history when available, otherwise from finalization, documents, customs/request progress or advisory request progress.

Evidence: `index_archivos/logistica/EmbarqueClass.php:123-149`.

### LST-REQ-006 - Operator Quotation Lifecycle

The system shall track shipment operator quotation send, review, filled, accepted and confirmed states.

Evidence: `index_archivos/logistica/CotizacionClass.php:605-627`, `index_archivos/logistica/CotizacionClass.php:861-896`.

### LST-REQ-007 - Cross-Domain Navigation

The system shall navigate from a shipment-linked request to additional services or customs screens depending on whether a previous case exists.

Evidence: `index_archivos/asesoria-gestion/components/solicitud.js:274-315`.

## Candidate Risks

- Dashboard SQL and logistics SQL use direct interpolation of session/request values.
- Shipment state can be derived from several sources; precedence needs formalization.
- EDP state ids are partly hardcoded in dashboards.
