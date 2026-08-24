# customs-operational-kpi-control Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Calculate AP DAM REQ KPI

ASGARD SHALL calculate AP, DAM and requirement KPIs using working days and capacity thresholds.

#### Scenario: KPI complies

- GIVEN a request with unit quantity within observed capacity
- AND elapsed working days within threshold
- WHEN the KPI report is generated
- THEN the KPI result is `CUMPLE`

#### Scenario: KPI does not comply

- GIVEN a request with elapsed working days outside threshold
- WHEN the KPI report is generated
- THEN the KPI result is `NO CUMPLE`

### Requirement: Calculate customs control indicators

ASGARD SHALL calculate customs-control indicators from EDP dates, tax forecast and actual cost data.

#### Scenario: Tax forecast indicator

- GIVEN forecast and actual tax/cost values
- WHEN the absolute difference is within the observed 5 percent threshold
- THEN the forecast indicator is positive

### Requirement: Derive logistics control state

ASGARD SHALL derive logistics control state from the highest observed EDP stage order.

#### Scenario: Current shipment state

- GIVEN an active shipment with EDP rows
- WHEN the control report is generated
- THEN ASGARD reports the state associated with the maximum EDP stage order

