# additional-services-request-management Specification

## Status

INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Create additional service request

ASGARD SHALL allow authorized users to create an additional service request linked to shipment, GA request or case context.

#### Scenario: Authorized user creates request

- GIVEN the user has write permission
- AND the shipment is not finalized when shipment context applies
- WHEN the user enters requester data and at least one service
- THEN ASGARD creates the request and associated tramite rows

### Requirement: Select service by entity, tramite and type

ASGARD SHALL constrain service selection by entity issuer, tramite and tramite type.

#### Scenario: User selects service catalog

- GIVEN the user selects an entidad emisora
- WHEN ASGARD loads tramites
- THEN only tramites for that entity are offered
- AND when the user selects a tramite
- THEN only corresponding tipos de tramite are offered

### Requirement: Manage request state queues

ASGARD SHALL present additional service requests grouped by operational state.

#### Scenario: User views states

- GIVEN requests exist
- WHEN the user opens Servicios Adicionales
- THEN ASGARD shows queues for Pendientes, Enviados, Recepcionados, Asignados, En Revision, En Proceso and Finalizado

### Requirement: Integrate document exchange

ASGARD SHALL create or link a document exchange for additional service requests.

#### Scenario: Request is saved

- GIVEN the request has an exchange id or requires one
- WHEN ASGARD saves the request
- THEN ASGARD links or creates the exchange
- AND adds documents using the selected tramite type hash

### Requirement: Lock tramite editing after reception

ASGARD SHALL prevent adding or editing tramites once the request reaches reception or later operational states.

#### Scenario: Request is beyond pending/sent editing window

- GIVEN the request state is recepcionado, asignado, en revision, en proceso or finalizado
- WHEN the user views tramites
- THEN add/edit controls are not available
