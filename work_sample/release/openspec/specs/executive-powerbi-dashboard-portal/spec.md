# executive-powerbi-dashboard-portal Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Render dashboard container

ASGARD SHALL render a portal page for each configured executive dashboard.

#### Scenario: User opens dashboard page

- GIVEN the user has access to the dashboard route
- WHEN the page loads
- THEN ASGARD renders menu, breadcrumb and dashboard iframe

### Requirement: Embed Power BI report

ASGARD SHALL embed the configured Power BI URL for the selected dashboard.

#### Scenario: Embedded report URL configured

- GIVEN a dashboard page contains a Power BI URL
- WHEN the page renders
- THEN the iframe points to that URL and allows fullscreen when configured

### Requirement: Delegate analytical interaction

ASGARD SHALL delegate dashboard filtering and visual interaction to Power BI.

#### Scenario: Report is loaded

- GIVEN Power BI successfully loads the report
- WHEN the user interacts with visuals
- THEN the interaction occurs inside the Power BI iframe
