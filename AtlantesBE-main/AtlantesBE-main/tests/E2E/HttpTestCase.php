<?php

namespace Tests\E2E;

use PHPUnit\Framework\TestCase;

/**
 * Base class for E2E tests.
 * Provides HTTP helpers and JWT login used by all suites.
 */
abstract class HttpTestCase extends TestCase
{
    protected string $baseUrl;
    protected string $token = '';

    protected function setUp(): void
    {
        $this->baseUrl = rtrim(getenv('TEST_BASE_URL') ?: 'http://localhost/atlantes-api/public', '/');

        $user = getenv('TEST_USER');
        $pass = getenv('TEST_PASS');

        if (!$user || !$pass) {
            $this->markTestSkipped('TEST_USER y TEST_PASS no configurados en phpunit.xml');
        }

        $this->token = $this->login($user, $pass);
        if (!$this->token) {
            $this->fail('Login fallido — verificar TEST_USER/TEST_PASS y que el servidor esté corriendo');
        }
    }

    // ── HTTP helpers ──────────────────────────────────────────────────────────

    protected function request(string $method, string $path, string $body = '{}'): array
    {
        $url = $this->baseUrl . $path;
        $ch  = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $this->token,
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $raw  = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            return ['code' => 0, 'json' => null, 'raw' => '', 'curl_error' => $err];
        }

        return [
            'code'       => $code,
            'json'       => json_decode($raw, true),
            'raw'        => $raw,
            'curl_error' => $err,
        ];
    }

    protected function get(string $path): array
    {
        return $this->request('GET', $path);
    }

    protected function postJson(string $path, array $data = []): array
    {
        return $this->request('POST', $path, json_encode($data));
    }

    protected function putJson(string $path, array $data = []): array
    {
        return $this->request('PUT', $path, json_encode($data));
    }

    // ── Auth ──────────────────────────────────────────────────────────────────

    private function login(string $user, string $pass): string
    {
        $ch = curl_init($this->baseUrl . '/login');
        curl_setopt($ch, CURLOPT_POST,           true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode(['username' => $user, 'contrasena' => $pass]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT,        120);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: application/json']);
        $raw = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($raw, true);
        return isset($data['token']) ? (string) $data['token'] : '';
    }
}
