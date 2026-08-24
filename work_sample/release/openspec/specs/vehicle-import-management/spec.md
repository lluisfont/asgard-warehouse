# Vehicle Import Management AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures observed vehicle import management behavior: vehicle validation for previous requests, DAM gating, chassis-document association, bulk vehicle modification by chassis, movement between previous requests, and SOAT lot reassignment.

## Requirements

### VIM-REQ-001 - Vehicle Count Validation

The system shall count vehicles associated with a previous request before sending or finalizing request/document workflows.

Evidence: `index_archivos/documentacion.php:31-41`, `index_archivos/enviarsolicitud_ajax.php:30-40`.

### VIM-REQ-002 - DAM Gate

When DAM by item is required, the system shall prevent completion while vehicles without DAM remain.

Evidence: `index_archivos/documentacion.php:381-435`, `index_archivos/enviarsolicitud_ajax.php:48-82`.

### VIM-REQ-003 - Vehicle Error Gate

The system shall check vehicle errors before allowing request progression.

Evidence: `index_archivos/documentacion.php:381-435`, `index_archivos/enviarsolicitud_ajax.php:48-82`.

### VIM-REQ-004 - Chassis Document Association

The system shall create chassis-to-document-code records when a request/document is sent or finalized.

Evidence: `index_archivos/documentacion.php:445-446`, `index_archivos/enviarsolicitud_ajax.php:90-91`, `.data_base/asgard.sql:680-689`.

### VIM-REQ-005 - Bulk Upload Staging

The system shall stage uploaded Excel rows in `dav_historialmodificacionvehiculos` with one load identifier and per-row validation messages.

Evidence: `index_archivos/logistica/ajax/vehiculos/uploadExcelSolicitud.php:16-123`, `index_archivos/controllers/VehiculosClass.php:15-57`, `.data_base/asgard.sql:6711-6726`.

### VIM-REQ-006 - Economic Field Update

The system shall update FOB and freight report fields on cases for valid staged rows when the selected modification type includes economic fields.

Evidence: `index_archivos/controllers/VehiculosClass.php:148-154`.

### VIM-REQ-007 - Item Field Update

The system shall update order, position and valuation fields on vehicle items for valid staged rows when the selected modification type includes item fields.

Evidence: `index_archivos/controllers/VehiculosClass.php:136-146`.

### VIM-REQ-008 - Previous Request Movement

The system shall move eligible vehicles to a destination previous request only when the target request exists, is sent, and the source vehicle has complete information without error.

Evidence: `index_archivos/controllers/VehiculosClass.php:156-205`, `index_archivos/controllers/VehiculosClass.php:223-280`.

### VIM-REQ-009 - SOAT Lot Reassignment

The system shall create or reuse SOAT lots and reassign related SOAT lot items when a vehicle is moved between requests.

Evidence: `index_archivos/controllers/VehiculosClass.php:290-334`, `.data_base/asgard.sql:16007-16033`.

### VIM-REQ-010 - Modification History Confirmation

After successful application, the system shall mark staged modification rows as updated.

Evidence: `index_archivos/controllers/VehiculosClass.php:206-217`.

## Candidate Risks

- SQL uses interpolated request/session values in vehicle modification and validation flows.
- The meaning of `camposmodificar` values is inferred from code and requires formal cataloging.
- DAM/AP/SOAT state transitions need business validation after full baseline reconstruction.
