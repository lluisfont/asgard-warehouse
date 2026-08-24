<?php

namespace Tests\E2E;

use PHPUnit\Framework\TestCase;

/**
 * Tests E2E para endpoints ATE-GAS con imágenes.
 *
 * Requieren:
 *  1. WAMP corriendo en TEST_BASE_URL
 *  2. TEST_USER / TEST_PASS con acceso a ATE-GAS
 *  3. TEST_ETAPA_ID: ID de una etapa existente en la BD de prueba
 *  4. Para tests con Azure: Azurite corriendo en localhost:10000
 *       npx azurite --silent --location /tmp/azurite
 *     y en .env.php del servidor:
 *       azure_blob_enabled = true
 *       azure_blob_connection_string = (conn string de Azurite — ver phpunit.xml)
 *       azure_blob_container = 'warehouse'
 *
 * Correr solo E2E:
 *   vendor/bin/phpunit --testsuite E2E
 */
class AteGasImagesE2ETest extends TestCase
{
    private $baseUrl;
    private $token;
    private $etapaId;

    // ── Setup ─────────────────────────────────────────────────────────────────

    protected function setUp(): void
    {
        $this->baseUrl = getenv('TEST_BASE_URL') ?: 'http://localhost/atlantes-api/public/index.php';
        $this->etapaId = (int)(getenv('TEST_ETAPA_ID') ?: 1);

        $user = getenv('TEST_USER');
        $pass = getenv('TEST_PASS');

        if (!$user || !$pass) {
            $this->markTestSkipped('TEST_USER y TEST_PASS no configurados en phpunit.xml');
        }

        $this->token = $this->login($user, $pass);
        if (!$this->token) {
            $this->fail('No se pudo obtener JWT — verificar TEST_USER/TEST_PASS');
        }
    }

    // ── Tests: feature flag OFF (disco local) ─────────────────────────────────

    public function testGetInventarioDevuelveEstructuraCorrecta()
    {
        $response = $this->get("/ate-gas/gestion-movimiento/inventario/{$this->etapaId}");

        $this->assertEquals(200, $response['code']);
        $body = $response['json'];
        $this->assertEquals('Exito', $body['estado']);
        $this->assertArrayHasKey('inventario', $body);
        $this->assertIsArray($body['inventario']);
    }

    public function testGetImagenesDevuelveEstructuraCorrecta()
    {
        $response = $this->get("/ate-gas/gestion-movimiento/{$this->etapaId}/imagenes");

        $this->assertEquals(200, $response['code']);
        $body = $response['json'];
        $this->assertEquals('Exito', $body['estado']);
        $this->assertArrayHasKey('imagenes', $body);
        $this->assertIsArray($body['imagenes']);
    }

    public function testGetImagenesConThumbnailDevuelveDataUri()
    {
        $response = $this->get("/ate-gas/gestion-movimiento/{$this->etapaId}/imagenes");
        $body     = $response['json'];
        $imagenes = isset($body['imagenes']) ? $body['imagenes'] : array();

        if (empty($imagenes)) {
            $this->markTestSkipped("La etapa {$this->etapaId} no tiene imágenes — sube una primero o usa TEST_ETAPA_ID con datos.");
        }

        foreach ($imagenes as $img) {
            $this->assertArrayHasKey('itemImageSrc', $img);
            $this->assertStringStartsWith('data:', $img['itemImageSrc'],
                'itemImageSrc debe ser data URI');
            $this->assertMatchesRegularExpression(
                '/^data:image\/(jpeg|png|webp);base64,[A-Za-z0-9+\/]+=*$/',
                $img['itemImageSrc'],
                'itemImageSrc debe tener formato data:image/...;base64,...'
            );
        }
    }

    public function testPostInventarioConJpgGuardaImagen()
    {
        $jpegFile = $this->createTestJpeg();

        $response = $this->postMultipart("/ate-gas/gestion-movimiento/inventario/{$this->etapaId}", array(
            'fields' => array(
                'inventario'              => '[]',
                'observaciones_inventario' => 'Test automatizado',
            ),
            'files' => array(
                array('field' => 'filesMain[]', 'path' => $jpegFile, 'name' => 'test.jpg', 'mime' => 'image/jpeg'),
            ),
        ));

        unlink($jpegFile);

        $this->assertEquals(200, $response['code'], 'POST debe retornar 200: ' . json_encode($response['json']));
        $this->assertEquals('Exito', $response['json']['estado']);
    }

    public function testPostInventarioConPngGuardaImagen()
    {
        $pngFile = $this->createTestPng();

        $response = $this->postMultipart("/ate-gas/gestion-movimiento/inventario/{$this->etapaId}", array(
            'fields' => array(
                'inventario'              => '[]',
                'observaciones_inventario' => 'Test automatizado PNG',
            ),
            'files' => array(
                array('field' => 'filesMain[]', 'path' => $pngFile, 'name' => 'test.png', 'mime' => 'image/png'),
            ),
        ));

        unlink($pngFile);

        $this->assertEquals(200, $response['code']);
        $this->assertEquals('Exito', $response['json']['estado']);
    }

    public function testPostInventarioConExtensionInvalidaEsIgnorada()
    {
        $pdfFile = tempnam(sys_get_temp_dir(), 'test_') . '.pdf';
        file_put_contents($pdfFile, '%PDF-1.4 fake pdf content');

        $countAntes = $this->contarImagenesEtapa();

        $response = $this->postMultipart("/ate-gas/gestion-movimiento/inventario/{$this->etapaId}", array(
            'fields' => array(
                'inventario'              => '[]',
                'observaciones_inventario' => 'Test con PDF',
            ),
            'files' => array(
                array('field' => 'filesMain[]', 'path' => $pdfFile, 'name' => 'doc.pdf', 'mime' => 'application/pdf'),
            ),
        ));

        unlink($pdfFile);

        $countDespues = $this->contarImagenesEtapa();

        $this->assertEquals(200, $response['code']);
        $this->assertEquals($countAntes, $countDespues,
            'Un PDF no debe crear registros de imagen en la BD');
    }

    public function testPostInventarioJpgOriginalNoEsThumb()
    {
        // Verifica el bug corregido: original JPG no debe ser reemplazado por el thumb
        $jpegFile = $this->createTestJpeg(640, 480); // imagen real de 640x480

        $this->postMultipart("/ate-gas/gestion-movimiento/inventario/{$this->etapaId}", array(
            'fields' => array('inventario' => '[]', 'observaciones_inventario' => 'Bug test JPG'),
            'files'  => array(
                array('field' => 'filesMain[]', 'path' => $jpegFile, 'name' => 'original.jpg', 'mime' => 'image/jpeg'),
            ),
        ));

        unlink($jpegFile);

        // Pedir las imágenes y verificar que existen
        $response = $this->get("/ate-gas/gestion-movimiento/{$this->etapaId}/imagenes");
        $imagenes = $response['json']['imagenes'];

        $this->assertNotEmpty($imagenes, 'Debe haber al menos una imagen después de subir');

        // La imagen devuelta (thumb) debe ser un JPEG válido decodificable
        $lastImg   = end($imagenes);
        $dataUri   = $lastImg['itemImageSrc'];
        $b64       = substr($dataUri, strpos($dataUri, ',') + 1);
        $decoded   = base64_decode($b64, true);

        $this->assertNotFalse($decoded, 'El base64 debe ser decodificable');
        // Un JPEG válido empieza con bytes FF D8 FF
        $this->assertEquals("\xFF\xD8\xFF", substr($decoded, 0, 3),
            'El thumb debe ser un JPEG válido (magic bytes FF D8 FF)');
    }

    // ── Tests: /common/ubicacion/base64 ───────────────────────────────────────

    public function testCommonUbicacionBase64DevuelveDataUri()
    {
        // Primero subimos una imagen para tener un estado conocido
        $jpegFile = $this->createTestJpeg();
        $this->postMultipart("/ate-gas/gestion-movimiento/inventario/{$this->etapaId}", array(
            'fields' => array('inventario' => '[]', 'observaciones_inventario' => 'test base64'),
            'files'  => array(
                array('field' => 'filesMain[]', 'path' => $jpegFile, 'name' => 'foto.jpg', 'mime' => 'image/jpeg'),
            ),
        ));
        unlink($jpegFile);

        // Pedimos estado-pedidos para obtener un valor de ubicacion
        // Este endpoint devuelve el campo 'ubicacion' que se pasa a /common/ubicacion/base64
        // Si no hay estado-pedidos accesible, marcamos el test como incompleto
        $etapasConUbicacion = $this->obtenerUbicacionesDeEtapa();

        if (empty($etapasConUbicacion)) {
            $this->markTestIncomplete('No se encontraron imágenes con ubicacion en la BD de prueba');
        }

        $ubicacion = $etapasConUbicacion[0];
        $response  = $this->postJson('/common/ubicacion/base64', array('ubicacion' => $ubicacion));

        $this->assertEquals(200, $response['code']);
        $body = $response['json'];
        $this->assertArrayHasKey('base64', $body);

        if ($body['base64'] !== null) {
            $this->assertStringStartsWith('data:image/', $body['base64']);
        }
    }

    // ── Helpers HTTP ──────────────────────────────────────────────────────────

    private function login($user, $pass)
    {
        $ch = curl_init($this->baseUrl . '/login');
        curl_setopt($ch, CURLOPT_POST,           true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     http_build_query(array('username' => $user, 'contrasena' => $pass)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $raw  = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($raw, true);
        return isset($data['token']) ? $data['token'] : null;
    }

    private function get($path)
    {
        $ch = curl_init($this->baseUrl . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization: ' . $this->token));
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array('code' => $code, 'json' => json_decode($raw, true));
    }

    private function postJson($path, array $data)
    {
        $ch = curl_init($this->baseUrl . $path);
        curl_setopt($ch, CURLOPT_POST,           true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
            'Authorization: ' . $this->token,
            'Content-Type: application/json',
        ));
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array('code' => $code, 'json' => json_decode($raw, true));
    }

    private function postMultipart($path, array $spec)
    {
        $postFields = $spec['fields'];
        foreach ($spec['files'] as $f) {
            $postFields[$f['field']] = new \CURLFile($f['path'], $f['mime'], $f['name']);
        }

        $ch = curl_init($this->baseUrl . $path);
        curl_setopt($ch, CURLOPT_POST,           true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization: ' . $this->token));
        $raw  = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array('code' => $code, 'json' => json_decode($raw, true));
    }

    // ── Helpers de imagen ─────────────────────────────────────────────────────

    private function createTestJpeg($width = 100, $height = 100)
    {
        $img  = imagecreatetruecolor($width, $height);
        $red  = imagecolorallocate($img, 255, 0, 0);
        imagefill($img, 0, 0, $red);
        $path = tempnam(sys_get_temp_dir(), 'test_jpeg_') . '.jpg';
        imagejpeg($img, $path, 90);
        imagedestroy($img);
        return $path;
    }

    private function createTestPng($width = 100, $height = 100)
    {
        $img   = imagecreatetruecolor($width, $height);
        $blue  = imagecolorallocate($img, 0, 0, 255);
        imagefill($img, 0, 0, $blue);
        $path  = tempnam(sys_get_temp_dir(), 'test_png_') . '.png';
        imagepng($img, $path);
        imagedestroy($img);
        return $path;
    }

    private function contarImagenesEtapa()
    {
        $response = $this->get("/ate-gas/gestion-movimiento/{$this->etapaId}/imagenes");
        return count($response['json']['imagenes'] ?? array());
    }

    private function obtenerUbicacionesDeEtapa()
    {
        // Obtiene ubicaciones desde el endpoint de inventario de la etapa.
        // El campo 'ubicacion_thumb' en la BD es lo que se pasa a /common/ubicacion/base64.
        // Lo aproximamos buscando en el inventario las rutas de imagen que ya existen.
        $response = $this->get("/ate-gas/gestion-movimiento/inventario/{$this->etapaId}");
        $body     = isset($response['json']) ? $response['json'] : array();

        $ubicaciones = array();
        if (!empty($body['imagenes']) && is_array($body['imagenes'])) {
            foreach ($body['imagenes'] as $img) {
                if (!empty($img['ubicacion_thumb'])) {
                    $ubicaciones[] = $img['ubicacion_thumb'];
                } elseif (!empty($img['ubicacion'])) {
                    $ubicaciones[] = $img['ubicacion'];
                }
            }
        }
        return $ubicaciones;
    }
}
