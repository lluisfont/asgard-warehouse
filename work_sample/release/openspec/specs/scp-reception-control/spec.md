# scp-reception-control Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Import SCP reception records from XLSX

ASGARD SHALL import SCP reception data from the expected XLSX format and associate each row with the active client/user session.

#### Scenario: Insert new SCP rows

- GIVEN an uploaded SCP XLSX file
- WHEN a row key does not exist in `dav_reporte_recepcion_scp`
- THEN ASGARD inserts a new reception row

#### Scenario: Update existing SCP rows

- GIVEN an uploaded SCP XLSX file
- WHEN a row key already exists
- THEN ASGARD updates changed controlled fields

### Requirement: Mark received records automatically

ASGARD SHALL mark an existing SCP record as received when the imported row has a valid reception date.

#### Scenario: Reception date present

- GIVEN an existing SCP row whose state is not `Recibido`
- WHEN the imported row has `fecha_recepcion` different from `0000-00-00`
- THEN ASGARD sets `estado` to `Recibido`

### Requirement: Report active SCP receptions by dispatch date

ASGARD SHALL list active SCP reception records filtered by dispatch date.

#### Scenario: Query report

- GIVEN a dispatch-date range
- WHEN the user generates the SCP report
- THEN ASGARD returns active rows from `dav_reporte_recepcion_scp`
- AND excludes rows with `deleted_at`

### Requirement: Compare SCP reception against packing-list data

ASGARD SHALL calculate whether SCP shipped quantity matches packing-list net weight.

#### Scenario: Matching quantity

- GIVEN a SCP row joined to packing-list detail
- WHEN `cantidad_enviada` equals `peso_neto_lista_empaque`
- THEN `estado_cuadra` is `CUADRA`

#### Scenario: Non-matching quantity

- GIVEN a SCP row joined to packing-list detail
- WHEN `cantidad_enviada` differs from `peso_neto_lista_empaque`
- THEN `estado_cuadra` is `REVISAR`

