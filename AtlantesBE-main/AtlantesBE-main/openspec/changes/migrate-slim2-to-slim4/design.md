# Design: Slim 2 to Slim 4 migration

## Current Architecture Context
The project is a PHP 7.4 REST API using Slim 2. Most endpoints are registered in large files under `app/routes/*.php`. Route handlers depend on `$app->request`, `$app->response->body(...)`, Slim 2 route placeholders like `:id`, and middleware closures such as `$verifyToken` and `$verifyRole`.

## Proposed Approach
Use Slim 4 as the real HTTP runtime and add a temporary `App\Legacy\LegacySlim2App` facade that accepts the old route registration style. This keeps the large route files stable during the first migration step.

The facade is responsible for:
- Translating `:param` route placeholders to `{param}`.
- Registering Slim 4 routes internally.
- Recreating `$app->request` and `$app->response` behavior needed by existing handlers.
- Capturing legacy `echo` output and writing it into the PSR-7 response.
- Translating `$app->stop()` into a controlled exception that ends the current request.

## API Changes
No public API changes are intended. Request methods, URLs, JSON fields, upload fields, status semantics and response payloads should remain as-is.

## Migration and Rollout Plan
1. Install Slim 4 dependencies: `slim/slim` and `slim/psr7`.
2. Replace `app/start.php` bootstrap with Slim 4 AppFactory and middlewares.
3. Keep legacy route files unchanged initially.
4. Run endpoint smoke tests against the current Slim 2 version and the Slim 4 compatibility version.
5. Convert modules incrementally to native PSR-7 handlers only after compatibility tests pass.

## Testing and Observability
- Validate login, protected endpoints, role-protected endpoints and file upload/download endpoints first.
- Compare JSON response bodies against the Slim 2 baseline.
- Confirm CORS preflight behavior from the Angular frontend.
- Run PHPUnit tests already present in the repository.

## Security and Failure Modes
The JWT secret and authorization logic remain unchanged. Error details are currently enabled for migration visibility; production should set `displayErrorDetails` to false.

## Alternatives Considered
A full mechanical rewrite of 331 route registrations was avoided in the first step because it creates high risk of response/body drift across large endpoint files.
