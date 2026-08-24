# Delta for API Runtime

## MODIFIED Requirements

### Requirement: Runtime framework
The API runtime MUST run on Slim 4 while preserving the existing public HTTP contract of the Slim 2 API.

#### Scenario: Existing JSON endpoint
- GIVEN an existing endpoint migrated through the compatibility facade
- WHEN a client sends the same method, route parameters, headers and body as before
- THEN the response body JSON structure MUST remain unchanged
- AND the response `Content-Type` SHOULD remain `application/json` unless the endpoint intentionally returns a file or non-JSON output

### Requirement: Route parameters
The API runtime MUST translate Slim 2 route parameters using `:param` into Slim 4 route parameters using `{param}` without changing the public URL.

#### Scenario: Parameterized endpoint
- GIVEN a legacy route declared as `/almacenes/:idalmacen`
- WHEN a client calls `/almacenes/10`
- THEN the legacy handler MUST receive `10` as the first route argument

### Requirement: Authentication and authorization middleware
The API runtime MUST preserve the behavior of the existing JWT token and role authorization middlewares.

#### Scenario: Expired or missing session
- GIVEN a protected endpoint
- WHEN the request has no valid `Authorization` token
- THEN the response body MUST keep `estado = Error`, `codigo = 401`, and `mensaje = La sesión no existe o ya expiró`

#### Scenario: Missing permission
- GIVEN a protected endpoint with role validation
- WHEN the token is valid but the user lacks permission
- THEN the response body MUST keep `estado = Error`, `codigo = 403`, and `mensaje = No tiene permisos para realizar esta operacion`

### Requirement: CORS and OPTIONS
The API runtime MUST continue allowing cross-origin clients using the same CORS headers and methods.

#### Scenario: Preflight request
- GIVEN a browser preflight request with method `OPTIONS`
- WHEN the API receives the request
- THEN the API MUST return the configured CORS headers without executing endpoint business logic
