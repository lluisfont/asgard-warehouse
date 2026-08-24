# Transport Export Tracking AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures observed transport and export tracking behavior: trip list/reporting, event and incident tracking, last position lookup, MIC/DEX physical document control, SCP upload, and export fiscal/cost/indicator reports.

## Requirements

### TET-REQ-001 - Trip Listing

The system shall list trips for the session customer with assignment date, order, provider, shipment, modality, scope, operator, plate, origins, destinations, state, driver and last position.

Evidence: `index_archivos/tracking/listaviajes.php:1-170`, `index_archivos/tracking/ReporteViajesClass.php:29-99`.

### TET-REQ-002 - Trip Report Filters

The system shall filter trip reports by date range, modality, logistics scope, operator, order and transport provider.

Evidence: `index_archivos/tracking/ReporteViajesClass.php:4-28`.

### TET-REQ-003 - Event Incident And Track Detail

The system shall retrieve trip origins/destinations, events, incidents and latest tracking position for trip detail/reporting.

Evidence: `index_archivos/tracking/ReporteViajesClass.php:69-207`.

### TET-REQ-004 - MIC DEX Document Listing

The system shall list MIC/DEX documents with invoice, DEX, manifest, transport company, plate, dates, weights and calculated document state.

Evidence: `index_archivos/operativos/exportaciones/ajax/RecepcionFisicaMICs.php:18-107`.

### TET-REQ-005 - MIC DEX State Update

The system shall accept or reject MIC/DEX state changes, update the corresponding date field and insert a state history record.

Evidence: `index_archivos/operativos/exportaciones/ajax/ActualizarMICs.php:12-91`.

### TET-REQ-006 - SCP Upload

The system shall read SCP Excel rows, normalize Excel/text dates and submit parsed rows to SCP persistence.

Evidence: `index_archivos/operativos/exportaciones/ajax/uploadDatosSCP.php:18-80`.

### TET-REQ-007 - Export Fiscal And Cost Reports

The system shall generate export fiscal, cost and indicator reports using invoice/case/item data, exchange rate, logistics costs, cancelled customer invoices and filters.

Evidence: `index_archivos/operativos/exportaciones/ExportacionesClass.php:64-360`.

## Candidate Risks

- SQL uses interpolated request/session values across tracking and export reports.
- MIC/DEX state transition mapping appears asymmetric and requires formal validation.
- Export reports mix operational, fiscal, logistics cost and document exchange data; ownership boundaries may need splitting.
