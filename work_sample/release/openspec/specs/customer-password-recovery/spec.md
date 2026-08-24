# customer-password-recovery

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirement: Generate customer password recovery code

ASGARD SHALL allow an active customer user to request a password recovery code using username or email.

### Scenario: Active user requests recovery

- GIVEN an active `dav_clienteusuarios` row matching username or email
- WHEN the recovery form is submitted
- THEN ASGARD records a row in `dav_reseteos_passswords_clientes`
- AND sends the generated code by email.

## Requirement: Validate recovery code

ASGARD SHALL validate a non-deleted recovery code for the submitted username/email and reject expired codes.

### Scenario: Valid same-day code

- GIVEN a non-deleted recovery row for the submitted user/email and token
- WHEN the token is verified on the same calendar day
- THEN ASGARD marks the row as deleted/consumed
- AND returns the recovery identifier.

## Requirement: Reset password and unlock customer user

ASGARD SHALL update the customer user's password using bcrypt and clear login block counters after successful code validation.

### Scenario: New password submitted after token validation

- GIVEN a consumed recovery identifier
- WHEN the new password is submitted
- THEN ASGARD updates `dav_clienteusuarios.password`
- AND clears `fechabloqueo`
- AND sets `intentos` to `0`
- AND updates `fechacontrasena`.
