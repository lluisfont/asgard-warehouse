# warehouse-inventory-reporting

Status: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Atlantes Warehouse Reports

The system shall allow authorized users to query Atlantes warehouse reports for ingresos, salidas, inventory, timbrado and detailed movements.

#### Scenario: Query ingresos

- Given a user selects warehouse and date range
- When the ingresos report is generated
- Then the system requests Atlantes ingresos data for the session customer and displays it in a grid

#### Scenario: Query inventory at cutoff

- Given a user selects warehouse and cutoff date
- When the inventory report is generated
- Then the system requests Atlantes inventory data and displays only rows with positive quantity

### Requirement: Warehouse Report Export

The system shall prepare report headers and visible data for Excel export.

#### Scenario: Export grid

- Given report data has loaded into the grid
- When the user exports
- Then the system exports rows using the configured column model and labels

### Requirement: Vehicle Inventory Reports

The system shall allow authorized users to query vehicle inventory reports by date, chassis and inventory type.

#### Scenario: Query vehicle inventory by chassis

- Given a user provides date filters, optional chassis and inventory type
- When the report is generated
- Then the system requests inventory data from `url_pedidos` and displays reception, dispatch, accessories, damage, contamination and reporting information

### Requirement: Warehouse Session Scoping

The system shall scope warehouse queries using session customer, warehouse and authorization tokens.

#### Scenario: Load available warehouse

- Given a user has Atlantes session data
- When warehouses are loaded
- Then the system filters warehouses to the session warehouse id

## Traceability

- Business process: `.brownfield/work/release/business-analysis/processes/domains/warehouse-inventory-reporting`
- Use case: `.brownfield/work/release/business-analysis/use-cases/domains/warehouse-inventory-reporting/UC-001.md`
- Evidence map: `.brownfield/work/release/traceability/verification/warehouse-inventory-reporting-evidence-map.md`
