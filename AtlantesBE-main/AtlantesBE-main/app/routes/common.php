<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/common/ubicacion/base64', function (Request $request, Response $response, array $args) {
    $headers = apache_request_headers();
    $token = $headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idalmacen = $decoded_array['idalmacen'];

    $params   = json_decode((string) $request->getBody(), true);
    $ubicacion = isset($params['ubicacion']) ? $params['ubicacion'] : '';

    $common = new Common();
    $base64 = null;

    // Intento 1: disco local (comportamiento original)
    if ($ubicacion !== '') {
        $base64 = $common->crearBase64($ubicacion);
    }

    // Intento 2: Azure Blob Storage si el archivo no existe localmente
    $azureBlobComun = defined('azure_blob_enabled') ? (bool) constant('azure_blob_enabled') : false;
    if ($base64 === null && $ubicacion !== '' && $azureBlobComun) {
        // Derivar el blob name quitando el prefijo folder_files si está presente
        $blobName = str_replace('\\', '/', $ubicacion);
        $folderFilesNorm = str_replace('\\', '/', folder_files);
        if (strpos($blobName, $folderFilesNorm) === 0) {
            $blobName = substr($blobName, strlen($folderFilesNorm));
        }
        $blobName = ltrim($blobName, '/');

        $blobStorage = new BlobStorageService();
        try {
            $blob = $blobStorage->getBlob($blobName);
            if ($blob !== null) {
                $base64 = 'data:' . $blob['contentType'] . ';base64,' . base64_encode($blob['content']);
            }
        } catch (Throwable $e) {
            error_log('[AzureBlob] /common/ubicacion/base64 blob=' . $blobName . ' err=' . $e->getMessage());
        }
    }

    $response->getBody()->write(json_encode([
        'estado'  => 'Exito',
        'codigo'  => 200,
        'mensaje' => 'Todo correcto',
        'base64'  => $base64,
    ]));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);
