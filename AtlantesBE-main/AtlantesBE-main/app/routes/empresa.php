<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/empresa', function (Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token = $headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa = $decoded_array['idempresa'];

    $empresa = [];
    $result = $conexion->query("SELECT
        empresa,
        titulo,
        IFNULL(operaciones,0) as operaciones,
        IFNULL(contabilidad,0) as contabilidad,
        IFNULL(almacen,0) as almacen
        FROM
        t_empresa
        WHERE
        idempresa=$idempresa;");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $imagenes = [
            'logo'             => null,
            'factura_membrete' => null,
        ];
        $ruta = folder_files . $idempresa . DIRECTORY_SEPARATOR . 'empresa/';
        if (file_exists($ruta)) {
            $directorio = opendir($ruta);
            while ($documento = readdir($directorio)) {
                if (!is_dir($documento)) {
                    if (strpos($documento, 'logo')) {
                        $imagenes['logo'] = $documento;
                    }
                    if (strpos($documento, 'factura_membrete')) {
                        $imagenes['factura_membrete'] = $documento;
                    }
                }
            }
        }

        $empresa = [
            'empresa'      => $row['empresa'],
            'titulo'       => $row['titulo'],
            'operaciones'  => boolval($row['operaciones']),
            'contabilidad' => boolval($row['contabilidad']),
            'almacen'      => boolval($row['almacen']),
            'imagenes'     => $imagenes,
        ];
    }

    $response->getBody()->write(json_encode([
        'estado'  => 'Exito',
        'codigo'  => 200,
        'mensaje' => 'Todo correcto',
        'empresa' => $empresa,
    ]));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/empresa', function (Request $request, Response $response, array $args) use ($conexion) {
    $params = json_decode((string) $request->getBody(), true);
    $headers = apache_request_headers();
    $token = $headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa = $decoded_array['idempresa'];

    $empresa = $params['empresa'];
    $titulo  = $params['titulo'];

    $codigo  = 400;
    $status  = 'Error';
    $mensaje = 'No se guardo la información';

    $query = "UPDATE t_empresa SET
        empresa='$empresa',
        titulo='$titulo'
        WHERE
        idempresa=$idempresa;";

    $result = $conexion->exec($query);

    if ($result === false) {
    } else {
        $codigo  = 200;
        $status  = 'Exito';
        $mensaje = 'Se guardo la información General';
    }

    $resultado = [
        'codigo'  => $codigo,
        'estado'  => $status,
        'mensaje' => $mensaje,
    ];

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/empresa/cargardocumento/{tipo}', function (Request $request, Response $response, array $args) use ($conexion, $archivospermitidos_imagenes) {
    $tipo = $args['tipo'];
    $headers = apache_request_headers();
    $token = $headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa = $decoded_array['idempresa'];

    $creacion   = new Carpetas();
    $respuesta  = $creacion->procesarCarpeta($idempresa);

    $codigo    = 200;
    $status    = 'Exito';
    $mensaje   = '';
    $file_name = [];

    if (isset($_FILES['uploads'])) {
        for ($fi = 0; $fi < count($_FILES['uploads']['name']); $fi++) {
            $nombredoc = $_FILES['uploads']['name'][$fi];
            $extension = strtolower(pathinfo($nombredoc, PATHINFO_EXTENSION));

            $piramideUploader = new PiramideUploader();
            $upload = $piramideUploader->upload(
                "$tipo.$extension",
                'uploads',
                folder_files . $idempresa . DIRECTORY_SEPARATOR . 'empresa',
                $archivospermitidos_imagenes,
                true,
                $fi
            );

            $file = $piramideUploader->getInfoFile();

            if (isset($upload) && $upload['uploaded'] == false) {
                $file_name[] = [
                    'name'    => $file['complete_name'],
                    'error'   => true,
                    'mensaje' => $upload['error'],
                ];
            } else {
                $file_name[] = [
                    'name'    => $file['complete_name'],
                    'error'   => false,
                    'mensaje' => '',
                ];
            }
        }
    }

    $resultado = [
        'codigo'    => $codigo,
        'estado'    => $status,
        'mensaje'   => $mensaje,
        'file_name' => $file_name,
    ];

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);
