# Identity Access AS-IS Specification

Status: INFERRED_DRAFT_REVIEW_REQUIRED  
Business language: Spanish  
Technical language: English

## Purpose

This AS-IS specification captures the observed customer identity and access behavior around email MFA, session creation, and forced password change.

## Requirements

### IA-REQ-001 - Email MFA Challenge

The system shall send a six-digit MFA code to the customer email when the customer reaches the MFA challenge page.

#### Scenario: Send MFA code

- Given a customer reaches `index_archivos/2fa.php` with a decodable `u` payload
- When the page initializes the MFA flow
- Then the system generates a six-digit code
- And stores it in `dav_codigos_2fa`
- And sends it to the customer email

Evidence: `index_archivos/2fa.php:10-13`, `index_archivos/2fa/TwoFaClass.php:10-84`.

### IA-REQ-002 - MFA Code Validation

The system shall accept a submitted MFA code only while it is active and not older than 600 seconds.

#### Scenario: Valid code

- Given an active code exists in `dav_codigos_2fa`
- And the code age is not greater than 600 seconds
- When the customer submits the code
- Then the system invalidates the code by setting `deleted_at`
- And allows the authentication step to continue

Evidence: `index_archivos/2fa/TwoFaClass.php:86-106`.

### IA-REQ-003 - Failed MFA Attempt Blocking

The system shall block the customer user after more than three failed MFA code attempts in the active session.

#### Scenario: Too many failed attempts

- Given the customer submits invalid MFA codes
- When the session attempt counter becomes greater than 3
- Then the system writes `fechabloqueo = CURRENT_TIMESTAMP()`
- And writes `intentos = 5` in `dav_clienteusuarios`
- And returns a blocked result

Evidence: `index_archivos/2fa/TwoFaClass.php:107-123`.

### IA-REQ-004 - Session Creation

The system shall create a customer session after successful MFA validation.

#### Scenario: Authenticated customer session

- Given MFA validation has succeeded
- When the authentication endpoint is called
- Then the system stores customer identity and type values in `$_SESSION`
- And creates JWT values
- And updates user and customer activity counters

Evidence: `index_archivos/2fa/ajax/autenticar.php:1-6`, `index_archivos/2fa/TwoFaClass.php:132-218`.

### IA-REQ-005 - Forced Password Change

The system shall redirect the customer to password change when `cambiarcontrasena == 1`.

#### Scenario: Password change required

- Given the customer authenticates successfully
- And the customer record indicates password change is required
- When the authentication flow selects the next URL
- Then the system returns `cambiocontrasena.php`

Evidence: `index_archivos/2fa/TwoFaClass.php:204-210`.

## Candidate Risks

- SQL injection risk requires validation of the `consultar` helper.
- Hardcoded JWT secrets are present in the inspected flow.
- The MFA payload integrity control is not visible in the inspected entrypoint.
- MFA code lookup is not visibly bound to email or user id.

## Validation Status

This specification is a candidate AS-IS reconstruction. It must be validated with product/security owners before being promoted to a canonical business baseline.
