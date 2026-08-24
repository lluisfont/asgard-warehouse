# shipment-customs-request-management Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for customs request creation, update and sending from a logistics shipment.

## Requirements

### Requirement: Gate Customs Request Creation

The system SHALL show the add customs-management action only for open shipments and users with write permission.

#### Scenario: User can add GA

- GIVEN the shipment is not finalized
- AND the user has write permission `65`
- WHEN the customs tab is opened
- THEN the system SHALL show the add action unless a client-specific limit applies.

### Requirement: Persist Shipment-Linked Customs Request

The system SHALL create a `dav_casosprevios` record linked to the shipment.

#### Scenario: Save GA

- GIVEN valid form data is submitted
- WHEN the user saves
- THEN the system SHALL insert `dav_casosprevios`
- AND SHALL set `idembarquelogis` to the shipment id.

### Requirement: Seed Prior Documents And EDP

The system SHALL seed prior documents and an automatic EDP when the customs request is saved.

#### Scenario: Documents generated

- GIVEN the request is inserted
- WHEN modality documents are configured
- THEN the system SHALL insert missing `dav_documentosprevios`
- AND SHALL register an EDP event.

### Requirement: Send Customs Request

The system SHALL mark the request as sent when the user sends it.

#### Scenario: Send existing request

- GIVEN a customs request exists
- WHEN the user confirms sending
- THEN the system SHALL set `dav_casosprevios.fechafin`
- AND SHALL open the printable request output.

### Requirement: Update Customs Request

The system SHALL allow updating customs request fields according to request type and state.

#### Scenario: Approved request update

- GIVEN the request is in the approved-edit path
- WHEN changes are saved
- THEN the system SHALL update only the reduced set of observed fields.

