# master-data-configuration

Status: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirements

### Requirement: Customer User Administration

The system shall allow authorized users to list, create, activate/deactivate and logically delete customer users.

#### Scenario: Create customer user

- Given an authorized user provides name, username, email, user type and 2FA preference
- When the system validates no existing username or email
- Then the system creates a `dav_clienteusuarios` row with active, visible, password date and audit fields

#### Scenario: Prevent duplicate customer account

- Given the submitted username or email already exists
- When validation runs
- Then the system rejects account creation with duplicate-account feedback

### Requirement: Customer Report Permissions

The system shall manage report permissions for each customer user based on reports enabled for the customer.

#### Scenario: Read permission matrix

- Given a customer and customer user
- When permissions are loaded
- Then the system returns enabled customer reports with current user permission markers grouped by module

### Requirement: Provider And Consignee Administration

The system shall allow authorized users to create, relate, document and track providers or consignees for a customer.

#### Scenario: Create provider candidate

- Given provider identity, location, condition and contact data
- When the provider is submitted
- Then the system creates the provider and its contacts with pending or approval state

#### Scenario: Detect provider modification

- Given temporary provider data differs from current provider data
- When comparison runs
- Then the system marks differences and can create a provider modification request

### Requirement: Transport Operator Administration

The system shall allow authorized users to create customer transport operators, contacts and documents.

#### Scenario: Create customer transport operator

- Given transportist, trip type and contact data
- When the operator is submitted
- Then the system creates `dav_clientetransportista` with `estado = 1` and saves contacts

#### Scenario: Store operator document

- Given an operator document file and metadata
- When the file copy succeeds
- Then the system stores a `logis_documentosclienteoperador` record linked to customer and operator

### Requirement: Signing User Administration

The system shall maintain active signing users for official or transport-related flows.

#### Scenario: List active signing users

- Given signing users exist
- When the list is requested
- Then the system returns only records with `activo = 1` and no logical deletion

## Traceability

- Business process: `.brownfield/work/release/business-analysis/processes/domains/master-data-configuration`
- Use case: `.brownfield/work/release/business-analysis/use-cases/domains/master-data-configuration/UC-001.md`
- Evidence map: `.brownfield/work/release/traceability/verification/master-data-configuration-evidence-map.md`
