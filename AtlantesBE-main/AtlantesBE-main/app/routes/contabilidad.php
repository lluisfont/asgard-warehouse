<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/contabilidad/facturas', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $facturas=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_cobrado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_cobrado (idfacturanotadebito INT, cobrado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_cobrado (idfacturanotadebito, cobrado)
        SELECT 
        idfacturanotadebito,
        SUM(monto) as cobrado
        FROM 
        t_cobro
        WHERE
        idtipocobro=1
        GROUP BY
        idfacturanotadebito;");
    $conexion->query("ALTER TABLE tmp_cobrado ADD INDEX idfacturanotadebito (idfacturanotadebito);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_facturado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_facturado (idfactura INT, facturado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_facturado (idfactura, facturado)
        SELECT
        t_factura.idfactura,
	SUM(ROUND(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio,2)) as facturado
	FROM
	t_factura
	LEFT JOIN t_cargo ON t_factura.idfactura=t_cargo.idfacturanotadebito
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
        IFNULL(t_cargo.idtipofacturanotadebito,0)=1
        AND t_embarque.idempresa=$idempresa
        GROUP BY
        t_factura.idfactura;");
    $conexion->query("ALTER TABLE tmp_facturado ADD INDEX idfactura (idfactura);");
    
    $result = $conexion->query("SELECT 
        t_factura.idfactura,
        t_factura.nrofactura,
        CONCAT(t_factura.fecha,' 12:00:00') as fecha,
        t_factura.nit,
        t_factura.nombre,
        IFNULL(tmp_facturado.facturado,0) as valorfacturado,
        IFNULL(tmp_cobrado.cobrado,0) as cobrado,
        IFNULL(tmp_facturado.facturado,0)-IFNULL(tmp_cobrado.cobrado,0) as saldo,
        t_factura.idestadofactura,
        t_estadofactura.estadofactura,
        t_factura.codigocontrol,
        t_factura.idembarque,
        t_embarque.embarque,
        t_embarque.importacion_exportacion,
        t_importacion_exportacion.importacion_exportacion_codigo,
        t_embarque.nodui,
        IFNULL(t_embarque.carpetapacena,'') as carpetapacena,
        t_transportistaembarque.transportista,
        t_ciudad.ciudad as ciudad,
        t_ciudadembarque.ciudad as ciudadembarque,
        t_usuario.nombre as usuario,
        t_factura.idcobrara,
        t_factura.idcobraratipo,
        CASE t_factura.idcobraratipo
            WHEN 1 THEN t_cliente.cliente
            WHEN 2 THEN t_proveedor.proveedor
            WHEN 3 THEN t_prestador.prestador
            WHEN 4 THEN t_transportista.transportista
            WHEN 5 THEN t_agentecarga.agentecarga
        END as entidadcobrar,
        t_dosificacion.nroautorizacion,
        IFNULL(t_factura.outIdOrdenFactura,'') as outIdOrdenFactura,
        t_factura.outNumeroFactura,
        IFNULL(t_factura.errorOVPFact,'') as errorOVPFact,
        t_factura.fecha_anulacion,
        tmp_usuario_anulacion.nombre as usuario_anulacion,
        t_motivoanulacion.motivoanulacion,
        t_factura.otro_motivoanulacion,
        t_factura.resplado_anulacion
        from 
        t_factura
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        LEFT JOIN t_importacion_exportacion ON t_embarque.importacion_exportacion=t_importacion_exportacion.importacion_exportacion
        LEFT JOIN t_transportista as t_transportistaembarque ON t_embarque.idtransportista=t_transportistaembarque.idtransportista
        LEFT JOIN t_ciudad as t_ciudadembarque ON t_embarque.idciudad=t_ciudadembarque.idciudad
        LEFT JOIN t_ciudad ON t_embarque.idarribo=t_ciudad.idciudad
        LEFT JOIN t_usuario ON t_embarque.idusuario=t_usuario.idusuario
        LEFT JOIN t_estadofactura ON t_factura.idestadofactura=t_estadofactura.idestadofactura
        LEFT JOIN t_cliente ON t_factura.idcobrara=t_cliente.idcliente
        LEFT JOIN t_proveedor ON t_factura.idcobrara=t_proveedor.idproveedor
        LEFT JOIN t_prestador ON t_factura.idcobrara=t_prestador.idprestador
        LEFT JOIN t_transportista ON t_factura.idcobrara=t_transportista.idtransportista
        LEFT JOIN t_agentecarga ON t_factura.idcobrara=t_agentecarga.idagentecarga
        LEFT JOIN t_dosificacion ON t_factura.iddosificacion=t_dosificacion.iddosificacion
        LEFT JOIN tmp_cobrado ON t_factura.idfactura=tmp_cobrado.idfacturanotadebito
        LEFT JOIN tmp_facturado ON t_factura.idfactura=tmp_facturado.idfactura
        LEFT JOIN t_motivoanulacion ON t_factura.idmotivoanulacion=t_motivoanulacion.idmotivoanulacion
        LEFT JOIN t_usuario as tmp_usuario_anulacion ON t_factura.idusuarios_anulacion=tmp_usuario_anulacion.idusuario
        WHERE
        t_embarque.idempresa=$idempresa
        ORDER BY
        t_factura.iddosificacion DESC,
        t_factura.fecha DESC,
        IF(t_factura.nrofactura<0,99999999,t_factura.nrofactura) DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idordenovp=(!empty($row["outIdOrdenFactura"]) || $row["outIdOrdenFactura"] !=NULL ? $row["outNumeroFactura"]." | ".$row["outIdOrdenFactura"] : '');
        $facturas[]=array(
            'idfactura'=>(int)$row['idfactura'],
            'nrofactura'=>(int)$row['nrofactura'],
            'fecha'=>$row['fecha'],
            'nit'=>$row['nit'],
            'nombre'=>$row['nombre'],
            'valorfacturado'=>(float)$row['valorfacturado'],
            'cobrado'=>(float)$row['cobrado'],
            'saldo'=>(float)$row['saldo'],
            'idestadofactura'=>(int)$row['idestadofactura'],
            'estadofactura'=>$row['estadofactura'],
            'codigocontrol'=>$row['codigocontrol'],
            'idembarque'=>(int)$row['idembarque'],
            'embarque'=>$row['embarque'],
            'importacion_exportacion'=>(int)$row['importacion_exportacion'],
            'importacion_exportacion_codigo'=>$row['importacion_exportacion_codigo'],
            'nodui'=>$row['nodui'],
            'carpetapacena'=>$row['carpetapacena'],
            'transportista'=>$row['transportista'],
            'ciudad'=>$row['ciudad'],
            'ciudadembarque'=>$row['ciudadembarque'],
            'usuario'=>$row['usuario'],
            'idcobrara'=>(int)$row['idcobrara'],
            'idcobraratipo'=>(int)$row['idcobraratipo'],
            'entidadcobrar'=>$row['entidadcobrar'],
            'nroautorizacion'=>$row['nroautorizacion'],
            'outIdOrdenFactura'=>$row['outIdOrdenFactura'],
            'outNumeroFactura'=>$row['outNumeroFactura'],
            'idordenovp'=>$idordenovp,
            'errorOVPFact'=>$row['errorOVPFact'],
            'fecha_anulacion'=>$row['fecha_anulacion'],
            'usuario_anulacion'=>$row['usuario_anulacion'],
            'motivoanulacion'=>$row['motivoanulacion'],
            'otro_motivoanulacion'=>$row['otro_motivoanulacion'],
            'resplado_anulacion'=>$row['resplado_anulacion']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'facturas' => $facturas
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/rangofacturas/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $facturas=[];
    $result = $conexion->query("SELECT 
        t_factura.idfactura,
        t_factura.nrofactura,
        CONCAT(t_factura.fecha,' 12:00:00') as fecha,
        t_factura.nit,
        t_factura.nombre,
        valorfacturado(t_factura.idfactura) as valorfacturado,
        t_factura.idestadofactura,
        t_estadofactura.estadofactura,
        t_factura.codigocontrol,
        t_factura.idembarque,
        t_embarque.embarque,
        t_embarque.importacion_exportacion,
        t_importacion_exportacion.importacion_exportacion_codigo,
        t_embarque.nodui,
        IFNULL(t_embarque.carpetapacena,'') as carpetapacena,
        t_transportistaembarque.transportista,
        t_ciudad.ciudad as ciudad,
        t_ciudadembarque.ciudad as ciudadembarque,
        t_usuario.nombre as usuario,
        t_factura.idcobrara,
        t_factura.idcobraratipo,
        CASE t_factura.idcobraratipo
            WHEN 1 THEN t_cliente.cliente
            WHEN 2 THEN t_proveedor.proveedor
            WHEN 3 THEN t_prestador.prestador
            WHEN 4 THEN t_transportista.transportista
            WHEN 5 THEN t_agentecarga.agentecarga
        END as entidadcobrar,
        t_dosificacion.nroautorizacion,
        t_tipocambio.tipocambio,
        IFNULL(t_factura.outIdOrdenFactura,'') as outIdOrdenFactura,
        t_factura.outNumeroFactura,
        IFNULL(t_factura.errorOVPFact,'') as errorOVPFact
        from 
        t_factura
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        LEFT JOIN t_importacion_exportacion ON t_embarque.importacion_exportacion=t_importacion_exportacion.importacion_exportacion
        LEFT JOIN t_transportista as t_transportistaembarque ON t_embarque.idtransportista=t_transportistaembarque.idtransportista
        LEFT JOIN t_ciudad as t_ciudadembarque ON t_embarque.idciudad=t_ciudadembarque.idciudad
        LEFT JOIN t_ciudad ON t_embarque.idarribo=t_ciudad.idciudad
        LEFT JOIN t_usuario ON t_embarque.idusuario=t_usuario.idusuario
        LEFT JOIN t_estadofactura ON t_factura.idestadofactura=t_estadofactura.idestadofactura
        LEFT JOIN t_cliente ON t_factura.idcobrara=t_cliente.idcliente
        LEFT JOIN t_proveedor ON t_factura.idcobrara=t_proveedor.idproveedor
        LEFT JOIN t_prestador ON t_factura.idcobrara=t_prestador.idprestador
        LEFT JOIN t_transportista ON t_factura.idcobrara=t_transportista.idtransportista
        LEFT JOIN t_agentecarga ON t_factura.idcobrara=t_agentecarga.idagentecarga
        LEFT JOIN t_dosificacion ON t_factura.iddosificacion=t_dosificacion.iddosificacion
        LEFT JOIN t_tipocambio ON t_factura.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_factura.fecha) AND 1=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE 
        t_factura.fecha BETWEEN '$fechainicial' AND '$fechafinal'
        AND t_embarque.idempresa=$idempresa
        ORDER BY
        t_factura.iddosificacion DESC,
        t_factura.nrofactura DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idordenovp=(!empty($row["outIdOrdenFactura"]) || $row["outIdOrdenFactura"] !=NULL ? $row["outNumeroFactura"]." | ".$row["outIdOrdenFactura"] : '');
        $facturas[]=array(
            'idfactura'=>(int)$row['idfactura'],
            'nrofactura'=>(int)$row['nrofactura'],
            'fecha'=>$row['fecha'],
            'nit'=>$row['nit'],
            'nombre'=>$row['nombre'],
            'valorfacturado'=>(float)$row['valorfacturado'],
            'idestadofactura'=>(int)$row['idestadofactura'],
            'estadofactura'=>$row['estadofactura'],
            'codigocontrol'=>$row['codigocontrol'],
            'idembarque'=>(int)$row['idembarque'],
            'embarque'=>$row['embarque'],
            'importacion_exportacion'=>(int)$row['importacion_exportacion'],
            'importacion_exportacion_codigo'=>$row['importacion_exportacion_codigo'],
            'nodui'=>$row['nodui'],
            'carpetapacena'=>$row['carpetapacena'],
            'transportista'=>$row['transportista'],
            'ciudad'=>$row['ciudad'],
            'ciudadembarque'=>$row['ciudadembarque'],
            'usuario'=>$row['usuario'],
            'idcobrara'=>(int)$row['idcobrara'],
            'idcobraratipo'=>(int)$row['idcobraratipo'],
            'entidadcobrar'=>$row['entidadcobrar'],
            'nroautorizacion'=>$row['nroautorizacion'],
            'tipocambio'=>(float)$row['tipocambio'],
            'outIdOrdenFactura'=>$row['outIdOrdenFactura'],
            'outNumeroFactura'=>$row['outNumeroFactura'],
            'idordenovp'=>$idordenovp,
            'errorOVPFact'=>$row['errorOVPFact']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'facturas' => $facturas
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/generarfactura/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idusuario=$decoded_array["idusuario"];
    $idempresa=$decoded_array["idempresa"];

    $params = json_decode((string) $request->getBody(),true);
    $idfactura = $params['idfactura'] ?? 0;
    $cargos = $params["cargos"] ?? [];
    $correos = $params["correos"] ?? [];

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $codigocontrol = '';
    $nrofactura = rand(-9999999, -1000000);
    $iddosificacion = 0;

    $respuesta = [
        "migrado" => false
    ];

    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if (empty($idempresa)) {
            $mensaje = 'No se recibió la empresa';
            $continuar = false;
        }

        if ($continuar && empty($idembarque)) {
            $mensaje = 'No se recibió el embarque';
            $continuar = false;
        }

        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        if ($continuar && empty($params['idcobrara'])) {
            $mensaje = 'No se recibió el dato de cobrar a';
            $continuar = false;
        }

        if ($continuar && empty($params['nombre'])) {
            $mensaje = 'No se recibió el nombre para la factura';
            $continuar = false;
        }

        if ($continuar && empty($params['nit'])) {
            $mensaje = 'No se recibió el NIT';
            $continuar = false;
        }

        if ($continuar && empty($params['idtipodocumento'])) {
            $mensaje = 'No se recibió el tipo de documento';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Buscar dosificación activa
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDosificacion = "
                SELECT 
                    iddosificacion,
                    llave,
                    nroautorizacion
                FROM t_dosificacion
                WHERE CURRENT_DATE() BETWEEN fechainicio AND IFNULL(fechalimite, CURRENT_DATE())
                AND idempresa = :idempresa
                ORDER BY fechainicio
                LIMIT 1
            ";

            $stmtDosificacion = $conexion->prepare($queryDosificacion);
            $stmtDosificacion->execute([
                ':idempresa' => $idempresa
            ]);

            $rowDosificacion = $stmtDosificacion->fetch(PDO::FETCH_ASSOC);

            if (!$rowDosificacion) {
                $mensaje = 'No existe dosificación cargada';
                $continuar = false;
            } else {
                $iddosificacion = (int)$rowDosificacion['iddosificacion'];
                $llave = $rowDosificacion['llave'];
                $nroautorizacion = $rowDosificacion['nroautorizacion'];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Separar idcobrara
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idcobrara = $params['idcobrara'];
            $idcobrarasplit = explode("-", $idcobrara);

            if (count($idcobrarasplit) < 2) {
                $mensaje = 'El formato de cobrar a no es válido';
                $continuar = false;
            } else {
                $idcobraratipo = (int)$idcobrarasplit[0];
                $idcobraraValor = (int)$idcobrarasplit[1];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Iniciar transacción
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $conexion->beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Actualizar o insertar factura
            |--------------------------------------------------------------------------
            */
            if ((int)$idfactura > 0) {

                $queryFactura = "
                    UPDATE t_factura
                    SET
                        idcobrara = :idcobrara,
                        idcobraratipo = :idcobraratipo,
                        nombre = :nombre,
                        nit = :nit,
                        codigocontrol = '',
                        idestadofactura = 1,
                        pallets = :pallets,
                        rotacion = :rotacion
                    WHERE idfactura = :idfactura
                ";

                $stmtFactura = $conexion->prepare($queryFactura);
                $stmtFactura->execute([
                    ':idcobrara' => $idcobraraValor,
                    ':idcobraratipo' => $idcobraratipo,
                    ':nombre' => $params["nombre"],
                    ':nit' => $params["nit"],
                    ':pallets' => $params["pallets"] ?? '',
                    ':rotacion' => $params["rotacion"] ?? '',
                    ':idfactura' => $idfactura
                ]);

                $idfacturaNueva = (int)$idfactura;

            } else {

                $queryFactura = "
                    INSERT INTO t_factura (
                        idembarque,
                        iddosificacion,
                        nrofactura,
                        fecha,
                        idcobrara,
                        idcobraratipo,
                        nombre,
                        idtipodocumento,
                        nit,
                        codigocontrol,
                        idestadofactura,
                        pallets,
                        rotacion
                    ) VALUES (
                        :idembarque,
                        :iddosificacion,
                        :nrofactura,
                        CURRENT_DATE(),
                        :idcobrara,
                        :idcobraratipo,
                        :nombre,
                        :idtipodocumento,
                        :nit,
                        '',
                        1,
                        :pallets,
                        :rotacion
                    )
                ";

                $stmtFactura = $conexion->prepare($queryFactura);
                $stmtFactura->execute([
                    ':idembarque' => $idembarque,
                    ':iddosificacion' => $iddosificacion,
                    ':nrofactura' => $nrofactura,
                    ':idcobrara' => $idcobraraValor,
                    ':idcobraratipo' => $idcobraratipo,
                    ':nombre' => $params["nombre"],
                    ':idtipodocumento' => $params["idtipodocumento"],
                    ':nit' => $params["nit"],
                    ':pallets' => $params["pallets"] ?? '',
                    ':rotacion' => $params["rotacion"] ?? ''
                ]);

                $idfacturaNueva = (int)$conexion->lastInsertId();
            }

            /*
            |--------------------------------------------------------------------------
            | Asociar cargos a la factura
            |--------------------------------------------------------------------------
            */
            if (is_array($cargos)) {

                $queryCargo = "
                    UPDATE t_cargo
                    SET 
                        idfacturanotadebito = :idfactura,
                        idtipofacturanotadebito = 1
                    WHERE idcargo = :idcargo
                ";

                $stmtCargo = $conexion->prepare($queryCargo);

                foreach ($cargos as $idcargo) {
                    if (!empty($idcargo)) {
                        $stmtCargo->execute([
                            ':idfactura' => $idfacturaNueva,
                            ':idcargo' => $idcargo
                        ]);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Registrar correos nuevos
            |--------------------------------------------------------------------------
            */
            if (is_array($correos)) {

                $queryExisteCorreo = "
                    SELECT idcorreonit
                    FROM t_correonit
                    WHERE idtipodocumento = :idtipodocumento
                    AND numero = :numero
                    AND correo = :correo
                    LIMIT 1
                ";

                $stmtExisteCorreo = $conexion->prepare($queryExisteCorreo);

                $queryInsertCorreo = "
                    INSERT INTO t_correonit (
                        idtipodocumento,
                        numero,
                        correo
                    ) VALUES (
                        :idtipodocumento,
                        :numero,
                        :correo
                    )
                ";

                $stmtInsertCorreo = $conexion->prepare($queryInsertCorreo);

                foreach ($correos as $correoItem) {
                    $correo = $correoItem["correo"] ?? '';

                    if (!empty($correo)) {
                        $stmtExisteCorreo->execute([
                            ':idtipodocumento' => $params["idtipodocumento"],
                            ':numero' => $params["nit"],
                            ':correo' => $correo
                        ]);

                        $rowCorreo = $stmtExisteCorreo->fetch(PDO::FETCH_ASSOC);

                        if (!$rowCorreo) {
                            $stmtInsertCorreo->execute([
                                ':idtipodocumento' => $params["idtipodocumento"],
                                ':numero' => $params["nit"],
                                ':correo' => $correo
                            ]);
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Eliminar correos que ya no están en el arreglo recibido
            |--------------------------------------------------------------------------
            */
            $correosRecibidos = [];

            if (is_array($correos)) {
                foreach ($correos as $correoItem) {
                    if (!empty($correoItem["correo"])) {
                        $correosRecibidos[] = $correoItem["correo"];
                    }
                }
            }

            $queryCorreosActuales = "
                SELECT 
                    idcorreonit,
                    correo
                FROM t_correonit
                WHERE idtipodocumento = :idtipodocumento
                AND numero = :numero
            ";

            $stmtCorreosActuales = $conexion->prepare($queryCorreosActuales);
            $stmtCorreosActuales->execute([
                ':idtipodocumento' => $params["idtipodocumento"],
                ':numero' => $params["nit"]
            ]);

            $queryDeleteCorreo = "
                DELETE FROM t_correonit
                WHERE idcorreonit = :idcorreonit
            ";

            $stmtDeleteCorreo = $conexion->prepare($queryDeleteCorreo);

            while ($rowCorreoActual = $stmtCorreosActuales->fetch(PDO::FETCH_ASSOC)) {
                if (!in_array($rowCorreoActual["correo"], $correosRecibidos)) {
                    $stmtDeleteCorreo->execute([
                        ':idcorreonit' => $rowCorreoActual["idcorreonit"]
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Recuperar factura generada / actualizada
            |--------------------------------------------------------------------------
            */
            $queryFacturaFinal = "
                SELECT 
                    idfactura,
                    nrofactura
                FROM t_factura
                WHERE idfactura = :idfactura
                LIMIT 1
            ";

            $stmtFacturaFinal = $conexion->prepare($queryFacturaFinal);
            $stmtFacturaFinal->execute([
                ':idfactura' => $idfacturaNueva
            ]);

            $rowFacturaFinal = $stmtFacturaFinal->fetch(PDO::FETCH_ASSOC);

            if ($rowFacturaFinal) {
                $idfactura = (int)$rowFacturaFinal['idfactura'];
                $nrofactura = $rowFacturaFinal['nrofactura'];
            }

            $conexion->commit();
        }

        /*
        |--------------------------------------------------------------------------
        | Migrar a OVP / generar factura / enviar correo
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            if (OVPACTIVO == 1) {

                $respuesta = migrarfacturaovp($idfactura, $idusuario, $conexion);

                if ($respuesta["migrado"]) {

                    $queryFacturaMigrada = "
                        SELECT nrofactura
                        FROM t_factura
                        WHERE idfactura = :idfactura
                        LIMIT 1
                    ";

                    $stmtFacturaMigrada = $conexion->prepare($queryFacturaMigrada);
                    $stmtFacturaMigrada->execute([
                        ':idfactura' => $idfactura
                    ]);

                    $rowFacturaMigrada = $stmtFacturaMigrada->fetch(PDO::FETCH_ASSOC);

                    if ($rowFacturaMigrada) {
                        $nrofactura = $rowFacturaMigrada['nrofactura'];
                    }

                    generarFactura($idfactura, $conexion, true);
                    enviarmailfactura($idfactura, $correos, $conexion);
                }

            } else {
                $respuesta["migrado"] = true;
            }

            if ($respuesta["migrado"]) {
                $codigo = 200;
                $status = 'Exito';
                $mensaje = 'Factura generada exitosamente';
            } else {
                $mensaje = 'Se generó la factura pero no se pudo migrar a OVP';
            }
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idfactura'=> $idfactura,
        'nrofactura'=>$nrofactura,
        'facturas' => getFacturas($idembarque, $conexion)
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/reservarfactura/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];

    $codigo=400;
    $status='Error';
    $mensaje='Esta Operación ya no esta disponible';
    /*
    $nrofactura="";
    $idfactura=0;

    $iddosificacion=0;
    $result = $conexion->query("select 
        iddosificacion, 
        llave, 
        nroautorizacion 
        from 
        t_dosificacion 
        WHERE 
        CURRENT_DATE() BETWEEN fechainicio AND IFNULL(fechalimite,CURRENT_DATE())
        AND idempresa=$idempresa
        ORDER BY
        fechainicio
        LIMIT 0,1;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $iddosificacion=$row['iddosificacion'];
        $llave=$row['llave'];
        $nroautorizacion=$row['nroautorizacion'];
    }

    $query='';
    if((int)$iddosificacion>0){
        $query=$query."INSERT INTO t_factura (idembarque,   iddosificacion,     nrofactura,                                                                                 fecha,          idestadofactura) 
                                       SELECT $idembarque,  $iddosificacion,    (SELECT IFNULL(MAX(nrofactura),0)+1 FROM t_factura WHERE iddosificacion=$iddosificacion),   CURRENT_DATE(), 3;";
        $query=$query."SELECT LAST_INSERT_ID() INTO @idfactura_nueva;";

        $query=$query."UPDATE t_dosificacion SET nrofacturaactual = 
                            (SELECT MAX(nrofactura) AS nrofactura
                                FROM t_factura
                                WHERE t_factura.iddosificacion = t_dosificacion.iddosificacion)
                        WHERE iddosificacion=$iddosificacion;";


        if(strlen($query)>0){
            $result = $conexion->exec($query);
            if($result){
                $resultnrofactura = $conexion->query("select 
                    idfactura,
                    nrofactura
                    from 
                    t_factura 
                    WHERE 
                    idfactura=@idfactura_nueva;");
                while ($rownrofactura =  $resultnrofactura ->fetch(PDO::FETCH_ASSOC)){
                    $nrofactura=$rownrofactura['nrofactura'];
                    $idfactura=$rownrofactura['idfactura'];
                }


                $codigo=200;
                $status='Exito';
                $mensaje='Factura generada exitosamente';



            }
        }
    }else{
        $mensaje="No existe Dosificacion Cargada";
    }
    */
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idfactura'=> null,
        'nrofactura'=>null,
        'facturas' => getFacturas($idembarque, $conexion)
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/facturas/download/{idfactura}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idfactura = $args['idfactura'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta=[];

    $file=folder_files.$idempresa."/documentos/facturas/factura$idfactura.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    generarFactura($idfactura, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/facturas/download/membretada/{idfactura}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idfactura = $args['idfactura'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/facturas/facturamembretada$idfactura.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    generarFactura($idfactura, $conexion, true);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/contabilidad/facturas/migrarovp/{idfactura}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idfactura = $args['idfactura'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idusuario=$decoded_array["idusuario"];
    
    $correos = json_decode((string) $request->getBody(),true);


    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';

    $respuesta= migrarfacturaovp($idfactura, $idusuario, $conexion);


    if($respuesta["migrado"]){
        $codigo=200;
        $status='Exito';
        $mensaje='Los datos se guardaron correctamente';

        generarFactura($idfactura, $conexion, true);

        enviarmailfactura($idfactura, $correos, $conexion);


    }




    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'respuesta'=>$respuesta
    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/facturas/anular/{idfactura}', function(Request $request, Response $response, array $args) use ($conexion, $archivospermitidos) {

    $idfactura = $args['idfactura'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $resplado_anulacion = '';
    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Token ya validado por middleware
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded_array = (array) $decoded;

        $idusuario = $decoded_array["idusuario"] ?? null;
        $idempresa = $decoded_array["idempresa"] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if (empty($idfactura)) {
            $mensaje = 'No se recibió la factura';
            $continuar = false;
        }

        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        if ($continuar && empty($idempresa)) {
            $mensaje = 'No se recibió la empresa';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Crear carpeta de empresa
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $creacion = new Carpetas();
            $respuesta = $creacion->procesarCarpeta($idempresa);
        }

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros del formulario
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $bodyParams = $request->getParsedBody();

            if (!is_array($bodyParams)) {
                $bodyParams = [];
            }

            $idmotivoanulacion = $bodyParams['idmotivoanulacion'] ?? null;
            $otro_motivoanulacion = '';

            if (empty($idmotivoanulacion)) {
                $mensaje = 'No se recibió el motivo de anulación';
                $continuar = false;
            }

            if ($continuar && (int)$idmotivoanulacion === 5) {
                $otro_motivoanulacion = $bodyParams['otro_motivoanulacion'] ?? '';

                if (trim($otro_motivoanulacion) === '') {
                    $mensaje = 'Debe ingresar el otro motivo de anulación';
                    $continuar = false;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validar archivo
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $files = $_FILES['uploads'] ?? null;

            $totalArchivos = (
                isset($files['name']) &&
                is_array($files['name'])
            ) ? count($files['name']) : 0;

            if ($totalArchivos === 0) {
                $mensaje = 'Debe adjuntar el respaldo de anulación';
                $continuar = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Subir respaldo
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            for ($fi = 0; $fi < $totalArchivos; $fi++) {

                if (!$continuar) {
                    break;
                }

                if (($files['error'][$fi] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    $mensaje = 'Ocurrió un problema al subir el archivo';
                    $continuar = false;
                    break;
                }

                $nombredoc = $files["name"][$fi] ?? '';

                if (trim($nombredoc) === '') {
                    $mensaje = 'El archivo no tiene nombre válido';
                    $continuar = false;
                    break;
                }

                $piramideUploader = new PiramideUploader();

                $upload = $piramideUploader->upload(
                    $nombredoc,
                    'uploads',
                    folder_files . $idempresa . DIRECTORY_SEPARATOR . 'respaldos_facturas_anuladas/' . $idfactura,
                    $archivospermitidos,
                    true,
                    $fi
                );

                $file = $piramideUploader->getInfoFile();

                if (isset($upload['uploaded']) && $upload['uploaded']) {
                    /*
                    Si llega más de un archivo, se guarda el último,
                    igual que en tu lógica original.
                    */
                    $resplado_anulacion = $file['complete_name'] ?? '';
                } else {
                    $mensaje = $upload['error'] ?? 'No se pudo subir el respaldo';
                    $continuar = false;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Anular factura y liberar cargos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            if (trim($resplado_anulacion) === '') {
                $mensaje = 'No se pudo obtener el respaldo de anulación';
                $continuar = false;
            }
        }

        if ($continuar) {

            $conexion->beginTransaction();

            $queryFactura = "
                UPDATE t_factura
                SET
                    idestadofactura = 2,
                    fecha_anulacion = CURRENT_TIMESTAMP(),
                    idusuarios_anulacion = :idusuario,
                    idmotivoanulacion = :idmotivoanulacion,
                    otro_motivoanulacion = :otro_motivoanulacion,
                    resplado_anulacion = :resplado_anulacion
                WHERE idfactura = :idfactura
            ";

            $stmtFactura = $conexion->prepare($queryFactura);

            $resultFactura = $stmtFactura->execute([
                ':idusuario' => $idusuario,
                ':idmotivoanulacion' => $idmotivoanulacion,
                ':otro_motivoanulacion' => $otro_motivoanulacion,
                ':resplado_anulacion' => $resplado_anulacion,
                ':idfactura' => $idfactura
            ]);

            if (!$resultFactura) {
                $mensaje = 'No se pudo anular la factura';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        if ($continuar) {

            $queryCargo = "
                UPDATE t_cargo
                SET
                    idfacturanotadebito = NULL,
                    idtipofacturanotadebito = NULL
                WHERE idfacturanotadebito = :idfactura
                  AND idtipofacturanotadebito = 1
            ";

            $stmtCargo = $conexion->prepare($queryCargo);

            $resultCargo = $stmtCargo->execute([
                ':idfactura' => $idfactura
            ]);

            if (!$resultCargo) {
                $mensaje = 'No se pudieron liberar los cargos asociados';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmar operación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->commit();

            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Los datos se guardaron correctamente';
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado = array(
        'codigo' => $codigo,
        'estado' => $status,
        'mensaje' => $mensaje,
        'resplado_anulacion' => $resplado_anulacion
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->post('/contabilidad/downloadrespaldo', function(Request $request, Response $response, array $args) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];

    $params = json_decode((string) $request->getBody(),true);
    $archivo=$params['archivo'];
    
    
    $codigo=400;
    $status='Error';
    $mensaje='Documento inexistente';
    $data='';
    $pathinfo='';
    $file=folder_files.$idempresa.DIRECTORY_SEPARATOR.$archivo;
    if (file_exists($file)) {
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/notascobranza', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $notascobranza=[];
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_cobrado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_cobrado (idfacturanotadebito INT, cobrado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_cobrado (idfacturanotadebito, cobrado)
        SELECT 
        idfacturanotadebito,
        SUM(monto) as cobrado
        FROM 
        t_cobro
        WHERE
        idtipocobro=2
        GROUP BY
        idfacturanotadebito;");
    $conexion->query("ALTER TABLE tmp_cobrado ADD INDEX idfacturanotadebito (idfacturanotadebito);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_debitado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_debitado (idfacturanotadebito INT, debitado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_debitado (idfacturanotadebito, debitado)
        SELECT
        t_notadebito.idnotadebito,
	SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as debitado
	FROM
	t_notadebito
	LEFT JOIN t_cargo ON t_notadebito.idnotadebito=t_cargo.idfacturanotadebito
        LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_notadebito.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
        IFNULL(t_cargo.idtipofacturanotadebito,0)=2
        AND t_embarque.idempresa=$idempresa
        GROUP BY
        t_notadebito.idnotadebito;");
    $conexion->query("ALTER TABLE tmp_debitado ADD INDEX idfacturanotadebito (idfacturanotadebito);");
    $result = $conexion->query("select 
        t_notadebito.idnotadebito,
        CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion) as nronotadebito,
        CONCAT(t_notadebito.fecha,' 12:00:00') as fecha,
        t_embarque.embarque,
        t_cliente.cliente,
        t_embarque.importacion_exportacion,
        t_importacion_exportacion.importacion_exportacion_codigo,
        t_embarque.nodui,
        IFNULL(t_embarque.carpetapacena,'') as carpetapacena,
        t_transportistaembarque.transportista,
        t_ciudad.ciudad as ciudad,
        t_ciudadembarque.ciudad as ciudadembarque,
        t_usuario.nombre as usuario,
        CASE t_notadebito.idestadonotadebito WHEN 2 THEN 0 ELSE ifnull(tmp_debitado.debitado,0) END as monto,
        IFNULL(tmp_cobrado.cobrado,0) as cobrado,
        (CASE t_notadebito.idestadonotadebito WHEN 2 THEN 0 ELSE ifnull(tmp_debitado.debitado,0) END)-IFNULL(tmp_cobrado.cobrado,0) as saldo,
        t_notadebito.idestadonotadebito,
        t_estadofactura.estadonotadebito,
        t_notadebito.idcobrara,
        t_notadebito.idcobraratipo,
        CASE t_notadebito.idcobraratipo
            WHEN 1 THEN t_clientecobrar.cliente
            WHEN 2 THEN t_proveedor.proveedor
            WHEN 3 THEN t_prestador.prestador
            WHEN 4 THEN t_transportista.transportista
            WHEN 5 THEN t_agentecarga.agentecarga
        END as entidadcobrar,
        t_notadebito.fecha_anulacion,
        tmp_usuario_anulacion.nombre as usuario_anulacion,
        t_motivoanulacion.motivoanulacion,
        t_notadebito.otro_motivoanulacion,
        t_notadebito.resplado_anulacion
        FROM
        t_notadebito
        LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_importacion_exportacion ON t_embarque.importacion_exportacion=t_importacion_exportacion.importacion_exportacion
        LEFT JOIN t_transportista as t_transportistaembarque ON t_embarque.idtransportista=t_transportistaembarque.idtransportista
        LEFT JOIN t_ciudad as t_ciudadembarque ON t_embarque.idciudad=t_ciudadembarque.idciudad
        LEFT JOIN t_ciudad ON t_embarque.idarribo=t_ciudad.idciudad
        LEFT JOIN t_usuario ON t_embarque.idusuario=t_usuario.idusuario
        LEFT JOIN t_estadofactura ON t_notadebito.idestadonotadebito=t_estadofactura.idestadofactura
        
        LEFT JOIN t_cliente as t_clientecobrar ON t_notadebito.idcobrara=t_clientecobrar.idcliente
        LEFT JOIN t_proveedor ON t_notadebito.idcobrara=t_proveedor.idproveedor
        LEFT JOIN t_prestador ON t_notadebito.idcobrara=t_prestador.idprestador
        LEFT JOIN t_transportista ON t_notadebito.idcobrara=t_transportista.idtransportista
        LEFT JOIN t_agentecarga ON t_notadebito.idcobrara=t_agentecarga.idagentecarga
        LEFT JOIN tmp_cobrado ON t_notadebito.idnotadebito=tmp_cobrado.idfacturanotadebito
        LEFT JOIN tmp_debitado ON t_notadebito.idnotadebito=tmp_debitado.idfacturanotadebito
        LEFT JOIN t_motivoanulacion ON t_notadebito.idmotivoanulacion=t_motivoanulacion.idmotivoanulacion
        LEFT JOIN t_usuario as tmp_usuario_anulacion ON t_notadebito.idusuarios_anulacion=tmp_usuario_anulacion.idusuario
        WHERE
        t_embarque.idempresa=$idempresa
        ORDER BY 
        t_notadebito.fecha DESC, 
        t_notadebito.nronotadebito DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $notascobranza[]=array(
            'idnotadebito'=>(int)$row['idnotadebito'],
            'nronotadebito'=>$row['nronotadebito'],
            'fecha'=>$row['fecha'],
            'cliente'=>$row['cliente'],
            'importacion_exportacion'=>(int)$row['importacion_exportacion'],
            'importacion_exportacion_codigo'=>$row['importacion_exportacion_codigo'],
            'nodui'=>$row['nodui'],
            'carpetapacena'=>$row['carpetapacena'],
            'transportista'=>$row['transportista'],
            'ciudad'=>$row['ciudad'],
            'ciudadembarque'=>$row['ciudadembarque'],
            'usuario'=>$row['usuario'],
            'monto'=>(float)$row['monto'],
            'cobrado'=>(float)$row['cobrado'],
            'saldo'=>(float)$row['saldo'],
            'estadonotadebito'=>$row['estadonotadebito'],
            'idestadonotadebito'=>(int)$row['idestadonotadebito'],
            'embarque'=>$row['embarque'],
            'idcobrara'=>(int)$row['idcobrara'],
            'idcobraratipo'=>(int)$row['idcobraratipo'],
            'fecha_anulacion'=>$row['fecha_anulacion'],
            'usuario_anulacion'=>$row['usuario_anulacion'],
            'motivoanulacion'=>$row['motivoanulacion'],
            'otro_motivoanulacion'=>$row['otro_motivoanulacion'],
            'resplado_anulacion'=>$row['resplado_anulacion'],
            'entidadcobrar'=>$row['entidadcobrar']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'notascobranza' => $notascobranza
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/notascobranza/anular/{idnotadebito}', function(Request $request, Response $response, array $args) use ($conexion, $archivospermitidos) {

    $idnotadebito = $args['idnotadebito'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $resplado_anulacion = '';
    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Token ya validado por middleware
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded_array = (array) $decoded;

        $idempresa = $decoded_array["idempresa"] ?? null;
        $idusuario = $decoded_array["idusuario"] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if (empty($idnotadebito)) {
            $mensaje = 'No se recibió la nota de cobranza';
            $continuar = false;
        }

        if ($continuar && empty($idempresa)) {
            $mensaje = 'No se recibió la empresa';
            $continuar = false;
        }

        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Crear carpeta de empresa
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $creacion = new Carpetas();
            $respuesta = $creacion->procesarCarpeta($idempresa);
        }

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros del formulario
        |--------------------------------------------------------------------------
        | En Slim 3/4 es preferible usar getParsedBody()
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $bodyParams = $request->getParsedBody();

            if (!is_array($bodyParams)) {
                $bodyParams = [];
            }

            $idmotivoanulacion = $bodyParams['idmotivoanulacion'] ?? null;
            $otro_motivoanulacion = '';

            if (empty($idmotivoanulacion)) {
                $mensaje = 'No se recibió el motivo de anulación';
                $continuar = false;
            }

            if ($continuar && (int)$idmotivoanulacion === 5) {
                $otro_motivoanulacion = $bodyParams['otro_motivoanulacion'] ?? '';

                if (trim($otro_motivoanulacion) === '') {
                    $mensaje = 'Debe ingresar el otro motivo de anulación';
                    $continuar = false;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validar archivos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $files = $_FILES['uploads'] ?? null;

            $totalArchivos = (
                isset($files['name']) &&
                is_array($files['name'])
            ) ? count($files['name']) : 0;

            if ($totalArchivos === 0) {
                $mensaje = 'Debe adjuntar el respaldo de anulación';
                $continuar = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Subir respaldo
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            for ($fi = 0; $fi < $totalArchivos; $fi++) {

                if (!$continuar) {
                    break;
                }

                if (($files['error'][$fi] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    $mensaje = 'Ocurrió un problema al subir el archivo';
                    $continuar = false;
                    break;
                }

                $nombredoc = $files["name"][$fi];

                $piramideUploader = new PiramideUploader();

                $upload = $piramideUploader->upload(
                    $nombredoc,
                    'uploads',
                    folder_files . $idempresa . DIRECTORY_SEPARATOR . 'respaldos_notasdebito_anuladas/' . $idnotadebito,
                    $archivospermitidos,
                    true,
                    $fi
                );

                $file = $piramideUploader->getInfoFile();

                if ($upload['uploaded']) {
                    /*
                    Si llega más de un archivo, se guarda el último,
                    igual que en tu lógica original.
                    */
                    $resplado_anulacion = $file['complete_name'];
                } else {
                    $mensaje = $upload['error'];
                    $continuar = false;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Anular nota de cobranza y liberar cargos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryNotaDebito = "
                UPDATE t_notadebito
                SET
                    idestadonotadebito = 2,
                    fecha_anulacion = CURRENT_TIMESTAMP(),
                    idusuarios_anulacion = :idusuario,
                    idmotivoanulacion = :idmotivoanulacion,
                    otro_motivoanulacion = :otro_motivoanulacion,
                    resplado_anulacion = :resplado_anulacion
                WHERE idnotadebito = :idnotadebito
            ";

            $stmtNotaDebito = $conexion->prepare($queryNotaDebito);

            $resultNotaDebito = $stmtNotaDebito->execute([
                ':idusuario' => $idusuario,
                ':idmotivoanulacion' => $idmotivoanulacion,
                ':otro_motivoanulacion' => $otro_motivoanulacion,
                ':resplado_anulacion' => $resplado_anulacion,
                ':idnotadebito' => $idnotadebito
            ]);

            if (!$resultNotaDebito) {
                $mensaje = 'No se pudo anular la nota de cobranza';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        if ($continuar) {

            $queryCargo = "
                UPDATE t_cargo
                SET
                    idfacturanotadebito = NULL,
                    idtipofacturanotadebito = NULL
                WHERE idfacturanotadebito = :idnotadebito
                  AND idtipofacturanotadebito = 2
            ";

            $stmtCargo = $conexion->prepare($queryCargo);

            $resultCargo = $stmtCargo->execute([
                ':idnotadebito' => $idnotadebito
            ]);

            if (!$resultCargo) {
                $mensaje = 'No se pudieron liberar los cargos asociados';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmar operación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->commit();

            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Los datos se guardaron correctamente';
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado = array(
        'codigo' => $codigo,
        'estado' => $status,
        'mensaje' => $mensaje,
        'resplado_anulacion' => $resplado_anulacion
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->get('/contabilidad/rangonotascobranza/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $notascobranza=[];
    $result = $conexion->query("select 
        t_notadebito.idnotadebito,
        CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion) as nronotadebito,
        CONCAT(t_notadebito.fecha,' 12:00:00') as fecha,
        t_embarque.embarque,
        t_cliente.cliente,
        t_embarque.importacion_exportacion,
        t_importacion_exportacion.importacion_exportacion_codigo,
        t_embarque.nodui,
        IFNULL(t_embarque.carpetapacena,'') as carpetapacena,
        t_transportistaembarque.transportista,
        t_ciudad.ciudad as ciudad,
        t_ciudadembarque.ciudad as ciudadembarque,
        t_usuario.nombre as usuario,
        CASE t_notadebito.idestadonotadebito WHEN 2 THEN 0 ELSE valordebitado(t_notadebito.idnotadebito) END as monto,
        t_notadebito.idestadonotadebito,
        t_estadofactura.estadonotadebito,
        t_notadebito.idcobrara,
        t_notadebito.idcobraratipo,
        CASE t_notadebito.idcobraratipo
            WHEN 1 THEN t_clientecobrar.cliente
            WHEN 2 THEN t_proveedor.proveedor
            WHEN 3 THEN t_prestador.prestador
            WHEN 4 THEN t_transportista.transportista
            WHEN 5 THEN t_agentecarga.agentecarga
        END as entidadcobrar,
        t_tipocambio.tipocambio
        FROM
        t_notadebito
        LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_importacion_exportacion ON t_embarque.importacion_exportacion=t_importacion_exportacion.importacion_exportacion
        LEFT JOIN t_transportista as t_transportistaembarque ON t_embarque.idtransportista=t_transportistaembarque.idtransportista
        LEFT JOIN t_ciudad as t_ciudadembarque ON t_embarque.idciudad=t_ciudadembarque.idciudad
        LEFT JOIN t_ciudad ON t_embarque.idarribo=t_ciudad.idciudad
        LEFT JOIN t_usuario ON t_embarque.idusuario=t_usuario.idusuario
        LEFT JOIN t_estadofactura ON t_notadebito.idestadonotadebito=t_estadofactura.idestadofactura
        LEFT JOIN t_cliente as t_clientecobrar ON t_notadebito.idcobrara=t_clientecobrar.idcliente
        LEFT JOIN t_proveedor ON t_notadebito.idcobrara=t_proveedor.idproveedor
        LEFT JOIN t_prestador ON t_notadebito.idcobrara=t_prestador.idprestador
        LEFT JOIN t_transportista ON t_notadebito.idcobrara=t_transportista.idtransportista
        LEFT JOIN t_agentecarga ON t_notadebito.idcobrara=t_agentecarga.idagentecarga
        LEFT JOIN t_tipocambio ON t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_notadebito.fecha) AND 1=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE
        t_notadebito.fecha BETWEEN '$fechainicial' AND '$fechafinal'
        AND t_embarque.idempresa=$idempresa
        ORDER BY 
        t_notadebito.fecha DESC, 
        t_notadebito.nronotadebito DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $notascobranza[]=array(
            'idnotadebito'=>(int)$row['idnotadebito'],
            'nronotadebito'=>$row['nronotadebito'],
            'fecha'=>$row['fecha'],
            'cliente'=>$row['cliente'],
            'importacion_exportacion'=>(int)$row['importacion_exportacion'],
            'importacion_exportacion_codigo'=>$row['importacion_exportacion_codigo'],
            'nodui'=>$row['nodui'],
            'carpetapacena'=>$row['carpetapacena'],
            'transportista'=>$row['transportista'],
            'ciudad'=>$row['ciudad'],
            'ciudadembarque'=>$row['ciudadembarque'],
            'usuario'=>$row['usuario'],
            'monto'=>(float)$row['monto'],
            'estadonotadebito'=>$row['estadonotadebito'],
            'idestadonotadebito'=>(int)$row['idestadonotadebito'],
            'embarque'=>$row['embarque'],
            'idcobrara'=>(int)$row['idcobrara'],
            'idcobraratipo'=>(int)$row['idcobraratipo'],
            'entidadcobrar'=>$row['entidadcobrar'],
            'tipocambio'=>(float)$row['tipocambio']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'notascobranza' => $notascobranza
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/generarnotacobranza/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {

    $idembarque = $args['idembarque'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $idnotadebito = 0;
    $nronotadebito = '';

    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Token ya validado por middleware
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded_array = (array) $decoded;

        $idempresa = $decoded_array["idempresa"] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros
        |--------------------------------------------------------------------------
        */
        $params = json_decode((string) $request->getBody(), true);

        if (!is_array($params)) {
            $mensaje = 'No se recibieron parámetros válidos';
            $continuar = false;
        }

        if ($continuar) {
            $cargos = $params["cargos"] ?? [];

            if (empty($idembarque)) {
                $mensaje = 'No se recibió el embarque';
                $continuar = false;
            }
        }

        if ($continuar && empty($idempresa)) {
            $mensaje = 'No se recibió la empresa';
            $continuar = false;
        }

        if ($continuar && empty($params['idcobrara'])) {
            $mensaje = 'No se recibió el dato de cobrar a';
            $continuar = false;
        }

        if ($continuar && empty($params["idcuenta"])) {
            $mensaje = 'No se recibió la cuenta';
            $continuar = false;
        }

        if ($continuar && !isset($params["observaciones"])) {
            $mensaje = 'No se recibieron las observaciones';
            $continuar = false;
        }

        if ($continuar && empty($params["iddivisa"])) {
            $mensaje = 'No se recibió la divisa';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Separar idcobrara
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $idcobrara = $params['idcobrara'];
            $idcobrarasplit = explode("-", $idcobrara);

            if (count($idcobrarasplit) < 2) {
                $mensaje = 'El formato de cobrar a no es válido';
                $continuar = false;
            } else {
                $idcobraratipo = (int)$idcobrarasplit[0];
                $idcobraraValor = (int)$idcobrarasplit[1];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Registrar nota de cobranza
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Obtener nuevo número de nota de cobranza
            |--------------------------------------------------------------------------
            */
            $queryNumero = "
                SELECT 
                    IFNULL(MAX(t_notadebito.nronotadebito), 0) + 1 AS nronotadebito
                FROM t_notadebito
                LEFT JOIN t_embarque 
                    ON t_notadebito.idembarque = t_embarque.idembarque
                WHERE t_notadebito.gestion = YEAR(CURRENT_DATE())
                  AND t_embarque.idempresa = :idempresa
            ";

            $stmtNumero = $conexion->prepare($queryNumero);

            $stmtNumero->execute([
                ':idempresa' => $idempresa
            ]);

            $rowNumero = $stmtNumero->fetch(PDO::FETCH_ASSOC);
            $numeroNotaDebito = (int)($rowNumero['nronotadebito'] ?? 1);

            /*
            |--------------------------------------------------------------------------
            | Insertar nota de cobranza
            |--------------------------------------------------------------------------
            */
            $queryNotaDebito = "
                INSERT INTO t_notadebito (
                    idembarque,
                    fecha,
                    nronotadebito,
                    gestion,
                    idcobrara,
                    idcobraratipo,
                    idcuenta,
                    idestadonotadebito,
                    observaciones,
                    iddivisa
                ) VALUES (
                    :idembarque,
                    CURRENT_DATE(),
                    :nronotadebito,
                    YEAR(CURRENT_DATE()),
                    :idcobrara,
                    :idcobraratipo,
                    :idcuenta,
                    1,
                    :observaciones,
                    :iddivisa
                )
            ";

            $stmtNotaDebito = $conexion->prepare($queryNotaDebito);

            $resultNotaDebito = $stmtNotaDebito->execute([
                ':idembarque' => $idembarque,
                ':nronotadebito' => $numeroNotaDebito,
                ':idcobrara' => $idcobraraValor,
                ':idcobraratipo' => $idcobraratipo,
                ':idcuenta' => $params["idcuenta"],
                ':observaciones' => $params["observaciones"],
                ':iddivisa' => $params["iddivisa"]
            ]);

            if (!$resultNotaDebito) {
                $mensaje = 'No se pudo registrar la nota de cobranza';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idnotadebitoNueva = (int)$conexion->lastInsertId();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Asociar cargos a la nota de cobranza
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            if (is_array($cargos)) {

                $queryCargo = "
                    UPDATE t_cargo
                    SET 
                        idfacturanotadebito = :idnotadebito,
                        idtipofacturanotadebito = 2
                    WHERE idcargo = :idcargo
                ";

                $stmtCargo = $conexion->prepare($queryCargo);

                foreach ($cargos as $idcargo) {
                    if (!empty($idcargo)) {
                        $stmtCargo->execute([
                            ':idnotadebito' => $idnotadebitoNueva,
                            ':idcargo' => $idcargo
                        ]);
                    }
                }

            } else {
                $mensaje = 'Los cargos recibidos no tienen un formato válido';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Recuperar nota de cobranza generada
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryNotaDebitoFinal = "
                SELECT 
                    idnotadebito,
                    nronotadebito,
                    gestion
                FROM t_notadebito
                WHERE idnotadebito = :idnotadebito
                LIMIT 1
            ";

            $stmtNotaDebitoFinal = $conexion->prepare($queryNotaDebitoFinal);

            $stmtNotaDebitoFinal->execute([
                ':idnotadebito' => $idnotadebitoNueva
            ]);

            $rowNotaDebito = $stmtNotaDebitoFinal->fetch(PDO::FETCH_ASSOC);

            if ($rowNotaDebito) {
                $idnotadebito = (int)$rowNotaDebito['idnotadebito'];
                $nronotadebito = $rowNotaDebito['nronotadebito'] . "/" . $rowNotaDebito['gestion'];

                $conexion->commit();

                $codigo = 200;
                $status = 'Exito';
                $mensaje = 'Nota de cobranza generada exitosamente';
            } else {
                $mensaje = 'No se pudo recuperar la nota de cobranza generada';

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado = array(
        'codigo' => $codigo,
        'estado' => $status,
        'mensaje' => $mensaje,
        'idnotadebito' => $idnotadebito,
        'nronotadebito' => $nronotadebito,
        'notascobranza' => getNotasCobranza($idembarque, $conexion)
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->get('/contabilidad/notascobranza/download/{idnotadebito}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idnotadebito = $args['idnotadebito'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/notascobranza/notacobranza$idnotadebito.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    generarNC($idnotadebito, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/notascobranza/download/membretada/{idnotadebito}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idnotadebito = $args['idnotadebito'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/notascobranza/notacobranzamembretada$idnotadebito.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    generarNC($idnotadebito, $conexion, true);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/invoices', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $invoices=[];
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_valoresfactura;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_valoresfactura (idembarque INT, valorfacturado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_valoresfactura (idembarque, valorfacturado)
        SELECT
        t_cargo.idembarque,
        SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as valorfactura
        FROM
        t_cargo
        LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE 
        t_factura.idestadofactura=1
        AND IFNULL(t_cargo.idinvoice,0)>0
        AND t_embarque.idempresa=$idempresa
        GROUP BY
        t_cargo.idembarque;");
    $conexion->query("ALTER TABLE tmp_valoresfactura ADD INDEX idembarque (idembarque);");

    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_valoresnotadebito;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_valoresnotadebito (idembarque INT, valordebitado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_valoresnotadebito (idembarque, valordebitado)
        SELECT
        t_cargo.idembarque,
        SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as valordebitado
        FROM
        t_cargo
        LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
        LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_notadebito.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE 
        t_notadebito.idestadonotadebito=1
        AND IFNULL(t_cargo.idinvoice,0)>0
        AND t_embarque.idempresa=$idempresa
        GROUP BY
        t_cargo.idembarque;");
    $conexion->query("ALTER TABLE tmp_valoresnotadebito ADD INDEX idembarque (idembarque);");


    $result = $conexion->query("select 
        t_invoice.idinvoice,
        CONCAT(t_invoice.numero,'/',t_invoice.gestion) as nroinvoice,
        t_invoice.fecha,
        t_embarque.embarque,
        t_cliente.cliente,
        valorinvoiceus(t_invoice.idinvoice) as montoinvoice,
        t_estadofactura.estadonotadebito as estado,
        tmp_valoresfactura.valorfacturado,
        tmp_valoresnotadebito.valordebitado,
        IFNULL(tmp_valoresfactura.valorfacturado,0)+IFNULL(tmp_valoresnotadebito.valordebitado,0) as total,
        valorinvoiceus(t_invoice.idinvoice)-(IFNULL(tmp_valoresfactura.valorfacturado,0)+IFNULL(tmp_valoresnotadebito.valordebitado,0)) as diferencia
        from 
        t_invoice
        LEFT JOIN t_embarque ON t_invoice.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_estadofactura ON t_invoice.idestadoinvoice=t_estadofactura.idestadofactura
        LEFT JOIN tmp_valoresfactura ON t_embarque.idembarque=tmp_valoresfactura.idembarque
        LEFT JOIN tmp_valoresnotadebito ON t_embarque.idembarque=tmp_valoresnotadebito.idembarque
        WHERE
        t_embarque.idempresa=$idempresa
        ORDER BY
        t_invoice.gestion DESC, t_invoice.numero DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $invoices[]=array(
            'idinvoice'=>(int)$row['idinvoice'],
            'nroinvoice'=>$row['nroinvoice'],
            'fecha'=>$row['fecha'],
            'embarque'=>$row['embarque'],
            'cliente'=>$row['cliente'],
            'montoinvoice'=>(float)$row['montoinvoice'],
            'estado'=>$row['estado'],
            'valorfacturado'=>(float)$row['valorfacturado'],
            'valordebitado'=>(float)$row['valordebitado'],
            'total'=>(float)$row['total'],
            'diferencia'=>(float)$row['diferencia']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'invoices' => $invoices
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/rangoinvoices/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $invoices=[];
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_valoresfactura;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_valoresfactura (idembarque INT, valorfacturado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_valoresfactura (idembarque, valorfacturado)
        SELECT
        t_cargo.idembarque,
        SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as valorfactura
        FROM
        t_cargo
        LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE 
        t_factura.idestadofactura=1
        AND IFNULL(t_cargo.idinvoice,0)>0
        AND t_embarque.idempresa=$idempresa
        GROUP BY
        t_cargo.idembarque;");
    $conexion->query("ALTER TABLE tmp_valoresfactura ADD INDEX idembarque (idembarque);");

    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_valoresnotadebito;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_valoresnotadebito (idembarque INT, valordebitado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_valoresnotadebito (idembarque, valordebitado)
        SELECT
        t_cargo.idembarque,
        SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as valordebitado
        FROM
        t_cargo
        LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
        LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_notadebito.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE 
        t_notadebito.idestadonotadebito=1
        AND IFNULL(t_cargo.idinvoice,0)>0
        AND t_embarque.idempresa=$idempresa
        GROUP BY
        t_cargo.idembarque;");
    $conexion->query("ALTER TABLE tmp_valoresnotadebito ADD INDEX idembarque (idembarque);");


    $result = $conexion->query("select 
        t_invoice.idinvoice,
        CONCAT(t_invoice.numero,'/',t_invoice.gestion) as nroinvoice,
        CONCAT(t_invoice.fecha,' 12:00:00') as fecha,
        t_embarque.embarque,
        t_cliente.cliente,
        valorinvoiceus(t_invoice.idinvoice) as montoinvoice,
        t_estadofactura.estadonotadebito as estado,
        tmp_valoresfactura.valorfacturado,
        tmp_valoresnotadebito.valordebitado,
        IFNULL(tmp_valoresfactura.valorfacturado,0)+IFNULL(tmp_valoresnotadebito.valordebitado,0) as total,
        valorinvoiceus(t_invoice.idinvoice)-(IFNULL(tmp_valoresfactura.valorfacturado,0)+IFNULL(tmp_valoresnotadebito.valordebitado,0)) as diferencia
        from 
        t_invoice
        LEFT JOIN t_embarque ON t_invoice.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_estadofactura ON t_invoice.idestadoinvoice=t_estadofactura.idestadofactura
        LEFT JOIN tmp_valoresfactura ON t_embarque.idembarque=tmp_valoresfactura.idembarque
        LEFT JOIN tmp_valoresnotadebito ON t_embarque.idembarque=tmp_valoresnotadebito.idembarque
        WHERE
        t_invoice.fecha BETWEEN '$fechainicial' AND '$fechafinal'
        AND t_embarque.idempresa=$idempresa
        ORDER BY
        t_invoice.gestion DESC, t_invoice.numero DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $invoices[]=array(
            'idinvoice'=>(int)$row['idinvoice'],
            'nroinvoice'=>$row['nroinvoice'],
            'fecha'=>$row['fecha'],
            'embarque'=>$row['embarque'],
            'cliente'=>$row['cliente'],
            'montoinvoice'=>(float)$row['montoinvoice'],
            'estado'=>$row['estado'],
            'valorfacturado'=>(float)$row['valorfacturado'],
            'valordebitado'=>(float)$row['valordebitado'],
            'total'=>(float)$row['total'],
            'diferencia'=>(float)$row['diferencia']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'invoices' => $invoices
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/reservarinvoice/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    $nroinvoice="";
    $idinvoice=0;


    $query='';
    
    $query=$query."INSERT INTO t_invoice (fecha,            numero,                                                                                                                                                                                                         gestion,                idembarque,     idestadoinvoice) 
                                  SELECT CURRENT_DATE(),    (SELECT IFNULL(MAX(t_invoice.numero),0)+1 FROM t_invoice LEFT JOIN t_embarque ON t_invoice.idembarque=t_embarque.idembarque WHERE t_invoice.gestion=YEAR(CURRENT_DATE()) AND t_embarque.idempresa=$idempresa),  YEAR(CURRENT_DATE()),   $idembarque,    3;";
    $query=$query."SELECT LAST_INSERT_ID() INTO @idinvoice_nueva;";
    
    if(strlen($query)>0){
        $result = $conexion->exec($query);
        if($result){
            $resultnroinvoice = $conexion->query("select 
                idinvoice,
                numero,
                gestion
                from 
                t_invoice 
                WHERE 
                idinvoice=@idinvoice_nueva;");
            while ($rownroinvoice =  $resultnroinvoice ->fetch(PDO::FETCH_ASSOC)){
                $nroinvoice=$rownroinvoice['numero']."/".$rownroinvoice["gestion"];
                $idinvoice=$rownroinvoice['idinvoice'];
            }


            $codigo=200;
            $status='Exito';
            $mensaje='Invoice reservado exitosamente';



        }
    }


    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idinvoice'=> $idinvoice,
        'nroinvoice'=>$nroinvoice,
        'invoices' => getInvoices($idembarque, $conexion)
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/generarinvoice/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $idinvoice = 0;
    $nroinvoice = '';
    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Validar headers y token
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'] ?? null;

        if (empty($token)) {
            $mensaje = 'No se recibió el token de autorización';
            $continuar = false;
        }

        if ($continuar) {
            $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
            $decoded_array = (array) $decoded;

            $idempresa = $decoded_array["idempresa"] ?? null;

            if (empty($idempresa)) {
                $mensaje = 'No se recibió la empresa';
                $continuar = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $params = json_decode((string) $request->getBody(), true);

            if (!is_array($params)) {
                $mensaje = 'No se recibieron parámetros válidos';
                $continuar = false;
            }
        }

        if ($continuar) {
            $cargos = $params["cargos"] ?? [];
            $idinvoice = $params['idinvoice'] ?? 0;

            if (empty($idembarque)) {
                $mensaje = 'No se recibió el embarque';
                $continuar = false;
            }
        }

        if ($continuar && empty($params["idagentecarga"])) {
            $mensaje = 'No se recibió el agente de carga';
            $continuar = false;
        }

        if ($continuar && !isset($params["idagentecargadireccion"])) {
            $mensaje = 'No se recibió la dirección del agente de carga';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Iniciar transacción
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Actualizar o insertar invoice
            |--------------------------------------------------------------------------
            */
            if ((int)$idinvoice > 0) {

                $queryInvoice = "
                    UPDATE t_invoice
                    SET
                        idagentecarga = :idagentecarga,
                        idagentecargadireccion = :idagentecargadireccion,
                        idestadoinvoice = 1
                    WHERE idinvoice = :idinvoice
                ";

                $stmtInvoice = $conexion->prepare($queryInvoice);

                $stmtInvoice->execute([
                    ':idagentecarga' => $params["idagentecarga"],
                    ':idagentecargadireccion' => $params["idagentecargadireccion"],
                    ':idinvoice' => $idinvoice
                ]);

                $idinvoiceNueva = (int)$idinvoice;

            } else {

                /*
                |--------------------------------------------------------------------------
                | Obtener nuevo número de invoice
                |--------------------------------------------------------------------------
                */
                $queryNumero = "
                    SELECT IFNULL(MAX(t_invoice.numero), 0) + 1 AS numero
                    FROM t_invoice
                    LEFT JOIN t_embarque 
                        ON t_invoice.idembarque = t_embarque.idembarque
                    WHERE t_invoice.gestion = YEAR(CURRENT_DATE())
                    AND t_embarque.idempresa = :idempresa
                ";

                $stmtNumero = $conexion->prepare($queryNumero);

                $stmtNumero->execute([
                    ':idempresa' => $idempresa
                ]);

                $rowNumero = $stmtNumero->fetch(PDO::FETCH_ASSOC);
                $numeroInvoice = (int)($rowNumero['numero'] ?? 1);

                /*
                |--------------------------------------------------------------------------
                | Insertar invoice
                |--------------------------------------------------------------------------
                */
                $queryInvoice = "
                    INSERT INTO t_invoice (
                        fecha,
                        numero,
                        gestion,
                        idembarque,
                        idagentecarga,
                        idagentecargadireccion,
                        idestadoinvoice
                    ) VALUES (
                        CURRENT_DATE(),
                        :numero,
                        YEAR(CURRENT_DATE()),
                        :idembarque,
                        :idagentecarga,
                        :idagentecargadireccion,
                        1
                    )
                ";

                $stmtInvoice = $conexion->prepare($queryInvoice);

                $stmtInvoice->execute([
                    ':numero' => $numeroInvoice,
                    ':idembarque' => $idembarque,
                    ':idagentecarga' => $params["idagentecarga"],
                    ':idagentecargadireccion' => $params["idagentecargadireccion"]
                ]);

                $idinvoiceNueva = (int)$conexion->lastInsertId();
            }

            /*
            |--------------------------------------------------------------------------
            | Asociar cargos al invoice
            |--------------------------------------------------------------------------
            */
            if (is_array($cargos)) {

                $queryCargo = "
                    UPDATE t_cargo
                    SET idinvoice = :idinvoice
                    WHERE idcargo = :idcargo
                ";

                $stmtCargo = $conexion->prepare($queryCargo);

                foreach ($cargos as $idcargo) {
                    if (!empty($idcargo)) {
                        $stmtCargo->execute([
                            ':idinvoice' => $idinvoiceNueva,
                            ':idcargo' => $idcargo
                        ]);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Obtener invoice generado / actualizado
            |--------------------------------------------------------------------------
            */
            $queryInvoiceFinal = "
                SELECT 
                    idinvoice,
                    numero,
                    gestion
                FROM t_invoice
                WHERE idinvoice = :idinvoice
                LIMIT 1
            ";

            $stmtInvoiceFinal = $conexion->prepare($queryInvoiceFinal);

            $stmtInvoiceFinal->execute([
                ':idinvoice' => $idinvoiceNueva
            ]);

            $rowInvoice = $stmtInvoiceFinal->fetch(PDO::FETCH_ASSOC);

            if ($rowInvoice) {
                $idinvoice = (int)$rowInvoice['idinvoice'];
                $nroinvoice = $rowInvoice['numero'] . "/" . $rowInvoice['gestion'];

                $codigo = 200;
                $status = 'Exito';
                $mensaje = 'Invoice generado exitosamente';

                $conexion->commit();
            } else {
                $mensaje = 'No se pudo recuperar el invoice generado';

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idinvoice'=> $idinvoice,
        'nroinvoice'=>$nroinvoice,
        'invoices'=> getInvoices($idembarque, $conexion)
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/invoices/download/{idinvoice}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idinvoice = $args['idinvoice'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/invoices/invoice$idinvoice.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    generarInvoice($idinvoice, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/invoices/download/membretada/{idinvoice}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idinvoice = $args['idinvoice'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/invoices/invoicemembretada$idinvoice.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    generarInvoice($idinvoice, $conexion, true);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/planillas', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $planillas=[];

    $result = $conexion->query("SELECT
        t_planilla.idplanilla,
        t_planilla.numero,
        t_planilla.fecha,
        t_embarque.embarque,
        t_cliente.cliente,
        CASE t_planilla.idestadoplanilla WHEN 2 THEN 0 ELSE valorplanilladous(t_planilla.idplanilla) END as monto,
        t_estadofactura.estadonotadebito
        FROM
        t_planilla
        LEFT JOIN t_embarque ON t_planilla.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_estadofactura ON t_planilla.idestadoplanilla=t_estadofactura.idestadofactura
        WHERE
        t_embarque.idempresa=$idempresa
        ORDER BY
        t_planilla.fecha DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $planillas[]=array(
            'idplanilla'=>(int)$row['idplanilla'],
            'numero'=>$row['numero'],
            'fecha'=>$row['fecha'],
            'embarque'=>$row['embarque'],
            'cliente'=>$row['cliente'],
            'monto'=>(float)$row['monto'],
            'estadonotadebito'=>$row['estadonotadebito']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'planillas' => $planillas
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/generarplanilla/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $params = json_decode((string) $request->getBody(),true);
    $cargos=$params["cargos"] ?? [];

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    $idinvoice=0;

    $query='';

    $query=$query."INSERT INTO t_planilla (numero,                                                                                                                                                                  fecha,          idembarque,     textoadicional,                     idestadoplanilla,   pacenainvoice,                  slginvoice,                     alloginvoice) 
                                   SELECT (SELECT IFNULL(MAX(t_planilla.numero),0)+1 FROM t_planilla LEFT JOIN t_embarque ON t_planilla.idembarque=t_embarque.idembarque WHERE t_embarque.idempresa=$idempresa),    CURRENT_DATE(), $idembarque,    '".$params["textoadicional"]."',    1,                  '".$params["pacenainvoice"]."', '".$params["slginvoice"]."',    '".$params["alloginvoice"]."';";
    $query=$query."SELECT LAST_INSERT_ID() INTO @idplanilla_nueva;";
    for($ff=0; $ff<count($cargos); $ff++){
    $query=$query."UPDATE t_cargo SET idplanilla=@idplanilla_nueva, idtipoplanilla='".$cargos[$ff]["idtipoplanilla"]."' WHERE idcargo=".$cargos[$ff]["idcargo"].";";
    }

    if(strlen($query)>0){
        $result = $conexion->exec($query);
        if($result){

            $resultplanilla = $conexion->query("select 
                idplanilla,
                numero
                from 
                t_planilla 
                WHERE 
                idplanilla=@idplanilla_nueva;");
            while ($rowplanilla =  $resultplanilla ->fetch(PDO::FETCH_ASSOC)){
                $idplanilla=(int)$rowplanilla['idplanilla'];
                $numero=$rowplanilla['numero'];
            }


            $codigo=200;
            $status='Exito';
            $mensaje='Planilla generada exitosamente';
        }
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idplanilla'=> $idplanilla,
        'numero'=>$numero,
        'planillas'=> getPlanillas($idembarque, $conexion)
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/planillas/download/{idplanilla}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idplanilla = $args['idplanilla'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/planillas/planilla$idplanilla.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    generarPlanilla($idplanilla, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/ordenespago', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $ordenespago=[];
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_pagos;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_pagos (idfacturapago INT, pagado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_pagos (idfacturapago, pagado)
        SELECT 
        t_pagodetalle.idfacturapago,
        SUM(t_pagodetalle.monto) 
        FROM 
        t_pagodetalle
        LEFT JOIN t_pago ON t_pagodetalle.idpago=t_pago.idpago
        GROUP BY
        t_pagodetalle.idfacturapago;");
    $conexion->query("ALTER TABLE tmp_pagos ADD INDEX idfacturapago (idfacturapago);");

    $result = $conexion->query("SELECT
        t_facturapago.idfacturapago,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as numerofactura,
        t_facturapago.fecha,
        t_embarque.embarque,
        CASE t_facturapago.idpagaratipo
                WHEN 1 THEN t_clientepagara.cliente
                WHEN 2 THEN t_proveedor.proveedor
                WHEN 3 THEN t_prestador.prestador
                WHEN 4 THEN t_transportista.transportista
                WHEN 5 THEN t_agentecarga.agentecarga
        END as proveedor,
        t_cliente.cliente,
        CASE t_facturapago.idestadofacturapago 
            WHEN 2 THEN 0 
            ELSE SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) 
        END as monto,
        t_facturapago.idestadofacturapago,
        t_estadofactura.estadonotadebito,
        IFNULL(tmp_pagos.pagado,0) as pagado,
        t_divisa.codigo as divisa,
        t_facturapago.tipoop AS idtipoop,
        CASE t_facturapago.tipoop
            WHEN 1 THEN 'COSTO'
            WHEN 2 THEN 'CARGO'
        END as tipoop,
        IFNULL(t_facturapago.outNroAsignacion,'') as outNroAsignacion,
        IFNULL(t_facturapago.errorOVP,'') as errorOVP,
        t_facturapago.fecha_anulacion,
        tmp_usuario_anulacion.nombre as usuario_anulacion,
        t_motivoanulacion.motivoanulacion,
        t_facturapago.otro_motivoanulacion,
        t_facturapago.resplado_anulacion
        FROM
        t_facturapago
        LEFT JOIN t_costo ON t_facturapago.idfacturapago=t_costo.idfacturanotadebito AND 1=t_costo.idtipofacturanotadebito
        LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_estadofactura ON t_facturapago.idestadofacturapago=t_estadofactura.idestadofactura
        LEFT JOIN tmp_pagos ON t_facturapago.idfacturapago=tmp_pagos.idfacturapago
        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND t_facturapago.iddivisa=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_facturapago.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        LEFT JOIN t_divisa ON t_facturapago.iddivisa=t_divisa.iddivisa
        LEFT JOIN t_cliente as t_clientepagara ON t_facturapago.idpagara=t_clientepagara.idcliente
        LEFT JOIN t_proveedor ON t_facturapago.idpagara=t_proveedor.idproveedor
        LEFT JOIN t_prestador ON t_facturapago.idpagara=t_prestador.idprestador
        LEFT JOIN t_transportista ON t_facturapago.idpagara=t_transportista.idtransportista
        LEFT JOIN t_agentecarga ON t_facturapago.idpagara=t_agentecarga.idagentecarga
        LEFT JOIN t_motivoanulacion ON t_facturapago.idmotivoanulacion=t_motivoanulacion.idmotivoanulacion
        LEFT JOIN t_usuario as tmp_usuario_anulacion ON t_facturapago.idusuarios_anulacion=tmp_usuario_anulacion.idusuario
        WHERE 
        t_facturapago.idtipofacturapago=1
        AND t_embarque.idempresa=$idempresa
        GROUP BY
        t_facturapago.idfacturapago,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion),
        DATE_FORMAT(t_facturapago.fecha,'%d/%m/%Y'),
        t_embarque.embarque,
        CASE t_facturapago.idpagaratipo
                WHEN 1 THEN t_clientepagara.cliente
                WHEN 2 THEN t_proveedor.proveedor
                WHEN 3 THEN t_prestador.prestador
                WHEN 4 THEN t_transportista.transportista
                WHEN 5 THEN t_agentecarga.agentecarga
        END,
        t_cliente.cliente,
        t_facturapago.idestadofacturapago,
        t_estadofactura.estadonotadebito,
        tmp_pagos.pagado,
        t_divisa.codigo,
        t_facturapago.tipoop,
        IFNULL(t_facturapago.outNroAsignacion,''),
        IFNULL(t_facturapago.errorOVP,''),
        t_facturapago.fecha_anulacion,
        tmp_usuario_anulacion.nombre,
        t_motivoanulacion.motivoanulacion,
        t_facturapago.otro_motivoanulacion,
        t_facturapago.resplado_anulacion
        ORDER BY 
        t_facturapago.fecha DESC, t_facturapago.gestion DESC, t_facturapago.numerofactura DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $ordenespago[]=array(
            'idfacturapago'=>(int)$row['idfacturapago'],
            'numerofactura'=>$row['numerofactura'],
            'fecha'=>$row['fecha'],
            'embarque'=>$row['embarque'],
            'proveedor'=>$row['proveedor'],
            'cliente'=>$row['cliente'],
            'monto'=>(float)$row['monto'],
            'idestadofacturapago'=>(int)$row['idestadofacturapago'],
            'estadonotadebito'=>$row['estadonotadebito'],
            'pagado'=>(float)$row['pagado'],
            'divisa'=>$row['divisa'],
            'idtipoop'=>(int)$row['idtipoop'],
            'tipoop'=>$row['tipoop'],
            'outNroAsignacion'=>$row['outNroAsignacion'],
            'errorOVP'=>$row['errorOVP'],
            'fecha_anulacion'=>$row['fecha_anulacion'],
            'usuario_anulacion'=>$row['usuario_anulacion'],
            'motivoanulacion'=>$row['motivoanulacion'],
            'otro_motivoanulacion'=>$row['otro_motivoanulacion'],
            'resplado_anulacion'=>$row['resplado_anulacion']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'ordenespago' => $ordenespago
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/ordenespago/rango/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $ordenespago=[];
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_pagos;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_pagos (idfacturapago INT, pagado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_pagos (idfacturapago, pagado)
        SELECT 
        t_pagodetalle.idfacturapago,
        SUM(t_pagodetalle.monto) 
        FROM 
        t_pagodetalle
        LEFT JOIN t_pago ON t_pagodetalle.idpago=t_pago.idpago
        GROUP BY
        t_pagodetalle.idfacturapago;");
    $conexion->query("ALTER TABLE tmp_pagos ADD INDEX idfacturapago (idfacturapago);");

    $result = $conexion->query("SELECT
        t_facturapago.idfacturapago,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as numerofactura,
        t_facturapago.fecha,
        t_embarque.embarque,
        CASE t_facturapago.idpagaratipo
                WHEN 1 THEN t_clientepagara.cliente
                WHEN 2 THEN t_proveedor.proveedor
                WHEN 3 THEN t_prestador.prestador
                WHEN 4 THEN t_transportista.transportista
                WHEN 5 THEN t_agentecarga.agentecarga
        END as proveedor,
        t_cliente.cliente,
        CASE t_facturapago.idestadofacturapago 
            WHEN 2 THEN 0 
            ELSE SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) END as monto,
        t_estadofactura.estadonotadebito,
        IFNULL(tmp_pagos.pagado,0) as pagado,
        t_divisa.codigo as divisa,
        t_facturapago.tipoop AS idtipoop,
        CASE t_facturapago.tipoop
            WHEN 1 THEN 'COSTO'
            WHEN 2 THEN 'CARGO'
        END as tipoop,
        IFNULL(t_facturapago.outNroAsignacion,'') as outNroAsignacion,
        IFNULL(t_facturapago.errorOVP,'') as errorOVP
        FROM
        t_facturapago
        LEFT JOIN t_costo ON t_facturapago.idfacturapago=t_costo.idfacturanotadebito AND 1=t_costo.idtipofacturanotadebito
        LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_estadofactura ON t_facturapago.idestadofacturapago=t_estadofactura.idestadofactura
        LEFT JOIN tmp_pagos ON t_facturapago.idfacturapago=tmp_pagos.idfacturapago
        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND t_facturapago.iddivisa=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_facturapago.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        LEFT JOIN t_divisa ON t_facturapago.iddivisa=t_divisa.iddivisa
        LEFT JOIN t_cliente as t_clientepagara ON t_facturapago.idpagara=t_clientepagara.idcliente
        LEFT JOIN t_proveedor ON t_facturapago.idpagara=t_proveedor.idproveedor
        LEFT JOIN t_prestador ON t_facturapago.idpagara=t_prestador.idprestador
        LEFT JOIN t_transportista ON t_facturapago.idpagara=t_transportista.idtransportista
        LEFT JOIN t_agentecarga ON t_facturapago.idpagara=t_agentecarga.idagentecarga
        WHERE 
        t_facturapago.idtipofacturapago=1
        AND t_facturapago.fecha BETWEEN '$fechainicial' AND '$fechafinal'
        AND t_embarque.idempresa=$idempresa
        GROUP BY
        t_facturapago.idfacturapago,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion),
        DATE_FORMAT(t_facturapago.fecha,'%d/%m/%Y'),
        t_embarque.embarque,
        CASE t_facturapago.idpagaratipo
                WHEN 1 THEN t_clientepagara.cliente
                WHEN 2 THEN t_proveedor.proveedor
                WHEN 3 THEN t_prestador.prestador
                WHEN 4 THEN t_transportista.transportista
                WHEN 5 THEN t_agentecarga.agentecarga
        END,
        t_cliente.cliente,
        t_estadofactura.estadonotadebito,
        tmp_pagos.pagado,
        t_divisa.codigo,
        t_facturapago.tipoop,
        IFNULL(t_facturapago.outNroAsignacion,''),
        IFNULL(t_facturapago.errorOVP,'')
        ORDER BY 
        t_facturapago.fecha DESC, t_facturapago.gestion DESC, t_facturapago.numerofactura DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $ordenespago[]=array(
            'idfacturapago'=>(int)$row['idfacturapago'],
            'numerofactura'=>$row['numerofactura'],
            'fecha'=>$row['fecha'],
            'embarque'=>$row['embarque'],
            'proveedor'=>$row['proveedor'],
            'cliente'=>$row['cliente'],
            'monto'=>(float)$row['monto'],
            'estadonotadebito'=>$row['estadonotadebito'],
            'pagado'=>(float)$row['pagado'],
            'divisa'=>$row['divisa'],
            'idtipoop'=>(int)$row['idtipoop'],
            'tipoop'=>$row['tipoop'],
            'outNroAsignacion'=>$row['outNroAsignacion'],
            'errorOVP'=>$row['errorOVP']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'ordenespago' => $ordenespago
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/pagosagenteexterior', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $pagosagenteexterior=[];

    $result = $conexion->query("SELECT
        t_facturapago.idfacturapago,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as numerofactura,
        t_facturapago.fecha,
        t_embarque.embarque,
        t_cliente.cliente,
        CASE t_facturapago.idestadofacturapago WHEN 2 THEN 0 ELSE valorpagofacturado(t_facturapago.idfacturapago) END as monto,
        t_estadofactura.estadonotadebito,
        v_entidades.entidad AS agente,
        IFNULL(t_facturapago.outNroAsignacion,'') as outNroAsignacion,
        IFNULL(t_facturapago.errorOVP,'') as errorOVP
        FROM
        t_facturapago
        LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_estadofactura ON t_facturapago.idestadofacturapago=t_estadofactura.idestadofactura
        LEFT JOIN v_entidades ON t_facturapago.idpagara=v_entidades.identidad AND t_facturapago.idpagaratipo=v_entidades.idtipoentidad
        WHERE
        t_facturapago.idtipofacturapago=2
        AND t_embarque.idempresa=$idempresa
        ORDER BY 
        t_facturapago.fecha DESC, t_facturapago.gestion DESC, t_facturapago.numerofactura DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $pagosagenteexterior[]=array(
            'idfacturapago'=>(int)$row['idfacturapago'],
            'numerofactura'=>$row['numerofactura'],
            'fecha'=>$row['fecha'],
            'embarque'=>$row['embarque'],
            'cliente'=>$row['cliente'],
            'monto'=>(float)$row['monto'],
            'estadonotadebito'=>$row['estadonotadebito'],
            'agente'=>$row['agente'],
            'outNroAsignacion'=>$row['outNroAsignacion'],
            'errorOVP'=>$row['errorOVP']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'pagosagenteexterior' => $pagosagenteexterior
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/generarordenpago/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {

    $idembarque = $args['idembarque'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $idfacturapago = 0;
    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Token ya validado por middleware
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded_array = (array) $decoded;

        $idempresa = $decoded_array["idempresa"] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros
        |--------------------------------------------------------------------------
        */
        $params = json_decode((string) $request->getBody(), true);

        if (!is_array($params)) {
            $mensaje = 'No se recibieron parámetros válidos';
            $continuar = false;
        }

        if ($continuar) {
            $costos = $params["costos"] ?? [];

            if (empty($idembarque)) {
                $mensaje = 'No se recibió el embarque';
                $continuar = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idempresa)) {
            $mensaje = 'No se recibió la empresa';
            $continuar = false;
        }

        if ($continuar && empty($params["idtipofacturapago"])) {
            $mensaje = 'No se recibió el tipo de factura de pago';
            $continuar = false;
        }

        if ($continuar && empty($params['idpagara'])) {
            $mensaje = 'No se recibió el dato de pagar a';
            $continuar = false;
        }

        if ($continuar && empty($params['idcobrara'])) {
            $mensaje = 'No se recibió el dato de cobrar a';
            $continuar = false;
        }

        if ($continuar && !isset($params["idtransportista"])) {
            $mensaje = 'No se recibió el transportista';
            $continuar = false;
        }

        if ($continuar && !isset($params["idpagaradireccion"])) {
            $mensaje = 'No se recibió la dirección de pago';
            $continuar = false;
        }

        if ($continuar && empty($params["fechadocumento"])) {
            $mensaje = 'No se recibió la fecha del documento';
            $continuar = false;
        }
        /*
        if ($continuar && !isset($params["tipocambio"])) {
            $mensaje = 'No se recibió el tipo de cambio';
            $continuar = false;
        }
        
        if ($continuar && !isset($params["observaciones"])) {
            $mensaje = 'No se recibieron las observaciones';
            $continuar = false;
        }
        */
        if ($continuar && empty($params["iddivisa"])) {
            $mensaje = 'No se recibió la divisa';
            $continuar = false;
        }

        if ($continuar && !isset($params["tipoop"])) {
            $mensaje = 'No se recibió el tipo de operación';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Separar idpagara
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $idpagara = $params['idpagara'];
            $idpagarasplit = explode("-", $idpagara);

            if (count($idpagarasplit) < 2) {
                $mensaje = 'El formato de pagar a no es válido';
                $continuar = false;
            } else {
                $idpagaratipo = (int)$idpagarasplit[0];
                $idpagaraValor = (int)$idpagarasplit[1];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Separar idcobrara
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $idcobrara = $params['idcobrara'];
            $idcobrarasplit = explode("-", $idcobrara);

            if (count($idcobrarasplit) < 2) {
                $mensaje = 'El formato de cobrar a no es válido';
                $continuar = false;
            } else {
                $idcobraratipo = (int)$idcobrarasplit[0];
                $idcobraraValor = (int)$idcobrarasplit[1];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Registrar orden de pago
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Obtener nuevo número de factura de pago
            |--------------------------------------------------------------------------
            */
            $queryNumero = "
                SELECT 
                    IFNULL(MAX(t_facturapago.numerofactura), 0) + 1 AS numerofactura
                FROM t_facturapago
                LEFT JOIN t_embarque 
                    ON t_facturapago.idembarque = t_embarque.idembarque
                WHERE t_facturapago.gestion = YEAR(CURRENT_DATE())
                  AND t_facturapago.idtipofacturapago = :idtipofacturapago
                  AND t_embarque.idempresa = :idempresa
            ";

            $stmtNumero = $conexion->prepare($queryNumero);

            $stmtNumero->execute([
                ':idtipofacturapago' => $params["idtipofacturapago"],
                ':idempresa' => $idempresa
            ]);

            $rowNumero = $stmtNumero->fetch(PDO::FETCH_ASSOC);
            $numeroFactura = (int)($rowNumero['numerofactura'] ?? 1);

            /*
            |--------------------------------------------------------------------------
            | Insertar factura de pago / orden de pago
            |--------------------------------------------------------------------------
            */
            $queryFacturaPago = "
                INSERT INTO t_facturapago (
                    idtipofacturapago,
                    fecha,
                    numerofactura,
                    gestion,
                    idtransportista,
                    idpagara,
                    idpagaratipo,
                    idpagaradireccion,
                    fechadocumento,
                    idcobrara,
                    idcobraratipo,
                    idembarque,
                    idestadofacturapago,
                    tipocambio,
                    observaciones,
                    iddivisa,
                    tipoop
                ) VALUES (
                    :idtipofacturapago,
                    CURRENT_DATE(),
                    :numerofactura,
                    YEAR(CURRENT_DATE()),
                    :idtransportista,
                    :idpagara,
                    :idpagaratipo,
                    :idpagaradireccion,
                    :fechadocumento,
                    :idcobrara,
                    :idcobraratipo,
                    :idembarque,
                    1,
                    :tipocambio,
                    :observaciones,
                    :iddivisa,
                    :tipoop
                )
            ";

            $stmtFacturaPago = $conexion->prepare($queryFacturaPago);

            $resultFacturaPago = $stmtFacturaPago->execute([
                ':idtipofacturapago' => $params["idtipofacturapago"],
                ':numerofactura' => $numeroFactura,
                ':idtransportista' => $params["idtransportista"],
                ':idpagara' => $idpagaraValor,
                ':idpagaratipo' => $idpagaratipo,
                ':idpagaradireccion' => $params["idpagaradireccion"],
                ':fechadocumento' => $params["fechadocumento"],
                ':idcobrara' => $idcobraraValor,
                ':idcobraratipo' => $idcobraratipo,
                ':idembarque' => $idembarque,
                ':tipocambio' => ($params["tipocambio"] ?? null),
                ':observaciones' => ($params["observaciones"] ?? ''),
                ':iddivisa' => $params["iddivisa"],
                ':tipoop' => $params["tipoop"]
            ]);

            if (!$resultFacturaPago) {
                $mensaje = 'No se pudo registrar el documento';

                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idfacturapagoNueva = (int)$conexion->lastInsertId();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Asociar costos al documento generado
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            if (is_array($costos)) {

                $queryCosto = "
                    UPDATE t_costo
                    SET 
                        idfacturanotadebito = :idfacturapago,
                        idtipofacturanotadebito = 1
                    WHERE idcosto = :idcosto
                ";

                $stmtCosto = $conexion->prepare($queryCosto);

                foreach ($costos as $idcosto) {
                    if (!empty($idcosto)) {
                        $stmtCosto->execute([
                            ':idfacturapago' => $idfacturapagoNueva,
                            ':idcosto' => $idcosto
                        ]);
                    }
                }

            } else {
                $mensaje = 'Los costos recibidos no tienen un formato válido';

                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Recuperar documento generado
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryFacturaPagoFinal = "
                SELECT 
                    idfacturapago
                FROM t_facturapago
                WHERE idfacturapago = :idfacturapago
                LIMIT 1
            ";

            $stmtFacturaPagoFinal = $conexion->prepare($queryFacturaPagoFinal);

            $stmtFacturaPagoFinal->execute([
                ':idfacturapago' => $idfacturapagoNueva
            ]);

            $rowFacturaPago = $stmtFacturaPagoFinal->fetch(PDO::FETCH_ASSOC);

            if ($rowFacturaPago) {
                $idfacturapago = (int)$rowFacturaPago['idfacturapago'];

                $conexion->commit();

                $codigo = 200;
                $status = 'Exito';
                $mensaje = 'Documento generado exitosamente';
            } else {
                $mensaje = 'No se pudo recuperar el documento generado';

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado = array(
        'codigo' => $codigo,
        'estado' => $status,
        'mensaje' => $mensaje,
        'idfacturapago' => $idfacturapago,
        'facturaspago' => getFacturasPago($idembarque, $conexion)
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->get('/contabilidad/ordenespago/download/{idfacturapago}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idfacturapago = $args['idfacturapago'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/ordenespago/ordenpago$idfacturapago.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    $carpeta=generarOP($idfacturapago, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta,
        'carpeta'=>$carpeta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/contabilidad/ordenespago/migrarovp/{idfacturapago}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idfacturapago = $args['idfacturapago'];
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';

    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idusuario=$decoded_array["idusuario"];

    $respuesta=[];

    $clienteovp='';
    $ovppago = new OVP();//iniciamos OVP

    $clienteovp = new SoapClient(servicioovp, array('trace' => 1, 'encoding' => 'UTF-8'));//ISO-8859-1
    /*
    $result = mysql_query("");
    $resultfactura = mysql_fetch_assoc($result);
    if ($resultfactura['idembarque']!=0){
        $respuesta=$ovppago->agregarpago($idfacturapago,"ovppago",ciudadovp,$clienteovp,$idusuario,$conexion);
    }
    */
    $result = $conexion->query("SELECT idfacturapago,idtipofacturapago,tipoop,fecha,numerofactura,idembarque FROM t_facturapago WHERE idfacturapago=$idfacturapago;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $respuesta=$ovppago->agregarpago($idfacturapago,"ovppago",ciudadovp,$clienteovp,$idusuario,$conexion);
    }

    if($respuesta["migrado"]){
        $codigo=200;
        $status='Exito';
        $mensaje='Los datos se guardaron correctamente';
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'respuesta'=>$respuesta
    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/ordenespago/anular/{idfacturapago}', function(Request $request, Response $response, array $args) use ($conexion, $archivospermitidos) {

    $idfacturapago = $args['idfacturapago'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $resplado_anulacion = '';
    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Token ya validado por middleware
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded_array = (array) $decoded;

        $idusuario = $decoded_array["idusuario"] ?? null;
        $idempresa = $decoded_array["idempresa"] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if (empty($idfacturapago)) {
            $mensaje = 'No se recibió la orden de pago';
            $continuar = false;
        }

        if ($continuar && empty($idempresa)) {
            $mensaje = 'No se recibió la empresa';
            $continuar = false;
        }

        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Crear carpeta de empresa
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $creacion = new Carpetas();
            $respuesta = $creacion->procesarCarpeta($idempresa);
        }

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros del formulario
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $bodyParams = $request->getParsedBody();

            if (!is_array($bodyParams)) {
                $bodyParams = [];
            }

            $idmotivoanulacion = $bodyParams['idmotivoanulacion'] ?? null;
            $otro_motivoanulacion = '';

            if (empty($idmotivoanulacion)) {
                $mensaje = 'No se recibió el motivo de anulación';
                $continuar = false;
            }

            if ($continuar && (int)$idmotivoanulacion === 5) {
                $otro_motivoanulacion = $bodyParams['otro_motivoanulacion'] ?? '';

                if (trim($otro_motivoanulacion) === '') {
                    $mensaje = 'Debe ingresar el otro motivo de anulación';
                    $continuar = false;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validar archivos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $files = $_FILES['uploads'] ?? null;

            $totalArchivos = (
                isset($files['name']) &&
                is_array($files['name'])
            ) ? count($files['name']) : 0;

            if ($totalArchivos === 0) {
                $mensaje = 'Debe adjuntar el respaldo de anulación';
                $continuar = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Subir respaldo
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            for ($fi = 0; $fi < $totalArchivos; $fi++) {

                if (!$continuar) {
                    break;
                }

                if (($files['error'][$fi] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    $mensaje = 'Ocurrió un problema al subir el archivo';
                    $continuar = false;
                    break;
                }

                $nombredoc = $files["name"][$fi];

                $piramideUploader = new PiramideUploader();

                $upload = $piramideUploader->upload(
                    $nombredoc,
                    'uploads',
                    folder_files . $idempresa . DIRECTORY_SEPARATOR . 'respaldos_facturaspago_anuladas/' . $idfacturapago,
                    $archivospermitidos,
                    true,
                    $fi
                );

                $file = $piramideUploader->getInfoFile();

                if ($upload['uploaded']) {
                    /*
                    Si llega más de un archivo, se guarda el último,
                    igual que en tu lógica original.
                    */
                    $resplado_anulacion = $file['complete_name'];
                } else {
                    $mensaje = $upload['error'];
                    $continuar = false;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Anular orden de pago y liberar costos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryFacturaPago = "
                UPDATE t_facturapago
                SET
                    idestadofacturapago = 2,
                    fecha_anulacion = CURRENT_TIMESTAMP(),
                    idusuarios_anulacion = :idusuario,
                    idmotivoanulacion = :idmotivoanulacion,
                    otro_motivoanulacion = :otro_motivoanulacion,
                    resplado_anulacion = :resplado_anulacion
                WHERE idfacturapago = :idfacturapago
            ";

            $stmtFacturaPago = $conexion->prepare($queryFacturaPago);

            $resultFacturaPago = $stmtFacturaPago->execute([
                ':idusuario' => $idusuario,
                ':idmotivoanulacion' => $idmotivoanulacion,
                ':otro_motivoanulacion' => $otro_motivoanulacion,
                ':resplado_anulacion' => $resplado_anulacion,
                ':idfacturapago' => $idfacturapago
            ]);

            if (!$resultFacturaPago) {
                $mensaje = 'No se pudo anular la orden de pago';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        if ($continuar) {

            $queryCosto = "
                UPDATE t_costo
                SET
                    idfacturanotadebito = NULL,
                    idtipofacturanotadebito = NULL
                WHERE idfacturanotadebito = :idfacturapago
                  AND idtipofacturanotadebito = 1
            ";

            $stmtCosto = $conexion->prepare($queryCosto);

            $resultCosto = $stmtCosto->execute([
                ':idfacturapago' => $idfacturapago
            ]);

            if (!$resultCosto) {
                $mensaje = 'No se pudieron liberar los costos asociados';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmar operación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->commit();

            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Los datos se guardaron correctamente';
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado = array(
        'codigo' => $codigo,
        'estado' => $status,
        'mensaje' => $mensaje,
        'resplado_anulacion' => $resplado_anulacion
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->post('/contabilidad/generarordenservicio/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {

    $idembarque = $args['idembarque'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $idordenservicio = 0;
    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Token ya validado por middleware
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded_array = (array) $decoded;

        $idempresa = $decoded_array["idempresa"] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros
        |--------------------------------------------------------------------------
        */
        $params = json_decode((string) $request->getBody(), true);

        if (!is_array($params)) {
            $mensaje = 'No se recibieron parámetros válidos';
            $continuar = false;
        }

        if ($continuar) {
            $conceptos = $params["conceptos"] ?? [];

            if (empty($idembarque)) {
                $mensaje = 'No se recibió el embarque';
                $continuar = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idempresa)) {
            $mensaje = 'No se recibió la empresa';
            $continuar = false;
        }

        if ($continuar && empty($params["tipoos"])) {
            $mensaje = 'No se recibió el tipo de orden de servicio';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Validar tipo de orden de servicio
        |--------------------------------------------------------------------------
        | tipoos = i => ingresos / cargos
        | tipoos = e => egresos / costos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $tipoos = strtolower(trim($params["tipoos"]));

            if (!in_array($tipoos, ['i', 'e'])) {
                $mensaje = 'El tipo de orden de servicio no es válido';
                $continuar = false;
            }
        }

        if ($continuar && empty($params["idsolicitadopor"])) {
            $mensaje = 'No se recibió el solicitante';
            $continuar = false;
        }

        if ($continuar && empty($params["iddivisaordenservicio"])) {
            $mensaje = 'No se recibió la divisa de la orden de servicio';
            $continuar = false;
        }

        if ($continuar && !isset($params["tipocambio"])) {
            $mensaje = 'No se recibió el tipo de cambio';
            $continuar = false;
        }

        if ($continuar && !isset($params["creditnot"])) {
            $mensaje = 'No se recibió el credit note';
            $continuar = false;
        }

        if ($continuar && empty($params["idusuario"])) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        if ($continuar && !is_array($conceptos)) {
            $mensaje = 'Los conceptos recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Definir tabla y columna según tipoos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $tablaOrdenServicio = "t_ordenservicio" . $tipoos;
            $columnaOrdenServicio = "idordenservicio" . $tipoos;

            if ($tipoos === 'i') {
                $tablaConcepto = "t_cargo";
                $idConcepto = "idcargo";
            } else {
                $tablaConcepto = "t_costo";
                $idConcepto = "idcosto";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Registrar orden de servicio
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Obtener nuevo número de orden de servicio
            |--------------------------------------------------------------------------
            */
            $queryNumero = "
                SELECT 
                    IFNULL(MAX(os.numero), 0) + 1 AS numero
                FROM $tablaOrdenServicio os
                LEFT JOIN t_embarque e
                    ON os.idembarque = e.idembarque
                WHERE os.gestion = YEAR(CURRENT_DATE())
                  AND e.idempresa = :idempresa
            ";

            $stmtNumero = $conexion->prepare($queryNumero);

            $stmtNumero->execute([
                ':idempresa' => $idempresa
            ]);

            $rowNumero = $stmtNumero->fetch(PDO::FETCH_ASSOC);
            $numeroOrdenServicio = (int)($rowNumero['numero'] ?? 1);

            /*
            |--------------------------------------------------------------------------
            | Insertar orden de servicio
            |--------------------------------------------------------------------------
            */
            $queryOrdenServicio = "
                INSERT INTO $tablaOrdenServicio (
                    fecha,
                    numero,
                    gestion,
                    idembarque,
                    idsolicitadopor,
                    iddivisaordenservicio,
                    tipocambio,
                    creditnot,
                    idestado,
                    idusuario
                ) VALUES (
                    CURRENT_DATE(),
                    :numero,
                    YEAR(CURRENT_DATE()),
                    :idembarque,
                    :idsolicitadopor,
                    :iddivisaordenservicio,
                    :tipocambio,
                    :creditnot,
                    1,
                    :idusuario
                )
            ";

            $stmtOrdenServicio = $conexion->prepare($queryOrdenServicio);

            $resultOrdenServicio = $stmtOrdenServicio->execute([
                ':numero' => $numeroOrdenServicio,
                ':idembarque' => $idembarque,
                ':idsolicitadopor' => $params["idsolicitadopor"],
                ':iddivisaordenservicio' => $params["iddivisaordenservicio"],
                ':tipocambio' => $params["tipocambio"],
                ':creditnot' => $params["creditnot"],
                ':idusuario' => $params["idusuario"]
            ]);

            if (!$resultOrdenServicio) {
                $mensaje = 'No se pudo registrar la orden de servicio';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idordenservicioNueva = (int)$conexion->lastInsertId();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Asociar conceptos a la orden de servicio
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryConcepto = "
                UPDATE $tablaConcepto
                SET $columnaOrdenServicio = :idordenservicio
                WHERE $idConcepto = :idconcepto
            ";

            $stmtConcepto = $conexion->prepare($queryConcepto);

            foreach ($conceptos as $idconceptoValor) {
                if (!empty($idconceptoValor)) {
                    $stmtConcepto->execute([
                        ':idordenservicio' => $idordenservicioNueva,
                        ':idconcepto' => $idconceptoValor
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmar y responder
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->commit();

            $idordenservicio = $idordenservicioNueva;

            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Documento generado exitosamente';
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado = array(
        'codigo' => $codigo,
        'estado' => $status,
        'mensaje' => $mensaje,
        'idordenservicio' => $idordenservicio,
        'ordenserviciosi' => getOrdenServicioI($idembarque, $conexion),
        'ordenserviciose' => getOrdenServicioE($idembarque, $conexion)
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->get('/contabilidad/ordenesservicio/{tipo}/download/{idordenservicio}', function(Request $request, Response $response, array $args) use ($conexion) {
    $tipo = $args['tipo'];
    $idordenservicio = $args['idordenservicio'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/ordenesservicio/$tipo/ordenservicio$idordenservicio.pdf";
    if(file_exists($file)){
        unlink($file);
    }
    generarOrdenServicio($idordenservicio, $tipo, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/cobros/detalle', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $cobrosdetalle=[];

    $result = $conexion->query("select 
        t_cobro.idfacturanotadebito, 
        t_cobro.idtipocobro, 
        t_cobro.fechapago, 
        SUM(t_cobro.monto) as cobrado 
        from 
        t_cobro 
        LEFT JOIN t_factura ON t_cobro.idfacturanotadebito=t_factura.idfactura AND t_cobro.idtipocobro=1
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        WHERE
        t_embarque.idempresa=$idempresa
        GROUP BY 
        t_cobro.idfacturanotadebito, 
        t_cobro.idtipocobro, 
        t_cobro.fechapago
        UNION ALL
        select 
        t_cobro.idfacturanotadebito, 
        t_cobro.idtipocobro, 
        t_cobro.fechapago, 
        SUM(t_cobro.monto) as cobrado 
        from 
        t_cobro 
        LEFT JOIN t_notadebito ON t_cobro.idfacturanotadebito=t_notadebito.idnotadebito AND t_cobro.idtipocobro=2
        LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
        WHERE
        t_embarque.idempresa=$idempresa
        GROUP BY 
        t_cobro.idfacturanotadebito, 
        t_cobro.idtipocobro, 
        t_cobro.fechapago;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cobrosdetalle[]=array(
            'idfacturanotadebito'=>(int)$row['idfacturanotadebito'],
            'idtipocobro'=>(int)$row['idtipocobro'],
            'fechapago'=>$row['fechapago'],
            'cobrado'=>(float)$row['cobrado']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'cobrosdetalle' => $cobrosdetalle
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/cobros/{idtipoentidad}/{id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];

    $cobros=[];

    $result = $conexion->query("SELECT
        t_factura.idfactura as iddocumento,
        1 as idtipodocumento,
        t_factura.fecha,
        'Factura' as tipodocumento,
        t_embarque.embarque,
        t_factura.idcobrara,
        t_factura.idcobraratipo,
        t_factura.nrofactura as numerodocumento,
        valorfacturado(t_factura.idfactura) as monto,
        valorcobradofactura(t_factura.idfactura) as cobrado,
        valorfacturado(t_factura.idfactura)-valorcobradofactura(t_factura.idfactura) as saldo,
        (valorfacturado(t_factura.idfactura)-valorcobradofactura(t_factura.idfactura))*t_tipocambio.tipocambio as saldous,
        DATEDIFF(CURRENT_DATE(),t_factura.fecha) as dias,
        invoicefactura(t_factura.idfactura) as invoice
        FROM
        t_factura
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_factura.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_factura.fecha) AND 1=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE 
        t_factura.idestadofactura=1
        AND t_factura.idcobrara=$id
        AND t_factura.idcobraratipo=$idtipoentidad
        AND (valorfacturado(t_factura.idfactura)-valorcobradofactura(t_factura.idfactura))>0
        UNION ALL
        SELECT
        t_notadebito.idnotadebito as iddocumento,
        2 as idtipodocumento,
        t_notadebito.fecha,
        'Nota de Cobranza' as tipodocumento,
        t_embarque.embarque,
        t_notadebito.idcobrara,
        t_notadebito.idcobraratipo,
        CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion) as numerodocumento,
        valordebitado(t_notadebito.idnotadebito) as monto,
        valorcobradonotadebito(t_notadebito.idnotadebito) as cobrado,
        valordebitado(t_notadebito.idnotadebito)-valorcobradonotadebito(t_notadebito.idnotadebito) as saldo,
        (valordebitado(t_notadebito.idnotadebito)-valorcobradonotadebito(t_notadebito.idnotadebito))*t_tipocambio.tipocambio as saldous,
        DATEDIFF(CURRENT_DATE(),t_notadebito.fecha) as dias,
        invoicenotadebito(t_notadebito.idnotadebito) as invoice
        FROM
        t_notadebito
        LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_notadebito.fecha) AND 1=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE 
        t_notadebito.idestadonotadebito=1
        AND t_notadebito.idcobrara=$id
        AND t_notadebito.idcobraratipo=$idtipoentidad
        AND (valordebitado(t_notadebito.idnotadebito)-valorcobradonotadebito(t_notadebito.idnotadebito))>0;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cobros[]=array(
            'iddocumento'=>(int)$row['iddocumento'],
            'idtipodocumento'=>(int)$row['idtipodocumento'],
            'embarque'=>$row['embarque'],
            'fecha'=>$row['fecha'],
            'tipodocumento'=>$row['tipodocumento'],
            'numerodocumento'=>$row['numerodocumento'],
            'monto'=>(float)$row['monto'],
            'cobrado'=>(float)$row['cobrado'],
            'saldo'=>(float)$row['saldo'],
            'saldous'=>(float)$row['saldous'],
            'dias'=>(int)$row['dias'],
            'invoice'=>$row['invoice']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'cobros' => $cobros
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/cobros/{idtipoentidad}/{id}/historico/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];

    $historico=[];

    $result = $conexion->query("SELECT
        t_anticipo.idanticipo,
        t_cobro.fechapago,
        t_cobro.numero,
        t_anticipo.recibo,
        CONCAT(t_cuenta.banco,' ',t_cuenta.cuenta) as banco,
        GROUP_CONCAT(t_embarque.embarque SEPARATOR ', ') as embarque,
        SUM(t_cobro.monto) as monto
        FROM
        t_cobro
        LEFT JOIN t_anticipo ON t_cobro.idanticipo=t_anticipo.idanticipo
        LEFT JOIN t_cuenta ON t_anticipo.idcuenta=t_cuenta.idcuenta
        LEFT JOIN t_factura ON t_cobro.idfacturanotadebito=t_factura.idfactura AND t_cobro.idtipocobro=1
        LEFT JOIN t_notadebito ON t_cobro.idfacturanotadebito=t_notadebito.idnotadebito AND t_cobro.idtipocobro=2
        LEFT JOIN t_embarque ON CASE t_cobro.idtipocobro WHEN 1 THEN t_factura.idembarque WHEN 2 THEN t_notadebito.idembarque END=t_embarque.idembarque
        WHERE 
        t_cobro.fechapago BETWEEN '$fechainicial' AND '$fechafinal'
        AND t_anticipo.identidad=$id
        AND t_anticipo.idtipoentidad=$idtipoentidad
        GROUP BY
        t_anticipo.idanticipo,
        t_cobro.fechapago,
        t_cobro.numero,
        t_anticipo.recibo,
        t_cuenta.banco,
        t_cuenta.cuenta
        ORDER BY 
        t_cobro.fechapago,
        t_cobro.numero;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $historico[]=array(
            'idanticipo'=>(int)$row['idanticipo'],
            'fechapago'=>$row['fechapago'],
            'numero'=>$row['numero'],
            'recibo'=>$row['recibo'],
            'banco'=>$row['banco'],
            'embarque'=>$row['embarque'],
            'monto'=>(float)$row['monto']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'historico' => $historico
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/cobros/{idtipoentidad}/{id}/aplicar', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $idusuario=$decoded_array["idusuario"];

    $params = json_decode((string) $request->getBody(),true);

    $fechapgo = $params["fechapago"] ?? null;
    $aplicaciones = $params['aplicaciones'] ?? [];

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    try {
        if (empty($fechapgo)) {
            throw new Exception('No se recibió la fecha de pago');
        }

        if (!is_array($aplicaciones) || count($aplicaciones) === 0) {
            throw new Exception('No se recibieron aplicaciones');
        }

        $conexion->beginTransaction();

        foreach ($aplicaciones as $aplicacion) {

            $idanticipo = $aplicacion["idanticipo"] ?? 0;

            if ((int)$idanticipo === 0) {

                $queryAnticipo = "
                    INSERT INTO t_anticipo (
                        identidad,
                        idtipoentidad,
                        fecha,
                        recibo,
                        idcuenta,
                        idtipotransferencia,
                        glosa,
                        monto,
                        anticiporeal,
                        idusuario
                    ) VALUES (
                        :identidad,
                        :idtipoentidad,
                        :fecha,
                        :recibo,
                        :idcuenta,
                        :idtipotransferencia,
                        :glosa,
                        :monto,
                        :anticiporeal,
                        :idusuario
                    )
                ";

                $stmtAnticipo = $conexion->prepare($queryAnticipo);

                $stmtAnticipo->execute([
                    ':identidad' => $id,
                    ':idtipoentidad' => $idtipoentidad,
                    ':fecha' => $fechapgo,
                    ':recibo' => $aplicacion['recibo'] ?? '',
                    ':idcuenta' => $aplicacion['idcuenta'] ?? null,
                    ':idtipotransferencia' => $aplicacion['idtipotransferencia'] ?? null,
                    ':glosa' => $aplicacion['glosa'] ?? '',
                    ':monto' => $aplicacion['monto'] ?? 0,
                    ':anticiporeal' => $aplicacion['anticiporeal'] ?? 0,
                    ':idusuario' => $idusuario
                ]);

                $idanticipoUsar = $conexion->lastInsertId();

            } else {
                $idanticipoUsar = (int)$idanticipo;
            }

            $queryNumeroCobro = "
                SELECT 
                    IFNULL(MAX(t_cobro.numero), 0) + 1 AS numerocobro
                FROM t_cobro
                LEFT JOIN t_anticipo 
                    ON t_cobro.idanticipo = t_anticipo.idanticipo
                LEFT JOIN v_entidades 
                    ON t_anticipo.idtipoentidad = v_entidades.idtipoentidad 
                AND t_anticipo.identidad = v_entidades.identidad
                WHERE v_entidades.idempresa = :idempresa
            ";

            $stmtNumeroCobro = $conexion->prepare($queryNumeroCobro);

            $stmtNumeroCobro->execute([
                ':idempresa' => $idempresa
            ]);

            $rowNumero = $stmtNumeroCobro->fetch(PDO::FETCH_ASSOC);
            $numerocobro = (int)($rowNumero['numerocobro'] ?? 1);

            $cobros = $aplicacion["cobros"] ?? [];

            if (!is_array($cobros) || count($cobros) === 0) {
                throw new Exception('Una aplicación no tiene cobros registrados');
            }

            foreach ($cobros as $cobro) {

                $queryCobro = "
                    INSERT INTO t_cobro (
                        numero,
                        fecha,
                        fechapago,
                        idanticipo,
                        idtipocobro,
                        idfacturanotadebito,
                        monto
                    ) VALUES (
                        :numero,
                        CURRENT_DATE(),
                        :fechapago,
                        :idanticipo,
                        :idtipocobro,
                        :idfacturanotadebito,
                        :monto
                    )
                ";

                $stmtCobro = $conexion->prepare($queryCobro);

                $stmtCobro->execute([
                    ':numero' => $numerocobro,
                    ':fechapago' => $fechapgo,
                    ':idanticipo' => $idanticipoUsar,
                    ':idtipocobro' => $cobro["idtipodocumento"] ?? null,
                    ':idfacturanotadebito' => $cobro["iddocumento"] ?? null,
                    ':monto' => $cobro["monto"] ?? 0
                ]);
            }
        }

        $conexion->commit();

        $codigo = 200;
        $status = 'Exito';
        $mensaje = 'Generado con éxito';

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = $e->getMessage();
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'query'=>$query
    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/anticipos/{idtipoentidad}/{id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];

    $anticipos=[];

    $result = $conexion->query("select 
            t_anticipo.idanticipo,
            t_anticipo.fecha,
            t_anticipo.recibo,
            t_anticipo.idcuenta,
            t_cuenta.banco,
            t_cuenta.cuenta,
            t_anticipo.idtipotransferencia,
            t_tipotransferencia.tipotransferencia,
            t_anticipo.glosa,
            t_anticipo.monto,
            valoraplicado(t_anticipo.idanticipo) as aplicado,
            t_anticipo.monto-valoraplicado(t_anticipo.idanticipo)-valordevuelto(t_anticipo.idanticipo) as saldo,
            IFNULL(t_anticipo.anticiporeal,0) as anticiporeal
            from 
            t_anticipo
            LEFT JOIN t_cuenta ON t_anticipo.idcuenta=t_cuenta.idcuenta
            LEFT JOIN t_tipotransferencia ON t_anticipo.idtipotransferencia=t_tipotransferencia.idtipotransferencia
            WHERE 
            t_anticipo.identidad=$id
            AND t_anticipo.idtipoentidad=$idtipoentidad
            ORDER BY t_anticipo.fecha;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $anticipos[]=array(
            'idanticipo'=>(int)$row['idanticipo'],
            'fecha'=>$row['fecha'],
            'recibo'=>$row['recibo'],
            'idcuenta'=>(int)$row['idcuenta'],
            'banco'=>$row['banco'],
            'cuenta'=>$row['cuenta'],
            'idtipotransferencia'=>(int)$row['idtipotransferencia'],
            'glosa'=>$row['glosa'],
            'tipotransferencia'=>$row['tipotransferencia'],
            'monto'=>(float)$row['monto'],
            'aplicado'=>(float)$row['aplicado'],
            'saldo'=>(float)$row['saldo'],
            'anticiporeal'=> boolval($row["anticiporeal"])
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'anticipos' => $anticipos
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/anticipos/{idtipoentidad}/{id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idusuario=$decoded_array["idusuario"];

    $params = json_decode((string) $request->getBody(),true);

    $fecha=$params['fecha'];
    $recibo=$params['recibo'];
    $idcuenta=$params['idcuenta'];
    $idtipotransferencia=$params['idtipotransferencia'];
    $glosa=$params['glosa'];
    $monto=$params['monto'];
    $anticiporeal=$params['anticiporeal'];

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';

    $query = "INSERT INTO t_anticipo (
        identidad,
        idtipoentidad,
        fecha,
        recibo,
        idcuenta,
        idtipotransferencia,
        glosa,
        monto,
        anticiporeal,
        idusuario
    ) VALUES (
        :identidad,
        :idtipoentidad,
        :fecha,
        :recibo,
        :idcuenta,
        :idtipotransferencia,
        :glosa,
        :monto,
        :anticiporeal,
        :idusuario
    )";

    $queryejecutar = $conexion->prepare($query);

    $result = $queryejecutar->execute([
        ':identidad' => $id,
        ':idtipoentidad' => $idtipoentidad,
        ':fecha' => $fecha,
        ':recibo' => $recibo,
        ':idcuenta' => $idcuenta,
        ':idtipotransferencia' => $idtipotransferencia,
        ':glosa' => $glosa,
        ':monto' => $monto,
        ':anticiporeal' => $anticiporeal,
        ':idusuario' => $idusuario
    ]);

    if($result){
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información General';
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/download/anticipos/{idanticipo}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idanticipo = $args['idanticipo'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/anticipos/anticipo$idanticipo.pdf";
    if(file_exists($file)){
        unlink($file);
    }

    $respuesta=generarAnticipo($idanticipo, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/download/cobros/{numero}', function(Request $request, Response $response, array $args) use ($conexion) {
    $numero = $args['numero'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/cobros/cobro$numero.pdf";
    if(file_exists($file)){
        unlink($file);
    }

    generarAplicacion($idempresa, $numero, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/pagos/{idtipoentidad}/{id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];

    $pagos=[];

    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_pagos;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_pagos (idfacturapago INT, pagado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_pagos (idfacturapago, pagado)
        SELECT 
        t_pagodetalle.idfacturapago,
        SUM(t_pagodetalle.monto) 
        FROM 
        t_pagodetalle
        LEFT JOIN t_facturapago ON t_pagodetalle.idfacturapago=t_facturapago.idfacturapago
        WHERE
        t_facturapago.idpagaratipo=$idtipoentidad
        AND t_facturapago.idpagara=$id
        GROUP BY
        t_pagodetalle.idfacturapago;");
    $conexion->query("ALTER TABLE tmp_pagos ADD INDEX idfacturapago (idfacturapago);");

    $result = $conexion->query("SELECT
        t_facturapago.idfacturapago,
        t_tipofacturapago.tipofacturapago,
        t_facturapago.fecha,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as numerofactura,
        t_embarque.embarque,
        SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) as valorpagofacturado,
        IFNULL(tmp_pagos.pagado,0) as pagado,
        ROUND(SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio)-IFNULL(tmp_pagos.pagado,0),2) as saldo,
        t_facturapago.iddivisa,
        t_divisa.codigo as divisa
        FROM
        t_facturapago
        LEFT JOIN t_costo ON t_facturapago.idfacturapago=t_costo.idfacturanotadebito
        LEFT JOIN t_divisa ON t_facturapago.iddivisa=t_divisa.iddivisa
        LEFT JOIN t_tipofacturapago ON t_facturapago.idtipofacturapago=t_tipofacturapago.idtipofacturapago
        LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
        LEFT JOIN tmp_pagos ON t_facturapago.idfacturapago=tmp_pagos.idfacturapago
        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND t_facturapago.iddivisa=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_facturapago.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE
        t_facturapago.idestadofacturapago=1
        AND t_facturapago.idpagara=$id
        AND t_facturapago.idpagaratipo=$idtipoentidad
        GROUP BY 
        t_facturapago.idfacturapago,
        t_tipofacturapago.tipofacturapago,
        t_facturapago.fecha,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion),
        t_embarque.embarque,
        tmp_pagos.pagado,
        t_facturapago.iddivisa,
        t_divisa.codigo
        HAVING (SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio)-IFNULL(tmp_pagos.pagado,0))>0
        ORDER BY t_facturapago.fecha, t_facturapago.numerofactura;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $pagos[]=array(
            'idfacturapago'=>(int)$row['idfacturapago'],
            'tipofacturapago'=>$row['tipofacturapago'],
            'fecha'=>$row['fecha'],
            'numerofactura'=>$row['numerofactura'],
            'embarque'=>$row['embarque'],
            'valorpagofacturado'=>(float)$row['valorpagofacturado'],
            'pagado'=>(float)$row['pagado'],
            'saldo'=>(float)$row['saldo'],
            'iddivisa'=>(int)$row['iddivisa'],
            'divisa'=>$row['divisa']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'pagos' => $pagos
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/pagos/{idtipoentidad}/{id}/aplicar', function(Request $request, Response $response, array $args) use ($conexion) {

    $idtipoentidad = $args['idtipoentidad'] ?? null;
    $id = $args['id'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Token ya validado por middleware
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded_array = (array) $decoded;

        $idusuario = $decoded_array["idusuario"] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros
        |--------------------------------------------------------------------------
        */
        $params = json_decode((string) $request->getBody(), true);

        if (!is_array($params)) {
            $mensaje = 'No se recibieron parámetros válidos';
            $continuar = false;
        }

        if ($continuar) {
            $fecha = $params["fecha"] ?? null;
            $idcuenta = $params["idcuenta"] ?? null;
            $pagoa = $params["pagoa"] ?? '';
            $idtipotransferencia = $params["idtipotransferencia"] ?? null;
            $nrotransaccion = $params["nrotransaccion"] ?? '';
            $alaordende = $params["alaordende"] ?? '';
            $concepto = $params["concepto"] ?? '';
            $aplicaciones = $params['aplicaciones'] ?? [];
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idtipoentidad)) {
            $mensaje = 'No se recibió el tipo de entidad';
            $continuar = false;
        }

        if ($continuar && empty($id)) {
            $mensaje = 'No se recibió la entidad';
            $continuar = false;
        }

        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        if ($continuar && empty($fecha)) {
            $mensaje = 'No se recibió la fecha';
            $continuar = false;
        }

        if ($continuar && empty($idcuenta)) {
            $mensaje = 'No se recibió la cuenta';
            $continuar = false;
        }

        if ($continuar && empty($idtipotransferencia)) {
            $mensaje = 'No se recibió el tipo de transferencia';
            $continuar = false;
        }

        if ($continuar && !is_array($aplicaciones)) {
            $mensaje = 'Las aplicaciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && count($aplicaciones) === 0) {
            $mensaje = 'No se recibieron aplicaciones para el pago';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Registrar pago
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Obtener nuevo número de pago
            |--------------------------------------------------------------------------
            */
            $queryNumeroPago = "
                SELECT IFNULL(MAX(nropago), 0) + 1 AS nropago
                FROM t_pago
            ";

            $stmtNumeroPago = $conexion->prepare($queryNumeroPago);
            $stmtNumeroPago->execute();

            $rowNumeroPago = $stmtNumeroPago->fetch(PDO::FETCH_ASSOC);
            $nropago = (int)($rowNumeroPago['nropago'] ?? 1);

            /*
            |--------------------------------------------------------------------------
            | Insertar cabecera del pago
            |--------------------------------------------------------------------------
            */
            $queryPago = "
                INSERT INTO t_pago (
                    nropago,
                    fecha,
                    idcuenta,
                    idmetodopago,
                    nrotransaccion,
                    alaordende,
                    concepto,
                    pagoa,
                    idusuario
                ) VALUES (
                    :nropago,
                    :fecha,
                    :idcuenta,
                    :idmetodopago,
                    :nrotransaccion,
                    :alaordende,
                    :concepto,
                    :pagoa,
                    :idusuario
                )
            ";

            $stmtPago = $conexion->prepare($queryPago);

            $resultPago = $stmtPago->execute([
                ':nropago' => $nropago,
                ':fecha' => $fecha,
                ':idcuenta' => $idcuenta,
                ':idmetodopago' => $idtipotransferencia,
                ':nrotransaccion' => $nrotransaccion,
                ':alaordende' => $alaordende,
                ':concepto' => $concepto,
                ':pagoa' => $pagoa,
                ':idusuario' => $idusuario
            ]);

            if (!$resultPago) {
                $mensaje = 'No se pudo registrar el pago';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idpagoUsar = (int)$conexion->lastInsertId();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar detalle del pago
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDetalle = "
                INSERT INTO t_pagodetalle (
                    idpago,
                    idfacturapago,
                    monto,
                    iddivisa
                ) VALUES (
                    :idpago,
                    :idfacturapago,
                    :monto,
                    :iddivisa
                )
            ";

            $stmtDetalle = $conexion->prepare($queryDetalle);

            foreach ($aplicaciones as $aplicacion) {

                $idfacturapago = $aplicacion["idfacturapago"] ?? null;
                $monto = $aplicacion["monto"] ?? null;
                $iddivisa = $aplicacion["iddivisa"] ?? null;

                if (empty($idfacturapago)) {
                    $mensaje = 'Una aplicación no tiene factura de pago';
                    $continuar = false;
                    break;
                }

                if ($monto === null || $monto === '') {
                    $mensaje = 'Una aplicación no tiene monto';
                    $continuar = false;
                    break;
                }

                if (empty($iddivisa)) {
                    $mensaje = 'Una aplicación no tiene divisa';
                    $continuar = false;
                    break;
                }

                $resultDetalle = $stmtDetalle->execute([
                    ':idpago' => $idpagoUsar,
                    ':idfacturapago' => $idfacturapago,
                    ':monto' => $monto,
                    ':iddivisa' => $iddivisa
                ]);

                if (!$resultDetalle) {
                    $mensaje = 'No se pudo registrar el detalle del pago';
                    $continuar = false;
                    break;
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmar operación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->commit();

            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Generado con éxito';
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado = array(
        'codigo' => $codigo,
        'estado' => $status,
        'mensaje' => $mensaje
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->get('/contabilidad/pagado/{idtipoentidad}/{id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];

    $pagado=[];

    $result = $conexion->query("SELECT
        t_pago.idpago,
        t_pago.nropago,
        t_pago.fecha,
        t_pago.nrotransaccion,
        t_cuenta.banco,
        t_cuenta.cuenta,
        SUM(t_pagodetalle.monto) as monto,
        t_divisa.codigo as divisa
        FROM
        t_pago
        LEFT JOIN t_pagodetalle ON t_pago.idpago=t_pagodetalle.idpago
        LEFT JOIN t_cuenta ON t_pago.idcuenta=t_cuenta.idcuenta
        LEFT JOIN t_divisa ON t_pagodetalle.iddivisa=t_divisa.iddivisa
        LEFT JOIN t_facturapago ON t_pagodetalle.idfacturapago=t_facturapago.idfacturapago
        WHERE 
        t_facturapago.idpagara=$id
        AND t_facturapago.idpagaratipo=$idtipoentidad
        GROUP BY
        t_pago.idpago,
        t_pago.nropago,
        t_pago.fecha,
        CONCAT(t_cuenta.banco,' ',t_cuenta.cuenta),
        t_pago.nrotransaccion,
        t_divisa.codigo
        ORDER BY
        t_pago.fecha DESC, t_pago.nropago DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $pagado[]=array(
            'idpago'=>(int)$row['idpago'],
            'nropago'=>(int)$row['nropago'],
            'fecha'=>$row['fecha'],
            'nrotransaccion'=>$row['nrotransaccion'],
            'banco'=>$row['banco'],
            'cuenta'=>$row['cuenta'],
            'monto'=>(float)$row['monto'],
            'divisa'=>$row['divisa']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'pagado' => $pagado
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/download/pagos/{idpago}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idpago = $args['idpago'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/pagos/pago$idpago.pdf";
    if(file_exists($file)){
        unlink($file);
    }

    generarPago($idempresa,$idpago, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/saldos/{idtipoentidad}/{id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];

    $saldos=[];

    $result = $conexion->query("SELECT 
                            idanticipo,
                            glosa,
                            monto-valoraplicado(t_anticipo.idanticipo)-valordevuelto(t_anticipo.idanticipo) as monto,
                            recibo
                            FROM 
                            t_anticipo
                            WHERE 
                            identidad=$id
                            AND idtipoentidad=$idtipoentidad
                            AND (monto-valoraplicado(t_anticipo.idanticipo)-valordevuelto(t_anticipo.idanticipo))>0;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $saldos[]=array(
            'idanticipo'=>(int)$row['idanticipo'],
            'recibo'=>$row['recibo'],
            'glosa'=>$row['glosa'],
            'monto'=>(float)$row['monto']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'saldos' => $saldos
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contabilidad/saldos/{idtipoentidad}/{id}/devolver', function(Request $request, Response $response, array $args) use ($conexion) {

    $idtipoentidad = $args['idtipoentidad'] ?? null;
    $id = $args['id'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Token ya validado por middleware
        |--------------------------------------------------------------------------
        */
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
        $decoded_array = (array) $decoded;

        $idusuario = $decoded_array["idusuario"] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros
        |--------------------------------------------------------------------------
        */
        $params = json_decode((string) $request->getBody(), true);

        if (!is_array($params)) {
            $mensaje = 'No se recibieron parámetros válidos';
            $continuar = false;
        }

        if ($continuar) {
            $fechadevolucion = $params["fechadevolucion"] ?? null;
            $idcuenta = $params["idcuenta"] ?? null;
            $numerotransaccion = $params["numerotransaccion"] ?? '';
            $concepto = $params["concepto"] ?? '';
            $ordende = $params["ordende"] ?? '';
            $devoluciones = $params['devoluciones'] ?? [];
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idtipoentidad)) {
            $mensaje = 'No se recibió el tipo de entidad';
            $continuar = false;
        }

        if ($continuar && empty($id)) {
            $mensaje = 'No se recibió la entidad';
            $continuar = false;
        }

        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        if ($continuar && empty($fechadevolucion)) {
            $mensaje = 'No se recibió la fecha de devolución';
            $continuar = false;
        }

        if ($continuar && empty($idcuenta)) {
            $mensaje = 'No se recibió la cuenta';
            $continuar = false;
        }

        if ($continuar && !is_array($devoluciones)) {
            $mensaje = 'Las devoluciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && count($devoluciones) === 0) {
            $mensaje = 'No se recibieron devoluciones';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Registrar devolución
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Obtener nuevo número de devolución
            |--------------------------------------------------------------------------
            */
            $queryNumeroDevolucion = "
                SELECT IFNULL(MAX(numero), 0) + 1 AS numero
                FROM t_devolucion
            ";

            $stmtNumeroDevolucion = $conexion->prepare($queryNumeroDevolucion);
            $stmtNumeroDevolucion->execute();

            $rowNumeroDevolucion = $stmtNumeroDevolucion->fetch(PDO::FETCH_ASSOC);
            $numeroDevolucion = (int)($rowNumeroDevolucion['numero'] ?? 1);

            /*
            |--------------------------------------------------------------------------
            | Insertar cabecera de devolución
            |--------------------------------------------------------------------------
            */
            $queryDevolucion = "
                INSERT INTO t_devolucion (
                    numero,
                    identidad,
                    idtipoentidad,
                    fechadevolucion,
                    idcuenta,
                    numerotransaccion,
                    concepto,
                    ordende
                ) VALUES (
                    :numero,
                    :identidad,
                    :idtipoentidad,
                    :fechadevolucion,
                    :idcuenta,
                    :numerotransaccion,
                    :concepto,
                    :ordende
                )
            ";

            $stmtDevolucion = $conexion->prepare($queryDevolucion);

            $resultDevolucion = $stmtDevolucion->execute([
                ':numero' => $numeroDevolucion,
                ':identidad' => $id,
                ':idtipoentidad' => $idtipoentidad,
                ':fechadevolucion' => $fechadevolucion,
                ':idcuenta' => $idcuenta,
                ':numerotransaccion' => $numerotransaccion,
                ':concepto' => $concepto,
                ':ordende' => $ordende
            ]);

            if (!$resultDevolucion) {
                $mensaje = 'No se pudo registrar la devolución';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $iddevolucionUsar = (int)$conexion->lastInsertId();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar detalle de devolución
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDetalle = "
                INSERT INTO t_devoluciondetalle (
                    iddevolucion,
                    idanticipo,
                    monto
                ) VALUES (
                    :iddevolucion,
                    :idanticipo,
                    :monto
                )
            ";

            $stmtDetalle = $conexion->prepare($queryDetalle);

            foreach ($devoluciones as $devolucion) {

                $idanticipo = $devolucion["idanticipo"] ?? null;
                $monto = $devolucion["monto"] ?? null;

                if (empty($idanticipo)) {
                    $mensaje = 'Una devolución no tiene anticipo';
                    $continuar = false;
                    break;
                }

                if ($monto === null || $monto === '') {
                    $mensaje = 'Una devolución no tiene monto';
                    $continuar = false;
                    break;
                }

                $resultDetalle = $stmtDetalle->execute([
                    ':iddevolucion' => $iddevolucionUsar,
                    ':idanticipo' => $idanticipo,
                    ':monto' => $monto
                ]);

                if (!$resultDetalle) {
                    $mensaje = 'No se pudo registrar el detalle de la devolución';
                    $continuar = false;
                    break;
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmar operación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->commit();

            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Generado con éxito';
        }

    } catch (PDOException $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $resultado = array(
        'codigo' => $codigo,
        'estado' => $status,
        'mensaje' => $mensaje
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->get('/contabilidad/devuelto/{idtipoentidad}/{id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];

    $devuelto=[];

    $result = $conexion->query("SELECT 
        t_devolucion.iddevolucion,
        t_devolucion.numero,
        t_devolucion.fechadevolucion,
        SUM(t_devoluciondetalle.monto) as monto
        from 
        t_devolucion
        LEFT JOIN t_devoluciondetalle ON t_devolucion.iddevolucion=t_devoluciondetalle.iddevolucion
        WHERE
        t_devolucion.identidad=$id
        AND t_devolucion.idtipoentidad=$idtipoentidad
        GROUP BY
        t_devolucion.iddevolucion,
        t_devolucion.numero,
        t_devolucion.fechadevolucion
        ORDER BY
        t_devolucion.numero DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $devuelto[]=array(
            'iddevolucion'=>(int)$row['iddevolucion'],
            'numero'=>(int)$row['numero'],
            'fechadevolucion'=>$row['fechadevolucion'],
            'monto'=>(float)$row['monto']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'devuelto' => $devuelto
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/download/devoluciones/{iddevolucion}', function(Request $request, Response $response, array $args) use ($conexion) {
    $iddevolucion = $args['iddevolucion'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema, intentelo mas tarde';
    $data='';
    $pathinfo='';
    $respuesta='';

    $file=folder_files.$idempresa."/documentos/devoluciones/devolucion$iddevolucion.pdf";
    if(file_exists($file)){
        unlink($file);
    }

    generarDevolucion($iddevolucion, $conexion, false);

    if(file_exists($file)){
        $contenido = file_get_contents($file);
        $data = base64_encode($contenido);
        $codigo=200;
        $status='Exito';
        $mensaje='';
        $pathinfo=mime_content_type($file);
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'data'=>$data,
        'pathinfo'=>$pathinfo,
        'respuesta'=>$respuesta

    );

    $response->getBody()->write(json_encode($resultado));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/devoluciones', function(Request $request, Response $response, array $args) use ($conexion) {

    $devoluciones=[];

    $result = $conexion->query("SELECT 
        t_devolucion.iddevolucion,
        t_devolucion.numero,
        CONCAT(t_cuenta.banco,' | ',t_cuenta.cuenta) as banco,
        t_devolucion.fechadevolucion,
        t_devolucion.numerotransaccion,
        t_devolucion.concepto,
        t_devolucion.ordende,
        CASE t_devolucion.idtipoentidad
            WHEN 1 THEN t_cliente.cliente
            WHEN 2 THEN t_proveedor.proveedor
            WHEN 3 THEN t_prestador.prestador
            WHEN 4 THEN t_transportista.transportista
            WHEN 5 THEN t_agentecarga.agentecarga
        END as entidad,
        SUM(t_devoluciondetalle.monto) as monto
        from 
        t_devolucion
        LEFT JOIN t_cuenta ON t_devolucion.idcuenta=t_cuenta.idcuenta
        LEFT JOIN t_cliente ON t_devolucion.identidad=t_cliente.idcliente
        LEFT JOIN t_proveedor ON t_devolucion.identidad=t_proveedor.idproveedor
        LEFT JOIN t_prestador ON t_devolucion.identidad=t_prestador.idprestador
        LEFT JOIN t_transportista ON t_devolucion.identidad=t_transportista.idtransportista
        LEFT JOIN t_agentecarga ON t_devolucion.identidad=t_agentecarga.idagentecarga
        LEFT JOIN t_devoluciondetalle ON t_devolucion.iddevolucion=t_devoluciondetalle.iddevolucion
        GROUP BY
        t_devolucion.iddevolucion,
        t_devolucion.numero,
        CONCAT(t_cuenta.banco,' | ',t_cuenta.cuenta),
        t_devolucion.fechadevolucion,
        t_devolucion.numerotransaccion,
        t_devolucion.concepto,
        t_devolucion.ordende,
        CASE t_devolucion.idtipoentidad
            WHEN 1 THEN t_cliente.cliente
            WHEN 2 THEN t_proveedor.proveedor
            WHEN 3 THEN t_prestador.prestador
            WHEN 4 THEN t_transportista.transportista
            WHEN 5 THEN t_agentecarga.agentecarga
        END
        ORDER BY
        t_devolucion.numero DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $devoluciones[]=array(
            'iddevolucion'=>(int)$row['iddevolucion'],
            'numero'=>(int)$row['numero'],
            'banco'=>$row['banco'],
            'fechadevolucion'=>$row['fechadevolucion'],
            'numerotransaccion'=>$row['numerotransaccion'],
            'concepto'=>$row['concepto'],
            'ordende'=>$row['ordende'],
            'entidad'=>$row['entidad'],
            'monto'=>(float)$row['monto']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'devoluciones' => $devoluciones
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/reportes/cobranzas/{idtipoentidad}/{id}/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];

    $cobranzas=[];

    $filtroentidad='';
    if((int)$idtipoentidad>0){
        $filtroentidad=" AND tmp_entidad.identidad='$idtipoentidad-$id' ";
    }

    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_entidad;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_entidad (identidad VARCHAR(10), entidad VARCHAR(250));");
    $conexion->query("INSERT INTO tmp_entidad (identidad, entidad)
        SELECT
        CONCAT('1-',idcliente) as identidad,
        cliente as entidad
        FROM t_cliente
        UNION ALL
        SELECT
        CONCAT('2-',idproveedor) as identidad,
        proveedor as entidad
        FROM t_proveedor
        UNION ALL
        SELECT
        CONCAT('3-',idprestador) as identidad,
        prestador as entidad
        FROM t_prestador
        UNION ALL
        SELECT
        CONCAT('4-',idtransportista) as identidad,
        transportista as entidad
        FROM t_transportista
        UNION ALL
        SELECT
        CONCAT('2-',idagentecarga) as identidad,
        agentecarga as entidad
        FROM t_agentecarga;");
    $conexion->query("ALTER TABLE tmp_entidad ADD INDEX identidad (identidad);");

    $result = $conexion->query("select
        t_cobro.fechapago,
        t_anticipo.recibo,
        t_embarque.embarque,
        tmp_entidad.entidad,
        IFNULL(t_factura.nrofactura,CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion)) as numero,
        CASE t_cobro.idtipocobro
            WHEN 1 THEN 'Factura'
            WHEN 2 THEN 'Nota de Cobranza'
        END as tipo,
        CASE t_cobro.idtipocobro
            WHEN 1 THEN valorfacturado(t_factura.idfactura)
            WHEN 2 THEN valordebitado(t_notadebito.idnotadebito)
        END as monto,
        IFNULL(t_factura.fecha,t_notadebito.fecha) as fecha,
        DATEDIFF(t_cobro.fechapago, IFNULL(t_factura.fecha,t_notadebito.fecha)) as dias,
        t_cobro.monto as cobrado,
        CONCAT(t_cuenta.banco,' ',t_cuenta.cuenta) as cuenta,
        tmp_entidad.identidad
        FROM
        t_cobro
        LEFT JOIN t_anticipo On t_cobro.idanticipo=t_anticipo.idanticipo
        LEFT JOIN tmp_entidad ON CONCAT(t_anticipo.idtipoentidad,'-',t_anticipo.identidad)=tmp_entidad.identidad
        LEFT JOIN t_factura ON t_cobro.idfacturanotadebito=t_factura.idfactura AND t_cobro.idtipocobro=1
        LEFT JOIN t_notadebito ON t_cobro.idfacturanotadebito=t_notadebito.idnotadebito AND t_cobro.idtipocobro=2
        LEFT JOIN t_embarque ON IFNULL(t_factura.idembarque,t_notadebito.idembarque)=t_embarque.idembarque
        LEFT JOIN t_cuenta ON t_anticipo.idcuenta=t_cuenta.idcuenta
        WHERE
        t_cobro.fechapago BETWEEN '$fechainicial' AND '$fechafinal' $filtroentidad;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cobranzas[]=array(
            'fechapago'=>$row['fechapago'],
            'recibo'=>$row['recibo'],
            'embarque'=>$row['embarque'],
            'entidad'=>$row['entidad'],
            'numero'=>$row['numero'],
            'tipo'=>$row['tipo'],
            'monto'=>(float)$row['monto'],
            'fecha'=>$row['fecha'],
            'dias'=>(int)$row['dias'],
            'cobrado'=>(float)$row['cobrado'],
            'cuenta'=>$row['cuenta']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'cobranzas' => $cobranzas
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/reportes/anticipos/{idtipoentidad}/{id}/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipoentidad = $args['idtipoentidad'];
    $id = $args['id'];
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];

    $anticipos=[];
    $filtrocliente="";
    if((int)$idtipoentidad<>0 && (int)$id<>0){
        $filtrocliente=" AND t_anticipo.identidad=$id AND t_anticipo.idtipoentidad=$idtipoentidad";
    }

    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_embarquescobros;");
    $conexion->query("CREATE TEMPORARY TABLE  tmp_embarquescobros (idanticipo INT, embarque TEXT);");
    $conexion->query("INSERT INTO tmp_embarquescobros (idanticipo, embarque)
        SELECT
        t_cobro.idanticipo,
        GROUP_CONCAT(DISTINCT IFNULL(t_embarquefactura.embarque,t_embarquenotadebito.embarque) SEPARATOR ' ') as embarque
        FROM
        t_cobro
        LEFT JOIN t_factura ON t_cobro.idfacturanotadebito=t_factura.idfactura AND t_cobro.idtipocobro=1
        LEFT JOIN t_embarque as t_embarquefactura ON t_factura.idembarque=t_embarquefactura.idembarque
        LEFT JOIN t_notadebito ON t_cobro.idfacturanotadebito=t_notadebito.idnotadebito AND t_cobro.idtipocobro=2
        LEFT JOIN t_embarque as t_embarquenotadebito ON t_notadebito.idembarque=t_embarquenotadebito.idembarque
        LEFT JOIN t_anticipo ON t_cobro.idanticipo=t_anticipo.idanticipo
        WHERE
        CAST(t_anticipo.fecha as DATE) BETWEEN '$fechainicial' AND '$fechafinal'
        $filtrocliente
        GROUP BY
        t_cobro.idanticipo;");
    $conexion->query("ALTER TABLE tmp_embarquescobros ADD INDEX idanticipo (idanticipo);");
    
    $result = $conexion->query("select 
            t_anticipo.idanticipo,
            t_anticipo.fecha,
            t_anticipo.recibo,
            v_entidades.entidad,
            t_anticipo.idcuenta,
            t_cuenta.banco,
            t_cuenta.cuenta,
            t_anticipo.idtipotransferencia,
            t_tipotransferencia.tipotransferencia,
            t_anticipo.glosa,
            t_anticipo.monto,
            valoraplicado(t_anticipo.idanticipo) as aplicado,
            tmp_embarquescobros.embarque,
            valordevuelto(t_anticipo.idanticipo) as devuelto,
            t_anticipo.monto-valoraplicado(t_anticipo.idanticipo)-valordevuelto(t_anticipo.idanticipo) as saldo,
            IFNULL(t_anticipo.anticiporeal,0) as anticiporeal
            from 
            t_anticipo
            LEFT JOIN t_cuenta ON t_anticipo.idcuenta=t_cuenta.idcuenta
            LEFT JOIN t_tipotransferencia ON t_anticipo.idtipotransferencia=t_tipotransferencia.idtipotransferencia
            LEFT JOIN tmp_embarquescobros ON t_anticipo.idanticipo=tmp_embarquescobros.idanticipo
            LEFT JOIN v_entidades ON t_anticipo.identidad=v_entidades.identidad AND t_anticipo.idtipoentidad=v_entidades.idtipoentidad
            WHERE 
            CAST(t_anticipo.fecha as DATE) BETWEEN '$fechainicial' AND '$fechafinal'
            $filtrocliente
            ORDER BY t_anticipo.fecha;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $anticipos[]=array(
            'idanticipo'=>(int)$row['idanticipo'],
            'fecha'=>$row['fecha'],
            'recibo'=>$row['recibo'],
            'entidad'=>$row['entidad'],
            'idcuenta'=>(int)$row['idcuenta'],
            'banco'=>$row['banco'],
            'cuenta'=>$row['cuenta'],
            'banco_cuenta'=>$row['banco']." ".$row['cuenta'],
            'idtipotransferencia'=>(int)$row['idtipotransferencia'],
            'glosa'=>$row['glosa'],
            'tipotransferencia'=>$row['tipotransferencia'],
            'monto'=>(float)$row['monto'],
            'aplicado'=>(float)$row['aplicado'],
            'embarque'=>$row['embarque'],
            'devuelto'=>(float)$row['devuelto'],
            'saldo'=>(float)$row['saldo'],
            'anticiporeal'=> boolval($row["anticiporeal"])
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'anticipos' => $anticipos
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/reportes/facturas-concepto/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];

    $facturas=[];

    $result = $conexion->query("select
        MONTH(t_factura.fecha) as mes,
        t_factura.nombre,
        t_embarque.embarque,
        t_factura.nit,
        t_factura.fecha,
        t_factura.nrofactura,
        t_estadofactura.estadofactura,
        t_concepto.concepto,
        ROUND(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio,2) as monto,
        ROUND(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio,2)*0.87 as montoneto
        FROM
        t_factura
        LEFT JOIN t_cargo ON t_factura.idfactura=t_cargo.idfacturanotadebito AND 1=t_cargo.idtipofacturanotadebito
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        LEFT JOIN t_estadofactura ON t_factura.idestadofactura=t_estadofactura.idestadofactura
        LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE
        t_factura.fecha BETWEEN '$fechainicial' AND '$fechafinal';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $facturas[]=array(
            'mes'=>(int)$row['mes'],
            'nombre'=>$row['nombre'],
            'embarque'=>$row['embarque'],
            'nit'=>$row['nit'],
            'fecha'=>$row['fecha'],
            'nrofactura'=>$row['nrofactura'],
            'estadofactura'=>$row['estadofactura'],
            'concepto'=>$row['concepto'],
            'monto'=>(float)$row['monto'],
            'montoneto'=>(float)$row['montoneto']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'facturas' => $facturas
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/reportes/ordenes-pago-concepto/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];

    $ordenespago=[];

    $result = $conexion->query("SELECT
        MONTH(t_facturapago.fecha) as mes,
        t_cliente.cliente,
        t_embarque.embarque,
        t_facturapago.fecha,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as numero,
        t_estadofactura.estadonotadebito as estado,
        t_concepto.concepto,
        CASE t_facturapago.idestadofacturapago
            WHEN 1 THEN ROUND(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio,2)
            ELSE 0
        END as monto
        FROM
        t_facturapago
        LEFT JOIN t_costo ON t_facturapago.idfacturapago=t_costo.idfacturanotadebito AND 1=t_costo.idtipofacturanotadebito
        LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_estadofactura ON t_facturapago.idestadofacturapago=t_estadofactura.idestadofactura
        LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE
        t_facturapago.fecha BETWEEN '$fechainicial' AND '$fechafinal';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $ordenespago[]=array(
            'mes'=>(int)$row['mes'],
            'cliente'=>$row['cliente'],
            'embarque'=>$row['embarque'],
            'fecha'=>$row['fecha'],
            'numero'=>$row['numero'],
            'estado'=>$row['estado'],
            'concepto'=>$row['concepto'],
            'monto'=>(float)$row['monto']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'ordenespago' => $ordenespago
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contabilidad/reportes/conceptos/{fechainicial}/{fechafinal}', function(Request $request, Response $response, array $args) use ($conexion) {
    $fechainicial = $args['fechainicial'];
    $fechafinal = $args['fechafinal'];

    $conceptos=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_embarques;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_embarques (idembarque INT, idempresa INT, embarque VARCHAR(50), cliente VARCHAR(255), fecharealizacion DATE, estado VARCHAR(20), nodui VARCHAR(100), transportista VARCHAR(250));");
    $conexion->query("INSERT INTO tmp_embarques (idembarque, idempresa, embarque, cliente, fecharealizacion, estado, nodui, transportista)
        select
        t_embarque.idembarque,
        t_embarque.idempresa,
        t_embarque.embarque,
        t_cliente.cliente,
        t_embarque.fecharealizacion,
        IF(t_embarque.fechafinalizacion IS NULL,'','Cerrado') as estado,
        t_embarque.nodui,
        t_transportista.transportista
        FROM
        t_embarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_transportista ON t_embarque.idtransportista=t_transportista.idtransportista
        WHERE
        t_embarque.fecharealizacion BETWEEN '$fechainicial' AND '$fechafinal';");
    $conexion->query("ALTER TABLE tmp_embarques ADD INDEX idembarque (idembarque);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_reporteconceptos;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_reporteconceptos (idembarque INT, embarque VARCHAR(50), cliente VARCHAR(255), fecharealizacion DATE, estado VARCHAR(20), nodui VARCHAR(100), fecha DATE, concepto VARCHAR(150), montofactura DECIMAL(13,2), montonotadebito DECIMAL(13,2), montoi DECIMAL(13,2), montoe DECIMAL(13,2), montoinvoice DECIMAL(13,2), montoop DECIMAL(13,2), montocosto DECIMAL(13,2), montocargo DECIMAL(13,2), proveedor VARCHAR(255), transportista VARCHAR(250));");
    
    $conexion->query("INSERT INTO tmp_reporteconceptos (idembarque, embarque, cliente, fecharealizacion, estado, nodui, transportista, fecha, concepto, montofactura, montonotadebito, montoi, montoe, montoinvoice, montoop, montocosto, montocargo)
        select 
        tmp_embarques.idembarque,
        tmp_embarques.embarque,
        tmp_embarques.cliente, 
        tmp_embarques.fecharealizacion, 
        tmp_embarques.estado,
        tmp_embarques.nodui,
        tmp_embarques.transportista,
        IFNULL(t_factura.fecha,t_notadebito.fecha) as fecha,
        t_concepto.concepto,
        CASE t_cargo.idtipofacturanotadebito
                WHEN 1 THEN t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio
                ELSE 0
        END as montofactura,
        CASE t_cargo.idtipofacturanotadebito
                WHEN 2 THEN t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio
                ELSE 0
        END as montonotadebito,
        0 as montoi,
        0 as montoe,
        0 as montoinvoice,
        0 as montoop,
        0 as montocosto,
        0 as montocargo
        FROM
        tmp_embarques
        LEFT JOIN t_cargo ON tmp_embarques.idembarque=t_cargo.idembarque
        LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1 AND 1=t_factura.idestadofactura
        LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2 AND 1=t_notadebito.idestadonotadebito
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_factura.fecha,t_notadebito.fecha) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_factura.fecha,t_notadebito.fecha)) AND t_tipocambio.idempresa=tmp_embarques.idempresa
        WHERE
        t_cargo.idfacturanotadebito>0;");
    
    $conexion->query("INSERT INTO tmp_reporteconceptos (idembarque, embarque, cliente, fecharealizacion, estado, nodui, transportista, fecha, concepto, montofactura, montonotadebito, montoi, montoe, montoinvoice, montoop, montocosto, montocargo)
        select 
        tmp_embarques.idembarque,
        tmp_embarques.embarque,
        tmp_embarques.cliente, 
        tmp_embarques.fecharealizacion, 
        tmp_embarques.estado,
        tmp_embarques.nodui,
        tmp_embarques.transportista,
        t_ordenservicioi.fecha,
        t_concepto.concepto,
        0 as montofactura,
        0 as montonotadebito,
        t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio as montoi,
        0 as montoe,
        0 as montoinvoice,
        0 as montoop,
        0 as montocosto,
        0 as montocargo
        FROM
        tmp_embarques
        LEFT JOIN t_cargo ON tmp_embarques.idembarque=t_cargo.idembarque
        LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_ordenservicioi ON t_cargo.idordenservicioi=t_ordenservicioi.idordenservicioi AND 1=t_ordenservicioi.iddivisaordenservicio
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_ordenservicioi.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_ordenservicioi.fecha) AND t_tipocambio.idempresa=tmp_embarques.idempresa
        WHERE
        t_cargo.idordenservicioi>0;");
    
    $conexion->query("INSERT INTO tmp_reporteconceptos (idembarque, embarque, cliente, fecharealizacion, estado, nodui, transportista, fecha, concepto, montofactura, montonotadebito, montoi, montoe, montoinvoice, montoop, montocosto, montocargo)
        select 
        tmp_embarques.idembarque,
        tmp_embarques.embarque,
        tmp_embarques.cliente, 
        tmp_embarques.fecharealizacion, 
        tmp_embarques.estado,
        tmp_embarques.nodui,
        tmp_embarques.transportista,
        t_invoice.fecha,
        t_concepto.concepto,
        0 as montofactura,
        0 as montonotadebito,
        0 as montoi,
        0 as montoe,
        t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio as montoinvoice,
        0 as montoop,
        0 as montocosto,
        0 as montocargo
        FROM
        tmp_embarques
        LEFT JOIN t_cargo ON tmp_embarques.idembarque=t_cargo.idembarque
        LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_invoice ON t_cargo.idinvoice=t_invoice.idinvoice AND 1=t_invoice.idestadoinvoice
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_invoice.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_invoice.fecha) AND t_tipocambio.idempresa=tmp_embarques.idempresa
        WHERE
        t_cargo.idinvoice>0;");
    
    $conexion->query("INSERT INTO tmp_reporteconceptos (idembarque, embarque, cliente, fecharealizacion, estado, nodui, transportista, fecha, concepto, montofactura, montonotadebito, montoi, montoe, montoinvoice, montoop, montocosto, montocargo, proveedor)
        select 
        tmp_embarques.idembarque,
        tmp_embarques.embarque,
        tmp_embarques.cliente, 
        tmp_embarques.fecharealizacion, 
        tmp_embarques.estado,
        tmp_embarques.nodui,
        tmp_embarques.transportista,
        t_ordenservicioe.fecha,
        t_concepto.concepto,
        0 as montofactura,
        0 as montonotadebito,
        0 as montoi,
        t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio as montoe,
        0 as montoinvoice,
        0 as montoop,
        0 as montocosto,
        0 as montocargo,
        t_agentecarga.agentecarga as proveedor
        FROM
        tmp_embarques
        LEFT JOIN t_costo ON tmp_embarques.idembarque=t_costo.idembarque
        LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_ordenservicioe ON t_costo.idordenservicioe=t_ordenservicioe.idordenservicioe AND 1=t_ordenservicioe.idestado
        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_ordenservicioe.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_ordenservicioe.fecha) AND t_tipocambio.idempresa=tmp_embarques.idempresa
        LEFT JOIN t_agentecarga ON t_ordenservicioe.idsolicitadopor=t_agentecarga.idagentecarga
        WHERE
        t_costo.idordenservicioe>0;");
    
    $conexion->query("INSERT INTO tmp_reporteconceptos (idembarque, embarque, cliente, fecharealizacion, estado, nodui, transportista, fecha, concepto, montofactura, montonotadebito, montoi, montoe, montoinvoice, montoop, montocosto, montocargo, proveedor)
        select 
        tmp_embarques.idembarque,
        tmp_embarques.embarque,
        tmp_embarques.cliente, 
        tmp_embarques.fecharealizacion, 
        tmp_embarques.estado,
        tmp_embarques.nodui,
        tmp_embarques.transportista,
        t_facturapago.fecha,
        t_concepto.concepto,
        0 as montofactura,
        0 as montonotadebito,
        0 as montoi,
        0 as montoe,
        0 as montoinvoice,
        t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio as montoop,
        0 as montocosto,
        0 as montocargo,
        v_entidades.entidad as proveedor
        FROM
        tmp_embarques
        LEFT JOIN t_costo ON tmp_embarques.idembarque=t_costo.idembarque
        LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago AND 1=t_facturapago.idestadofacturapago
        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha) AND t_tipocambio.idempresa=tmp_embarques.idempresa
        LEFT JOIN v_entidades ON t_facturapago.idpagara=v_entidades.identidad AND t_facturapago.idpagaratipo=v_entidades.idtipoentidad
        WHERE
        t_costo.idfacturanotadebito>0;");
    
    $conexion->query("INSERT INTO tmp_reporteconceptos (idembarque, embarque, cliente, fecharealizacion, estado, nodui, transportista, fecha, concepto, montofactura, montonotadebito, montoi, montoe, montoinvoice, montoop, montocosto, montocargo, proveedor)
        select 
        tmp_embarques.idembarque,
        tmp_embarques.embarque,
        tmp_embarques.cliente, 
        tmp_embarques.fecharealizacion, 
        tmp_embarques.estado,
        tmp_embarques.nodui,
        tmp_embarques.transportista,
        NULL as fecha,
        t_concepto.concepto,
        0 as montofactura,
        0 as montonotadebito,
        0 as montoi,
        0 as montoe,
        0 as montoinvoice,
        0 as montoop,
        t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio as montocosto,
        0 as montocargo,
        v_entidades.entidad as proveedor
        FROM
        tmp_embarques
        LEFT JOIN t_costo ON tmp_embarques.idembarque=t_costo.idembarque
        LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=tmp_embarques.idempresa
        LEFT JOIN v_entidades ON t_costo.iddestinatario=v_entidades.identidad AND t_costo.idtipodestinatario=v_entidades.idtipoentidad
        WHERE
        t_costo.idfacturanotadebito IS NULL AND t_costo.idordenservicioe IS NULL AND IFNULL(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio,0)>0;");
    
    $conexion->query("INSERT INTO tmp_reporteconceptos (idembarque, embarque, cliente, fecharealizacion, estado, nodui, transportista, fecha, concepto, montofactura, montonotadebito, montoi, montoe, montoinvoice, montoop, montocosto, montocargo)
        select 
        tmp_embarques.idembarque,
        tmp_embarques.embarque,
        tmp_embarques.cliente, 
        tmp_embarques.fecharealizacion, 
        tmp_embarques.estado,
        tmp_embarques.nodui,
        tmp_embarques.transportista,
        NULL as fecha,
        t_concepto.concepto,
        0 as montofactura,
        0 as montonotadebito,
        0 as montoi,
        0 as montoe,
        0 as montoinvoice,
        0 as montoop,
        0 as montocosto,
        t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio as montocargo
        FROM
        tmp_embarques
        LEFT JOIN t_cargo ON tmp_embarques.idembarque=t_cargo.idembarque
        LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=tmp_embarques.idempresa
        WHERE
        t_cargo.idfacturanotadebito IS NULL AND t_cargo.idinvoice IS NULL AND t_cargo.idordenservicioi IS NULL AND IFNULL(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio,0)>0;");
    
    $result = $conexion->query("SELECT embarque, cliente, fecharealizacion, estado, nodui, transportista, fecha, concepto, montofactura, montonotadebito, montoi, montoe, montoinvoice, montoop, montocosto, montocargo, proveedor FROM tmp_reporteconceptos ORDER BY embarque;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $conceptos[]=array(
            'embarque'=>$row['embarque'],
            'cliente'=>$row['cliente'],
            'fecharealizacion'=>$row['fecharealizacion'],
            'estado'=>$row['estado'],
            'nodui'=>$row['nodui'],
            'transportista'=>$row['transportista'],
            'fecha'=>$row['fecha'],
            'concepto'=>$row['concepto'],
            'montofactura'=>(float)$row['montofactura'],
            'montonotadebito'=>(float)$row['montonotadebito'],
            'montoi'=>(float)$row['montoi'],
            'montoe'=>(float)$row['montoe'],
            'montoinvoice'=>(float)$row['montoinvoice'],
            'montoop'=>(float)$row['montoop'],
            'montocosto'=>(float)$row['montocosto'],
            'montocargo'=>(float)$row['montocargo'],
            'proveedor'=>$row['proveedor']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'conceptos' => $conceptos
    )));


    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);



function convertNumberToWord($num = false){
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    }
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    $data= implode(' ', $words);
	return $data;
}

function fechaliteral($fechaconvertir) {
    switch((int)date("m", strtotime($fechaconvertir))){
        case 1:
            $mesliteral="Enero";
            break;
        case 2:
            $mesliteral="Febrero";
            break;
        case 3:
            $mesliteral="Marzo";
            break;
        case 4:
            $mesliteral="Abril";
            break;
        case 5:
            $mesliteral="Mayo";
            break;
        case 6:
            $mesliteral="Junio";
            break;
        case 7:
            $mesliteral="Julio";
            break;
        case 8:
            $mesliteral="Agosto";
            break;
        case 9:
            $mesliteral="Septiembre";
            break;
        case 10:
            $mesliteral="Octubre";
            break;
        case 11:
            $mesliteral="Noviembre";
            break;
        case 12:
            $mesliteral="Diciembre";
            break;
    }
    return date("d", strtotime($fechaconvertir))." de ".$mesliteral." de ".date("Y", strtotime($fechaconvertir));
}

function Cfecha($fec){
    $fechafec = explode( '-', $fec );
    return $fechafec[2]."/".$fechafec[1]."/".$fechafec[0];
    //return $fec;
}

function generarFactura($idfactura, $conexion, $membretado = false){
    $result = $conexion->query("select iddosificacion FROM t_factura WHERE idfactura=$idfactura;");
    while ($row =  $result->fetch(PDO::FETCH_ASSOC)){
        $iddosificacion=$row['iddosificacion'];
    }
    
    if((int)$iddosificacion>=27){
        $terceraLeyenda = [
            0 => '',
            1 => "Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea.",
            2 => "Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido fuera de línea, verifique su envío con su proveedor o en la página web www.impuestos.gob.bo"
        ];
        $result = $conexion->query("SELECT
            t_factura.nrofactura,
            t_dosificacion.nroautorizacion,
            t_factura.nombre,
            t_factura.nit,
            DATE_FORMAT(t_factura.fecha, '%d/%m/%Y') AS fecha,
            t_tipoembarque.tipoembarque,
            t_embarque.embarque,
            t_embarque.numeroguia,
            t_factura.codigocontrol,
            DATE_FORMAT(t_dosificacion.fechalimite,'%d/%m/%Y') as fechalimite,
            t_dosificacion.actividadeconomica,
            t_dosificacion.leyenda,
            t_dosificacion.nitrazonsocial,
            t_dosificacion.llave,
            CASE ifnull(t_embarque.idtipoexpedidor,0)
                WHEN 1 THEN t_cliente.cliente
                WHEN 2 THEN t_proveedor.proveedor
                WHEN 3 THEN t_prestador.prestador
                WHEN 5 THEN t_agentecarga.agentecarga
                ELSE 'Sin Dato'
            END as proveedor,
            t_embarque.carpetapacena,
            t_embarque.nodui,
            t_origen.ciudad as origen,
            t_destino.ciudad as destino,
            t_embarque.peso,
            t_embarque.volumen,
            t_embarque.piezas,
            t_factura.pallets,
            t_factura.hora,
            t_factura.urlDocumento,
            t_factura.codigoEmision,
            t_embarque.idempresa
            FROM
            t_factura
            LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
            LEFT JOIN t_dosificacion ON t_factura.iddosificacion=t_dosificacion.iddosificacion
            LEFT JOIN t_tipoembarque On t_embarque.idtipoembarque=t_tipoembarque.idtipoembarque
            LEFT JOIN t_cliente ON t_embarque.idexpedidor=t_cliente.idcliente
            LEFT JOIN t_proveedor ON t_embarque.idexpedidor=t_proveedor.idproveedor
            LEFT JOIN t_prestador ON t_embarque.idexpedidor=t_prestador.idprestador
            LEFT JOIN t_agentecarga ON t_embarque.idexpedidor=t_agentecarga.idagentecarga
            LEFT JOIN t_ciudad as t_origen ON t_embarque.idsalida=t_origen.idciudad
            LEFT JOIN t_ciudad as t_destino ON t_embarque.idarribo=t_destino.idciudad
            WHERE t_factura.idfactura=".$idfactura.";");
        while ($row =  $result->fetch(PDO::FETCH_ASSOC)){
            $nrofactura=$row["nrofactura"];
            $nroautorizacion=$row["nroautorizacion"];
            $nombre=$row["nombre"];
            $nit=$row["nit"];
            $fecha=$row["fecha"];
            $tipoembarque=$row["tipoembarque"];
            $embarque=$row["embarque"];
            $numeroguia=$row["numeroguia"];
            $codigocontrol=$row["codigocontrol"];
            $fechalimite=$row["fechalimite"];
            $actividadeconomica=$row["actividadeconomica"];
            $leyenda=$row["leyenda"];
            $nitrazonsocial=$row["nitrazonsocial"];
            $proveedor=$row["proveedor"];
            $carpetapacena=$row["carpetapacena"];
            $nodui=$row["nodui"];
            $origen=$row["origen"];
            $destino=$row["destino"];
            $peso=$row["peso"];
            $volumen=$row["volumen"];
            $piezas=$row["piezas"];
            $llave=$row["llave"];
            $pallets=$row["pallets"];
            $hora = $row['hora'];
            $urlDocumento = $row['urlDocumento'];
            $codigoEmision = intval($row['codigoEmision']);
            $idempresa=$row['idempresa'];
        }
        
        $creacion=new Carpetas();
        $respuesta=$creacion->procesarCarpeta($idempresa);

        $resultDatosAdicionales = $conexion->query("SELECT CONCAT(concepto,' - ',notas) AS dato_adicional
                                    FROM t_factura
                                    LEFT JOIN t_cargo ON t_cargo.idfacturanotadebito = t_factura.idfactura
                                    LEFT JOIN t_concepto ON t_concepto.idconcepto = t_cargo.idconcepto
                                    WHERE idfactura = $idfactura AND t_cargo.notas IS NOT NULL AND t_cargo.notas != '';");
        $datosAdicionales = '<table style="width: 100%; border: none; border-collapse: collapse"><tr><td width="275">';
        while ($row = $resultDatosAdicionales->fetch(PDO::FETCH_ASSOC)){
          $datosAdicionales .= "<span style='font-size: 9pt; font-family: Tahoma'>{$row['dato_adicional']}</span><br/>";
        }
        $datosAdicionales .= "</td></tr></table>";

        $nombrearchivo="";
        $membretadoImagen = '';
        if($membretado){
            $nombrearchivo="membretada";
            $membretadoImagen='<style>
                body {
                    background: url("'.folder_files.$idempresa.'/documentos/facturas/facturamembretada.png");
                    background-repeat: no-repeat;
                    background-position: center center;
                    background-image-resize: 6;
                }
                </style>';
        }

        $datosXML = getDatosXML($idfactura, $conexion);
        $detalleFactura = detalleFactura($datosXML['datos']['detalle']);
        $primeraFila = isset($datosXML['datos']['detalle'][0]) ? $datosXML['datos']['detalle'][0] : null;
        $pageBreak = '';
        $altura = '';
        $pageBreakTable = '';
        $alturaTable = '';
        if ($resultDatosAdicionales->rowCount() >= 2) {
          $pageBreak = '<pagebreak>';
          //$altura = 'height: 300px';
        }
        if (is_array($primeraFila)) {
          if ($resultDatosAdicionales->rowCount() === 0 && count($datosXML['datos']['detalle']) > 7){
            $pageBreak = '<pagebreak>';
            //$altura = 'height: 300px';
          }
          if (count($datosXML['datos']['detalle']) > 6){
            $pageBreak = '<pagebreak>';
            //$altura = 'height: 300px';
          }
        }

        if ($resultDatosAdicionales->rowCount() >= 4 && is_array($primeraFila) && count($datosXML['datos']['detalle']) >= 7){
          $pageBreakTable = '<pagebreak>';
          //$alturaTable = 'height: 200px';
          $pageBreak = '';
          $altura = '';
        }
        $V=new EnLetras();
        include("../lib/phpqrcode/qrlib.php");
        QRcode::png($urlDocumento, folder_files.$idempresa.'/documentos/facturas/QR_code-'.$idfactura.'.png', QR_ECLEVEL_M, 2, 1);

        $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right'=> 15,
            'margin_top'=> 80,
            'margin_bottom'=> 30,
            'margin_header'=> 38,
            'margin_footer'=> 12,
            'default_font_size' => 8,
            'fontDir' => array_merge($fontDirs, [
              __DIR__ . '/../../lib/fonts/',
            ]),
            'fontdata' => $fontData + [
                "tahoma" => [
                  'R' => "Tahoma.ttf",
                  'B' => "TAHOMAB0.TTF",
                  'I' => "TAHOMABD.TTF"
                ],
            ],
            'default_font' => 'tahoma',
        ]);
        $cabecera = cabeceraFactura($datosXML);
        $piePagina = "<div style='margin: 0;padding: 0'>
                        <div style='text-align: center;font-family: Tahoma; font-size: 8pt'>
                          ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAIS. EL USO ILICITO SERA SANCIONADA DE ACUERDO A LEY<br>
                          {$datosXML['datos']['cabecera']['leyenda']}<br>
                          {$terceraLeyenda[$codigoEmision]}<br>
                        </div>
                        <div style='text-align: right;font-family: Tahoma; font-size: 8pt'>
                          {PAGENO} de {nbpg} / Factura Nro.:{$datosXML['datos']['cabecera']['numeroFactura']}
                        </div>
                      </div>";
        ob_start();
        include_once __DIR__."/../views/factura.php";
        $html = ob_get_clean();
        $mpdf->SetHTMLHeader($cabecera);
        $mpdf->SetHTMLFooter($piePagina);
        $mpdf->WriteHTML($html);
        unlink(folder_files.$idempresa.'/documentos/facturas/QR_code-'.$idfactura.'.png');
        $mpdf->Output(folder_files.$idempresa."/documentos/facturas/factura$nombrearchivo$idfactura.pdf");

        
    }else{
        $result = $conexion->query("SELECT
            t_factura.nrofactura,
            t_dosificacion.nroautorizacion,
            t_factura.nombre,
            t_factura.nit,
            t_factura.fecha,
            t_tipoembarque.tipoembarque,
            t_embarque.embarque,
            t_embarque.numeroguia,
            t_factura.codigocontrol,
            DATE_FORMAT(t_dosificacion.fechalimite,'%d/%m/%Y') as fechalimite,
            t_dosificacion.actividadeconomica,
            t_dosificacion.leyenda,
            t_dosificacion.nitrazonsocial,
            t_dosificacion.llave,
            CASE ifnull(t_embarque.idtipoexpedidor,0)
                WHEN 1 THEN t_cliente.cliente
                WHEN 2 THEN t_proveedor.proveedor
                WHEN 3 THEN t_prestador.prestador
                WHEN 5 THEN t_agentecarga.agentecarga
                ELSE 'Sin Dato'
            END as proveedor,
            t_embarque.carpetapacena,
            t_embarque.nodui,
            t_origen.ciudad as origen,
            t_destino.ciudad as destino,
            t_embarque.peso,
            t_embarque.volumen,
            t_embarque.piezas,
            t_factura.pallets,
            t_embarque.idempresa
            FROM
            t_factura
            LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
            LEFT JOIN t_dosificacion ON t_factura.iddosificacion=t_dosificacion.iddosificacion
            LEFT JOIN t_tipoembarque On t_embarque.idtipoembarque=t_tipoembarque.idtipoembarque
            LEFT JOIN t_cliente ON t_embarque.idexpedidor=t_cliente.idcliente
            LEFT JOIN t_proveedor ON t_embarque.idexpedidor=t_proveedor.idproveedor
            LEFT JOIN t_prestador ON t_embarque.idexpedidor=t_prestador.idprestador
            LEFT JOIN t_agentecarga ON t_embarque.idexpedidor=t_agentecarga.idagentecarga
            LEFT JOIN t_ciudad as t_origen ON t_embarque.idsalida=t_origen.idciudad
            LEFT JOIN t_ciudad as t_destino ON t_embarque.idarribo=t_destino.idciudad
            WHERE t_factura.idfactura=".$idfactura.";");
        while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
            $nrofactura=$row["nrofactura"];
            $nroautorizacion=$row["nroautorizacion"];
            $nombre=$row["nombre"];
            $nit=$row["nit"];
            $fecha=$row["fecha"];
            $tipoembarque=$row["tipoembarque"];
            $embarque=$row["embarque"];
            $numeroguia=$row["numeroguia"];
            $codigocontrol=$row["codigocontrol"];
            $fechalimite=$row["fechalimite"];
            $actividadeconomica=$row["actividadeconomica"];
            $leyenda=$row["leyenda"];
            $nitrazonsocial=$row["nitrazonsocial"];
            $proveedor=$row["proveedor"];
            $carpetapacena=$row["carpetapacena"];
            $nodui=$row["nodui"];
            $origen=$row["origen"];
            $destino=$row["destino"];
            $peso=$row["peso"];
            $volumen=$row["volumen"];
            $piezas=$row["piezas"];
            $llave=$row["llave"];
            $pallets=$row["pallets"];
            $idempresa=$row['idempresa'];
        }

        $html='';
        $nombrearchivo="";
        if($membretado){
            $nombrearchivo="membretada";
            $html=$html.'<style>
                @page {
                    background: url("'.folder_files.$idempresa.'/documentos/facturas/facturamembretada.png"); 
                    background-repeat: no-repeat;
                    background-position: left top;
                    background-image-resize:2;
                }
                </style>';
        }

        $html=$html.'
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <head>
            <meta equiv="Content-Type" content="text/html; charset=utf-8" />
            </head>
            <body>
            <br /><br /><br /><br /><br />
            <br />
            <table border="0" cellpadding="0" cellspacing="0" width="725">
                <tr>
                    <td align="right" width="600"><span style="font-size: 9pt; font-family: verdana">FACTURA No.</span></td>
                    <td align="right" width="125"><span style="font-size: 9pt; font-family: verdana">'.$nrofactura.'</span></td>
                </tr>
                <tr>
                    <td align="right" width="600"><span style="font-size: 9pt; font-family: verdana">AUTORIZACION No.</span></td>
                    <td align="right" width="125"><span style="font-size: 9pt; font-family: verdana">'.$nroautorizacion.'</span></td>
                </tr>
            </table>
            <br />
            <br />
            <br />
            <table border="0" cellpadding="0" cellspacing="6" width="725">
                <tr>
                    <td width="450" valign="top">
                        <table border="1" id="main" cellpadding="3" cellspacing="3" width="450">
                            <tr>
                                <td width="100%" colspan="2"><span style="font-size: 9pt; font-family: verdana">NOMBRE:</span></td>
                            </tr>
                            <tr>
                                <td width="100%" colspan="2" align="center"><span style="font-size: 9pt; font-family: verdana">'.$nombre.'</span></td>
                            </tr>
                            <tr>
                                <td width="50%"><span style="font-size: 9pt; font-family: verdana">NIT/CI</span></td>
                                <td width="50%" align="right"><span style="font-size: 9pt; font-family: verdana">'.$nit.'</span></td>
                            </tr>
                            <tr>
                                <td width="50%"><span style="font-size: 9pt; font-family: verdana">FECHA</span></td>
                                <td width="50%" align="right"><span style="font-size: 9pt; font-family: verdana">'.fechaliteral($fecha).'</span></td>
                            </tr>
                            <tr>
                                <td width="50%"><span style="font-size: 9pt; font-family: verdana">MODO TRANSPORTE</span></td>
                                <td width="50%" align="right"><span style="font-size: 9pt; font-family: verdana">'.$tipoembarque.'</span></td>
                            </tr>
                        </table>
                    </td>
                    <td width="275">
                        <span style="font-size: 9pt; font-family: verdana">'.$actividadeconomica.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">No. Carpeta: '.$embarque.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">No. Guia: '.$numeroguia.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">Proveedor: '.$proveedor.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">Origen: '.$origen.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">Destino: '.$destino.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">Peso: '.$peso.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">Volumen: '.$volumen.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">Piezas: '.$piezas.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">Pallets: '.$pallets.'</span><br />
                        <span style="font-size: 9pt; font-family: verdana">No PACEÑA: '.$carpetapacena.' '.$nodui.'</span>
                    </td>
                </tr>
            </table>
            <br />
            <table border="1" cellpadding="2" cellspacing="2" width="725">
                <tr>
                    <td width="400"><span style="font-size: 9pt; font-family: verdana"><strong>DETALLE</strong></span></td>
                    <td width="100"><span style="font-size: 9pt; font-family: verdana"><strong>CANTIDAD</strong></span></td>
                    <td width="100"><span style="font-size: 9pt; font-family: verdana"><strong>PRECIO/U</strong></span></td>
                    <td width="125"><span style="font-size: 9pt; font-family: verdana"><strong>SUBTOTAL</strong></span></td>
                </tr>
                <tr>
                    <td width="725" colspan="4">
                        <table border="0" cellpadding="2" cellspacing="2" width="725">
                        ';
                        $resultdetalle = $conexion->query("SELECT 
                                            COUNT(idcargo) as cantidadcargos
                                            FROM
                                            t_cargo
                                            WHERE IFNULL(idtipofacturanotadebito,0)=1
                                            AND idfacturanotadebito=".$idfactura.";") or die("SQL Error 1: " . mysql_error());
                        while ($rowdetalle =  $resultdetalle ->fetch(PDO::FETCH_ASSOC)){
                            $cantidadcargos=$rowdetalle["cantidadcargos"];
                        }


                        $cantitems=0;
                        $totalfactura=0;
                        $resultdetalle = $conexion->query("SELECT 
                                            t_concepto.concepto,
                                            t_cargo.cantidad,
                                            t_cargo.notas,
                                            t_cargo.monto*t_tipocambio.tipocambio as monto,
                                            t_cargo.cantidad*t_cargo.monto*t_tipocambio.tipocambio as subtotal
                                            FROM
                                            t_cargo
                                            LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
                                            LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura
                                            LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
                                            LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
                                            WHERE IFNULL(t_cargo.idtipofacturanotadebito,0)=1
                                            AND t_cargo.idfacturanotadebito=".$idfactura.";") or die("SQL Error 1: " . mysql_error());
                        while ($row =  $resultdetalle ->fetch(PDO::FETCH_ASSOC)){
                            $cantitems++;

                            if((int)$cantidadcargos<=7){
                                $html=$html.'
                                <tr>
                                    <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["concepto"].'</span></td>
                                    <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["cantidad"], 2, ",", ".").'</span></td>
                                    <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["monto"], 2, ",", ".").'</span></td>
                                    <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["subtotal"], 2, ",", ".").'</span></td>
                                </tr>';

                                if(strlen($row["notas"])>0){
                                    $cantitems++;
                                    $html=$html.'
                                    <tr>
                                        <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["notas"].'</span></td>
                                        <td width="100" align="right"></td>
                                        <td width="100" align="right"></td>
                                        <td width="125" align="right"></td>
                                    </tr>';
                                }
                            }else{
                                $textomostrardetalle=$row["concepto"];
                                if(strlen($row["notas"])>0){
                                    $textomostrardetalle=$row["concepto"].", ".$row["notas"];
                                }
                                $html=$html.'
                                <tr>
                                    <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$textomostrardetalle.'</span></td>
                                    <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["cantidad"], 2, ",", ".").'</span></td>
                                    <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["monto"], 2, ",", ".").'</span></td>
                                    <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["subtotal"], 2, ",", ".").'</span></td>
                                </tr>';

                            }
                            $totalfactura=$totalfactura+(float)$row["subtotal"];
                        }

                        $numfilas=12;
                        //************************
                        //*** CASOS ESPECIALES ***
                        //************************
                        if((int)$idfactura==699){
                            $numfilas=4;
                        }
                        //************************
                        //* FIN CASOS ESPECIALES *
                        //************************



                        for($i=$cantitems;$i<=$numfilas;$i++){
                            $html=$html.'<tr>
                                <td width="725" colspan="4">&nbsp;</td>
                            </tr>';
                        }


                    $html=$html.'
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="600" colspan="3" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>TOTAL Bs:</strong></span></td>
                    <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>'.  number_format($totalfactura, 2, ",", ".").'</strong></span></td>
                </tr>
            </table>
            <br />';


            $V=new EnLetras();

            $html=$html.'<span style="font-size: 9pt; font-family: verdana">SON: '.$V->ValorEnLetras(number_format($totalfactura, 2, ".", ""),"Bolivianos").'</span>';
                $codeContents = $nitrazonsocial.'|'.$nrofactura.'|'.$nroautorizacion.'|'.Cfecha($fecha).'|'.number_format($totalfactura, 2, '.', '').'|'.number_format($totalfactura, 2, '.', '').'|'.$codigocontrol.'|'.$nit.'|0|0|0|0';
                include("../lib/phpqrcode/qrlib.php");
                QRcode::png($codeContents, folder_files.$idempresa.'/documentos/facturas/QR_code-'.$idfactura.'.png', QR_ECLEVEL_M, 2, 1);
                $html=$html.'<p align="right"><img src="'.folder_files.$idempresa.'/documentos/facturas/QR_code-'.$idfactura.'.png" /></p></body>';


        $piedepagina='<table border="0" width="100%" cellpadding="0" cellspacing="0">';
        $piedepagina=$piedepagina.'<tr>';
        $piedepagina=$piedepagina.'<td width="50%" align="left"><font style="font-family: Helvetica; font-size: 9pt"><b>CÓDIGO DE CONTROL: '.$codigocontrol.'</b></FONT></td>';
        $piedepagina=$piedepagina.'<td width="50%" align="right"><font style="font-family: Helvetica; font-size: 9pt"><b>FECHA LIMITE DE EMISION: '.$fechalimite.'</b></FONT></td>';
        $piedepagina=$piedepagina.'</tr>';
        if($leyenda<>""){
            $piedepagina=$piedepagina.'<tr>';
            $piedepagina=$piedepagina.'<td colspan="2" width="100%" align="center"><font style="font-family: Helvetica; font-size: 8pt">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAIS. EL USO ILICITO SERA SANCIONADA DE ACUERDO A LEY"</FONT></td>';
            $piedepagina=$piedepagina.'</tr>';
            $piedepagina=$piedepagina.'<tr>';
            $piedepagina=$piedepagina.'<td colspan="2" width="100%" align="center"><font style="font-family: Helvetica; font-size: 8pt">'.$leyenda.'</FONT></td>';
            $piedepagina=$piedepagina.'</tr>';
        }
        $piedepagina=$piedepagina.'</table>';
        $cabecera='';

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right'=> 15,
            'margin_top'=> 12,
            'margin_bottom'=> 16,
            'margin_header'=> 9,
            'margin_footer'=> 12
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->SetHTMLFooter($piedepagina);
        $mpdf->Output(folder_files.$idempresa."/documentos/facturas/factura$nombrearchivo$idfactura.pdf");
        
    }
    
    $respuesta=array(
        'cabecera'=>$cabecera,
        'html'=>$html,
        'piePagina'=>$piePagina
    );

    return $respuesta;

}

function cabeceraFactura ($datosXML){
  return "<table border='0' cellpadding='0' cellspacing='0' width='725'>
		<tr>
			<td align='center' width='200'><span
					style='font-size: 9pt; font-family: Tahoma'><b>{$datosXML['datos']['cabecera']['razonSocialEmisor']}</b></span>
			</td>
			<td align='center' width='280'>&nbsp;</td>
			<td align='left' width='130'><span style='font-size: 9pt; font-family: Tahoma'>NIT</span></td>
			</td>
			<td align='left' width='115'><span style='font-size: 9pt; font-family: Tahoma'>{$datosXML['datos']['cabecera']['nitEmisor']}</span></td>
			</td>
		</tr>
		<tr>
			<td align='center' width='200'><span style='font-size: 9pt; font-family: Tahoma'><b>CASA MATRIZ</b></span></td>
			<td align='center' width='280'>&nbsp;</td>
			<td align='left' width='130'><span style='font-size: 9pt; font-family: Tahoma'>FACTURA Nº.</span></td>
			<td align='left' width='115'><span style='font-size: 9pt; font-family: Tahoma'>{$datosXML['datos']['cabecera']['numeroFactura']}</span></td>
		</tr>
		<tr>
			<td align='center' width='200'>
				<span style='font-size: 9pt; font-family: Tahoma'>Nro. de Punto de Venta {$datosXML['datos']['cabecera']['codigoSucursal']}</span><br>
				<span style='font-size: 9pt; font-family: Tahoma'>{$datosXML['datos']['cabecera']['direccion']}</span><br>
				<span style='font-size: 9pt; font-family: Tahoma'>Teléfono: {$datosXML['datos']['cabecera']['telefono']}</span><br>
				<span style='font-size: 9pt; font-family: Tahoma'>{$datosXML['datos']['cabecera']['municipio']}</span>
			</td>
			<td align='center' width='280'>&nbsp;</td>
			<td align='left' width='130' style='vertical-align: top'><span style='font-size: 9pt; font-family: Tahoma'>CÓD. AUTORIZACIÓN</span></td>
			<td align='left' width='115'><span style='font-size: 9pt; font-family: Tahoma; word-break: break-all'>".splitString($datosXML['datos']['cabecera']['cuf'])."</span></td>
		</tr>
	</table>";
}

function detalleFactura ($detalle){
  $unidadMedida = [
    57 =>	'UNIDAD (BIENES)',
    58	=> 'UNIDAD (SERVICIOS)'
  ];
  $primeraFila = isset($detalle[0]) ? $detalle[0] : null;
  $html = '';
  if (is_array($primeraFila)) {
    foreach ($detalle as $item) {
      $html .= "<tr>
                  <td style='width:55px;vertical-align: top' align='right'><span style='font-size: 9pt; font-family: Tahoma'>".number_format($item['cantidad'], 2, '.', ',')."</span></td>
                  <td style='width:75px;vertical-align: top'><span style='font-size: 9pt; font-family: Tahoma'>{$unidadMedida[intval($item['unidadMedida'])]}</span></td>
                  <td style='width:120px;vertical-align: top'><span style='font-size: 9pt; font-family: Tahoma'>{$item['codigoProducto']}</span></td>
                  <td style='width:250px;vertical-align: top'><span style='font-size: 9pt; font-family: Tahoma'>{$item['descripcion']}</span></td>
                  <td style='width:90px;vertical-align: top' align='right'><span style='font-size: 9pt; font-family: Tahoma'>".number_format($item['precioUnitario'], 2, '.', ',')."</span></td>
                  <td style='width:70px;vertical-align: top' align='right'><span style='font-size: 9pt; font-family: Tahoma'>0.00</span></td>
                  <td style='width:75px;vertical-align: top' align='right'><span style='font-size: 9pt; font-family: Tahoma'>".number_format($item['subTotal'], 2, '.', ',')."</span></td>
              </tr>";
    }
  } else {
    $html .= "<tr>
                  <td  style='width:55px;vertical-align: top' align='right'><span style='font-size: 9pt; font-family: Tahoma'>".number_format($detalle['cantidad'], 2, '.', ',')."</span></td>
                  <td  style='width:75px;vertical-align: top'><span style='font-size: 9pt; font-family: Tahoma'>{$unidadMedida[intval($detalle['unidadMedida'])]}</span></td>
                  <td  style='width:120px;vertical-align: top'><span style='font-size: 9pt; font-family: Tahoma'>{$detalle['codigoProducto']}</span></td>
                  <td style='width:250px;vertical-align: top'><span style='font-size: 9pt; font-family: Tahoma'>{$detalle['descripcion']}</span></td>
                  <td  style='width:90px;vertical-align: top' align='right'><span style='font-size: 9pt; font-family: Tahoma'>".number_format($detalle['precioUnitario'], 2, '.', ',')."</span></td>
                  <td  style='width:70px;vertical-align: top' align='right'><span style='font-size: 9pt; font-family: Tahoma'>0.00</span></td>
                  <td  style='width:75px;vertical-align: top' align='right'><span style='font-size: 9pt; font-family: Tahoma'>".number_format($detalle['subTotal'], 2, '.', ',')."</span></td>
              </tr>";
  }

  return $html;
}

function getDatosXML ($idfactura, $conexion){
  $result = $conexion->query("SELECT idfactura, DocumentoXML, NombreDocumentoXML FROM t_factura WHERE idfactura = $idfactura");
  $data = $result->fetchAll(PDO::FETCH_ASSOC);
  $xmlDecoded = simplexml_load_string($data[0]['DocumentoXML']);

  return [
    'nombreDocumento' => $data[0]['NombreDocumentoXML'],
    'datos' => json_decode(json_encode($xmlDecoded), true)
  ];

}

function migrarfacturaovp($idfactura, $idusuario, $conexion){
    $respuesta=[];
    $clienteovp='';
    $ovppago = new OVP();//iniciamos OVP
    $clienteovp = new SoapClient(servicioovp, array('trace' => 1, 'encoding' => 'UTF-8'));//ISO-8859-1
    $result = $conexion->query("SELECT idfactura,idembarque,nrofactura,fecha, nombre FROM t_factura WHERE idfactura=$idfactura;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $respuesta=$ovppago->agregarfacturaventasiat($idfactura,$row['nrofactura'],"ovpfactura",ciudadovp,$clienteovp,$idusuario,$conexion);
    }
    return $respuesta;
}

function enviarmailfactura($idfactura, $correosarray, $conexion){

    $nombrearchivo="membretada";

    $result = $conexion->query("SELECT 
        t_factura.nrofactura, 
        DATE_FORMAT(t_factura.fecha,'%d/%m/%Y') as fecha, 
        t_factura.nombre, 
        t_factura.nit, 
        IFNULL(t_factura.idtipodocumento,5) as idtipodocumento, 
        valorfacturado(t_factura.idfactura) as totalfactura, 
        t_factura.DocumentoXML, 
        t_factura.NombreDocumentoXML,
        t_embarque.idempresa
        FROM 
        t_factura 
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        WHERE 
        t_factura.idfactura=$idfactura;");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)){
        $nrofactura=$row['nrofactura'];
        $nombre=$row['nombre'];
        $fecha=$row['fecha'];
        $totalfactura=$row['totalfactura'];
        $documentoXML = $row['DocumentoXML'];
        $nombreDocumentoXML = $row['NombreDocumentoXML'];
        $nit = $row['nit'];
        $tipoDocumentoId = $row['idtipodocumento'];
        $idempresa=$row['idempresa'];
    }

    $correos = [];
    for($cc=0;$cc<count($correosarray);$cc++){
        $correos[] = $correosarray[$cc]["correo"];
    }
    /*
    $resultcorreo = $conexion->query("SELECT correo FROM t_correonit WHERE numero = '$nit' AND idtipodocumento = $tipoDocumentoId;");
    while ($rowcorreo = $resultcorreo->fetch(PDO::FETCH_ASSOC)){
      $correos[] = $rowcorreo['correo'];
    }
     * 
     */
    //$correos = ['mnava@kpogroup.bo', 'calvarez@kpogroup.bo', 'mhenao@kpogroup.bo', 'rhinojosa@kpogroup.bo', 'fcabrera@kpogroup.bo', 'rsalcedo@kpogroup.bo']; // quitar esta linea para producción
    if (count($correos)) {

        $contenido = file_get_contents(folder_files.$idempresa."/documentos/facturas/factura$nombrearchivo$idfactura.pdf");
        $data = base64_encode($contenido);

        // Generar archivo XML
        $dom = new DOMDocument();
        $dom->loadXML($documentoXML);
        $dom->save(folder_files.$idempresa."/documentos/facturas/xml/$nombreDocumentoXML");

      // Convertir archivo XML a base64
        $contenido = file_get_contents(folder_files.$idempresa."/documentos/facturas/xml/$nombreDocumentoXML");
        $base64_encoded = base64_encode($contenido);





        (new SendMail())->enviarMail($correos, [], "FACTURA N° $nrofactura - SLG S.R.L.", '', "<div style='margin:0; padding: 0; border: 1px solid black; font-family: Tahoma'>
                <div style='background-color: lightgrey; text-align: center; height: 80px; margin: 0; line-height: 75px; padding: 15px'>
                    <img src='".image_files."/LogoSLG.png' alt='SLG' style='width: 180px;height: 80px;float: right'>
                    <h2 style='margin: 0; padding: 0'>SLG</h2>
                </div>
                <div style='text-align: center;clear: both'>
                    <h2>FACTURA</h2>
                </div>
                <div style='margin: 0 10px; padding: 0 10px'>
                    <p><b>Razón Social: $nombre</b></p>
                    <p><b>Factura Nº: $nrofactura</b></p>
                    <p><b>Monto: $totalfactura</b></p>
                    <p><b>Moneda: Boliviano</b></p>
                    <p><b>Fecha: $fecha</b></p>
                </div>
            </div>",[
            new \SendGrid\Mail\Attachment($data,
                "application/pdf",
                "factura$nombrearchivo$idfactura.pdf"),
            new \SendGrid\Mail\Attachment($base64_encoded,
                "application/xml",
                $nombreDocumentoXML)
        ]);

        unlink(folder_files.$idempresa."/documentos/facturas/xml/$nombreDocumentoXML");
    }
}

function generarNC($idnotadebito, $conexion, $membretado = false){
    $result = $conexion->query("SELECT
        CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion) as nronotadebito,
        t_notadebito.fecha,
        CASE ifnull(t_notadebito.idcobraratipo,0)
                WHEN 1 THEN t_clientecobrara.cliente
                WHEN 2 THEN t_proveedorcobrara.proveedor
                WHEN 3 THEN t_prestadorcobrara.prestador
                WHEN 4 THEN t_transportistacobrara.transportista
                WHEN 5 THEN t_agentecargacobrara.agentecarga
                ELSE 'Sin Dato'
        END as cobrara,
        t_embarque.embarque,
        CASE ifnull(t_embarque.idtipoexpedidor,0)
                WHEN 1 THEN t_clienteexpedidor.cliente
                WHEN 2 THEN t_proveedorexpedidor.proveedor
                WHEN 3 THEN t_prestadorexpedidor.prestador
                WHEN 5 THEN t_agentecargaexpedidor.agentecarga
                ELSE 'Sin Dato'
        END as expedidor,
        CASE ifnull(t_embarque.idtipoultimoconsignatario,0)
                WHEN 1 THEN t_clienteconsignatario.cliente
                WHEN 2 THEN t_proveedorconsignatario.proveedor
                WHEN 3 THEN t_prestadorconsignatario.prestador
                WHEN 5 THEN t_agentecargaconsignatario.agentecarga
                ELSE 'Sin Dato'
        END as consignatario,
        t_origen.ciudad as origen,
        t_destino.ciudad as destino,
        t_embarque.piezas,
        t_embarque.peso,
        t_cuenta.cuenta,
        t_cuenta.moneda,
        t_embarque.nodui,
        t_notadebito.observaciones,
        t_embarque.idempresa
        FROM
        t_notadebito
        LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
        LEFT JOIN t_cliente as t_clienteexpedidor ON t_embarque.idexpedidor=t_clienteexpedidor.idcliente
        LEFT JOIN t_proveedor as t_proveedorexpedidor ON t_embarque.idexpedidor=t_proveedorexpedidor.idproveedor
        LEFT JOIN t_prestador as t_prestadorexpedidor ON t_embarque.idexpedidor=t_prestadorexpedidor.idprestador
        LEFT JOIN t_agentecarga as t_agentecargaexpedidor ON t_embarque.idexpedidor=t_agentecargaexpedidor.idagentecarga
        LEFT JOIN t_cliente as t_clienteconsignatario ON t_embarque.idultimoconsignatario=t_clienteconsignatario.idcliente
        LEFT JOIN t_proveedor as t_proveedorconsignatario ON t_embarque.idultimoconsignatario=t_proveedorconsignatario.idproveedor
        LEFT JOIN t_prestador as t_prestadorconsignatario ON t_embarque.idultimoconsignatario=t_prestadorconsignatario.idprestador
        LEFT JOIN t_agentecarga as t_agentecargaconsignatario ON t_embarque.idultimoconsignatario=t_agentecargaconsignatario.idagentecarga

        LEFT JOIN t_cliente as t_clientecobrara ON t_notadebito.idcobrara=t_clientecobrara.idcliente
        LEFT JOIN t_proveedor as t_proveedorcobrara ON t_notadebito.idcobrara=t_proveedorcobrara.idproveedor
        LEFT JOIN t_prestador as t_prestadorcobrara ON t_notadebito.idcobrara=t_prestadorcobrara.idprestador
        LEFT JOIN t_transportista as t_transportistacobrara ON t_notadebito.idcobrara=t_transportistacobrara.idtransportista
        LEFT JOIN t_agentecarga as t_agentecargacobrara ON t_notadebito.idcobrara=t_agentecargacobrara.idagentecarga

        LEFT JOIN t_ciudad as t_origen ON t_embarque.idsalida=t_origen.idciudad
        LEFT JOIN t_ciudad as t_destino ON t_embarque.idarribo=t_destino.idciudad
        LEFT JOIN t_cuenta ON t_notadebito.idcuenta=t_cuenta.idcuenta
        WHERE t_notadebito.idnotadebito=".$idnotadebito.";") or die("SQL Error 1: " . mysql_error());
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $nronotadebito=$row["nronotadebito"];
        $cobrara=$row["cobrara"];
        $fecha=$row["fecha"];
        $expedidor=$row["expedidor"];
        $embarque=$row["embarque"];
        $consignatario=$row["consignatario"];
        $origen=$row["origen"];
        $destino=$row["destino"];
        $peso=$row["peso"];
        $piezas=$row["piezas"];
        $cuenta=$row["cuenta"];
        $moneda=$row["moneda"];
        $observaciones=nl2br($row["observaciones"]);
        $nodui=$row["nodui"];
        $idempresa=$row['idempresa'];
    }
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);

    $html='';
    $nombrearchivo="";
    if($membretado){
        $nombrearchivo="membretada";
        $html=$html.'<style>
        @page {
            background: url("'.folder_files.$idempresa.'/documentos/notascobranza/membretadalogistica.png");
            background-repeat: no-repeat;
            background-position: left top;
            background-image-resize:2;
        }
        </style>';
    }

    $html=$html.'
        <table border="0" cellpadding="0" cellspacing="0" width="725">
            <tr>
                <td align="right"><span style="font-size: 15pt; font-family: verdana">NOTA DE COBRANZA</span></td>
            </tr>
        </table>
        <table border="0" cellpadding="3" cellspacing="2" width="725">
            <tr>
                <td width="355">&nbsp;</td>
                <td width="150" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana">Fecha</span></td>
                <td width="220" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana">Numero</span></td>
            </tr>
            <tr>
                <td width="355">&nbsp;</td>
                <td width="150" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px"><span style="font-size: 9pt; font-family: verdana">'.fechaliteral($fecha).'</span></td>
                <td width="220" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px"><span style="font-size: 9pt; font-family: verdana">'.$nronotadebito.'</span></td>
            </tr>
            <tr>
                <td width="355" height="17" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana">COBRAR A</span></td>
                <td width="150" rowspan="2" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                    <span style="font-size: 9pt; font-family: verdana">No de Documento:</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Expedidor:</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Consignatario:</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Origen:</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Destino:</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Piezas:</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Peso:</span><br />
                    <span style="font-size: 9pt; font-family: verdana">No DUI:</span>
                </td>
                <td width="220" rowspan="2" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                    <span style="font-size: 9pt; font-family: verdana">'.$embarque.'&nbsp;</span><br />
                    <span style="font-size: 9pt; font-family: verdana">'.substr($expedidor, 0,27).'&nbsp;</span><br />
                    <span style="font-size: 9pt; font-family: verdana">'.substr($consignatario, 0,27).'&nbsp;</span><br />
                    <span style="font-size: 9pt; font-family: verdana">'.substr($origen, 0,27).'&nbsp;</span><br />
                    <span style="font-size: 9pt; font-family: verdana">'.substr($destino, 0,27).'&nbsp;</span><br />
                    <span style="font-size: 9pt; font-family: verdana">'.substr($piezas, 0,27).'&nbsp;</span><br />
                    <span style="font-size: 9pt; font-family: verdana">'.substr($peso, 0,27).'&nbsp;</span><br />
                    <span style="font-size: 9pt; font-family: verdana">'.$nodui.'&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td width="355" valign="top" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px"><span style="font-size: 9pt; font-family: verdana">'.$cobrara.'</span></td>
            </tr>
        </table>
        <br />
        <table border="0" cellpadding="2" cellspacing="2" width="725">
            <tr>
                <td width="400" bgcolor="#CCCCCC" style="border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>DETALLE</strong></span></td>
                <td width="100" bgcolor="#CCCCCC" style="border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>CANTIDAD</strong></span></td>
                <td width="100" bgcolor="#CCCCCC" style="border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>PRECIO/U</strong></span></td>
                <td width="125" bgcolor="#CCCCCC" style="border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>SUBTOTAL</strong></span></td>
            </tr>
            ';

            $cantitems=0;
            $totalnotadebito=0;
            $result = $conexion->query("SELECT
                                t_concepto.concepto,
                                t_cargo.cantidad,
                                t_cargo.notas,
                                t_cargo.monto*t_tipocambio.tipocambio as monto,
                                t_cargo.cantidad*t_cargo.monto*t_tipocambio.tipocambio as subtotal,
                                t_divisa.codigo as divisa
                                FROM
                                t_cargo
                                LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
                                LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito
                                LEFT JOIN t_embarque ON t_notadebito.idembarque=t_embarque.idembarque
                                LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND t_notadebito.iddivisa=t_tipocambio.iddivisadestino AND t_notadebito.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_notadebito.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
                                LEFT JOIN t_divisa ON t_notadebito.iddivisa=t_divisa.iddivisa
                                WHERE IFNULL(t_cargo.idtipofacturanotadebito,0)=2
                                AND t_notadebito.idnotadebito=".$idnotadebito.";") or die("SQL Error 1: " . mysql_error());
            while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
                $cantitems++;
                $divisa=$row["divisa"];
                $html=$html.'
                <tr>
                    <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["concepto"].'</span></td>
                    <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["cantidad"], 2, ",", ".").'</span></td>
                    <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["monto"], 2, ",", ".").'</span></td>
                    <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["subtotal"], 2, ",", ".").'</span></td>
                </tr>';
                if(strlen($row["notas"])>0){
                    $cantitems++;
                    $html=$html.'
                    <tr>
                        <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["notas"].'</span></td>
                        <td width="100" align="right"></td>
                        <td width="100" align="right"></td>
                        <td width="125" align="right"></td>
                    </tr>';
                }

                $totalnotadebito=$totalnotadebito+$row["subtotal"];
            }
            for($i=$cantitems;$i<=18;$i++){
                $html=$html.'<tr>
                    <td width="725" colspan="4">&nbsp;</td>
                </tr>';
            }

    $html=$html.'
            <tr>
                <td width="600" colspan="3" align="right" style="border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>TOTAL '.$divisa.':</strong></span></td>
                <td width="125" align="right" style="border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>'.  number_format($totalnotadebito, 2, ",", ".").'</strong></span></td>
            </tr>
        </table><br />';
    $html=$html.'<table border="0" cellpadding="0" cellspacing="0" width="725">';
    $html=$html.'<tr><td width="50%" valign="top">';
    $html=$html.'<span style="font-size: 9pt; font-family: verdana">'.$observaciones.'</span>';
    $html=$html.'</td><td width="50%" valign="top" align="right">';
    $html=$html.'<span style="font-size: 9pt; font-family: verdana">SOLUCION LOGISTICA GLOBAL SRL</span><br />';
    $html=$html.'<span style="font-size: 9pt; font-family: verdana"><strong>BANCO BISA</strong></span><br />';
    $html=$html.'<span style="font-size: 9pt; font-family: verdana">Cta.- '.$cuenta.' '.$moneda.'</span><br />';
    $html=$html.'<span style="font-size: 9pt; font-family: verdana"><strong>NIT 1018393028</strong></span><br />';
    $html=$html.'</td></tr></table>';

    //$mpdf = new \Mpdf\Mpdf('','Letter',0,'Helvetica',15,15,35,25,9,12,'P');
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right'=> 15,
        'margin_top'=> 32,
        'margin_bottom'=> 25,
        'margin_header'=> 9,
        'margin_footer'=> 12
    ]);
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa."/documentos/notascobranza/notacobranza$nombrearchivo$idnotadebito.pdf");

}

function generarOP($idfacturapago, $conexion, $membretado = false){
    $result = $conexion->query("SELECT
            CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as numero,
            t_tipofacturapago.tipofacturapago,
            t_facturapago.fecha,
            t_transportista.transportista,
            CASE ifnull(t_facturapago.idpagaratipo,0)
                    WHEN 2 THEN t_proveedorpagara.proveedor
                    WHEN 3 THEN t_prestadorpagara.prestador
                    WHEN 4 THEN t_transportistapagara.transportista
                    WHEN 5 THEN t_agentecargapagara.agentecarga
                    ELSE 'Sin Dato'
            END as pagara,
            CASE ifnull(t_facturapago.idpagaratipo,0)
                    WHEN 2 THEN t_proveedorpagaradireccion.direccion
                    WHEN 3 THEN t_prestadorpagaradireccion.direccion
                    WHEN 4 THEN t_transportistapagaradireccion.direccion
                    WHEN 5 THEN t_agentecargapagaradireccion.direccion
                    ELSE 'Sin Dato'
            END as pagaradireccion,
            CASE ifnull(t_facturapago.idpagaratipo,0)
                    WHEN 2 THEN t_proveedorpagaradireccion.ciudad
                    WHEN 3 THEN t_prestadorpagaradireccion.ciudad
                    WHEN 4 THEN t_transportistapagaradireccion.ciudad
                    WHEN 5 THEN t_agentecargapagaradireccion.ciudad
                    ELSE 'Sin Dato'
            END as pagaraciudad,
            CASE ifnull(t_facturapago.idpagaratipo,0)
                    WHEN 2 THEN t_proveedorpagaradireccion.idpais
                    WHEN 3 THEN t_prestadorpagaradireccion.idpais
                    WHEN 4 THEN t_transportistapagaradireccion.idpais
                    WHEN 5 THEN t_agentecargapagaradireccion.idpais
                    ELSE 'Sin Dato'
            END as pagaraidpais,
            t_embarque.embarque,
            CASE ifnull(t_facturapago.idcobraratipo,0)
                    WHEN 1 THEN t_clientecobrara.cliente
                    WHEN 2 THEN t_proveedorcobrara.proveedor
                    WHEN 3 THEN t_prestadorcobrara.prestador
                    WHEN 4 THEN t_transportistacobrara.transportista
                    WHEN 5 THEN t_agentecargacobrara.agentecarga
                    ELSE 'Sin Dato'
            END as cobrara,
            CASE ifnull(t_embarque.idtipoultimoconsignatario,0)
                    WHEN 1 THEN t_clienteconsignatario.cliente
                    WHEN 2 THEN t_proveedorconsignatario.proveedor
                    WHEN 3 THEN t_prestadorconsignatario.prestador
                    WHEN 5 THEN t_agentecargaconsignatario.agentecarga
                    ELSE 'Sin Dato'
            END as consignatario,
            t_origen.ciudad as origen,
            t_destino.ciudad as destino,
            t_embarque.piezas,
            t_embarque.peso,
            t_embarque.nodui, /*Daniel Peralta: Añadi esta linea para optener el nodui para que visualize en la Orden de Pago*/
            t_facturapago.observaciones,
            t_facturapago.tipocambio,
            t_facturapago.idembarque,
            t_facturapago.iddivisa,
            t_facturapago.idtipofacturapago,
            CASE t_facturapago.tipoop
                WHEN 1 THEN 'COSTO'
                WHEN 2 THEN 'CARGO'
            END as tipoop,
            t_embarque.idempresa
            FROM
            t_facturapago
            LEFT JOIN t_tipofacturapago ON t_facturapago.idtipofacturapago=t_tipofacturapago.idtipofacturapago
            LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
            LEFT JOIN t_transportista ON t_facturapago.idtransportista=t_transportista.idtransportista
            LEFT JOIN t_proveedor as t_proveedorpagara ON t_facturapago.idpagara=t_proveedorpagara.idproveedor
            LEFT JOIN t_prestador as t_prestadorpagara ON t_facturapago.idpagara=t_prestadorpagara.idprestador
            LEFT JOIN t_transportista as t_transportistapagara ON t_facturapago.idpagara=t_transportistapagara.idtransportista
            LEFT JOIN t_agentecarga as t_agentecargapagara ON t_facturapago.idpagara=t_agentecargapagara.idagentecarga
            LEFT JOIN t_proveedordireccion as t_proveedorpagaradireccion ON t_facturapago.idpagaradireccion=t_proveedorpagaradireccion.idproveedordireccion
            LEFT JOIN t_prestadordireccion as t_prestadorpagaradireccion ON t_facturapago.idpagaradireccion=t_prestadorpagaradireccion.idprestadordireccion
            LEFT JOIN t_transportistadireccion as t_transportistapagaradireccion ON t_facturapago.idpagaradireccion=t_transportistapagaradireccion.idtransportistadireccion
            LEFT JOIN t_agentecargadireccion as t_agentecargapagaradireccion ON t_facturapago.idpagaradireccion=t_agentecargapagaradireccion.idagentecargadireccion
            LEFT JOIN t_cliente as t_clientecobrara ON t_facturapago.idcobrara=t_clientecobrara.idcliente
            LEFT JOIN t_proveedor as t_proveedorcobrara ON t_facturapago.idcobrara=t_proveedorcobrara.idproveedor
            LEFT JOIN t_prestador as t_prestadorcobrara ON t_facturapago.idcobrara=t_prestadorcobrara.idprestador
            LEFT JOIN t_transportista as t_transportistacobrara ON t_facturapago.idcobrara=t_transportistacobrara.idtransportista
            LEFT JOIN t_agentecarga as t_agentecargacobrara ON t_facturapago.idcobrara=t_agentecargacobrara.idagentecarga
            LEFT JOIN t_cliente as t_clienteconsignatario ON t_embarque.idultimoconsignatario=t_clienteconsignatario.idcliente
            LEFT JOIN t_proveedor as t_proveedorconsignatario ON t_embarque.idultimoconsignatario=t_proveedorconsignatario.idproveedor
            LEFT JOIN t_prestador as t_prestadorconsignatario ON t_embarque.idultimoconsignatario=t_prestadorconsignatario.idprestador
            LEFT JOIN t_agentecarga as t_agentecargaconsignatario ON t_embarque.idultimoconsignatario=t_agentecargaconsignatario.idagentecarga
            LEFT JOIN t_ciudad as t_origen ON t_embarque.idsalida=t_origen.idciudad
            LEFT JOIN t_ciudad as t_destino ON t_embarque.idarribo=t_destino.idciudad
            WHERE t_facturapago.idfacturapago=".$idfacturapago.";");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $numero=$row["numero"];
        $tipofacturapago=$row["tipofacturapago"];
        $transportista=$row["transportista"];
        $pagara=$row["pagara"];
        $pagaradireccion=$row["pagaradireccion"];
        $pagaraciudad=$row["pagaraciudad"];
        $pagaraidpais=$row["pagaraidpais"];
        $fecha=$row["fecha"];
        $cobrara=$row["cobrara"];
        $embarque=$row["embarque"];
        $consignatario=$row["consignatario"];
        $origen=$row["origen"];
        $destino=$row["destino"];
        $peso=$row["peso"];
        $piezas=$row["piezas"];
        $tipocambio=$row["tipocambio"];
        $observaciones=nl2br($row["observaciones"]);
        $idembarque=$row["idembarque"];
        $iddivisa=$row["iddivisa"];
        $nodui=$row["nodui"]; /*Daniel Peralta: es necesario añadir la variable para optener el nodui*/
        $idtipofacturapago=$row["idtipofacturapago"];
        $tipoop=$row['tipoop'];
        $idempresa=$row['idempresa'];
    }


    $html='<br />
    <table border="0" cellpadding="0" cellspacing="0" width="725">
        <tr>
            <td align="left">
                <span style="font-size: 20pt; font-family: verdana">'.$pagara.'</span><br /><span style="font-size: 10pt; font-family: verdana">'.$pagaradireccion.'</span><br /><span style="font-size: 10pt; font-family: verdana">'.$pagaraciudad.', '.$pagaraidpais.'</span>
            </td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="725">
        <tr>
            <td align="right"><span style="font-size: 15pt; font-family: verdana">'.  strtoupper($tipofacturapago).'</span></td>
        </tr>
    </table>
    <table border="0" cellpadding="3" cellspacing="2" width="725">
        <tr>
            <td width="355">&nbsp;</td>
            <td width="150" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana">Fecha</span></td>
            <td width="220" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana">Numero</span></td>
        </tr>
        <tr>
            <td width="355">&nbsp;</td>
            <td width="150" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px"><span style="font-size: 9pt; font-family: verdana">'.fechaliteral($fecha).'</span></td>
            <td width="220" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px"><span style="font-size: 9pt; font-family: verdana">'.$numero.'</span></td>
        </tr>
        <tr>
            <td width="355" height="17" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana">FACTURAR A</span></td>
            <td width="150" rowspan="2" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                <span style="font-size: 9pt; font-family: verdana">No de Documento:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Consignatario:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Origen:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Destino:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Transportista:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Piezas:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Peso:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Cobrar a:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Tipo de Cambio:</span><br />
                <span style="font-size: 9pt; font-family: verdana">No DUI:</span><br />
                <span style="font-size: 9pt; font-family: verdana">Tipo:</span>
            </td>
            <td width="220" rowspan="2" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                <span style="font-size: 9pt; font-family: verdana">'.$embarque.'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.substr($consignatario, 0,27).'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.substr($origen, 0,27).'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.substr($destino, 0,27).'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.substr($transportista, 0,27).'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.substr($piezas, 0,27).'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.substr($peso, 0,27).'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.substr($cobrara, 0,27).'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.number_format($tipocambio, 2, ",", ".").'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.$nodui.'&nbsp;</span><br />
                <span style="font-size: 9pt; font-family: verdana">'.$tipoop.'&nbsp;</span>
            </td>
        </tr>
        <tr>
            <td width="355" valign="top" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                <span style="font-size: 9pt; font-family: verdana">
                Solucion Logistica Global - SLG<br />
                BARRIO SIRARI CALLE NOGALES No 100<br />
                ZONA EQUIPETROL<br />
                SANTA CRUZ, BOLIVIA<br />
                Tel: 591 3 3129126, Fax: 591 3 3129126
                </span>
            </td>
        </tr>
    </table>
    <br />
    <table border="1" cellpadding="2" cellspacing="2" width="725">
        <tr>
            <td width="400" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>DETALLE</strong></span></td>
            <td width="100" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>CANTIDAD</strong></span></td>
            <td width="100" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>PRECIO/U</strong></span></td>
            <td width="125" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>SUBTOTAL</strong></span></td>
        </tr>
    </table>
    <table border="0" cellpadding="2" cellspacing="2" width="725">';

    $cantitems=0;
    $totalfacturapago=0;
    $result = $conexion->query("SELECT
                        t_concepto.concepto,
                        t_costo.cantidad,
                        t_costo.notas,
                        t_costo.monto*t_tipocambio.tipocambio as monto,
                        t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio as subtotal,
                        t_divisa.codigo as divisa,
                        t_costo.factura,
                        t_costo.nota_entrega
                        FROM
                        t_costo
                        LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
                        LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
                        LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
                        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND t_facturapago.iddivisa=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
                        LEFT JOIN t_divisa ON t_facturapago.iddivisa=t_divisa.iddivisa
                        WHERE IFNULL(t_costo.idtipofacturanotadebito,0)=1
                        AND t_facturapago.idfacturapago=".$idfacturapago.";") or die("SQL Error 1: " . mysql_error());
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cantitems++;
        $divisa=$row["divisa"];
        $html=$html.'
        <tr>
            <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["concepto"].'</span></td>
            <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["cantidad"], 2, ",", ".").'</span></td>
            <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["monto"], 2, ",", ".").'</span></td>
            <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["subtotal"], 2, ",", ".").'</span></td>
        </tr>';
        
        if(strlen($row["factura"])>0){
            $cantitems++;
            $html=$html.'
            <tr>
                <td width="400"><span style="font-size: 9pt; font-family: verdana">FACT '.$row["factura"].'</span></td>
                <td width="100" align="right"></td>
                <td width="100" align="right"></td>
                <td width="125" align="right"></td>
            </tr>';
        }
        
        if(strlen($row["nota_entrega"])>0){
            $cantitems++;
            $html=$html.'
            <tr>
                <td width="400"><span style="font-size: 9pt; font-family: verdana">NE '.$row["nota_entrega"].'</span></td>
                <td width="100" align="right"></td>
                <td width="100" align="right"></td>
                <td width="125" align="right"></td>
            </tr>';
        }
        
        
        if(strlen($row["notas"])>0){
            $cantitems++;
            $html=$html.'
            <tr>
                <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["notas"].'</span></td>
                <td width="100" align="right"></td>
                <td width="100" align="right"></td>
                <td width="125" align="right"></td>
            </tr>';
        }

        $totalfacturapago=$totalfacturapago+$row["subtotal"];
    }
    for($i=$cantitems;$i<=16;$i++){
        $html=$html.'<tr>
            <td width="725" colspan="4">&nbsp;</td>
        </tr>';
    }


    if((int)$idtipofacturapago==2){
        $html=$html.'
            <tr>
                <td width="600" colspan="3" align="right" style="border-color: black; border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>SUBTOTAL '.$divisa.':</strong></span></td>
                <td width="125" align="right" style="border-color: black; border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>'.  number_format($totalfacturapago, 2, ",", ".").'</strong></span></td>
            </tr>';
        $montoi=0;
        $result = $conexion->query("select
                                CONCAT(t_ordenservicioi.numero,'/',t_ordenservicioi.gestion) as numeroi,
                                SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as monto
                                FROM
                                t_ordenservicioi
                                LEFT JOIN t_cargo ON t_ordenservicioi.idordenservicioi=t_cargo.idordenservicioi
                                LEFT JOIN t_embarque ON t_ordenservicioi.idembarque=t_embarque.idembarque
                                LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND ".$iddivisa."=t_tipocambio.iddivisadestino AND t_ordenservicioi.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_ordenservicioi.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
                                WHERE
                                t_ordenservicioi.idembarque=".$idembarque."
                                AND t_ordenservicioi.idestado=1
                                GROUP BY
                                t_ordenservicioi.numero,
                                t_ordenservicioi.gestion;") or die("SQL Error 1: " . mysql_error());
            while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
                $html=$html.'
                    <tr>
                        <td width="600" colspan="3" align="right" style="border-color: black; border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>ORDEN DE INGRESO '.$row["numeroi"].' '.$divisa.':</strong></span></td>
                        <td width="125" align="right" style="border-color: black; border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>'.  number_format($row["monto"], 2, ",", ".").'</strong></span></td>
                    </tr>';
                $montoi=$montoi+$row["monto"];
            }
            $html=$html.'
                <tr>
                    <td width="600" colspan="3" align="right" style="border-color: black; border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>TOTAL PAGO AGENTE '.$divisa.':</strong></span></td>
                    <td width="125" align="right" style="border-color: black; border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>'.  number_format($totalfacturapago-$montoi, 2, ",", ".").'</strong></span></td>
                </tr>';
    }else{
        $html=$html.'
            <tr>
                <td width="600" colspan="3" align="right" style="border-color: black; border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>TOTAL '.$divisa.':</strong></span></td>
                <td width="125" align="right" style="border-color: black; border-style: solid; border-width: 1px"><span style="font-size: 9pt; font-family: verdana"><strong>'.  number_format($totalfacturapago, 2, ",", ".").'</strong></span></td>
            </tr>';
    }
    $html=$html.'</table><br /><span style="font-size: 9pt; font-family: verdana">'.$observaciones.'</span>';
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);
    
    
    
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa."/documentos/ordenespago/ordenpago$idfacturapago.pdf");
}

function generarPlanilla($idplanilla, $conexion, $membretado = false){
    $result = $conexion->query("SELECT
        t_planilla.numero,
        DATE_FORMAT(t_planilla.fecha,'%M %D, %Y') as fechaliteral,
        t_agentecarga.agentecarga,
        t_embarque.numeroguia,
        t_embarque.noidentificacion,
        t_embarque.nodui,
        CASE ifnull(t_embarque.idtipoexpedidor,0)
                        WHEN 1 THEN t_clienteexpedidor.cliente
                        WHEN 2 THEN t_proveedorexpedidor.proveedor
                        WHEN 3 THEN t_prestadorexpedidor.prestador
                        WHEN 4 THEN t_transportistaexpedidor.transportista
                        WHEN 5 THEN t_agentecargaexpedidor.agentecarga
                        ELSE 'Sin Dato'
        END as expedidor,
        t_planilla.pacenainvoice,
        t_planilla.slginvoice,
        t_planilla.alloginvoice,
        t_planilla.textoadicional,
        t_embarque.idempresa
        FROM
        t_planilla
        LEFT JOIN t_embarque ON t_planilla.idembarque=t_embarque.idembarque
        LEFT JOIN t_agentecarga ON t_embarque.idagentecarga=t_agentecarga.idagentecarga
        LEFT JOIN t_cliente as t_clienteexpedidor ON t_embarque.idexpedidor=t_clienteexpedidor.idcliente
        LEFT JOIN t_proveedor as t_proveedorexpedidor ON t_embarque.idexpedidor=t_proveedorexpedidor.idproveedor
        LEFT JOIN t_prestador as t_prestadorexpedidor ON t_embarque.idexpedidor=t_prestadorexpedidor.idprestador
        LEFT JOIN t_transportista as t_transportistaexpedidor ON t_embarque.idexpedidor=t_transportistaexpedidor.idtransportista
        LEFT JOIN t_agentecarga as t_agentecargaexpedidor ON t_embarque.idexpedidor=t_agentecargaexpedidor.idagentecarga
        WHERE
        t_planilla.idplanilla=".$idplanilla.";");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $numero=$row["numero"];
        $fechaliteral=$row["fechaliteral"];
        $agentecarga=$row["agentecarga"];
        $numeroguia=$row["numeroguia"];
        $noidentificacion=$row["noidentificacion"];
        $nodui=$row["nodui"];
        $expedidor=$row["expedidor"];
        $pacenainvoice=$row["pacenainvoice"];
        $slginvoice=$row["slginvoice"];
        $alloginvoice=$row["alloginvoice"];
        $textoadicional=$row["textoadicional"];
        $idempresa=$row['idempresa'];
    }


    $html='<br /><br /><br /><br />
    <table border="0" cellpadding="0" cellspacing="0" width="725">
        <tr>
            <td align="center"><span style="font-size: 15pt; font-family: verdana">CUSTOMS BROKERAGE SERVICES / IOR-EOR No. '.$numero.'</span></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><span style="font-size: 10pt; font-family: verdana">'.$fechaliteral.'</span></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><span style="font-size: 10pt; font-family: verdana"><strong>Company: '.$agentecarga.'</strong></span></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><span style="font-size: 10pt; font-family: verdana"><strong>Detail of services provided:</strong></span></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="725">
        <tr>
            <td width="33%" align="center"><span style="font-size: 10pt; font-family: verdana"><strong>'.$numeroguia.'</strong></span></td>
            <td width="33%" align="center"><span style="font-size: 10pt; font-family: verdana"><strong>'.$noidentificacion.'</strong></span></td>
            <td width="33%" align="center"><span style="font-size: 10pt; font-family: verdana"><strong>DUI/DUE: '.$nodui.'</strong></span></td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="725">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td><span style="font-size: 10pt; font-family: verdana"><strong>Supplier: '.$expedidor.'</strong></span></td>
        </tr>
    </table>
    <br />
    <br />
    <br />
    <span style="font-size: 10pt; font-family: verdana"><strong>Detail of Charges:</strong></span>
    <table border="0" cellpadding="0" cellspacing="0" width="725">
    ';
    $valortotal=0;
    $result = $conexion->query("SELECT
        t_cargo.idtipoplanilla,
        t_tipoplanilla.tipoplanilla,
        SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as monto
        FROM
        t_cargo
        LEFT JOIN t_planilla ON t_cargo.idplanilla=t_planilla.idplanilla
        LEFT JOIN t_tipoplanilla ON t_cargo.idtipoplanilla=t_tipoplanilla.idtipoplanilla
        LEFT JOIN t_embarque ON t_planilla.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_planilla.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_planilla.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE
        t_cargo.idplanilla=".$idplanilla."
        GROUP BY
        t_cargo.idtipoplanilla,
        t_tipoplanilla.tipoplanilla
        ORDER BY
        t_tipoplanilla.orden;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $html=$html.'<tr>'
                . '<td width="300" align="right"><span style="font-size: 10pt; font-family: verdana">'.$row["tipoplanilla"].'</span></td>'
                . '<td width="100" align="center"><span style="font-size: 10pt; font-family: verdana">USD</span></td>'
                . '<td width="100" align="right"><span style="font-size: 10pt; font-family: verdana">'.number_format($row["monto"], 0, ".", ",").'</span></td>'
                . '<td width="225" align="right">&nbsp;</td>'
                . '</tr>';
        if((int)$row["idtipoplanilla"]==2){
            $html=$html.'<tr>'
                . '<td width="300" align="right"><span style="font-size: 10pt; font-family: verdana">Paceña Invoice No. '.$pacenainvoice.'</span></td>'
                . '<td width="425" colspan="3" align="right">&nbsp;</td>'
                . '</tr>';
            $html=$html.'<tr>'
                . '<td width="300" align="right"><span style="font-size: 10pt; font-family: verdana">SLG Invoice No. '.$slginvoice.'</span></td>'
                . '<td width="425" colspan="3" align="right">&nbsp;</td>'
                . '</tr>';
        }
        if((int)$row["idtipoplanilla"]==3 || (int)$row["idtipoplanilla"]==4){
            $html=$html.'<tr>'
                . '<td width="300" align="right"><span style="font-size: 10pt; font-family: verdana">Allog Invoice No. '.$alloginvoice.'</span></td>'
                . '<td width="425" colspan="3" align="right">&nbsp;</td>'
                . '</tr>';
        }
        $valortotal=$valortotal+$row["monto"];

    }
    $html=$html.'<tr>'
                . '<td width="425" colspan="4" align="right">&nbsp;</td>'
                . '</tr>';
    $html=$html."</table>";
    $html=$html.'<table border="1" cellpadding="0" cellspacing="0" width="500">'
            . '<tr>'
            . '<td width="300" align="right"><span style="font-size: 10pt; font-family: verdana"><strong>TOTAL CHARGES</strong></span></td>'
                . '<td width="100" align="center"><span style="font-size: 10pt; font-family: verdana"><strong>USD</strong></span></td>'
                . '<td width="100" align="right"><span style="font-size: 10pt; font-family: verdana"><strong>'.number_format($valortotal, 0, ".", ",").'</strong></span></td>'
                . '</tr>';
    $html=$html."</table>";
    $html=$html.'<br /><span style="font-size: 10pt; font-family: verdana"><strong>TOTAL AMOUNT: '.strtoupper(convertNumberToWord(number_format($valortotal, 0, ".", ""))).' 00/100 US DOLLARS</strong></span>';
    $html=$html."<br /><br />";
    $html=$html.'<span style="font-size: 10pt; font-family: verdana"><strong>Additional Information</strong></span><br />';
    $html=$html.'<span style="font-size: 10pt; font-family: verdana">'.$textoadicional.'</span><br />';
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa."/documentos/planillas/planilla$idplanilla.pdf");
}

function generarInvoice($idinvoice, $conexion, $membretado = false){
    $result = $conexion->query("SELECT
        DATE_FORMAT(t_invoice.fecha,'%M %D, %Y') as fechaliteral,
        t_agentecarga.agentecarga,
        t_agentecargadireccion.direccion,
        t_invoice.numero,
        t_invoice.gestion,
        t_embarque.embarque,
        t_origen.ciudad as origen,
        t_destino.ciudad as destino,
        t_embarque.peso,
        t_embarque.volumen,
        t_embarque.piezas,
        t_embarque.numeroguia,
        t_embarque.noidentificacion,
        t_tipoembarque.tipoembarque_en,
        t_embarque.idtipoembarque,
        t_embarque.idempresa
        FROM
        t_invoice
        LEFT JOIN t_agentecarga ON t_invoice.idagentecarga=t_agentecarga.idagentecarga
        LEFT JOIN t_agentecargadireccion ON t_invoice.idagentecargadireccion=t_agentecargadireccion.idagentecargadireccion
        LEFT JOIN t_embarque ON t_invoice.idembarque=t_embarque.idembarque
        LEFT JOIN t_ciudad as t_origen ON t_embarque.idsalida=t_origen.idciudad
        LEFT JOIN t_ciudad as t_destino ON t_embarque.idarribo=t_destino.idciudad
        LEFT JOIN t_tipoembarque ON t_embarque.idtipoembarque=t_tipoembarque.idtipoembarque
        WHERE
        t_invoice.idinvoice=".$idinvoice.";");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $numero=$row["numero"];
        $gestion=$row["gestion"];
        $agentecarga=$row["agentecarga"];
        $direccion=$row["direccion"];
        $embarque=$row["embarque"];
        $origen=$row["origen"];
        $destino=$row["destino"];
        $peso=$row["peso"];
        $volumen=$row["volumen"];
        $piezas=$row["piezas"];
        $numeroguia=$row["numeroguia"];
        $noidentificacion=$row["noidentificacion"];
        $fechaliteral=$row["fechaliteral"];
        $tipoembarque=$row["tipoembarque_en"];
        $idtipoembarque=$row["idtipoembarque"];
        $idempresa=$row['idempresa'];
    }
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);

    if((int)$idtipoembarque==3){
        $texto1="MAWB";
        $texto2="HAWB";
    }else{
        $texto1="MBL";
        $texto2="HBL";
    }

    $html='';
    $nombrearchivo='';
    if($membretado){
        $nombrearchivo="membretada";
        $html=$html.'<style>
            @page {
            background: url("'.folder_files.$idempresa.'/documentos/notascobranza/membretadalogistica.png");
            background-repeat: no-repeat;
            background-position: left top;
            background-image-resize:2;
            }
            </style>';
    }

    $html=$html.'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <head>
        <meta equiv="Content-Type" content="text/html; charset=utf-8" />

        </head>
        <body>
        <br /><br /><br /><br /><br />
        <table border="0" cellpadding="0" cellspacing="0" width="725">
            <tr>
                <td align="right" width="600"><span style="font-size: 9pt; font-family: verdana">INVOICE:</span></td>
                <td align="right" width="125"><span style="font-size: 9pt; font-family: verdana">'.$numero.'/'.$gestion.'</span></td>
            </tr>
        </table>
        <br />
        <br />
        <br />
        <table border="0" cellpadding="0" cellspacing="6" width="725">
            <tr>
                <td width="450" valign="top">
                    <table border="1" id="main" cellpadding="3" cellspacing="3" width="450">
                        <tr>
                            <td width="100%" colspan="2"><span style="font-size: 9pt; font-family: verdana">TO:</span></td>
                        </tr>
                        <tr>
                            <td width="100%" colspan="2" align="center"><span style="font-size: 9pt; font-family: verdana">'.$agentecarga.'</span></td>
                        </tr>
                        <tr>
                            <td width="100%" colspan="2" align="center"><span style="font-size: 9pt; font-family: verdana">'.$direccion.'</span></td>
                        </tr>
                        <tr>
                            <td width="50%"><span style="font-size: 9pt; font-family: verdana">DATE</span></td>
                            <td width="50%" align="right"><span style="font-size: 9pt; font-family: verdana">'.$fechaliteral.'</span></td>
                        </tr>
                        <tr>
                            <td width="50%"><span style="font-size: 9pt; font-family: verdana">TRANSPORT</span></td>
                            <td width="50%" align="right"><span style="font-size: 9pt; font-family: verdana">'.$tipoembarque.'</span></td>
                        </tr>
                    </table>
                </td>
                <td width="275">
                    <span style="font-size: 9pt; font-family: verdana">Service No: '.$embarque.'</span><br />
                    <span style="font-size: 9pt; font-family: verdana">From: '.$origen.'</span><br />
                    <span style="font-size: 9pt; font-family: verdana">To: '.$destino.'</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Weight: '.$peso.'</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Volume: '.$volumen.'</span><br />
                    <span style="font-size: 9pt; font-family: verdana">Pieces: '.$piezas.'</span><br />
                </td>
            </tr>
        </table>
        <br />
        <table border="1" cellpadding="2" cellspacing="2" width="725">
            <tr>
                <td width="400"><span style="font-size: 9pt; font-family: verdana"><strong>DESCRIPTION</strong></span></td>
                <td width="100"><span style="font-size: 9pt; font-family: verdana"><strong>QUANTITY</strong></span></td>
                <td width="100"><span style="font-size: 9pt; font-family: verdana"><strong>U/PRICE</strong></span></td>
                <td width="125"><span style="font-size: 9pt; font-family: verdana"><strong>SUBTOTAL</strong></span></td>
            </tr>
            <tr>
                <td width="725" colspan="4">
                    <table border="0" cellpadding="2" cellspacing="2" width="725">';
    $valortotal=0;
    $cantitems=0;
    $totalinvoice=0;
    $result = $conexion->query("SELECT
        t_concepto.concepto_en,
        t_cargo.cantidad,
        t_cargo.notas,
        t_cargo.monto*t_tipocambio.tipocambio as monto,
        t_cargo.cantidad*t_cargo.monto*t_tipocambio.tipocambio as subtotal
        FROM
        t_cargo
        LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_invoice ON t_cargo.idinvoice=t_invoice.idinvoice
        LEFT JOIN t_embarque ON t_invoice.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND t_invoice.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_invoice.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE
        t_cargo.idinvoice=".$idinvoice.";");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cantitems++;
        $html=$html.'
        <tr>
            <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["concepto_en"].'</span></td>
            <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["cantidad"], 2, ",", ".").'</span></td>
            <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["monto"], 2, ",", ".").'</span></td>
            <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["subtotal"], 2, ",", ".").'</span></td>
        </tr>';
        $html=$html.'
        <tr>
            <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["notas"].'</span></td>
            <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana"></span></td>
            <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana"></span></td>
            <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana"></span></td>
        </tr>';

        $totalinvoice=$totalinvoice+$row["subtotal"];
    }
    $numfilas=8;
    for($i=$cantitems;$i<=$numfilas;$i++){
        $html=$html.'<tr>
            <td width="725" colspan="4">&nbsp;</td>
        </tr>';
    }

    $html=$html.'
                </table>
            </td>
        </tr>
        <tr>
            <td width="600" colspan="3" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>TOTAL CHARGES USD:</strong></span></td>
            <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>'.  number_format($totalinvoice, 2, ",", ".").'</strong></span></td>
        </tr>
    </table>';

    $html=$html.'<br /><span style="font-size: 10pt; font-family: verdana"><strong>TOTAL AMOUNT: '.strtoupper(convertNumberToWord($totalinvoice)).' 00/100 US DOLLARS</strong></span>';

    $html=$html.'<br /><br /><table border="0" cellpadding="0" cellspacing="0" width="725">
        <tr>
            <td align="left" width="525"><span style="font-size: 9pt; font-family: verdana">
                BANCO BISA<br />
                LA PAZ - BOLIVIA<br />
                CTA CTE. 0805622016<br />
                CODIGO SWIFT: BANIBOLXXXX<br />
                CLIENTE: SOLUCION LOGISTICA GLOBAL SRL<br />
                DIR: LOAYZA Nº 255 EDIFICIO DE UGARTE INGENIERIA PISO Nº 10 OF 1003-1005
            </span></td>
            <td align="left" width="200" valign="top"><span style="font-size: 9pt; font-family: verdana">
                '.$texto1.': '.$noidentificacion.'<br />
                '.$texto2.': '.$numeroguia.'
            </span></td>
        </tr>
    </table>';

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right'=> 15,
        'margin_top'=> 32,
        'margin_bottom'=> 25,
        'margin_header'=> 9,
        'margin_footer'=> 12
    ]);
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa."/documentos/invoices/invoice$nombrearchivo$idinvoice.pdf");
}

function generarOrdenServicio($idordenservicio, $tipo, $conexion, $membretado = false) {

    $codigo = "ING";
    $tabla = "t_cargo";
    $idOrdenCampo = "";
    $tablaOrdenServicio = "";
    $credit = "CREDIT";

    /*
    |--------------------------------------------------------------------------
    | Validar tipo
    |--------------------------------------------------------------------------
    | Solo se permite:
    | i = ingreso
    | e = egreso
    |--------------------------------------------------------------------------
    */
    $tipo = strtolower(trim($tipo));

    if (!in_array($tipo, ['i', 'e'])) {
        return false;
    }

    if ((int)$idordenservicio <= 0) {
        return false;
    }

    if ($tipo === "e") {
        $codigo = "EGR";
        $tabla = "t_costo";
        $credit = "DEBIT";
    }

    $tablaOrdenServicio = "t_ordenservicio" . $tipo;
    $idOrdenCampo = "idordenservicio" . $tipo;

    /*
    |--------------------------------------------------------------------------
    | Variables por defecto
    |--------------------------------------------------------------------------
    */
    $numero = '';
    $fecha = '';
    $agentecarga = '';
    $embarque = '';
    $divisaordenservicio = '';
    $tipocambio = 1;
    $creditnot = '';
    $noidentificacion = '';
    $numeroguia = '';
    $nombre = '';
    $idempresa = 0;

    /*
    |--------------------------------------------------------------------------
    | Obtener cabecera de la orden de servicio
    |--------------------------------------------------------------------------
    */
    $queryCabecera = "
        SELECT
            CONCAT(os.numero, '/', os.gestion) AS numero,
            DATE_FORMAT(os.fecha, '%d/%m/%y') AS fecha,
            IFNULL(ac.agentecarga, '') AS agentecarga,
            IFNULL(e.embarque, '') AS embarque,
            IFNULL(dos.divisaordenservicio, '') AS divisaordenservicio,
            IFNULL(os.tipocambio, 1) AS tipocambio,
            IFNULL(os.creditnot, '') AS creditnot,
            IFNULL(e.noidentificacion, '') AS noidentificacion,
            IFNULL(e.numeroguia, '') AS numeroguia,
            IFNULL(u.nombre, '') AS nombre,
            IFNULL(e.idempresa, 0) AS idempresa
        FROM $tablaOrdenServicio os
        LEFT JOIN t_agentecarga ac 
            ON os.idsolicitadopor = ac.idagentecarga
        LEFT JOIN t_embarque e 
            ON os.idembarque = e.idembarque
        LEFT JOIN t_divisaordenservicio dos 
            ON os.iddivisaordenservicio = dos.iddivisaordenservicio
        LEFT JOIN t_usuario u 
            ON os.idusuario = u.idusuario
        WHERE os.$idOrdenCampo = :idordenservicio
        LIMIT 1
    ";

    $stmtCabecera = $conexion->prepare($queryCabecera);

    $stmtCabecera->execute([
        ':idordenservicio' => $idordenservicio
    ]);

    $rowCabecera = $stmtCabecera->fetch(PDO::FETCH_ASSOC);

    if (!$rowCabecera) {
        return false;
    }

    $numero = $rowCabecera["numero"];
    $fecha = $rowCabecera["fecha"];
    $agentecarga = $rowCabecera["agentecarga"];
    $embarque = $rowCabecera["embarque"];
    $divisaordenservicio = $rowCabecera["divisaordenservicio"];
    $tipocambio = (float)$rowCabecera["tipocambio"];
    $creditnot = $rowCabecera["creditnot"];
    $noidentificacion = $rowCabecera["noidentificacion"];
    $numeroguia = $rowCabecera["numeroguia"];
    $nombre = $rowCabecera["nombre"];
    $idempresa = (int)$rowCabecera["idempresa"];

    if ($tipocambio <= 0) {
        $tipocambio = 1;
    }

    if ($idempresa <= 0) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Crear carpeta
    |--------------------------------------------------------------------------
    */
    $creacion = new Carpetas();
    $respuesta = $creacion->procesarCarpeta($idempresa);

    /*
    |--------------------------------------------------------------------------
    | HTML cabecera
    |--------------------------------------------------------------------------
    */
    $html = '<br />
        <table border="0" cellpadding="0" cellspacing="0" width="725">
            <tr>
                <td align="center">
                    <span style="font-size: 17pt; font-family: verdana">ORDEN DE SERVICIO ' . htmlspecialchars($codigo) . '</span>
                </td>
            </tr>
        </table>
        <br />
        <br />
        <table border="0" cellpadding="0" cellspacing="0" width="725">
            <tr>
                <td width="150" align="right"><span style="font-size: 10pt; font-family: verdana">Fecha:</span></td>
                <td width="100" align="center" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                    <span style="font-size: 12pt; font-family: verdana">' . htmlspecialchars($fecha) . '</span>
                </td>
                <td width="100" align="center"><span style="font-size: 9pt; font-family: verdana">(dd/mm/aa)</span></td>
                <td width="150" align="right"><span style="font-size: 10pt; font-family: verdana">No.</span></td>
                <td width="150" align="center" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                    <span style="font-size: 12pt; font-family: verdana">' . htmlspecialchars($numero) . '</span>
                </td>
                <td width="75" align="right">&nbsp;</td>
            </tr>
        </table>
        <br />
        <table border="0" cellpadding="3" cellspacing="5" width="725">
            <tr>
                <td width="150"><span style="font-size: 10pt; font-family: verdana">Solicitado por:</span></td>
                <td width="575" align="center" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                    <span style="font-size: 13pt; font-family: verdana">' . htmlspecialchars($agentecarga) . '</span>
                </td>
            </tr>
            <tr>
                <td width="150"><span style="font-size: 10pt; font-family: verdana">Tipo de Servicio:</span></td>
                <td width="575" align="center" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px">
                    <span style="font-size: 13pt; font-family: verdana">' . htmlspecialchars($embarque) . '</span>
                </td>
            </tr>
        </table>
        <br /><br />
        <table border="1" cellpadding="2" cellspacing="2" width="725">
            <tr>
                <td width="575" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>DETALLE</strong></span></td>
                <td width="150" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>VALOR (' . htmlspecialchars($divisaordenservicio) . ')</strong></span></td>
            </tr>
        </table>
        <table border="0" cellpadding="2" cellspacing="2" width="725">';

    /*
    |--------------------------------------------------------------------------
    | Obtener detalle
    |--------------------------------------------------------------------------
    */
    $cantitems = 0;
    $totalordenservicio = 0;

    $queryDetalle = "
        SELECT
            IFNULL(c.concepto, '') AS concepto,
            IFNULL(det.cantidad, 0) * IFNULL(det.monto, 0) * IFNULL(tc.tipocambio, 1) AS subtotal
        FROM $tabla det
        LEFT JOIN t_concepto c 
            ON det.idconcepto = c.idconcepto
        LEFT JOIN $tablaOrdenServicio os 
            ON det.$idOrdenCampo = os.$idOrdenCampo
        LEFT JOIN t_embarque e 
            ON os.idembarque = e.idembarque
        LEFT JOIN t_tipocambio tc 
            ON det.iddivisa = tc.iddivisaorigen
           AND 2 = tc.iddivisadestino
           AND os.fecha BETWEEN tc.fechainicio AND IFNULL(tc.fechafin, os.fecha)
           AND tc.idempresa = e.idempresa
        WHERE det.$idOrdenCampo = :idordenservicio
    ";

    $stmtDetalle = $conexion->prepare($queryDetalle);

    $stmtDetalle->execute([
        ':idordenservicio' => $idordenservicio
    ]);

    while ($row = $stmtDetalle->fetch(PDO::FETCH_ASSOC)) {
        $cantitems++;

        $subtotal = (float)$row["subtotal"];
        $totalordenservicio += $subtotal;

        $html .= '
            <tr>
                <td width="575"><span style="font-size: 9pt; font-family: verdana">' . htmlspecialchars($row["concepto"]) . '</span></td>
                <td width="150" align="right"><span style="font-size: 9pt; font-family: verdana">' . number_format($subtotal / $tipocambio, 2, ",", ".") . '</span></td>
            </tr>';
    }

    for ($i = $cantitems; $i <= 10; $i++) {
        $html .= '
            <tr>
                <td width="725" colspan="2">&nbsp;</td>
            </tr>';
    }

    $html .= '
            <tr>
                <td width="575" align="right" style="border-color: black; border-style: solid; border-width: 1px">
                    <span style="font-size: 9pt; font-family: verdana"><strong>Subtotal</strong></span>
                </td>
                <td width="150" align="right" style="border-color: black; border-style: solid; border-width: 1px">
                    <span style="font-size: 9pt; font-family: verdana"><strong>' . number_format($totalordenservicio / $tipocambio, 2, ",", ".") . '</strong></span>
                </td>
            </tr>
            <tr>
                <td width="575" align="right" style="border-color: black; border-style: solid; border-width: 1px">
                    <span style="font-size: 9pt; font-family: verdana"><strong>T/C</strong></span>
                </td>
                <td width="150" align="right" style="border-color: black; border-style: solid; border-width: 1px">
                    <span style="font-size: 9pt; font-family: verdana"><strong>' . number_format($tipocambio, 2, ",", ".") . '</strong></span>
                </td>
            </tr>
            <tr>
                <td width="575" align="right" style="border-color: black; border-style: solid; border-width: 1px">
                    <span style="font-size: 9pt; font-family: verdana"><strong>TOTAL USD</strong></span>
                </td>
                <td width="150" align="right" style="border-color: black; border-style: solid; border-width: 1px">
                    <span style="font-size: 9pt; font-family: verdana"><strong>' . number_format($totalordenservicio, 2, ",", ".") . '</strong></span>
                </td>
            </tr>
        </table>
        <br />
        <br />
        <table border="0" cellpadding="2" cellspacing="2">
            <tr>
                <td width="20">&nbsp;</td>
                <td width="150"><span style="font-size: 9pt; font-family: verdana">' . htmlspecialchars($credit) . ' NOT N°:</span></td>
                <td width="250"><span style="font-size: 9pt; font-family: verdana">' . htmlspecialchars($creditnot) . '</span></td>
            </tr>
            <tr>
                <td width="20">&nbsp;</td>
                <td width="150"><span style="font-size: 9pt; font-family: verdana">MAWB/MBL:</span></td>
                <td width="250"><span style="font-size: 9pt; font-family: verdana">' . htmlspecialchars($noidentificacion) . '</span></td>
            </tr>
            <tr>
                <td width="20">&nbsp;</td>
                <td width="150"><span style="font-size: 9pt; font-family: verdana">HAWB/HBL:</span></td>
                <td width="250"><span style="font-size: 9pt; font-family: verdana">' . htmlspecialchars($numeroguia) . '</span></td>
            </tr>
        </table>';

    /*
    |--------------------------------------------------------------------------
    | Pie de página
    |--------------------------------------------------------------------------
    */
    $piedepagina = '<table border="0" width="200" cellpadding="0" cellspacing="0">';
    $piedepagina .= '<tr>';
    $piedepagina .= '<td align="center" style="border-top-style: solid; border-top-width: 2px; border-top-color: black"><font style="font-family: Helvetica; font-size: 9pt">' . htmlspecialchars($nombre) . '</font></td>';
    $piedepagina .= '</tr>';
    $piedepagina .= '</table>';

    /*
    |--------------------------------------------------------------------------
    | Generar PDF
    |--------------------------------------------------------------------------
    */
    $rutaDirectorio = folder_files . $idempresa . "/documentos/ordenesservicio/" . $tipo;
    $rutaArchivo = $rutaDirectorio . "/ordenservicio" . $idordenservicio . ".pdf";

    if (!is_dir($rutaDirectorio)) {
        mkdir($rutaDirectorio, 0775, true);
    }

    $mpdf = new \Mpdf\Mpdf();

    $mpdf->WriteHTML($html);
    $mpdf->SetHTMLFooter($piedepagina);
    $mpdf->Output($rutaArchivo);

    return true;
}

function generarAnticipo($idanticipo, $conexion, $membretado = false){

    $result = $conexion->query("SELECT
        t_anticipo.idanticipo,
        t_anticipo.fecha,
        CASE t_anticipo.idtipoentidad
            WHEN 1 THEN t_cliente.cliente
            WHEN 2 THEN t_proveedor.proveedor
            WHEN 3 THEN t_prestador.prestador
            WHEN 4 THEN t_transportista.transportista
            WHEN 5 THEn t_agentecarga.agentecarga
        END as cliente,
        CASE t_anticipo.idtipoentidad
            WHEN 1 THEN t_cliente.idempresa
            WHEN 2 THEN t_proveedor.idempresa
            WHEN 3 THEN t_prestador.idempresa
            WHEN 4 THEN t_transportista.idempresa
            WHEN 5 THEn t_agentecarga.idempresa
        END as idempresa,
        t_anticipo.recibo,
        t_cuenta.banco,
        t_cuenta.cuenta,
        t_anticipo.glosa,
        t_anticipo.monto,
        t_usuario.nombre
        FROM
        t_anticipo
        LEFT JOIN t_cliente ON t_anticipo.identidad=t_cliente.idcliente AND t_anticipo.idtipoentidad=1
        LEFT JOIN t_proveedor ON t_anticipo.identidad=t_proveedor.idproveedor AND t_anticipo.idtipoentidad=2
        LEFT JOIN t_prestador ON t_anticipo.identidad=t_prestador.idprestador AND t_anticipo.idtipoentidad=3
        LEFT JOIN t_transportista ON t_anticipo.identidad=t_transportista.idtransportista AND t_anticipo.idtipoentidad=4
        LEFT JOIN t_agentecarga ON t_anticipo.identidad=t_agentecarga.idagentecarga AND t_anticipo.idtipoentidad=5
        LEFT JOIN t_cuenta ON t_anticipo.idcuenta=t_cuenta.idcuenta
        LEFT JOIN t_usuario ON t_anticipo.idusuario=t_usuario.idusuario
        WHERE
        t_anticipo.idanticipo=$idanticipo;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $fecha=$row["fecha"];
        $cliente=$row["cliente"];
        $recibo=$row["recibo"];
        $banco=$row["banco"];
        $cuenta=$row["cuenta"];
        $glosa=$row["glosa"];
        $monto=$row["monto"];
        $nombre=$row["nombre"];
        $idempresa=$row['idempresa'];
    }
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);

    $html='';

    $html=$html.'
        <br /><br />
        <font style="font-family: Helvetica; font-size: 9pt">'.fechaliteral($fecha).'</font><br />
        <p align="center"><font style="font-family: Helvetica; font-size: 9pt"><b>Comprobante de Ingreso Nro. '.$idanticipo.'<br />Anticipo Clientes</b></font></p>
        <font style="font-family: Helvetica; font-size: 9pt"><b>Cliente:</b> '.$cliente.'</font><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Importe:</b> '.  number_format($monto, 2, '.', ',').'</font><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Glosa:</b> '.$glosa.'</font><br /><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Detalle:</b></font><br />
        <div style="text-align:center;">
            <table border="1" cellpadding="0" cellspacing="0" width="80%" style="margin: 0 auto;">
                <tr>
                    <td width="20%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>No Recibo</b></font></td>
                    <td width="30%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>Monto Ingresado en Banco</b></font></td>
                    <td width="50%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>Banco</b></font></td>
                </tr>
                <tr>
                    <td width="20%" align="center"><font style="font-family: Helvetica; font-size: 8pt">'.$recibo.'</font></td>
                    <td width="30%" align="center"><font style="font-family: Helvetica; font-size: 8pt">'.number_format($monto, 2, '.', ',').'</font></td>
                    <td width="50%" align="center"><font style="font-family: Helvetica; font-size: 8pt">'.$banco.' '.$cuenta.'</font></td>
                </tr>
            </table>
        </div>
        <br />
        <br />
        <br />
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="20%">&nbsp;</td>
                <td width="20%" style="border-top-style: solid; border-top-width: 1px;" align="center"><font style="font-family: Helvetica; font-size: 8pt">Aprobado</font></td>
                <td width="20%">&nbsp;</td>
                <td width="20%" style="border-top-style: solid; border-top-width: 1px;" align="center"><font style="font-family: Helvetica; font-size: 8pt">Procesado por:<br />'.$nombre.'</font></td>
                <td width="20%">&nbsp;</td>
            </tr>
        </table>
        ';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa."/documentos/anticipos/anticipo$idanticipo.pdf");



}

function generarAplicacion($idempresa, $numero, $conexion, $membretado = false){

    $result = $conexion->query("SELECT
        t_cobro.idanticipo,
        t_cobro.fecha,
        v_entidades.entidad as cliente,
        DATE_FORMAT(t_cobro.fechapago,'%d/%m/%Y') as fechapago,
        t_anticipo.recibo,
        SUM(t_cobro.monto) as monto,
        t_cuenta.banco,
        t_cuenta.cuenta,
        t_tipotransferencia.tipotransferencia
        FROM
        t_cobro
        LEFT JOIN t_anticipo ON t_cobro.idanticipo=t_anticipo.idanticipo
        LEFT JOIN v_entidades ON t_anticipo.idtipoentidad=v_entidades.idtipoentidad AND t_anticipo.identidad=v_entidades.identidad
        LEFT JOIN t_cuenta ON t_anticipo.idcuenta=t_cuenta.idcuenta
        LEFT JOIN t_tipotransferencia ON t_anticipo.idtipotransferencia=t_tipotransferencia.idtipotransferencia
        WHERE
        t_cobro.numero=$numero
        AND v_entidades.idempresa=$idempresa
        GROUP BY
        t_cobro.idanticipo,
        t_cobro.fecha,
        v_entidades.entidad,
        t_cobro.fechapago,
        t_anticipo.recibo,
        t_cuenta.banco,
        t_cuenta.cuenta,
        t_tipotransferencia.tipotransferencia;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $fecha=$row["fecha"];
        $cliente=$row["cliente"];
        $fechapago=$row["fechapago"];
        $recibo=$row["recibo"];
        $monto=$row["monto"];
        $banco=$row["banco"];
        $cuenta=$row["cuenta"];
        $tipotransferencia=$row["tipotransferencia"];
    }

    $html='';

    $html=$html.'
        <br /><br />
        <font style="font-family: Helvetica; font-size: 9pt">'.fechaliteral($fecha).'</font><br />
        <p align="center"><font style="font-family: Helvetica; font-size: 9pt"><b>Aplicacion de Fondos Nro. '.$numero.'</b></font></p>
        <font style="font-family: Helvetica; font-size: 9pt"><b>Cliente:</b> '.$cliente.'</font><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Recibo:</b> '.$recibo.'</font><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Importe:</b> '.  number_format($monto, 2, '.', ',').' BOB</font><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Fecha de Pago:</b> '.$fechapago.'</font><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Banco:</b> '.$banco.' '.$cuenta.'</font><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Forma de Pago:</b> '.$tipotransferencia.'</font><br /><br />
        <font style="font-family: Helvetica; font-size: 9pt"><b>Detalle:</b></font><br />
        <div style="text-align:center;">
            <table border="1" cellpadding="0" cellspacing="0" width="100%" style="margin: 0 auto;">
                <tr>
                    <td width="20%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>Embarque</b></font></td>
                    <td width="20%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>Tipo</b></font></td>
                    <td width="15%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>Nro</b></font></td>
                    <td width="15%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>Total BOB</b></font></td>
                    <td width="15%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>Cobrado BOB</b></font></td>
                    <td width="15%" align="center"><font style="font-family: Helvetica; font-size: 8pt"><b>Saldo BOB</b></font></td>
                </tr>';
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_totalcobradofacturas;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_totalcobradofacturas (idfactura INT, monto DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_totalcobradofacturas (idfactura, monto)
        select
        t_cobro.idfacturanotadebito,
        SUM(t_cobro.monto)
        from
        t_cobro
        LEFT JOIN t_anticipo ON t_cobro.idanticipo=t_anticipo.idanticipo
        LEFT JOIN v_entidades ON t_anticipo.idtipoentidad=v_entidades.idtipoentidad AND t_anticipo.identidad=v_entidades.identidad
        WHERE
        t_cobro.idtipocobro=1
        AND t_cobro.numero<$numero
        AND v_entidades.idempresa=$idempresa
        GROUP BY t_cobro.idfacturanotadebito;");
    $conexion->query("ALTER TABLE tmp_totalcobradofacturas ADD INDEX idfactura (idfactura);");

    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_totalfacturas;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_totalfacturas (idfactura INT, monto DOUBLE);");
    $conexion->query("INSERT INTO tmp_totalfacturas (idfactura, monto)
        SELECT
        t_factura.idfactura,
        valorfacturado(t_factura.idfactura)-ifnull(tmp_totalcobradofacturas.monto,0) as monto
        FROM
        t_factura
        LEFT JOIN tmp_totalcobradofacturas ON t_factura.idfactura=tmp_totalcobradofacturas.idfactura
        LEFT JOIN t_cobro ON t_factura.idfactura=t_cobro.idfacturanotadebito AND 1=t_cobro.idtipocobro
        LEFT JOIN t_anticipo ON t_cobro.idanticipo=t_anticipo.idanticipo
        LEFT JOIN v_entidades ON t_anticipo.idtipoentidad=v_entidades.idtipoentidad AND t_anticipo.identidad=v_entidades.identidad
        WHERE 
        t_cobro.numero=$numero
        AND v_entidades.idempresa=$idempresa;");
    $conexion->query("ALTER TABLE tmp_totalfacturas ADD INDEX idfactura (idfactura);");


    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_totalcobradonotasdebito;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_totalcobradonotasdebito (idnotadebito INT, monto DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_totalcobradonotasdebito (idnotadebito, monto)
        select
        t_cobro.idfacturanotadebito,
        SUM(t_cobro.monto)
        from
        t_cobro
        LEFT JOIN t_anticipo ON t_cobro.idanticipo=t_anticipo.idanticipo
        LEFT JOIN v_entidades ON t_anticipo.idtipoentidad=v_entidades.idtipoentidad AND t_anticipo.identidad=v_entidades.identidad
        WHERE
        t_cobro.idtipocobro=2
        AND t_cobro.numero<$numero
        AND v_entidades.idempresa=$idempresa
        GROUP BY 
        t_cobro.idfacturanotadebito;");
    $conexion->query("ALTER TABLE tmp_totalcobradonotasdebito ADD INDEX idnotadebito (idnotadebito);");

    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_totalnotasdebito;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_totalnotasdebito (idnotadebito INT, monto DOUBLE);");
    $conexion->query("INSERT INTO tmp_totalnotasdebito (idnotadebito, monto)
        select
        t_notadebito.idnotadebito,
        valordebitado(t_notadebito.idnotadebito)-ifnull(tmp_totalcobradonotasdebito.monto,0) as monto
        from
        t_notadebito
        LEFT JOIN t_cobro ON t_notadebito.idnotadebito=t_cobro.idfacturanotadebito AND 2=t_cobro.idtipocobro
        LEFT JOIN t_anticipo ON t_cobro.idanticipo=t_anticipo.idanticipo
        LEFT JOIN v_entidades ON t_anticipo.idtipoentidad=v_entidades.idtipoentidad AND t_anticipo.identidad=v_entidades.identidad
        LEFT JOIN tmp_totalcobradonotasdebito ON t_notadebito.idnotadebito=tmp_totalcobradonotasdebito.idnotadebito
        WHERE
        t_cobro.numero=$numero
        AND v_entidades.idempresa=$idempresa;");
    $conexion->query("ALTER TABLE tmp_totalnotasdebito ADD INDEX idnotadebito (idnotadebito);");

    $totalaplicado=0;
    $result = $conexion->query("SELECT
        CASE t_cobro.idtipocobro WHEN 2 THEN t_embarquend.embarque ELSE t_embarque.embarque END as embarque,
        t_cobro.idtipocobro,
        t_tipocobro.tipocobro,
        CASE t_cobro.idtipocobro WHEN 2 THEN CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion) ELSE t_factura.nrofactura END as numero,
        CASE t_cobro.idtipocobro WHEN 2 THEN tmp_totalnotasdebito.monto ELSE tmp_totalfacturas.monto END as montototal,
        t_cobro.monto,
        (CASE t_cobro.idtipocobro WHEN 2 THEN tmp_totalnotasdebito.monto ELSE tmp_totalfacturas.monto END)-t_cobro.monto as saldo
        FROM
        t_cobro
        LEFT JOIN t_anticipo ON t_cobro.idanticipo=t_anticipo.idanticipo
        LEFT JOIN v_entidades ON t_anticipo.idtipoentidad=v_entidades.idtipoentidad AND t_anticipo.identidad=v_entidades.identidad
        LEFT JOIN t_factura ON t_cobro.idfacturanotadebito=t_factura.idfactura AND 1=t_cobro.idtipocobro
        LEFT JOIN t_notadebito ON t_cobro.idfacturanotadebito=t_notadebito.idnotadebito AND 2=t_cobro.idtipocobro
        LEFT JOIN t_embarque ON t_factura.idembarque=t_embarque.idembarque
        LEFT JOIN t_embarque as t_embarquend ON t_notadebito.idembarque=t_embarquend.idembarque
        LEFT JOIN tmp_totalfacturas ON t_cobro.idfacturanotadebito=tmp_totalfacturas.idfactura AND t_cobro.idtipocobro=1
        LEFT JOIN tmp_totalnotasdebito ON t_cobro.idfacturanotadebito=tmp_totalnotasdebito.idnotadebito AND t_cobro.idtipocobro=2
        LEFT JOIN t_tipocobro ON t_cobro.idtipocobro=t_tipocobro.idtipocobro
        WHERE 
        t_cobro.numero=$numero
        AND v_entidades.idempresa=$idempresa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $html=$html.'<tr>
                <td width="20%" align="center"><font style="font-family: Helvetica; font-size: 8pt">'.$row['embarque'].'</font></td>
                <td width="20%" align="center"><font style="font-family: Helvetica; font-size: 8pt">'.$row['tipocobro'].'</font></td>
                <td width="15%" align="center"><font style="font-family: Helvetica; font-size: 8pt">'.$row['numero'].'</font></td>
                <td width="15%" align="right"><font style="font-family: Helvetica; font-size: 8pt">'.number_format($row['montototal'], 2, '.', ',').'</font></td>
                <td width="15%" align="right"><font style="font-family: Helvetica; font-size: 8pt">'.number_format($row['monto'], 2, '.', ',').'</font></td>
                <td width="15%" align="right"><font style="font-family: Helvetica; font-size: 8pt">'.number_format($row['saldo'], 2, '.', ',').'</font></td>
            </tr>';
        $totalaplicado=$totalaplicado+$row["monto"];
    }
    $html=$html.'<tr>
            <td width="20%" align="center"><font style="font-family: Helvetica; font-size: 8pt"></font></td>
            <td width="20%" align="center"><font style="font-family: Helvetica; font-size: 8pt"></font></td>
            <td width="15%" align="center"><font style="font-family: Helvetica; font-size: 8pt"></font></td>
            <td width="15%" align="right"><font style="font-family: Helvetica; font-size: 8pt"></font></td>
            <td width="15%" align="right"><font style="font-family: Helvetica; font-size: 8pt"><strong>'.number_format($totalaplicado, 2, '.', ',').'</strong></font></td>
            <td width="15%" align="right"><font style="font-family: Helvetica; font-size: 8pt"></font></td>
        </tr>';
    $html=$html.'</table>
        </div>
        <br />
        <br />
        <br />
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="20%">&nbsp;</td>
                <td width="20%" style="border-top-style: solid; border-top-width: 1px;" align="center"><font style="font-family: Helvetica; font-size: 8pt">Aprobado</font></td>
                <td width="20%">&nbsp;</td>
                <td width="20%" style="border-top-style: solid; border-top-width: 1px;" align="center"><font style="font-family: Helvetica; font-size: 8pt">Procesado por</font></td>
                <td width="20%">&nbsp;</td>
            </tr>
        </table>
        ';

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa."/documentos/cobros/cobro$numero.pdf");



}

function generarPago($idempresa, $idpago, $conexion, $membretado = false){
    $result = $conexion->query("select
        t_pago.nropago,
        t_pago.fecha,
        CONCAT(t_cuenta.banco,' ',t_cuenta.cuenta) as cuenta,
        t_metodopago.metodopago,
        t_pago.nrotransaccion,
        t_pago.alaordende,
        t_pago.concepto,
        IFNULL(t_pago.pagoa,0) as pagoa,
        t_usuario.nombre
        from
        t_pago
        LEFT JOIN t_cuenta ON t_pago.idcuenta=t_cuenta.idcuenta
        LEFT JOIN t_metodopago ON t_pago.idmetodopago=t_metodopago.idmetodopago
        LEFT JOIN t_usuario ON t_pago.idusuario=t_usuario.idusuario
        WHERE t_pago.idpago=$idpago;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $nropago=$row["nropago"];
        $fecha=$row["fecha"];
        $cuenta=$row["cuenta"];
        $metodopago=$row["metodopago"];
        $nrotransaccion=$row["nrotransaccion"];
        $alaordende=$row["alaordende"];
        $concepto=$row["concepto"];
        $nombre=$row["nombre"];
        if((int)$row['pagoa']==0){
            $pagoa="Proveedores";
        }else{
            $pagoa="Cuenta del Cliente";
        }
    }


    $html='<br /><br /><br /><br />
        <table border="0" cellpadding="0" cellspacing="0" width="725">
            <tr>
                <td align="right"><span style="font-size: 15pt; font-family: verdana">COMPROBANTE DE PAGO</span></td>
            </tr>
        </table>
        <table border="0" cellpadding="3" cellspacing="2" width="725">
            <tr>
                <td width="355">&nbsp;</td>
                <td width="150" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana">Fecha</span></td>
                <td width="220" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana">Numero</span></td>
            </tr>
            <tr>
                <td width="355">&nbsp;</td>
                <td width="150" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px"><span style="font-size: 9pt; font-family: verdana">'.fechaliteral($fecha).'</span></td>
                <td width="220" style="border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-left-style: solid; border-left-width: 1px; border-bottom-style: solid; border-bottom-width: 1px"><span style="font-size: 9pt; font-family: verdana">'.$nropago.'</span></td>
            </tr>
        </table>
        <br />
        <table border="1" cellpadding="2" cellspacing="2" width="500" style="margin:0 auto;">
            <tr>
                <td width="200" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>A la orden de:</strong></span></td>
                <td width="300"><span style="font-size: 9pt; font-family: verdana">'.$alaordende.'</td>
            </tr>
            <tr>
                <td width="200" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>Por Concepto:</strong></span></td>
                <td width="300"><span style="font-size: 9pt; font-family: verdana">'.$concepto.'</td>
            </tr>
            <tr>
                <td width="200" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>Cuenta:</strong></span></td>
                <td width="300"><span style="font-size: 9pt; font-family: verdana">'.$cuenta.'</td>
            </tr>
            <tr>
                <td width="200" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>Pago a:</strong></span></td>
                <td width="300"><span style="font-size: 9pt; font-family: verdana">'.$pagoa.'</td>
            </tr>
            <tr>
                <td width="200" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>Metodo pago:</strong></span></td>
                <td width="300"><span style="font-size: 9pt; font-family: verdana">'.$metodopago.'</td>
            </tr>
            <tr>
                <td width="200" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>Nro Transaccion:</strong></span></td>
                <td width="300"><span style="font-size: 9pt; font-family: verdana">'.$nrotransaccion.'</td>
            </tr>
        </table>
        <br />
        <table border="1" cellpadding="2" cellspacing="2" width="750">
            <tr>
                <td width="150" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>TIPO</strong></span></td>
                <td width="100" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>EMBARQUE</strong></span></td>
                <td width="100" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>NRO</strong></span></td>
                <td width="80" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>MONTO</strong></span></td>
                <td width="80" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>PAGADO</strong></span></td>
                <td width="80" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>PAGO ACTUAL</strong></span></td>
                <td width="80" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>SALDO</strong></span></td>
                <td width="80" bgcolor="#CCCCCC"><span style="font-size: 9pt; font-family: verdana"><strong>DIVISA</strong></span></td>
            </tr>';

            $totalpagado=0;
            $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_pagos;");
            $conexion->query("CREATE TEMPORARY TABLE tmp_pagos (idfacturapago INT, pagado DECIMAL(13,2));");
            $conexion->query("INSERT INTO tmp_pagos (idfacturapago, pagado)
                SELECT
                t_pagodetalle.idfacturapago,
                SUM(t_pagodetalle.monto)
                FROM
                t_pagodetalle
                LEFT JOIN t_pago ON t_pagodetalle.idpago=t_pago.idpago
                LEFT JOIN t_facturapago ON t_pagodetalle.idfacturapago=t_facturapago.idfacturapago
                LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
                WHERE 
                t_pago.idpago<$idpago
                AND t_embarque.idempresa=$idempresa
                GROUP BY
                t_pagodetalle.idfacturapago;");
            $conexion->query("ALTER TABLE tmp_pagos ADD INDEX idfacturapago (idfacturapago);");

            $result = $conexion->query("select
                t_tipofacturapago.tipofacturapago,
                CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as numerofactura,
                t_embarque.embarque,
                SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) as monto,
                IFNULL(tmp_pagos.pagado,0) as yapagado,
                t_pagodetalle.monto as pagado,
                SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio)-IFNULL(tmp_pagos.pagado,0)-t_pagodetalle.monto as saldo,
                t_divisa.codigo as divisa
                from
                t_pagodetalle
                LEFT JOIN t_facturapago ON t_pagodetalle.idfacturapago=t_facturapago.idfacturapago
                LEFT JOIN t_tipofacturapago ON t_facturapago.idtipofacturapago=t_tipofacturapago.idtipofacturapago
                LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
                LEFT JOIN t_costo ON t_facturapago.idfacturapago=t_costo.idfacturanotadebito AND 1=t_costo.idtipofacturanotadebito
                LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND t_facturapago.iddivisa=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_facturapago.fecha) AND t_tipocambio.idempresa=t_embarque.idempresa
                LEFT JOIN t_divisa ON t_facturapago.iddivisa=t_divisa.iddivisa
                LEFT JOIN tmp_pagos ON t_facturapago.idfacturapago=tmp_pagos.idfacturapago
                WHERE
                t_pagodetalle.idpago=$idpago
                AND t_facturapago.idestadofacturapago=1
                GROUP BY
                t_tipofacturapago.tipofacturapago,
                CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion),
                t_pagodetalle.monto,
                t_embarque.embarque,
                tmp_pagos.pagado,
                t_divisa.codigo;");
            while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
                //$cantitems++;
                $divisa=$row["divisa"];
                $html=$html.'
                <tr>
                    <td width="150"><span style="font-size: 9pt; font-family: verdana">'.$row["tipofacturapago"].'</span></td>
                    <td width="100"><span style="font-size: 9pt; font-family: verdana">'.$row["embarque"].'</span></td>
                    <td width="100"><span style="font-size: 9pt; font-family: verdana">'.$row["numerofactura"].'</span></td>
                    <td width="80" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["monto"], 2, ",", ".").'</span></td>
                    <td width="80" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["yapagado"], 2, ",", ".").'</span></td>
                    <td width="80" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["pagado"], 2, ",", ".").'</span></td>
                    <td width="80" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["saldo"], 2, ",", ".").'</span></td>
                    <td width="80"><span style="font-size: 9pt; font-family: verdana">'.$row["divisa"].'</span></td>
                </tr>';
                $totalpagado=$totalpagado+$row["pagado"];
            }
    $html=$html.'
            <tr>
                <td width="510" colspan="5" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>TOTAL:</strong></span></td>
                <td width="80" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($totalpagado, 2, ",", ".").'</span></td>
                <td width="160" colspan="2"></td>
            </tr>
        </table><br />';

    $piedepagina='<table border="0" width="200" cellpadding="0" cellspacing="0">';
    $piedepagina=$piedepagina.'<tr>';
    $piedepagina=$piedepagina.'<td align="center" style="border-top-style: solid; border-top-width: 2px; border-top-color: black"><font style="font-family: Helvetica; font-size: 9pt">Elaborado Por</FONT></td>';
    $piedepagina=$piedepagina.'</tr>';
    $piedepagina=$piedepagina.'<tr>';
    $piedepagina=$piedepagina.'<td align="center"><font style="font-family: Helvetica; font-size: 9pt">'.$nombre.'</FONT></td>';
    $piedepagina=$piedepagina.'</tr>';
    $piedepagina=$piedepagina.'</table>';

    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);

    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right'=> 15,
        'margin_top'=> 12,
        'margin_bottom'=> 16,
        'margin_header'=> 9,
        'margin_footer'=> 12
    ]);

    $mpdf->WriteHTML($html);
    $mpdf->SetHTMLFooter($piedepagina);
    $mpdf->Output(folder_files.$idempresa."/documentos/pagos/pago$idpago.pdf");

};

function generarDevolucion($iddevolucion, $conexion, $membretado = false){
    $result = $conexion->query("SELECT
        t_cliente.idempresa,
        t_devolucion.numero,
        CONCAT(t_cuenta.banco,' | ',t_cuenta.cuenta) as banco,
        DATE_FORMAT(t_devolucion.fechadevolucion,'%d/%m/%Y') as fechadevolucion,
        t_devolucion.numerotransaccion,
        t_devolucion.concepto,
        t_devolucion.ordende,
        CASE t_devolucion.idtipoentidad
                WHEN 1 THEN t_cliente.cliente
                WHEN 2 THEN t_proveedor.proveedor
                WHEN 3 THEN t_prestador.prestador
                WHEN 4 THEN t_transportista.transportista
                WHEN 5 THEN t_agentecarga.agentecarga
        END as entidad
        from
        t_devolucion
        LEFT JOIN t_cuenta ON t_devolucion.idcuenta=t_cuenta.idcuenta
        LEFT JOIN t_cliente ON t_devolucion.identidad=t_cliente.idcliente
        LEFT JOIN t_proveedor ON t_devolucion.identidad=t_proveedor.idproveedor
        LEFT JOIN t_prestador ON t_devolucion.identidad=t_prestador.idprestador
        LEFT JOIN t_transportista ON t_devolucion.identidad=t_transportista.idtransportista
        LEFT JOIN t_agentecarga ON t_devolucion.identidad=t_agentecarga.idagentecarga
        WHERE
        t_devolucion.iddevolucion=$iddevolucion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idempresa=$row['idempresa'];
        $numero=$row['numero'];
        $banco=$row['banco'];
        $fechadevolucion=$row['fechadevolucion'];
        $numerotransaccion=$row['numerotransaccion'];
        $concepto=$row['concepto'];
        $ordende=$row['ordende'];
        $entidad=$row['entidad'];
    }


    $html='
    <b><div style="font-family: Arial; font-size: 14pt; text-align: center;">Comprobante de Egreso</div></b><br />
        <table border="1" cellpadding="5" cellspacing="0" width="100%">
            <tr>
                <td width="75%">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="30%"><div style="font-family: Arial; font-size: 8pt">Hemos entregado a:</div></td>
                            <td width="70%" colspan="3" style="border-bottom-style: solid; border-bottom-width: 1px"><div style="font-family: Arial; font-size: 8pt">'.$ordende.'</div></td>
                        </tr>
                        <tr>
                            <td width="30%"><div style="font-family: Arial; font-size: 8pt">Por Concepto de:</div></td>
                            <td width="70%" colspan="3" style="border-bottom-style: solid; border-bottom-width: 1px"><div style="font-family: Arial; font-size: 8pt">'.$concepto.'</div></td>
                        </tr>
                        <tr>
                            <td width="30%"><div style="font-family: Arial; font-size: 8pt">Banco:</div></td>
                            <td width="70%" colspan="3" style="border-bottom-style: solid; border-bottom-width: 1px"><div style="font-family: Arial; font-size: 8pt">'.$banco.'</div></td>
                        </tr>
                        <tr>
                            <td width="30%"><div style="font-family: Arial; font-size: 8pt">Moneda:</div></td>
                            <td width="30%" style="border-bottom-style: solid; border-bottom-width: 1px"><div style="font-family: Arial; font-size: 8pt">Bolivianos</div></td>
                            <td width="20%" align="center"><div style="font-family: Arial; font-size: 8pt">Estado:</div></td>
                            <td width="20%" style="border-bottom-style: solid; border-bottom-width: 1px"><div style="font-family: Arial; font-size: 8pt">Pagado</div></td>
                        </tr>
                    </table>
                </td>
                <td width="25%">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="40%"><div style="font-family: Arial; font-size: 8pt">Nro</div></td>
                            <td width="60%" style="border-bottom-style: solid; border-bottom-width: 1px"><div style="font-family: Arial; font-size: 12pt"><b>'.$numero.'</b></div></td>
                        </tr>
                        <tr>
                            <td width="40%"><div style="font-family: Arial; font-size: 8pt">Fecha</div></td>
                            <td width="60%" style="border-bottom-style: solid; border-bottom-width: 1px"><div style="font-family: Arial; font-size: 8pt">'.$fechadevolucion.'</div></td>
                        </tr>
                        <tr>
                            <td width="40%"><div style="font-family: Arial; font-size: 8pt">No Transaccion</div></td>
                            <td width="60%" style="border-bottom-style: solid; border-bottom-width: 1px"><div style="font-family: Arial; font-size: 8pt">'.$numerotransaccion.'</div></td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
        ';

    $html=$html.'
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td width="35%" align="center" style="border-left-style: solid; border-left-width: 1px; border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-bottom-style: solid; border-bottom-width: 1px;"><b><div style="font-family: Arial; font-size: 8pt">CLIENTE</div></b></td>
            <td width="10%" align="center" style="border-left-style: solid; border-left-width: 1px; border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-bottom-style: solid; border-bottom-width: 1px;"><b><div style="font-family: Arial; font-size: 8pt">RECIBO</div></b></td>
            <td width="10%" align="center" style="border-left-style: solid; border-left-width: 1px; border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-bottom-style: solid; border-bottom-width: 1px;"><b><div style="font-family: Arial; font-size: 8pt">FECHA DE PAGO</div></b></td>
            <td width="35%" align="center" style="border-left-style: solid; border-left-width: 1px; border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-bottom-style: solid; border-bottom-width: 1px;"><b><div style="font-family: Arial; font-size: 8pt">BANCO ORIGEN</div></b></td>
            <td width="10%" align="center" style="border-left-style: solid; border-left-width: 1px; border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-bottom-style: solid; border-bottom-width: 1px;"><b><div style="font-family: Arial; font-size: 8pt">MONTO</div></b></td>
        </tr>
    ';

    $total=0;
    $result = $conexion->query("SELECT
        t_anticipo.recibo,
        DATE_FORMAT(t_anticipo.fecha,'%d/%m/%Y') as fecha,
        CONCAT(t_cuenta.banco,' | ',t_cuenta.cuenta) as banco,
        t_devoluciondetalle.monto
        from
        t_devoluciondetalle
        LEFT JOIN t_anticipo ON t_devoluciondetalle.idanticipo=t_anticipo.idanticipo
        LEFT JOIN t_cuenta ON t_anticipo.idcuenta=t_cuenta.idcuenta
        WHERE
        t_devoluciondetalle.iddevolucion=$iddevolucion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $html=$html.'
            <tr>
                <td width="35%" align="left" style="border-left-style: solid; border-left-width: 1px;"><div style="font-family: Arial; font-size: 8pt">'.$entidad.'</div></td>
                <td width="10%" align="center"><div style="font-family: Arial; font-size: 8pt">'.$row["recibo"].'</div></td>
                <td width="10%" align="center"><div style="font-family: Arial; font-size: 8pt">'.$row["fecha"].'</div></td>
                <td width="35%" align="left"><div style="font-family: Arial; font-size: 8pt">'.$row["banco"].'</div></td>
                <td width="10%" align="right" style="border-right-style: solid; border-right-width: 1px;"><div style="font-family: Arial; font-size: 8pt">'.number_format($row["monto"], 2, '.', ',').'</div></td>
            </tr>';
        $total=$total+$row["monto"];

    }

    $V=new EnLetras();
        $html=$html.'
        <tr>
            <td width="90%" colspan="4" align="right"  style="border-left-style: solid; border-left-width: 1px; border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-bottom-style: solid; border-bottom-width: 1px;"><b><div style="font-family: Arial; font-size: 8pt">'.$V->ValorEnLetras($total,"Bolivianos").'</div></b></td>
            <td width="10%" align="right" style="border-left-style: solid; border-left-width: 1px; border-top-style: solid; border-top-width: 1px; border-right-style: solid; border-right-width: 1px; border-bottom-style: solid; border-bottom-width: 1px;"><b><div style="font-family: Arial; font-size: 8pt">'.number_format($total, 2, '.', ',').'</div></b></td>
        </tr></table>';


    $html=$html.'<table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr style="height: 75px">
            <td width="7%">&nbsp;</td>
            <td width="20%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="20%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="20%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
        </tr>
        <tr style="height: 75px">
            <td width="7%">&nbsp;</td>
            <td width="20%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="20%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="20%">&nbsp;</td>
            <td width="6%">&nbsp;</td>
        </tr>
        <tr style="height: 75px">
            <td width="7%">&nbsp;</td>
            <td width="20%" style="border-bottom-style: solid; border-bottom-width: 1px">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="20%" style="border-bottom-style: solid; border-bottom-width: 1px">&nbsp;</td>
            <td width="6%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="20%" style="border-bottom-style: solid; border-bottom-width: 1px">&nbsp;</td>
            <td width="6%">&nbsp;</td>
        </tr>
        <tr>
            <td width="7%">&nbsp;</td>
            <td width="20%" align="center"><div style="font-family: Arial; font-size: 8pt">Aprobado</div></td>
            <td width="6%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="20%" align="center"><div style="font-family: Arial; font-size: 8pt"></div></td>
            <td width="6%">&nbsp;</td>
            <td width="7%">&nbsp;</td>
            <td width="20%" align="center"><div style="font-family: Arial; font-size: 8pt">Recibi Conforme</div></td>
            <td width="6%">&nbsp;</td>
        </tr>
    </table>
    ';
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);

    //include('MPDF57/mpdf.php');
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right'=> 15,
        'margin_top'=> 35,
        'margin_bottom'=> 25,
        'margin_header'=> 9,
        'margin_footer'=> 12
    ]);

    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa.DIRECTORY_SEPARATOR."documentos/devoluciones/devolucion$iddevolucion.pdf");

}

function splitString($cadena){
  $parts = str_split($cadena, strlen($cadena) / 4); // Divide la cadena en partes iguales
  $partsFinal = $parts[count($parts)-2].$parts[count($parts)-1];
  unset($parts[count($parts)-1]);
  unset($parts[count($parts)-1]);
  $parts = array_merge($parts,[$partsFinal]);
  return implode(' ', $parts);
}
