# legacy-dispatch-document-maintenance Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the observed as-is intent for a legacy dispatch maintenance module.

## Requirements

### Requirement: Open Existing Dispatch

The system SHALL open only dispatches that exist for the configured client and are marked as dispatch records.

#### Scenario: Dispatch not found

- GIVEN no matching dispatch exists
- WHEN the page is opened
- THEN the system SHALL redirect to the dispatch list.

### Requirement: Update Dispatch Data

The system SHALL update basic dispatch/shipment fields when the user saves the form.

#### Scenario: Save dispatch

- GIVEN the dispatch is open
- WHEN the user saves basic data
- THEN the system SHALL update `logis_despachos`.

### Requirement: Maintain Dispatch Documents

The system SHALL allow adding or editing dispatch document metadata and optional attachments.

#### Scenario: Add document

- GIVEN document metadata is submitted
- WHEN `iddocumento = 0`
- THEN the system SHOULD create a `logis_documentos` record.

#### Scenario: Edit document

- GIVEN an existing document id is submitted
- WHEN metadata is saved
- THEN the system SHALL update `logis_documentos`.

### Requirement: Preserve Legacy Risk Flags

The system documentation SHALL mark this module as legacy-risk until schema and insert behavior are validated.

#### Scenario: Schema missing

- GIVEN DDL for `logis_despachos` and `logis_documentos` is absent from the inspected database dump
- WHEN the baseline is reviewed
- THEN this domain SHALL require technical validation before canonical acceptance.

