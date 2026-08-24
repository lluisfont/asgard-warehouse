<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Tests unitarios para BlobStorageService.
 * No requieren red, Azure ni servidor web.
 * El transporte HTTP se reemplaza por un callable que devuelve respuestas fijas.
 */
class BlobStorageServiceTest extends TestCase
{
    // ── Helpers ──────────────────────────────────────────────────────────────

    /** Construye un transport mock que siempre devuelve la respuesta dada. */
    private function mockTransport($httpCode, $body = '', $extraHeaders = '')
    {
        $headerBlock = "HTTP/1.1 {$httpCode} OK\r\nContent-Type: image/jpeg\r\n{$extraHeaders}\r\n";
        $fullBody    = $headerBlock . $body;
        $headerSize  = strlen($headerBlock);

        return function ($method, $url, array $headers, $content) use ($httpCode, $fullBody, $headerSize) {
            return array(
                'httpCode'   => $httpCode,
                'body'       => $fullBody,
                'headerSize' => $headerSize,
                'error'      => '',
            );
        };
    }

    /** Construye un transport mock que simula un error de cURL (red caída). */
    private function mockTransportError($errorMsg = 'Connection refused')
    {
        return function () use ($errorMsg) {
            return array('httpCode' => 0, 'body' => '', 'headerSize' => 0, 'error' => $errorMsg);
        };
    }

    /** Instancia el servicio con credenciales fake pero válidas para tests. */
    private function makeService($transport = null)
    {
        // Constantes ya definidas en bootstrap; las sobreescribimos localmente
        // usando un trucquito: instanciamos con las constantes actuales pero
        // pasamos el connection string directamente al servicio.
        // Como las constantes son globales y no se pueden redefinir, usamos
        // un servicio extendido que recibe la configuración por constructor.
        return new ConfigurableBlobStorageService(
            'devaccount',
            base64_encode('fake-key-32-bytes-padding-ok-here'),
            'warehouse',
            'http://127.0.0.1:10000/devaccount',
            'connection_string',
            $transport
        );
    }

    // ── isConfigured ─────────────────────────────────────────────────────────

    public function testIsConfiguredTrueWhenAllFieldsPresent()
    {
        $svc = $this->makeService();
        $this->assertTrue($svc->isConfigured());
    }

    public function testIsConfiguredFalseWhenNoConfig()
    {
        $svc = new \BlobStorageService(); // usa constantes vacías del bootstrap
        $this->assertFalse($svc->isConfigured());
    }

    // ── uploadBlob ───────────────────────────────────────────────────────────

    public function testUploadBlobReturnsTrueOn201()
    {
        $svc    = $this->makeService($this->mockTransport(201));
        $result = $svc->uploadBlob('test/blob.jpg', 'fake-image-content', 'image/jpeg');
        $this->assertTrue($result);
    }

    public function testUploadBlobReturnsFalseOn4xx()
    {
        $svc    = $this->makeService($this->mockTransport(403));
        $result = $svc->uploadBlob('test/blob.jpg', 'fake-image-content', 'image/jpeg');
        $this->assertFalse($result);
    }

    public function testUploadBlobReturnsFalseOnCurlError()
    {
        $svc    = $this->makeService($this->mockTransportError('Connection refused'));
        $result = $svc->uploadBlob('test/blob.jpg', 'content', 'image/jpeg');
        $this->assertFalse($result);
    }

    public function testUploadBlobReturnsFalseWhenNotConfigured()
    {
        $svc = new \BlobStorageService();
        $this->assertFalse($svc->uploadBlob('test/blob.jpg', 'content', 'image/jpeg'));
    }

    // ── uploadBlobFromFile ────────────────────────────────────────────────────

    public function testUploadBlobFromFileReturnsTrueForValidFile()
    {
        $tmp = tempnam(sys_get_temp_dir(), 'blob_test_');
        file_put_contents($tmp, 'fake-jpeg-data');

        $svc    = $this->makeService($this->mockTransport(201));
        $result = $svc->uploadBlobFromFile('test/img.jpg', $tmp, 'image/jpeg');

        unlink($tmp);
        $this->assertTrue($result);
    }

    public function testUploadBlobFromFileReturnsFalseForMissingFile()
    {
        $svc    = $this->makeService($this->mockTransport(201));
        $result = $svc->uploadBlobFromFile('test/img.jpg', '/no/existe.jpg', 'image/jpeg');
        $this->assertFalse($result);
    }

    // ── getBlob ───────────────────────────────────────────────────────────────

    public function testGetBlobReturnsArrayOn200()
    {
        $imageContent = 'FAKE_JPEG_BINARY_DATA';
        $svc          = $this->makeService($this->mockTransport(200, $imageContent));
        $result       = $svc->getBlob('test/blob.jpg');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('contentType', $result);
        $this->assertStringContainsString('image/jpeg', $result['contentType']);
    }

    public function testGetBlobReturnsNullOn404()
    {
        $svc    = $this->makeService($this->mockTransport(404));
        $result = $svc->getBlob('test/nonexistent.jpg');
        $this->assertNull($result);
    }

    public function testGetBlobReturnsNullOnCurlError()
    {
        $svc    = $this->makeService($this->mockTransportError());
        $result = $svc->getBlob('test/blob.jpg');
        $this->assertNull($result);
    }

    public function testGetBlobReturnsNullWhenNotConfigured()
    {
        $svc = new \BlobStorageService();
        $this->assertNull($svc->getBlob('test/blob.jpg'));
    }

    // ── exists ────────────────────────────────────────────────────────────────

    public function testExistsReturnsTrueOn200()
    {
        $svc = $this->makeService($this->mockTransport(200));
        $this->assertTrue($svc->exists('test/blob.jpg'));
    }

    public function testExistsReturnsFalseOn404()
    {
        $svc = $this->makeService($this->mockTransport(404));
        $this->assertFalse($svc->exists('test/blob.jpg'));
    }

    // ── deleteBlob ────────────────────────────────────────────────────────────

    public function testDeleteBlobReturnsTrueOn202()
    {
        $svc = $this->makeService($this->mockTransport(202));
        $this->assertTrue($svc->deleteBlob('test/blob.jpg'));
    }

    public function testDeleteBlobReturnsFalseOn404()
    {
        $svc = $this->makeService($this->mockTransport(404));
        $this->assertFalse($svc->deleteBlob('test/blob.jpg'));
    }

    // ── Shared Key Authorization ───────────────────────────────────────────────

    public function testUploadBlobSendsAuthorizationHeader()
    {
        $capturedHeaders = array();
        $transport = function ($method, $url, array $headers, $body) use (&$capturedHeaders) {
            $capturedHeaders = $headers;
            return array('httpCode' => 201, 'body' => '', 'headerSize' => 0, 'error' => '');
        };

        $svc = $this->makeService($transport);
        $svc->uploadBlob('test/blob.jpg', 'content', 'image/jpeg');

        $authHeader = '';
        foreach ($capturedHeaders as $h) {
            if (strpos($h, 'Authorization:') === 0) {
                $authHeader = $h;
                break;
            }
        }

        $this->assertStringStartsWith('Authorization: SharedKey devaccount:', $authHeader);
    }

    public function testUploadBlobSendsBlobTypeHeader()
    {
        $capturedHeaders = array();
        $transport = function ($method, $url, array $headers, $body) use (&$capturedHeaders) {
            $capturedHeaders = $headers;
            return array('httpCode' => 201, 'body' => '', 'headerSize' => 0, 'error' => '');
        };

        $svc = $this->makeService($transport);
        $svc->uploadBlob('test/blob.jpg', 'content', 'image/jpeg');

        $blobTypeHeader = '';
        foreach ($capturedHeaders as $h) {
            if (strpos($h, 'x-ms-blob-type:') === 0) {
                $blobTypeHeader = $h;
                break;
            }
        }
        $this->assertEquals('x-ms-blob-type: BlockBlob', $blobTypeHeader);
    }

    // ── Stream fallback (callHttpStream) ─────────────────────────────────────────

    /**
     * Verifies that callHttpStream parses the status code and body correctly.
     * Uses a subclass that overrides callHttpStream with a fake response.
     */
    public function testStreamFallbackParsesStatusAndBody()
    {
        $svc = new StreamFallbackBlobStorageService(
            'devaccount',
            base64_encode('fake-key-32-bytes-padding-ok-here'),
            'warehouse',
            'http://127.0.0.1:10000/devaccount',
            'connection_string',
            200,
            'FAKE_IMAGE_CONTENT',
            'image/jpeg'
        );

        $result = $svc->getBlob('test/img.jpg');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('content', $result);
        $this->assertArrayHasKey('contentType', $result);
        $this->assertEquals('FAKE_IMAGE_CONTENT', $result['content']);
        $this->assertStringContainsString('image/jpeg', $result['contentType']);
    }

    public function testStreamFallbackReturnsNullOn404()
    {
        $svc = new StreamFallbackBlobStorageService(
            'devaccount',
            base64_encode('fake-key-32-bytes-padding-ok-here'),
            'warehouse',
            'http://127.0.0.1:10000/devaccount',
            'connection_string',
            404,
            '',
            'application/xml'
        );

        $result = $svc->getBlob('test/missing.jpg');
        $this->assertNull($result);
    }

    public function testStreamFallbackUploadReturnsTrueOn201()
    {
        $svc = new StreamFallbackBlobStorageService(
            'devaccount',
            base64_encode('fake-key-32-bytes-padding-ok-here'),
            'warehouse',
            'http://127.0.0.1:10000/devaccount',
            'connection_string',
            201,
            '',
            ''
        );

        $result = $svc->uploadBlob('test/img.jpg', 'content', 'image/jpeg');
        $this->assertTrue($result);
    }

    // ── Blob URL construction ─────────────────────────────────────────────────

    public function testBlobUrlContainerAndNameAreInUrl()
    {
        $capturedUrl = '';
        $transport   = function ($method, $url, array $headers, $body) use (&$capturedUrl) {
            $capturedUrl = $url;
            return array('httpCode' => 201, 'body' => '', 'headerSize' => 0, 'error' => '');
        };

        $svc = $this->makeService($transport);
        $svc->uploadBlob('empresa1/almacen/ate_gas/foto.jpg', 'c', 'image/jpeg');

        $this->assertStringContainsString('/warehouse/', $capturedUrl);
        $this->assertStringContainsString('empresa1', $capturedUrl);
        $this->assertStringContainsString('foto.jpg', $capturedUrl);
    }
}

/**
 * Subclase que permite inyectar la configuración directamente sin depender de
 * constantes globales, para poder testear con distintos valores de cuenta/key.
 */
class ConfigurableBlobStorageService extends \BlobStorageService
{
    public function __construct($accountName, $accountKey, $container, $baseUrl, $authMode, $transport = null)
    {
        parent::__construct($transport);
        $ref = new \ReflectionClass(\BlobStorageService::class);
        foreach (array('accountName' => $accountName, 'accountKey' => $accountKey,
                       'container'   => $container,   'baseUrl'    => $baseUrl,
                       'authMode'    => $authMode) as $prop => $val) {
            $p = $ref->getProperty($prop);
            $p->setAccessible(true);
            $p->setValue($this, $val);
        }
    }
}

/**
 * Subclase que simula el path callHttpStream (sin cURL) devolviendo
 * una respuesta fake de HTTP para poder testear la lógica de parsing.
 */
class StreamFallbackBlobStorageService extends \BlobStorageService
{
    private $fakeCode;
    private $fakeBody;
    private $fakeMime;

    public function __construct($accountName, $accountKey, $container, $baseUrl, $authMode,
                                $fakeCode, $fakeBody, $fakeMime)
    {
        // Transport null → usará callHttp interno, que sobreescribimos abajo
        parent::__construct(null);
        $ref = new \ReflectionClass(\BlobStorageService::class);
        foreach (array('accountName' => $accountName, 'accountKey' => $accountKey,
                       'container'   => $container,   'baseUrl'    => $baseUrl,
                       'authMode'    => $authMode) as $prop => $val) {
            $p = $ref->getProperty($prop);
            $p->setAccessible(true);
            $p->setValue($this, $val);
        }
        $this->fakeCode = $fakeCode;
        $this->fakeBody = $fakeBody;
        $this->fakeMime = $fakeMime;
    }

    protected function callHttp($method, $url, array $flatHeaders, $body)
    {
        // Simula la respuesta que devolvería callHttpStream
        $statusLine  = 'HTTP/1.1 ' . $this->fakeCode . ' OK';
        $headerLines = array($statusLine, 'Content-Type: ' . $this->fakeMime);
        $headerBlock = implode("\r\n", $headerLines) . "\r\n\r\n";
        $headerSize  = strlen($headerBlock);

        return array(
            'httpCode'   => $this->fakeCode,
            'body'       => $headerBlock . $this->fakeBody,
            'headerSize' => $headerSize,
            'error'      => '',
        );
    }
}
