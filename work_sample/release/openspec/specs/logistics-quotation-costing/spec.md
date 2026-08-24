# Logistics Quotation Costing AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures the observed logistics quotation and costing behavior: quotation creation, operator solicitation, cost submission, evaluation, acceptance and confirmation.

## Requirements

### LQC-REQ-001 - Quotation Creation

The system shall create a logistics quotation by storing shipment header, magnitude, route and operator candidate data.

Evidence: `index_archivos/logistica/embarquesController.php:94-178`, `index_archivos/logistica/CotizacionClass.php:423-627`.

### LQC-REQ-002 - Operator Solicitation

The system shall send quotation requests to operators with available email addresses and update send state.

Evidence: `index_archivos/logistica/embarquesController.php:238-274`, `index_archivos/logistica/CotizacionClass.php:861-868`.

### LQC-REQ-003 - Token-Based Cost Submission

The system shall identify the operator quotation by token and allow cost submission for that operator.

Evidence: `index_archivos/logistica/CostosClass.php:14-24`, `index_archivos/logistica/costosController.php:9-24`.

### LQC-REQ-004 - Cost Structure

The system shall build the cost structure from incoterm and shipment type.

Evidence: `index_archivos/logistica/CostosClass.php:412-427`.

### LQC-REQ-005 - Transit Time Calculation

The system shall calculate `TT` as the day difference between `ETA` and `ETD` when both dates exist in the same cost group.

Evidence: `index_archivos/logistica/CostosClass.php:431-463`.

### LQC-REQ-006 - Cost Submission Finalization

The system shall store cost details, nullify the token, and mark the operator quote as filled.

Evidence: `index_archivos/logistica/CostosClass.php:467-480`.

### LQC-REQ-007 - Cost Evaluation

The system shall display operator costs grouped by cost groups and allow decision actions.

Evidence: `index_archivos/logistica/evaluarcosto.php:16-180`.

### LQC-REQ-008 - Operator Acceptance Or Confirmation

The system shall mark the selected operator as accepted or confirmed and send the corresponding communication.

Evidence: `index_archivos/logistica/embarquesController.php:276-313`.

## Candidate Risks

- Token lifecycle and expiry are not visible in the inspected flow.
- Cost and quotation SQL uses interpolated runtime values.
- Business distinction between acceptance and confirmation must be formalized.
