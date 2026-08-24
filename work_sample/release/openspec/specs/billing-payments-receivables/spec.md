# Billing Payments Receivables AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures observed billing, payment and receivables behavior: invoice/planilla listing, PDF download/generation, debit note reporting, received payment balances, document receipt acknowledgement, and account statement aging.

## Requirements

### BPR-REQ-001 - Invoice Planilla Listing

The system shall list invoice-planilla documents for the session customer, filtered by dates and optional operational criteria.

Evidence: `index_archivos/contables/facplaquery.php:31-83`.

### BPR-REQ-002 - Invoice Amount Calculation

The system shall calculate invoice amount by summing invoice detail amounts by invoice-planilla id.

Evidence: `index_archivos/contables/facplaquery.php:86-89`, `.data_base/asgard.sql:6266-6282`.

### BPR-REQ-003 - Planilla Amount Calculation

The system shall calculate planilla amount by summing payment detail amounts by case, excluding cancelled payments where applicable.

Evidence: `index_archivos/contables/facplaquery.php:91-112`, `index_archivos/contables/generarfacturaplanillacliente.php:126-136`.

### BPR-REQ-004 - Invoice Planilla PDF

The system shall provide PDF download/generation links for invoice and planilla documents using invoice-planilla metadata.

Evidence: `index_archivos/contables/facplaquery.php:50-54`, `index_archivos/contables/descargarfactura.php:12-26`, `index_archivos/contables/descargarplanilla.php:11-29`, `index_archivos/contables/generarfacturaplanillacliente.php:12-170`.

### BPR-REQ-005 - Debit Note Reporting

The system shall report debit notes with customer, city, type, number, case/order context, glosa, detail concepts and amount.

Evidence: `index_archivos/contables/notasdebitoquery.php:10-91`, `.data_base/asgard.sql:7473-7531`.

### BPR-REQ-006 - Received Payment Balances

The system shall report received payments/advances with total amount, applied amount, returned amount and remaining balance.

Evidence: `index_archivos/contables/pagosrecibidosquery.php:7-43`.

### BPR-REQ-007 - Sent Document Receipt

The system shall list sent documents pending receipt and mark receipt for invoices, planillas, debit notes and cites.

Evidence: `index_archivos/contables/recepcionplanillas_ajax.php:1-352`.

### BPR-REQ-008 - Account Statement Aging

The system shall prepare account statement balances as of a selected date and classify each document as current or overdue using customer credit days.

Evidence: `index_archivos/contables/estadocuentasquery.php:1-75`.

## Candidate Risks

- SQL uses interpolated session, request and date filter values.
- State values such as `idestadofactura`, `idestadoplanilla`, `idestadopago`, `estado_enviado` and `estado_recepcionado` require formal cataloging.
- Receivables calculations depend on stored procedure `cobros2`, which must be analyzed in detail before final baseline approval.
