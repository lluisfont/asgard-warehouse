# customs-dav-client-review-approval Spec

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Purpose

Define the as-is behavior for client review and approval/rejection of DAV/FDM declarations associated with logistics shipments.

## Requirements

### Requirement: List Client-Reviewable DAV/FDM

The system SHALL list DAV/FDM records associated with a logistics shipment when they have a client-state value different from zero.

#### Scenario: DAV/FDM rows are shown

- GIVEN a shipment has related DAV/FDM records
- WHEN the client opens the logistics items/DAV view
- THEN each visible row SHALL show reference, provider, invoice numbers, FOB total, current client state and form type.

### Requirement: Persist Client Decision

The system SHALL allow a non-finalized DAV/FDM to be approved or rejected by the client.

#### Scenario: Approve DAV/FDM

- GIVEN a DAV/FDM is not finalized
- WHEN the client confirms approval
- THEN the system SHALL set `dav_dav.idestadocliente` to `1`
- AND SHALL store the submitted observations.

#### Scenario: Reject DAV/FDM

- GIVEN a DAV/FDM is not finalized
- WHEN the client confirms rejection
- THEN the system SHALL set `dav_dav.idestadocliente` to `2`
- AND SHALL store the submitted observations.

### Requirement: Block Closure With Pending DAV/FDM

The system SHALL prevent closing review for a case while any DAV/FDM has a client state other than `1` or `2`.

#### Scenario: Pending declaration exists

- GIVEN a case has at least one DAV/FDM not approved or rejected
- WHEN the client tries to finalize review
- THEN the system SHALL respond that DAVs are pending approval/rejection
- AND SHALL NOT mark the review as finalized.

### Requirement: Finalize Complete Review

The system SHALL close DAV/FDM client review for a case when every DAV/FDM is approved or rejected.

#### Scenario: All declarations decided

- GIVEN all DAV/FDM records for a case have client state `1` or `2`
- WHEN the client finalizes review
- THEN the system SHALL set `dav_dav.finalizardav` to `1` for the case
- AND SHALL register an EDP follow-up record for each DAV/FDM
- AND SHALL send a notification email to the case coordinator with copy to the official.

### Requirement: Hide Actions After Finalization

The system SHALL hide approval, rejection and finalize actions when the DAV/FDM review is finalized.

#### Scenario: Finalized review

- GIVEN `finalizardav = 1`
- WHEN the user opens the DAV/FDM detail or list
- THEN the system SHALL NOT show approval/rejection/finalize controls.

