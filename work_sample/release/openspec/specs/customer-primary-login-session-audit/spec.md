# customer-primary-login-session-audit

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

## Requirement: Validate primary login request

ASGARD SHALL reject a primary login request when CSRF token, username or password requirements fail.

### Scenario: Invalid CSRF

- GIVEN a login POST without a matching CSRF token
- WHEN `veriflogin.php` receives it
- THEN ASGARD returns HTTP 403.

## Requirement: Control failed attempts and blocking

ASGARD SHALL increment failed login attempts and block the user when the observed threshold is reached.

### Scenario: Incorrect password

- GIVEN an active customer user
- WHEN the supplied password does not match user password or master password
- THEN ASGARD logs a failed login
- AND increments `dav_clienteusuarios.intentos`
- AND sets `fechabloqueo`.

## Requirement: Create audited authenticated session

ASGARD SHALL create session/JWT and audit successful primary login when credentials are valid and MFA is not required.

### Scenario: Valid user without MFA

- GIVEN a valid active customer user without `2fa`
- WHEN login succeeds
- THEN ASGARD creates session variables and JWT
- AND resets attempts/blocking
- AND increments visits
- AND inserts a successful `log_asgard_ecosistema` row.
