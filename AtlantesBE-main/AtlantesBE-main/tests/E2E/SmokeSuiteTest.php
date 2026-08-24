<?php

namespace Tests\E2E;

/**
 * Smoke-tests every registered Slim 4 route.
 *
 * One authenticated request per endpoint with placeholder values for path
 * params ({idembarque} → 1).  Asserts the server responds (no curl error) and
 * does not return HTTP 5xx (no unhandled PHP exception).
 *
 * 4xx responses are intentionally accepted: the route exists and handled the
 * request, even if the specific resource was not found or validation failed.
 *
 * The class logs in once (setUpBeforeClass) instead of once per test so 331
 * endpoints don't trigger 331 extra login requests.
 */
class SmokeSuiteTest extends HttpTestCase
{
    private static bool   $loginAttempted = false;
    private static string $cachedToken    = '';
    private static string $cachedBaseUrl  = '';

    // ── Lazy-cached login ─────────────────────────────────────────────────────
    // First test does a real login via parent::setUp() so errors are visible.
    // Subsequent tests reuse the cached token to avoid 330 extra round-trips.

    protected function setUp(): void
    {
        if (self::$loginAttempted) {
            $this->baseUrl = self::$cachedBaseUrl;
            $this->token   = self::$cachedToken;
            if (!$this->token) {
                $this->markTestSkipped('Login fallido en el primer test del suite');
            }
            return;
        }

        self::$loginAttempted = true;
        parent::setUp();                          // may fail() or markTestSkipped()

        self::$cachedToken   = $this->token;
        self::$cachedBaseUrl = $this->baseUrl;
    }

    // ── Route scanner (data provider) ─────────────────────────────────────────

    public static function routeProvider(): array
    {
        $routeDir = __DIR__ . '/../../app/routes';
        $files    = glob($routeDir . '/*.php') ?: [];
        $cases    = [];

        foreach ($files as $file) {
            $content = file_get_contents($file);
            preg_match_all(
                '/^\$app->(get|post|put|delete|patch)\s*\(\s*\'([^\']+)\'/im',
                $content,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $m) {
                $method = strtoupper($m[1]);
                // Replace every {placeholder} with the digit 1
                //$path   = preg_replace('/\{[^}]+\}/', '1', $m[2]);
                $path = self::buildSmokePath($m[2]);
                $label  = $method . ' ' . $path;
                $cases[$label] = [$method, $path];
            }
        }

        ksort($cases);
        return $cases;
    }

    // ── Test ──────────────────────────────────────────────────────────────────

    /** @dataProvider routeProvider */
    public function testEndpointNotCrashes(string $method, string $path): void
    {
        $res = $this->request($method, $path);

        $this->assertGreaterThan(
            0,
            $res['code'],
            "curl error on {$method} {$path}: {$res['curl_error']}"
        );

        $this->assertLessThan(
            500,
            $res['code'],
            "HTTP {$res['code']} (server error) on {$method} {$path} — body: " . substr($res['raw'], 0, 300)
        );
    }

    private static function buildSmokePath(string $route): string
{
    return preg_replace_callback(
        '/\{([^}]+)\}/',
        fn ($match) => self::sampleValueForParam($match[1]),
        $route
    );
}

private static function sampleValueForParam(string $param): string
{
    $param = strtolower($param);

    // Fechas
    if (str_contains($param, 'fecha')) {
        return '2024-01-01';
    }

    if (str_contains($param, 'desde')) {
        return '2024-01-01';
    }

    if (str_contains($param, 'hasta')) {
        return '2024-01-15';
    }

    if (str_contains($param, 'inicio')) {
        return '2024-01-01';
    }

    if (str_contains($param, 'fin')) {
        return '2024-01-15';
    }

    // Años / meses
    if (str_contains($param, 'anio') || str_contains($param, 'año')) {
        return '2024';
    }

    if (str_contains($param, 'mes')) {
        return '1';
    }

    // IDs numéricos
    if (
        str_starts_with($param, 'id') ||
        str_ends_with($param, 'id') ||
        str_contains($param, 'codigo') ||
        str_contains($param, 'cod')
    ) {
        return '1';
    }

    // Estados / tipos
    /*
    if (str_contains($param, 'estado')) {
        return 'ACTIVO';
    }


    if (str_contains($param, 'tipo')) {
        return 'GENERAL';
    }
    */
    // Texto genérico
    if (
        str_contains($param, 'nombre') ||
        str_contains($param, 'descripcion') ||
        str_contains($param, 'buscar') ||
        str_contains($param, 'texto') ||
        str_contains($param, 'glosa')
    ) {
        return 'TEST';
    }

    // Valor por defecto seguro
    return '1';
}
}
