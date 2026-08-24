<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Response;

/**
 * Middleware de autenticación JWT.
 * Valida el token Authorization. Si es inválido o ausente devuelve 401.
 */
$verifyToken = function (Request $request, Handler $handler) use ($conexion) {
    $token = $request->getHeaderLine('Authorization');
    try {
        $decoded = (array) JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded = DateTimeService::ensureClaimsTimezone($conexion, $decoded);
        date_default_timezone_set($decoded['timezone_name']);
        $conexion->query("SET time_zone = '" . DateTimeService::mysqlTimeZoneOffset($decoded['utc_offset_minutos']) . "';");
    } catch (\Exception $e) {
        $response = new Response();
        $response->getBody()->write(json_encode([
            'estado'  => 'Error',
            'codigo'  => 401,
            'mensaje' => 'La sesión no existe o ya expiró',
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }
    $request = $request->withAttribute('auth', $decoded);
    $request = $request->withAttribute('timezone', DateTimeService::timezoneFromClaims($decoded));
    return $handler->handle($request);
};

/**
 * Middleware factory de autorización por rol.
 * $verifyRole(idmodulo, tipoPermiso) devuelve un middleware PSR-15.
 * tipoPermiso: 1 = lectura, 2 = escritura.
 */
$verifyRole = function (int $idmodulo, int $tipoPermiso) use ($conexion) {
    return function (Request $request, Handler $handler) use ($idmodulo, $tipoPermiso, $conexion) {
        $token = $request->getHeaderLine('Authorization');
        $decoded = (array) JWT::decode($token, new Key(jwt_key, 'HS256'));

        if ((int) $decoded['idtipousuario'] === 1) {
            return $handler->handle($request);
        }

        $columna = $tipoPermiso === 1 ? 'lectura' : 'escritura';
        $permiso = false;
        $result = $conexion->query(
            "SELECT $columna FROM t_usuariomodulo
             WHERE idmodulo=$idmodulo AND idusuario=" . (int) $decoded['idusuario'] . ";"
        );
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $permiso = boolval($row[$columna]);
        }

        if (!$permiso) {
            $response = new Response();
            $response->getBody()->write(json_encode([
                'estado'  => 'Error',
                'codigo'  => 403,
                'mensaje' => 'No tiene permisos para realizar esta operacion',
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    };
};
