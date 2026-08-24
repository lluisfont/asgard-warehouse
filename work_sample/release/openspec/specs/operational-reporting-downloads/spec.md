# operational-reporting-downloads Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Generate operational reports with session-scoped filters

ASGARD SHALL generate operational report grids using the active user/client session and the filters submitted by the user.

#### Scenario: User generates a report

- GIVEN a user with access to an operational report
- WHEN the user submits report filters
- THEN ASGARD displays a tabular result
- AND records report visualization where report logging is implemented

### Requirement: Export report results to Excel

ASGARD SHALL allow supported reports to export the generated result set to Excel.

#### Scenario: User exports Excel

- GIVEN a generated report result
- WHEN the user clicks Excel
- THEN ASGARD submits query and formatting metadata to the Excel generator
- AND includes the corresponding `idreportescliente`

### Requirement: Audit report visualization and download

ASGARD SHALL record report usage events with user, client, IP, host, report name and download flag.

#### Scenario: Visualization audit

- GIVEN a report has been generated
- WHEN the logging endpoint is called with `download = 0`
- THEN `log_asgard_ecosistema` records a visualization event

#### Scenario: Download audit

- GIVEN a user exports or downloads report data
- WHEN the logging endpoint is called with `download = 1`
- THEN `log_asgard_ecosistema` records a download event

### Requirement: Download document packages in bulk

ASGARD SHALL support bulk listing and ZIP download of active Intercambio Documental files by filters or uploaded folder list.

#### Scenario: List documents by filters

- GIVEN a customer user selects filter criteria
- WHEN the user generates the document list
- THEN ASGARD lists active documents grouped by exchange/folder and document type

#### Scenario: List documents by Excel folder upload

- GIVEN an uploaded Excel file with folders in column A
- WHEN the user loads the file
- THEN ASGARD resolves related shipments/exchanges
- AND lists active documents grouped by exchange/folder and document type

#### Scenario: Download ZIP

- GIVEN listed documents and at least one selected row
- WHEN the user requests compressed download
- THEN ASGARD prepares the file list
- AND calls the internal ZIP builder
- AND the browser downloads a ZIP file

