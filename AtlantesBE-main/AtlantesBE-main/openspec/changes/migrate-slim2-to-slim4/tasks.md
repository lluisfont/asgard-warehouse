# Tasks

## 1. Runtime and dependencies
- [x] 1.1 Update Composer requirements from Slim 2 to Slim 4 and add `slim/psr7`.
- [x] 1.2 Replace Slim 2 bootstrap with Slim 4 `AppFactory::create()`.
- [x] 1.3 Add body parsing, routing, CORS and error middleware.

## 2. Route migration (native PSR-7 — no compatibility layer)
> Approach changed: all modules migrated directly to native Slim 4 PSR-7, skipping the compatibility facade.
- [x] 2.1 ~~Add a legacy facade for Slim 2-style route registration.~~ (skipped — went native directly)
- [x] 2.2 ~~Add compatibility objects for `$app->request`, `$app->response`.~~ (skipped)
- [x] 2.3 Translate Slim 2 `:param` route placeholders to Slim 4 `{param}` in all modules.
- [x] 2.4 Rewrite all route handlers to `function(Request $request, Response $response, array $args)` signature.
- [x] 2.5 Replace `$app->response->body(...)` and `echo json_encode(...)` with PSR-7 `$response->getBody()->write(...)` + `return`.
- [x] 2.6 Replace `$app->request->getBody()` with `(string) $request->getBody()`.
- [x] 2.7 Move middleware from route second-arg to `->add($verifyToken)` / `->add($verifyRole(...))` chaining.

## 3. Authentication and authorization
- [x] 3.1 Preserve JWT token validation response payloads.
- [x] 3.2 Preserve role validation response payloads.
- [ ] 3.3 Test protected endpoints with valid, expired and missing tokens.

## 4. Endpoint validation
- [ ] 4.1 Run all existing PHPUnit tests.
- [ ] 4.2 Smoke test login and user endpoints.
- [ ] 4.3 Smoke test representative endpoints from almacenes, embarques, contabilidad, datosmaestro, entidades, asgard, empresa and common.
- [ ] 4.4 Validate multipart/form-data endpoints (file uploads).
- [ ] 4.5 Validate file download endpoints that use `header()`, `readfile()` or generated documents.
