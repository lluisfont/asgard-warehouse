<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/entidades', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $entidades=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_clientedireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_clientedireccion (idcliente INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_clientedireccion (idcliente, direcciones)
        SELECT
        t_clientedireccion.idcliente,
        GROUP_CONCAT(CONCAT('{\"identidaddireccion\": ',t_clientedireccion.idclientedireccion,', \"direccion\": \"',IFNULL(t_clientedireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_clientedireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_clientedireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_clientedireccion
        LEFT JOIN t_cliente ON t_clientedireccion.idcliente=t_cliente.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        GROUP BY
        t_clientedireccion.idcliente;");
    $conexion->query("ALTER TABLE tmp_clientedireccion ADD INDEX idcliente (idcliente);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_clientecorreosfacturacion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_clientecorreosfacturacion (idcliente INT, correosfacturacion TEXT);");
    $conexion->query("INSERT INTO tmp_clientecorreosfacturacion (idcliente, correosfacturacion)
        select
        t_clientecorreofacturacion.idcliente,
        GROUP_CONCAT(CONCAT('{\"idcorreofacturacion\": ',t_clientecorreofacturacion.idclientecorreofacturacion,', \"correo\": \"',t_clientecorreofacturacion.correo,'\"}') SEPARATOR ',') as correosfacturacion
        FROM
        t_clientecorreofacturacion
        LEFT JOIN t_cliente ON t_clientecorreofacturacion.idcliente=t_cliente.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        GROUP BY
        t_clientecorreofacturacion.idcliente;");
    $conexion->query("ALTER TABLE tmp_clientecorreosfacturacion ADD INDEX idcliente (idcliente);");
    
    
    $result = $conexion->query("SELECT
        1 as idtipoentidad,
        t_cliente.idcliente as identidad,
        t_cliente.cliente as entidad,
        t_cliente.numeroidentificacion,
        t_tipoentidad.tipoentidad,
        IFNULL(t_cliente.plazo,0) as plazo,
        t_cliente.idtipodocumento,
        t_cliente.numerofacturacion,
        t_cliente.razonsocial,
        CONCAT('[',IFNULL(tmp_clientedireccion.direcciones,''),']') as direcciones,
        CONCAT('[',IFNULL(tmp_clientecorreosfacturacion.correosfacturacion,''),']') as correosfacturacion
        FROM 
        t_cliente
        LEFT JOIN t_tipoentidad ON 1=t_tipoentidad.idtipoentidad
        LEFT JOIN tmp_clientedireccion ON t_cliente.idcliente=tmp_clientedireccion.idcliente
        LEFT JOIN tmp_clientecorreosfacturacion ON t_cliente.idcliente=tmp_clientecorreosfacturacion.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        ORDER BY
        t_cliente.cliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $entidades[]=array(
            'idtipoentidad'=>(int)$row['idtipoentidad'],
            'id'=>(int)$row['identidad'],
            'identidad'=>$row['idtipoentidad']."-".$row['identidad'],
            'entidad'=>$row['entidad'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'tipoentidad'=>$row['tipoentidad'],
            'plazo'=>(int)$row['plazo'],
            'idtipodocumento'=>$row['idtipodocumento'],
            'numerofacturacion'=>$row['numerofacturacion'],
            'razonsocial'=>$row['razonsocial'],
            'direcciones'=> json_decode($row["direcciones"], true),
            'correosfacturacion'=> json_decode($row["correosfacturacion"], true)
        );
    }
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_proveedordireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_proveedordireccion (idproveedor INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_proveedordireccion (idproveedor, direcciones)
        SELECT
        t_proveedordireccion.idproveedor,
        GROUP_CONCAT(CONCAT('{\"identidaddireccion\": ',t_proveedordireccion.idproveedordireccion,', \"direccion\": \"',IFNULL(t_proveedordireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_proveedordireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_proveedordireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_proveedordireccion
        LEFT JOIN t_proveedor ON t_proveedordireccion.idproveedor=t_proveedor.idproveedor
        WHERE
        t_proveedor.idempresa=$idempresa
        GROUP BY
        t_proveedordireccion.idproveedor;");
    $conexion->query("ALTER TABLE tmp_proveedordireccion ADD INDEX idproveedor (idproveedor);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_proveedorcorreosfacturacion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_proveedorcorreosfacturacion (idproveedor INT, correosfacturacion TEXT);");
    $conexion->query("INSERT INTO tmp_proveedorcorreosfacturacion (idproveedor, correosfacturacion)
        select
        t_proveedorcorreofacturacion.idproveedor,
        GROUP_CONCAT(CONCAT('{\"idcorreofacturacion\": ',t_proveedorcorreofacturacion.idproveedorcorreofacturacion,', \"correo\": \"',t_proveedorcorreofacturacion.correo,'\"}') SEPARATOR ',') as correosfacturacion
        FROM
        t_proveedorcorreofacturacion
        LEFT JOIN t_proveedor ON t_proveedorcorreofacturacion.idproveedor=t_proveedor.idproveedor
        WHERE
        t_proveedor.idempresa=$idempresa
        GROUP BY
        t_proveedorcorreofacturacion.idproveedor;");
    $conexion->query("ALTER TABLE tmp_proveedorcorreosfacturacion ADD INDEX idproveedor (idproveedor);");
    
    
    $result = $conexion->query("SELECT
        2 as idtipoentidad,
        t_proveedor.idproveedor as identidad,
        t_proveedor.proveedor as entidad,
        t_proveedor.numeroidentificacion,
        t_tipoentidad.tipoentidad,
        IFNULL(t_proveedor.plazo,0) as plazo,
        t_proveedor.idtipodocumento,
        t_proveedor.numerofacturacion,
        t_proveedor.razonsocial,
        CONCAT('[',IFNULL(tmp_proveedordireccion.direcciones,''),']') as direcciones,
        CONCAT('[',IFNULL(tmp_proveedorcorreosfacturacion.correosfacturacion,''),']') as correosfacturacion
        FROM 
        t_proveedor
        LEFT JOIN t_tipoentidad ON 2=t_tipoentidad.idtipoentidad
        LEFT JOIN tmp_proveedordireccion ON t_proveedor.idproveedor=tmp_proveedordireccion.idproveedor
        LEFT JOIN tmp_proveedorcorreosfacturacion ON t_proveedor.idproveedor=tmp_proveedorcorreosfacturacion.idproveedor
        WHERE
        t_proveedor.idempresa=$idempresa
        ORDER BY
        t_proveedor.proveedor;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $entidades[]=array(
            'idtipoentidad'=>(int)$row['idtipoentidad'],
            'id'=>(int)$row['identidad'],
            'identidad'=>$row['idtipoentidad']."-".$row['identidad'],
            'entidad'=>$row['entidad'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'tipoentidad'=>$row['tipoentidad'],
            'plazo'=>(int)$row['plazo'],
            'idtipodocumento'=>$row['idtipodocumento'],
            'numerofacturacion'=>$row['numerofacturacion'],
            'razonsocial'=>$row['razonsocial'],
            'direcciones'=> json_decode($row["direcciones"], true),
            'correosfacturacion'=> json_decode($row["correosfacturacion"], true)
        );
    }
    
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_prestadordireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_prestadordireccion (idprestador INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_prestadordireccion (idprestador, direcciones)
        SELECT
        t_prestadordireccion.idprestador,
        GROUP_CONCAT(CONCAT('{\"identidaddireccion\": ',t_prestadordireccion.idprestadordireccion,', \"direccion\": \"',IFNULL(t_prestadordireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_prestadordireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_prestadordireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_prestadordireccion
        LEFT JOIN t_prestador ON t_prestadordireccion.idprestador=t_prestador.idprestador
        WHERE
        t_prestador.idempresa=$idempresa
        GROUP BY
        t_prestadordireccion.idprestador;");
    $conexion->query("ALTER TABLE tmp_prestadordireccion ADD INDEX idprestador (idprestador);");
    $result = $conexion->query("SELECT
        3 as idtipoentidad,
        t_prestador.idprestador as identidad,
        t_prestador.prestador as entidad,
        t_prestador.numeroidentificacion,
        t_tipoentidad.tipoentidad,
        IFNULL(t_prestador.plazo,0) as plazo,
        0 as idtipodocumento,
        0 as numerofacturacion,
        '' as razonsocial,
        CONCAT('[',IFNULL(tmp_prestadordireccion.direcciones,''),']') as direcciones,
        '[]' as correosfacturacion
        FROM 
        t_prestador
        LEFT JOIN t_tipoentidad ON 3=t_tipoentidad.idtipoentidad
        LEFT JOIN tmp_prestadordireccion ON t_prestador.idprestador=tmp_prestadordireccion.idprestador
        WHERE
        t_prestador.idempresa=$idempresa
        ORDER BY
        t_prestador.prestador;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $entidades[]=array(
            'idtipoentidad'=>(int)$row['idtipoentidad'],
            'id'=>(int)$row['identidad'],
            'identidad'=>$row['idtipoentidad']."-".$row['identidad'],
            'entidad'=>$row['entidad'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'tipoentidad'=>$row['tipoentidad'],
            'plazo'=>(int)$row['plazo'],
            'idtipodocumento'=>$row['idtipodocumento'],
            'numerofacturacion'=>$row['numerofacturacion'],
            'razonsocial'=>$row['razonsocial'],
            'direcciones'=>json_decode($row["direcciones"], true),
            'correosfacturacion'=> json_decode($row["correosfacturacion"], true)
        );
    }
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_transportistadireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_transportistadireccion (idtransportista INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_transportistadireccion (idtransportista, direcciones)
        SELECT
        t_transportistadireccion.idtransportista,
        GROUP_CONCAT(CONCAT('{\"identidaddireccion\": ',t_transportistadireccion.idtransportistadireccion,', \"direccion\": \"',IFNULL(t_transportistadireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_transportistadireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_transportistadireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_transportistadireccion
        LEFT JOIN t_transportista ON t_transportistadireccion.idtransportista=t_transportista.idtransportista
        WHERE
        t_transportista.idempresa=$idempresa
        GROUP BY
        t_transportistadireccion.idtransportista;");
    $conexion->query("ALTER TABLE tmp_transportistadireccion ADD INDEX idtransportista (idtransportista);");
    $result = $conexion->query("SELECT
        4 as idtipoentidad,
        t_transportista.idtransportista as identidad,
        t_transportista.transportista as entidad,
        t_transportista.numeroidentificacion,
        t_tipoentidad.tipoentidad,
        IFNULL(t_transportista.plazo,0) as plazo,
        0 as idtipodocumento,
        0 as numerofacturacion,
        '' as razonsocial,
        CONCAT('[',IFNULL(tmp_transportistadireccion.direcciones,''),']') as direcciones,
        '[]' as correosfacturacion
        FROM 
        t_transportista
        LEFT JOIN t_tipoentidad ON 4=t_tipoentidad.idtipoentidad
        LEFT JOIN tmp_transportistadireccion ON t_transportista.idtransportista=tmp_transportistadireccion.idtransportista
        WHERE
        t_transportista.idempresa=$idempresa
        ORDER BY
        t_transportista.transportista;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $entidades[]=array(
            'idtipoentidad'=>(int)$row['idtipoentidad'],
            'id'=>(int)$row['identidad'],
            'identidad'=>$row['idtipoentidad']."-".$row['identidad'],
            'entidad'=>$row['entidad'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'tipoentidad'=>$row['tipoentidad'],
            'plazo'=>(int)$row['plazo'],
            'idtipodocumento'=>$row['idtipodocumento'],
            'numerofacturacion'=>$row['numerofacturacion'],
            'razonsocial'=>$row['razonsocial'],
            'direcciones'=>json_decode($row["direcciones"], true),
            'correosfacturacion'=> json_decode($row["correosfacturacion"], true)
        );
    }
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_agentecargadireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_agentecargadireccion (idagentecarga INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_agentecargadireccion (idagentecarga, direcciones)
        SELECT
        t_agentecargadireccion.idagentecarga,
        GROUP_CONCAT(CONCAT('{\"identidaddireccion\": ',t_agentecargadireccion.idagentecargadireccion,', \"direccion\": \"',IFNULL(t_agentecargadireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_agentecargadireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_agentecargadireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_agentecargadireccion
        LEFT JOIN t_agentecarga ON t_agentecargadireccion.idagentecarga=t_agentecarga.idagentecarga
        WHERE
        t_agentecarga.idempresa=$idempresa
        GROUP BY
        t_agentecargadireccion.idagentecarga;");
    $conexion->query("ALTER TABLE tmp_agentecargadireccion ADD INDEX idagentecarga (idagentecarga);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_agentecargacorreosfacturacion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_agentecargacorreosfacturacion (idagentecarga INT, correosfacturacion TEXT);");
    $conexion->query("INSERT INTO tmp_agentecargacorreosfacturacion (idagentecarga, correosfacturacion)
        select
        t_agentecargacorreofacturacion.idagentecarga,
        GROUP_CONCAT(CONCAT('{\"idcorreofacturacion\": ',t_agentecargacorreofacturacion.idagentecargacorreofacturacion,', \"correo\": \"',t_agentecargacorreofacturacion.correo,'\"}') SEPARATOR ',') as correosfacturacion
        FROM
        t_agentecargacorreofacturacion
        LEFT JOIN t_agentecarga ON t_agentecargacorreofacturacion.idagentecarga=t_agentecarga.idagentecarga
        WHERE
        t_agentecarga.idempresa=$idempresa
        GROUP BY
        t_agentecargacorreofacturacion.idagentecarga;");
    $conexion->query("ALTER TABLE tmp_agentecargacorreosfacturacion ADD INDEX idagentecarga (idagentecarga);");
    
    $result = $conexion->query("SELECT
        5 as idtipoentidad,
        t_agentecarga.idagentecarga as identidad,
        t_agentecarga.agentecarga as entidad,
        t_agentecarga.numeroidentificacion,
        t_tipoentidad.tipoentidad,
        IFNULL(t_agentecarga.plazo,0) as plazo,
        t_agentecarga.idtipodocumento,
        t_agentecarga.numerofacturacion,
        t_agentecarga.razonsocial,
        CONCAT('[',IFNULL(tmp_agentecargadireccion.direcciones,''),']') as direcciones,
        CONCAT('[',IFNULL(tmp_agentecargacorreosfacturacion.correosfacturacion,''),']') as correosfacturacion
        FROM 
        t_agentecarga
        LEFT JOIN t_tipoentidad ON 5=t_tipoentidad.idtipoentidad
        LEFT JOIN tmp_agentecargadireccion ON t_agentecarga.idagentecarga=tmp_agentecargadireccion.idagentecarga
        LEFT JOIN tmp_agentecargacorreosfacturacion ON t_agentecarga.idagentecarga=tmp_agentecargacorreosfacturacion.idagentecarga
        WHERE
        t_agentecarga.idempresa=$idempresa
        ORDER BY
        t_agentecarga.agentecarga;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $entidades[]=array(
            'idtipoentidad'=>(int)$row['idtipoentidad'],
            'id'=>(int)$row['identidad'],
            'identidad'=>$row['idtipoentidad']."-".$row['identidad'],
            'entidad'=>$row['entidad'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'tipoentidad'=>$row['tipoentidad'],
            'plazo'=>(int)$row['plazo'],
            'idtipodocumento'=>$row['idtipodocumento'],
            'numerofacturacion'=>$row['numerofacturacion'],
            'razonsocial'=>$row['razonsocial'],
            'direcciones'=>json_decode($row["direcciones"], true),
            'correosfacturacion'=> json_decode($row["correosfacturacion"], true)
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'entidades' => $entidades
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/entidades/cliente', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $clientes=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_inter_company;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_inter_company (idcliente INT, inter_company TEXT);");
    $conexion->query("INSERT INTO tmp_inter_company (idcliente, inter_company) 
        SELECT
        idcliente,
        GROUP_CONCAT(CONCAT('\"',md5(idcliente_intercompany),'\"')) as intercompany
        FROM (
        SELECT
        DISTINCT
        idcliente_1 as idcliente,
        idcliente_2 as idcliente_intercompany
        FROM
        t_inter_company
        UNION
        SELECT
        DISTINCT
        idcliente_2 as idcliente,
        idcliente_1 as idcliente_intercompany
        FROM
        t_inter_company
        ) as tmp_intercompany
        GROUP BY
        idcliente;");
    $conexion->query("ALTER TABLE tmp_inter_company ADD INDEX idcliente (idcliente);");
    
    $result = $conexion->query("SELECT
        1 as idtipoentidad,
        md5(t_cliente.idcliente) as identidad,
        t_cliente.idcliente as idcliente_num,
        t_cliente.cliente as entidad,
        t_cliente.numeroidentificacion,
        t_tipoentidad.tipoentidad,
        IFNULL(t_cliente.plazo,0) as plazo,
        CONCAT('[',IFNULL(tmp_inter_company.inter_company,''),']') as inter_company
        FROM 
        t_cliente
        LEFT JOIN t_tipoentidad ON 1=t_tipoentidad.idtipoentidad
        LEFT JOIN tmp_inter_company ON t_cliente.idcliente=tmp_inter_company.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        ORDER BY
        t_cliente.cliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $clientes[]=array(
            'idtipoentidad'=>(int)$row['idtipoentidad'],
            'id_num'=>$row['idcliente_num'],
            'id'=>$row['identidad'],
            'identidad'=>$row['idtipoentidad']."-".$row['identidad'],
            'entidad'=>$row['entidad'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'tipoentidad'=>$row['tipoentidad'],
            'plazo'=>(int)$row['plazo'],
            'inter_company'=> json_decode($row['inter_company'])
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'clientes' => $clientes
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);


$app->get('/entidades/clientes', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $clientes=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_clientedireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_clientedireccion (idcliente INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_clientedireccion (idcliente, direcciones)
        SELECT
        t_clientedireccion.idcliente,
        GROUP_CONCAT(CONCAT('{\"identidaddireccion\": ',t_clientedireccion.idclientedireccion,', \"direccion\": \"',IFNULL(t_clientedireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_clientedireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_clientedireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_clientedireccion
        LEFT JOIN t_cliente ON t_clientedireccion.idcliente=t_cliente.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        GROUP BY
        t_clientedireccion.idcliente;");
    $conexion->query("ALTER TABLE tmp_clientedireccion ADD INDEX idcliente (idcliente);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_diasvencimiento;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_diasvencimiento (idcliente INT, diasvencimiento TEXT);");
    $conexion->query("INSERT INTO tmp_diasvencimiento (idcliente, diasvencimiento)
        select
        t_clientediasvencimiento.idcliente,
        GROUP_CONCAT(CONCAT('{\"idclientediasvencimiento\": ',t_clientediasvencimiento.idclientediasvencimiento,', \"rubro_producto\": \"',t_clientediasvencimiento.rubro_producto,'\", \"diasvencimiento\": ',t_clientediasvencimiento.diasvencimiento,'}') SEPARATOR ',') as diasvencimiento
        FROM
        t_clientediasvencimiento
        LEFT JOIN t_cliente ON t_clientediasvencimiento.idcliente=t_cliente.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        GROUP BY
        t_clientediasvencimiento.idcliente;");
    $conexion->query("ALTER TABLE tmp_diasvencimiento ADD INDEX idcliente (idcliente);");

    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_correosfacturacion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_correosfacturacion (idcliente INT, correosfacturacion TEXT);");
    $conexion->query("INSERT INTO tmp_correosfacturacion (idcliente, correosfacturacion)
        select
        t_clientecorreofacturacion.idcliente,
        GROUP_CONCAT(CONCAT('{\"idclientecorreofacturacion\": ',t_clientecorreofacturacion.idclientecorreofacturacion,', \"correo\": \"',t_clientecorreofacturacion.correo,'\"}') SEPARATOR ',') as correosfacturacion
        FROM
        t_clientecorreofacturacion
        LEFT JOIN t_cliente ON t_clientecorreofacturacion.idcliente=t_cliente.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        GROUP BY
        idcliente;");
    $conexion->query("ALTER TABLE tmp_correosfacturacion ADD INDEX idcliente (idcliente);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_gestionlogistica;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_gestionlogistica (idcliente INT, gestionlogistica TEXT);");
    $conexion->query("INSERT INTO tmp_gestionlogistica (idcliente, gestionlogistica)
        select 
        t_clientegestionlogistica.idcliente,
        GROUP_CONCAT(CONCAT('{\"idclientegestionlogistica\": ',t_clientegestionlogistica.idclientegestionlogistica,', \"importacion_exportacion\": ',t_clientegestionlogistica.importacion_exportacion,', \"idmediotransporte\": ',t_clientegestionlogistica.idmediotransporte,', \"mediotransporte\": \"',t_mediotransporte.mediotransporte,'\", \"idtipocarga\": ',t_clientegestionlogistica.idtipocarga,', \"tipocarga\": \"',t_tipocarga.tipocarga,'\", \"idaduana\": ',t_clientegestionlogistica.idaduana,', \"aduana\": \"',t_aduana.aduana,'\", \"iddestino\": ',t_clientegestionlogistica.iddestino,', \"destino\": \"',t_ciudad.ciudad,'\", \"idtemperatura\": ',t_clientegestionlogistica.idtemperatura,', \"temperatura\": \"',t_temperatura.temperatura,'\", \"idhorario\": ',t_clientegestionlogistica.idhorario,', \"horario\": \"',t_horario.horario,'\", \"volumen\": ',t_clientegestionlogistica.volumen,', \"peso_desde\": ',t_clientegestionlogistica.peso_desde,', \"peso_hasta\": ',t_clientegestionlogistica.peso_hasta,', \"cantidad_pallets\": ',t_clientegestionlogistica.cantidad_pallets,', \"monto_fijo\": ',IFNULL(t_clientegestionlogistica.monto_fijo,0),', \"monto_por_peso\": ',t_clientegestionlogistica.monto_por_peso,'}') ORDER BY idclientegestionlogistica SEPARATOR ',') as gestionlogistica
        FROM
        t_clientegestionlogistica
        LEFT JOIN t_mediotransporte ON t_clientegestionlogistica.idmediotransporte=t_mediotransporte.idmediotransporte
        LEFT JOIN t_tipocarga ON t_clientegestionlogistica.idtipocarga=t_tipocarga.idtipocarga
        LEFT JOIN t_aduana ON t_clientegestionlogistica.idaduana=t_aduana.idaduana
        LEFT JOIN t_ciudad ON t_clientegestionlogistica.iddestino=t_ciudad.idciudad
        LEFT JOIN t_temperatura ON t_clientegestionlogistica.idtemperatura=t_temperatura.idtemperatura
        LEFT JOIN t_horario ON t_clientegestionlogistica.idhorario=t_horario.idhorario
        LEFT JOIN t_cliente ON t_clientegestionlogistica.idcliente=t_cliente.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        GROUP BY
        t_clientegestionlogistica.idcliente;");
    $conexion->query("ALTER TABLE tmp_gestionlogistica ADD INDEX idcliente (idcliente);");
    
    
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_serviciologistico;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_serviciologistico (idcliente INT, serviciologistico TEXT);");
    $conexion->query("INSERT INTO tmp_serviciologistico (idcliente, serviciologistico)
        select
        t_clienteserviciologistico.idcliente,
        GROUP_CONCAT(CONCAT('{\"idclienteserviciologistico\": ',t_clienteserviciologistico.idclienteserviciologistico,', \"idconcepto\": ',t_clienteserviciologistico.idconcepto,', \"concepto\": \"',t_concepto.concepto,'\", \"monto\": ',t_clienteserviciologistico.monto,', \"iddivisa\": ',t_clienteserviciologistico.iddivisa,', \"divisa\": \"',t_divisa.codigo,'\", \"montofijo\": ',t_clienteserviciologistico.montofijo,'}') SEPARATOR ',') as serviciologistico
        FROM
        t_clienteserviciologistico
        LEFT JOIN t_concepto ON t_clienteserviciologistico.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_divisa ON t_clienteserviciologistico.iddivisa=t_divisa.iddivisa
        LEFT JOIN t_cliente ON t_clienteserviciologistico.idcliente=t_cliente.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        GROUP BY
        t_clienteserviciologistico.idcliente;");
    $conexion->query("ALTER TABLE tmp_serviciologistico ADD INDEX idcliente (idcliente);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_metodotimbrado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_metodotimbrado (idcliente INT, metodotimbrado TEXT);");
    $conexion->query("INSERT INTO tmp_metodotimbrado (idcliente, metodotimbrado)
        select
        t_cleintemetodotimbrado.idcliente,
        GROUP_CONCAT(CONCAT('{\"idcleintemetodotimbrado\": ',t_cleintemetodotimbrado.idcleintemetodotimbrado,', \"metodotimbrado\": \"',t_cleintemetodotimbrado.metodotimbrado,'\", \"monto\": ',t_cleintemetodotimbrado.monto,', \"iddivisa\": ',t_cleintemetodotimbrado.iddivisa,', \"divisa\": \"',t_divisa.codigo,'\"}') SEPARATOR ',') as metodotimbrado
        FROM
        t_cleintemetodotimbrado
        LEFT JOIN t_divisa ON t_cleintemetodotimbrado.iddivisa=t_divisa.iddivisa
        GROUP BY
        t_cleintemetodotimbrado.idcliente;");
    $conexion->query("ALTER TABLE tmp_metodotimbrado ADD INDEX idcliente (idcliente);");
    
    $result = $conexion->query("SELECT
        t_cliente.idcliente,
        t_cliente.cliente,
        t_cliente.numeroidentificacion,
        t_cliente.direccion,
        t_cliente.telefono,
        t_cliente.fax,
        t_cliente.web,
        t_cliente.email,
        t_cliente.nombrecontacto,
        t_cliente.representante_legal,
        t_cliente.telefono_representante,
        t_cliente.email_representante,
        t_cliente.idtipodocumento,
        t_cliente.numerofacturacion,
        t_cliente.razonsocial,
        IFNULL(t_cliente.username,'') as username,
        IFNULL(t_cliente.contrasena,'') as contrasena,
        t_cliente.numerocuenta,
        IFNULL(t_cliente.plazo,0) as plazo,
        IFNULL(t_cliente.id_OVP,0) as id_OVP,
        t_cliente.idtipoliquidacion,
	t_cliente.monto_fee_mensual,
        t_cliente.tarifa_adicional,
        t_cliente.descarguio_adicional,
        t_cliente.inbound,
        t_cliente.outbound,
        t_cliente.servicios_administrativos,
        t_cliente.servicio_nocturno,
        t_cliente.servicio_fin_semana,
        t_cliente.estibadores,
        t_cliente.posiciones_fee,
        t_cliente.alto,
        t_cliente.ancho,
        t_cliente.largo,
        t_cliente.alto_adicional,
        t_cliente.ancho_adicional,
        t_cliente.largo_adicional,
        CONCAT('[',IFNULL(tmp_clientedireccion.direcciones,''),']') as direcciones,
        CONCAT('[',IFNULL(tmp_diasvencimiento.diasvencimiento,''),']') as diasvencimiento,
        CONCAT('[',IFNULL(tmp_correosfacturacion.correosfacturacion,''),']') as correosfacturacion,
        CONCAT('[',IFNULL(tmp_gestionlogistica.gestionlogistica,''),']') as gestionlogistica,
        CONCAT('[',IFNULL(tmp_serviciologistico.serviciologistico,''),']') as serviciologistico,
        CONCAT('[',IFNULL(tmp_metodotimbrado.metodotimbrado,''),']') as metodotimbrado
        FROM 
        t_cliente
        LEFT JOIN tmp_clientedireccion ON t_cliente.idcliente=tmp_clientedireccion.idcliente
        LEFT JOIN tmp_diasvencimiento ON t_cliente.idcliente=tmp_diasvencimiento.idcliente
        LEFT JOIN tmp_correosfacturacion ON t_cliente.idcliente=tmp_correosfacturacion.idcliente
        LEFT JOIN tmp_gestionlogistica ON t_cliente.idcliente=tmp_gestionlogistica.idcliente
        LEFT JOIN tmp_serviciologistico ON t_cliente.idcliente=tmp_serviciologistico.idcliente
        LEFT JOIN tmp_metodotimbrado ON t_cliente.idcliente=tmp_metodotimbrado.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        ORDER BY
        t_cliente.cliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        /*
        $direcciones=[];
        $resultdireccion = $conexion->query("SELECT
            idclientedireccion,
            direccion,
            ciudad,
            idpais,
            nombrecontacto,
            email
            from
            t_clientedireccion
            WHERE idcliente=".$row["idcliente"].";");
        while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC)){
            $direcciones[]=array(
                'idclientedireccion'=>(int)$rowdireccion['idclientedireccion'],
                'direccion'=>$rowdireccion['direccion'],
                'ciudad'=>$rowdireccion['ciudad'],
                'pais'=>$rowdireccion['idpais'],
                'nombrecontacto'=>$rowdireccion['nombrecontacto'],
                'email'=>$rowdireccion['email']
            );
        }
        */
        /*
        $diasvencimiento=[];
        $resultdiasvencimiento = $conexion->query("select
            idclientediasvencimiento,
            rubro_producto,
            diasvencimiento
            FROM
            t_clientediasvencimiento
            WHERE
            idcliente=".$row["idcliente"].";");
        while ($rowdiasvencimiento =  $resultdiasvencimiento ->fetch(PDO::FETCH_ASSOC)){
            $diasvencimiento[]=array(
                'idclientediasvencimiento'=>(int)$rowdiasvencimiento['idclientediasvencimiento'],
                'rubro_producto'=>$rowdiasvencimiento['rubro_producto'],
                'diasvencimiento'=>(int)$rowdiasvencimiento['diasvencimiento']
            );
        }
        */
        
        $clientes[]=array(
            'idcliente'=>(int)$row['idcliente'],
            'cliente'=>$row['cliente'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'direccion'=>$row['direccion'],
            'telefono'=>$row['telefono'],
            'fax'=>$row['fax'],
            'web'=>$row['web'],
            'email'=>$row['email'],
            'nombrecontacto'=>$row['nombrecontacto'],
            'representante_legal'=>$row['representante_legal'],
            'telefono_representante'=>$row['telefono_representante'],
            'email_representante'=>$row['email_representante'],
            'idtipodocumento'=>$row['idtipodocumento'],
            'numerofacturacion'=>$row['numerofacturacion'],
            'razonsocial'=>$row['razonsocial'],
            'username'=>$row['username'],
            'contrasena'=>$row['contrasena'],
            'numerocuenta'=>$row['numerocuenta'],
            'plazo'=>(int)$row['plazo'],
            'id_OVP'=>(int)$row['id_OVP'],
            'idtipoliquidacion'=>$row['idtipoliquidacion'],
            'monto_fee_mensual'=>(float)$row['monto_fee_mensual'],
            'tarifa_adicional'=>(float)$row['tarifa_adicional'],
            'descarguio_adicional'=>(float)$row['descarguio_adicional'],
            'inbound'=>(float)$row['inbound'],
            'outbound'=>(float)$row['outbound'],
            'servicios_administrativos'=>(float)$row['servicios_administrativos'],
            'servicio_nocturno'=>(float)$row['servicio_nocturno'],
            'servicio_fin_semana'=>(float)$row['servicio_fin_semana'],
            'estibadores'=>(float)$row['estibadores'],
            'posiciones_fee'=>(float)$row['posiciones_fee'],
            'alto'=>(float)$row['alto'],
            'ancho'=>(float)$row['ancho'],
            'largo'=>(float)$row['largo'],
            'alto_adicional'=>(float)$row['alto_adicional'],
            'ancho_adicional'=>(float)$row['ancho_adicional'],
            'largo_adicional'=>(float)$row['largo_adicional'],
            'direcciones'=>json_decode($row["direcciones"], true),
            'diasvencimiento'=>json_decode($row["diasvencimiento"], true),
            'correosfacturacion'=>json_decode($row["correosfacturacion"], true),
            'gestionlogistica'=> json_decode($row["gestionlogistica"],true),
            'serviciologistico'=> json_decode($row["serviciologistico"],true),
            'metodotimbrado'=> json_decode($row["metodotimbrado"],true)
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'clientes' => $clientes
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/entidades/clientes/verificarusername/{idcliente}/{username}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $username = $args['username'];
    
    $existe=false;
    $result = $conexion->query("SELECT
        idcliente
        FROM 
        t_cliente
        WHERE
        username='$username' AND idcliente<>$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $existe=true;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'existe' => $existe
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/entidades/clientes/no-conf-no-considerar/{idcliente}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];

    $no_considerar=array();
    $result = $conexion->query("SELECT
        idno_conf
        FROM 
        t_cliente_no_conf_no_considerar
        WHERE
        md5(idcliente)='$idcliente';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        array_push($no_considerar, (int)$row['idno_conf']);
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'no_considerar' => $no_considerar
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/entidades/clientes', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $cliente = $params['cliente'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $direccion = $params['direccion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $web = $params['web'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $representante_legal = $params['representante_legal'] ?? '';
            $telefono_representante = $params['telefono_representante'] ?? '';
            $email_representante = $params['email_representante'] ?? '';
            $idtipodocumento = $params['idtipodocumento'] ?? '';
            $numerofacturacion = $params['numerofacturacion'] ?? '';
            $razonsocial = $params['razonsocial'] ?? '';
            $username = $params['username'] ?? '';
            $contrasena = $params['contrasena'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVP = $params['id_OVP'] ?? 0;
            $idtipoliquidacion = $params['idtipoliquidacion'] ?? 0;

            $monto_fee_mensual = $params['monto_fee_mensual'] ?? 0;
            $tarifa_adicional = $params['tarifa_adicional'] ?? 0;
            $descarguio_adicional = $params['descarguio_adicional'] ?? 0;
            $inbound = $params['inbound'] ?? 0;
            $outbound = $params['outbound'] ?? 0;
            $servicios_administrativos = $params['servicios_administrativos'] ?? 0;
            $servicio_nocturno = $params['servicio_nocturno'] ?? 0;
            $servicio_fin_semana = $params['servicio_fin_semana'] ?? 0;
            $estibadores = $params['estibadores'] ?? 0;
            $posiciones_fee = $params['posiciones_fee'] ?? 0;
            $alto = $params['alto'] ?? 0;
            $ancho = $params['ancho'] ?? 0;
            $largo = $params['largo'] ?? 0;
            $alto_adicional = $params['alto_adicional'] ?? 0;
            $ancho_adicional = $params['ancho_adicional'] ?? 0;
            $largo_adicional = $params['largo_adicional'] ?? 0;

            $direcciones = $params['direcciones'] ?? [];
            $diasvencimiento = $params['diasvencimiento'] ?? [];
            $correosfacturacion = $params['correosfacturacion'] ?? [];
            $serviciologistico = $params['serviciologistico'] ?? [];
            $gestionlogistica = $params['gestionlogistica'] ?? [];
            $metodotimbrado = $params['metodotimbrado'] ?? [];
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

        if ($continuar && trim($cliente) === '') {
            $mensaje = 'No se recibió el cliente';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($diasvencimiento)) {
            $mensaje = 'Los días de vencimiento recibidos no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($correosfacturacion)) {
            $mensaje = 'Los correos de facturación recibidos no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($serviciologistico)) {
            $mensaje = 'Los servicios logísticos recibidos no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($gestionlogistica)) {
            $mensaje = 'La gestión logística recibida no tiene un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($metodotimbrado)) {
            $mensaje = 'Los métodos de timbrado recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar cliente
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryCliente = "
                INSERT INTO t_cliente (
                    idempresa,
                    cliente,
                    numeroidentificacion,
                    direccion,
                    telefono,
                    fax,
                    web,
                    email,
                    nombrecontacto,
                    representante_legal,
                    telefono_representante,
                    email_representante,
                    idtipodocumento,
                    numerofacturacion,
                    razonsocial,
                    username,
                    contrasena,
                    numerocuenta,
                    plazo,
                    id_OVP,
                    idtipoliquidacion,
                    monto_fee_mensual,
                    tarifa_adicional,
                    descarguio_adicional,
                    inbound,
                    outbound,
                    servicios_administrativos,
                    servicio_nocturno,
                    servicio_fin_semana,
                    estibadores,
                    posiciones_fee,
                    alto,
                    ancho,
                    largo,
                    alto_adicional,
                    ancho_adicional,
                    largo_adicional
                ) VALUES (
                    :idempresa,
                    :cliente,
                    :numeroidentificacion,
                    :direccion,
                    :telefono,
                    :fax,
                    :web,
                    :email,
                    :nombrecontacto,
                    :representante_legal,
                    :telefono_representante,
                    :email_representante,
                    :idtipodocumento,
                    :numerofacturacion,
                    :razonsocial,
                    :username,
                    :contrasena,
                    :numerocuenta,
                    :plazo,
                    :id_OVP,
                    :idtipoliquidacion,
                    :monto_fee_mensual,
                    :tarifa_adicional,
                    :descarguio_adicional,
                    :inbound,
                    :outbound,
                    :servicios_administrativos,
                    :servicio_nocturno,
                    :servicio_fin_semana,
                    :estibadores,
                    :posiciones_fee,
                    :alto,
                    :ancho,
                    :largo,
                    :alto_adicional,
                    :ancho_adicional,
                    :largo_adicional
                )
            ";

            $stmtCliente = $conexion->prepare($queryCliente);

            $resultCliente = $stmtCliente->execute([
                ':idempresa' => $idempresa,
                ':cliente' => $cliente,
                ':numeroidentificacion' => $numeroidentificacion,
                ':direccion' => $direccion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':web' => $web,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':representante_legal' => $representante_legal,
                ':telefono_representante' => $telefono_representante,
                ':email_representante' => $email_representante,
                ':idtipodocumento' => $idtipodocumento,
                ':numerofacturacion' => $numerofacturacion,
                ':razonsocial' => $razonsocial,
                ':username' => $username,
                ':contrasena' => $contrasena,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVP' => $id_OVP,
                ':idtipoliquidacion' => $idtipoliquidacion,
                ':monto_fee_mensual' => $monto_fee_mensual,
                ':tarifa_adicional' => $tarifa_adicional,
                ':descarguio_adicional' => $descarguio_adicional,
                ':inbound' => $inbound,
                ':outbound' => $outbound,
                ':servicios_administrativos' => $servicios_administrativos,
                ':servicio_nocturno' => $servicio_nocturno,
                ':servicio_fin_semana' => $servicio_fin_semana,
                ':estibadores' => $estibadores,
                ':posiciones_fee' => $posiciones_fee,
                ':alto' => $alto,
                ':ancho' => $ancho,
                ':largo' => $largo,
                ':alto_adicional' => $alto_adicional,
                ':ancho_adicional' => $ancho_adicional,
                ':largo_adicional' => $largo_adicional
            ]);

            if (!$resultCliente) {
                $mensaje = 'No se pudo registrar el cliente';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idclienteNuevo = (int)$conexion->lastInsertId();

                if ($idclienteNuevo <= 0) {
                    $mensaje = 'No se pudo obtener el cliente generado';
                    $continuar = false;

                    if ($conexion->inTransaction()) {
                        $conexion->rollBack();
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar direcciones
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccion = "
                INSERT INTO t_clientedireccion (
                    idcliente,
                    direccion,
                    ciudad,
                    idpais,
                    nombrecontacto,
                    email
                ) VALUES (
                    :idcliente,
                    :direccion,
                    :ciudad,
                    :idpais,
                    :nombrecontacto,
                    :email
                )
            ";

            $stmtDireccion = $conexion->prepare($queryDireccion);

            foreach ($direcciones as $item) {

                $resultDireccion = $stmtDireccion->execute([
                    ':idcliente' => $idclienteNuevo,
                    ':direccion' => $item["direccion"] ?? '',
                    ':ciudad' => $item["ciudad"] ?? '',
                    ':idpais' => $item["pais"] ?? '',
                    ':nombrecontacto' => $item["nombrecontacto"] ?? '',
                    ':email' => $item["email"] ?? ''
                ]);

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo registrar una dirección del cliente';
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
        | Insertar días de vencimiento
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDias = "
                INSERT INTO t_clientediasvencimiento (
                    idcliente,
                    rubro_producto,
                    diasvencimiento
                ) VALUES (
                    :idcliente,
                    :rubro_producto,
                    :diasvencimiento
                )
            ";

            $stmtDias = $conexion->prepare($queryDias);

            foreach ($diasvencimiento as $item) {

                $resultDias = $stmtDias->execute([
                    ':idcliente' => $idclienteNuevo,
                    ':rubro_producto' => $item["rubro_producto"] ?? '',
                    ':diasvencimiento' => $item["diasvencimiento"] ?? ''
                ]);

                if (!$resultDias) {
                    $mensaje = 'No se pudo registrar un día de vencimiento';
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
        | Insertar correos de facturación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryCorreo = "
                INSERT INTO t_clientecorreofacturacion (
                    idcliente,
                    correo
                ) VALUES (
                    :idcliente,
                    :correo
                )
            ";

            $stmtCorreo = $conexion->prepare($queryCorreo);

            foreach ($correosfacturacion as $item) {

                $correo = $item["correo"] ?? '';

                if (trim($correo) === '') {
                    continue;
                }

                $resultCorreo = $stmtCorreo->execute([
                    ':idcliente' => $idclienteNuevo,
                    ':correo' => $correo
                ]);

                if (!$resultCorreo) {
                    $mensaje = 'No se pudo registrar un correo de facturación';
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
        | Insertar servicios logísticos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryServicio = "
                INSERT INTO t_clienteserviciologistico (
                    idcliente,
                    idconcepto,
                    monto,
                    iddivisa,
                    montofijo
                ) VALUES (
                    :idcliente,
                    :idconcepto,
                    :monto,
                    :iddivisa,
                    :montofijo
                )
            ";

            $stmtServicio = $conexion->prepare($queryServicio);

            foreach ($serviciologistico as $item) {

                $montofijog = 0;

                if (
                    (isset($item["montofijo"]) && (int)$item["montofijo"] === 1) ||
                    (isset($item["montofijo"]) && $item["montofijo"] === true)
                ) {
                    $montofijog = 1;
                }

                $resultServicio = $stmtServicio->execute([
                    ':idcliente' => $idclienteNuevo,
                    ':idconcepto' => $item["idconcepto"] ?? null,
                    ':monto' => $item["monto"] ?? 0,
                    ':iddivisa' => $item["iddivisa"] ?? null,
                    ':montofijo' => $montofijog
                ]);

                if (!$resultServicio) {
                    $mensaje = 'No se pudo registrar un servicio logístico';
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
        | Insertar gestión logística
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryGestion = "
                INSERT INTO t_clientegestionlogistica (
                    idcliente,
                    importacion_exportacion,
                    idmediotransporte,
                    idtipocarga,
                    idaduana,
                    iddestino,
                    idtemperatura,
                    idhorario,
                    volumen,
                    peso_desde,
                    peso_hasta,
                    cantidad_pallets,
                    monto_fijo,
                    monto_por_peso
                ) VALUES (
                    :idcliente,
                    :importacion_exportacion,
                    :idmediotransporte,
                    :idtipocarga,
                    :idaduana,
                    :iddestino,
                    :idtemperatura,
                    :idhorario,
                    :volumen,
                    :peso_desde,
                    :peso_hasta,
                    :cantidad_pallets,
                    :monto_fijo,
                    :monto_por_peso
                )
            ";

            $stmtGestion = $conexion->prepare($queryGestion);

            foreach ($gestionlogistica as $item) {

                $resultGestion = $stmtGestion->execute([
                    ':idcliente' => $idclienteNuevo,
                    ':importacion_exportacion' => $item['importacion_exportacion'] ?? null,
                    ':idmediotransporte' => $item['idmediotransporte'] ?? null,
                    ':idtipocarga' => $item['idtipocarga'] ?? null,
                    ':idaduana' => $item['idaduana'] ?? null,
                    ':iddestino' => $item['iddestino'] ?? null,
                    ':idtemperatura' => $item['idtemperatura'] ?? null,
                    ':idhorario' => $item['idhorario'] ?? null,
                    ':volumen' => $item['volumen'] ?? '',
                    ':peso_desde' => $item['peso_desde'] ?? '',
                    ':peso_hasta' => $item['peso_hasta'] ?? '',
                    ':cantidad_pallets' => $item['cantidad_pallets'] ?? '',
                    ':monto_fijo' => $item['monto_fijo'] ?? '',
                    ':monto_por_peso' => $item['monto_por_peso'] ?? ''
                ]);

                if (!$resultGestion) {
                    $mensaje = 'No se pudo registrar una gestión logística';
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
        | Insertar métodos de timbrado
        |--------------------------------------------------------------------------
        | Se mantiene el nombre original de la tabla:
        | t_cleintemetodotimbrado
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryMetodo = "
                INSERT INTO t_cleintemetodotimbrado (
                    idcliente,
                    metodotimbrado,
                    monto,
                    iddivisa
                ) VALUES (
                    :idcliente,
                    :metodotimbrado,
                    :monto,
                    :iddivisa
                )
            ";

            $stmtMetodo = $conexion->prepare($queryMetodo);

            foreach ($metodotimbrado as $item) {

                $resultMetodo = $stmtMetodo->execute([
                    ':idcliente' => $idclienteNuevo,
                    ':metodotimbrado' => $item["metodotimbrado"] ?? '',
                    ':monto' => $item["monto"] ?? 0,
                    ':iddivisa' => $item["iddivisa"] ?? null
                ]);

                if (!$resultMetodo) {
                    $mensaje = 'No se pudo registrar un método de timbrado';
                    $continuar = false;
                    break;
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        if ($continuar) {
            $conexion->commit();

            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(2, 2))->add($verifyToken);

$app->put('/entidades/clientes', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $idcliente = $params['idcliente'] ?? null;
            $cliente = $params['cliente'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $direccion = $params['direccion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $web = $params['web'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $representante_legal = $params['representante_legal'] ?? '';
            $telefono_representante = $params['telefono_representante'] ?? '';
            $email_representante = $params['email_representante'] ?? '';
            $idtipodocumento = $params['idtipodocumento'] ?? '';
            $numerofacturacion = $params['numerofacturacion'] ?? '';
            $razonsocial = $params['razonsocial'] ?? '';
            $username = $params['username'] ?? '';
            $contrasena = $params['contrasena'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVP = $params['id_OVP'] ?? 0;
            $idtipoliquidacion = $params['idtipoliquidacion'] ?? 0;

            $monto_fee_mensual = ((int)$idtipoliquidacion === 0) ? 0 : ($params['monto_fee_mensual'] ?? 0);
            $tarifa_adicional = ((int)$idtipoliquidacion === 0) ? 0 : ($params['tarifa_adicional'] ?? 0);
            $descarguio_adicional = ((int)$idtipoliquidacion === 0) ? 0 : ($params['descarguio_adicional'] ?? 0);
            $inbound = ((int)$idtipoliquidacion === 0) ? 0 : ($params['inbound'] ?? 0);
            $outbound = ((int)$idtipoliquidacion === 0) ? 0 : ($params['outbound'] ?? 0);
            $servicios_administrativos = ((int)$idtipoliquidacion === 0) ? 0 : ($params['servicios_administrativos'] ?? 0);
            $servicio_nocturno = ((int)$idtipoliquidacion === 0) ? 0 : ($params['servicio_nocturno'] ?? 0);
            $servicio_fin_semana = ((int)$idtipoliquidacion === 0) ? 0 : ($params['servicio_fin_semana'] ?? 0);
            $estibadores = ((int)$idtipoliquidacion === 0) ? 0 : ($params['estibadores'] ?? 0);
            $posiciones_fee = ((int)$idtipoliquidacion === 0) ? 0 : ($params['posiciones_fee'] ?? 0);
            $alto = ((int)$idtipoliquidacion === 0) ? 0 : ($params['alto'] ?? 0);
            $ancho = ((int)$idtipoliquidacion === 0) ? 0 : ($params['ancho'] ?? 0);
            $largo = ((int)$idtipoliquidacion === 0) ? 0 : ($params['largo'] ?? 0);
            $alto_adicional = ((int)$idtipoliquidacion === 0) ? 0 : ($params['alto_adicional'] ?? 0);
            $ancho_adicional = ((int)$idtipoliquidacion === 0) ? 0 : ($params['ancho_adicional'] ?? 0);
            $largo_adicional = ((int)$idtipoliquidacion === 0) ? 0 : ($params['largo_adicional'] ?? 0);

            $direcciones = $params['direcciones'] ?? [];
            $diasvencimiento = $params['diasvencimiento'] ?? [];
            $correosfacturacion = $params['correosfacturacion'] ?? [];
            $serviciologistico = $params['serviciologistico'] ?? [];
            $gestionlogistica = $params['gestionlogistica'] ?? [];
            $metodotimbrado = $params['metodotimbrado'] ?? [];
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

        if ($continuar && empty($idcliente)) {
            $mensaje = 'No se recibió el cliente';
            $continuar = false;
        }

        if ($continuar && trim($cliente) === '') {
            $mensaje = 'No se recibió el nombre del cliente';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($diasvencimiento)) {
            $mensaje = 'Los días de vencimiento recibidos no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($correosfacturacion)) {
            $mensaje = 'Los correos de facturación recibidos no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($serviciologistico)) {
            $mensaje = 'Los servicios logísticos recibidos no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($gestionlogistica)) {
            $mensaje = 'La gestión logística recibida no tiene un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($metodotimbrado)) {
            $mensaje = 'Los métodos de timbrado recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar cliente
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryCliente = "
                UPDATE t_cliente
                SET
                    cliente = :cliente,
                    numeroidentificacion = :numeroidentificacion,
                    direccion = :direccion,
                    telefono = :telefono,
                    fax = :fax,
                    web = :web,
                    email = :email,
                    nombrecontacto = :nombrecontacto,
                    representante_legal = :representante_legal,
                    telefono_representante = :telefono_representante,
                    email_representante = :email_representante,
                    idtipodocumento = :idtipodocumento,
                    numerofacturacion = :numerofacturacion,
                    razonsocial = :razonsocial,
                    username = :username,
                    contrasena = :contrasena,
                    numerocuenta = :numerocuenta,
                    plazo = :plazo,
                    id_OVP = :id_OVP,
                    idtipoliquidacion = :idtipoliquidacion,
                    monto_fee_mensual = :monto_fee_mensual,
                    tarifa_adicional = :tarifa_adicional,
                    descarguio_adicional = :descarguio_adicional,
                    inbound = :inbound,
                    outbound = :outbound,
                    servicios_administrativos = :servicios_administrativos,
                    servicio_nocturno = :servicio_nocturno,
                    servicio_fin_semana = :servicio_fin_semana,
                    estibadores = :estibadores,
                    posiciones_fee = :posiciones_fee,
                    alto = :alto,
                    ancho = :ancho,
                    largo = :largo,
                    alto_adicional = :alto_adicional,
                    ancho_adicional = :ancho_adicional,
                    largo_adicional = :largo_adicional
                WHERE idcliente = :idcliente
                  AND idempresa = :idempresa
            ";

            $stmtCliente = $conexion->prepare($queryCliente);

            $resultCliente = $stmtCliente->execute([
                ':cliente' => $cliente,
                ':numeroidentificacion' => $numeroidentificacion,
                ':direccion' => $direccion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':web' => $web,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':representante_legal' => $representante_legal,
                ':telefono_representante' => $telefono_representante,
                ':email_representante' => $email_representante,
                ':idtipodocumento' => $idtipodocumento,
                ':numerofacturacion' => $numerofacturacion,
                ':razonsocial' => $razonsocial,
                ':username' => $username,
                ':contrasena' => $contrasena,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVP' => $id_OVP,
                ':idtipoliquidacion' => $idtipoliquidacion,
                ':monto_fee_mensual' => $monto_fee_mensual,
                ':tarifa_adicional' => $tarifa_adicional,
                ':descarguio_adicional' => $descarguio_adicional,
                ':inbound' => $inbound,
                ':outbound' => $outbound,
                ':servicios_administrativos' => $servicios_administrativos,
                ':servicio_nocturno' => $servicio_nocturno,
                ':servicio_fin_semana' => $servicio_fin_semana,
                ':estibadores' => $estibadores,
                ':posiciones_fee' => $posiciones_fee,
                ':alto' => $alto,
                ':ancho' => $ancho,
                ':largo' => $largo,
                ':alto_adicional' => $alto_adicional,
                ':ancho_adicional' => $ancho_adicional,
                ':largo_adicional' => $largo_adicional,
                ':idcliente' => $idcliente,
                ':idempresa' => $idempresa
            ]);

            if (!$resultCliente) {
                $mensaje = 'No se pudo actualizar el cliente';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Direcciones
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsRecibidos = [];

            $stmtUpdate = $conexion->prepare("
                UPDATE t_clientedireccion
                SET
                    direccion = :direccion,
                    ciudad = :ciudad,
                    idpais = :idpais,
                    nombrecontacto = :nombrecontacto,
                    email = :email
                WHERE idclientedireccion = :idclientedireccion
                  AND idcliente = :idcliente
            ");

            $stmtInsert = $conexion->prepare("
                INSERT INTO t_clientedireccion (
                    idcliente,
                    direccion,
                    ciudad,
                    idpais,
                    nombrecontacto,
                    email
                ) VALUES (
                    :idcliente,
                    :direccion,
                    :ciudad,
                    :idpais,
                    :nombrecontacto,
                    :email
                )
            ");

            foreach ($direcciones as $item) {

                $idDetalle = $item["idclientedireccion"] ?? 0;

                $data = [
                    ':idcliente' => $idcliente,
                    ':direccion' => $item["direccion"] ?? '',
                    ':ciudad' => $item["ciudad"] ?? '',
                    ':idpais' => $item["pais"] ?? '',
                    ':nombrecontacto' => $item["nombrecontacto"] ?? '',
                    ':email' => $item["email"] ?? ''
                ];

                if ((int)$idDetalle > 0) {
                    $idsRecibidos[] = (int)$idDetalle;
                    $data[':idclientedireccion'] = $idDetalle;
                    $ok = $stmtUpdate->execute($data);
                } else {
                    $ok = $stmtInsert->execute($data);

                    if ($ok) {
                        $idsRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$ok) {
                    $mensaje = 'No se pudo guardar una dirección del cliente';
                    $continuar = false;
                    break;
                }
            }

            if ($continuar) {
                $stmtActuales = $conexion->prepare("
                    SELECT idclientedireccion
                    FROM t_clientedireccion
                    WHERE idcliente = :idcliente
                ");

                $stmtActuales->execute([
                    ':idcliente' => $idcliente
                ]);

                $stmtDelete = $conexion->prepare("
                    DELETE FROM t_clientedireccion
                    WHERE idclientedireccion = :idclientedireccion
                      AND idcliente = :idcliente
                ");

                while ($row = $stmtActuales->fetch(PDO::FETCH_ASSOC)) {
                    $idActual = (int)$row['idclientedireccion'];

                    if (!in_array($idActual, $idsRecibidos)) {
                        $ok = $stmtDelete->execute([
                            ':idclientedireccion' => $idActual,
                            ':idcliente' => $idcliente
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo eliminar una dirección del cliente';
                            $continuar = false;
                            break;
                        }
                    }
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Días de vencimiento
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsRecibidos = [];

            $stmtUpdate = $conexion->prepare("
                UPDATE t_clientediasvencimiento
                SET
                    rubro_producto = :rubro_producto,
                    diasvencimiento = :diasvencimiento
                WHERE idclientediasvencimiento = :idclientediasvencimiento
                  AND idcliente = :idcliente
            ");

            $stmtInsert = $conexion->prepare("
                INSERT INTO t_clientediasvencimiento (
                    idcliente,
                    rubro_producto,
                    diasvencimiento
                ) VALUES (
                    :idcliente,
                    :rubro_producto,
                    :diasvencimiento
                )
            ");

            foreach ($diasvencimiento as $item) {

                $idDetalle = $item["idclientediasvencimiento"] ?? 0;

                $data = [
                    ':idcliente' => $idcliente,
                    ':rubro_producto' => $item["rubro_producto"] ?? '',
                    ':diasvencimiento' => $item["diasvencimiento"] ?? ''
                ];

                if ((int)$idDetalle > 0) {
                    $idsRecibidos[] = (int)$idDetalle;
                    $data[':idclientediasvencimiento'] = $idDetalle;
                    $ok = $stmtUpdate->execute($data);
                } else {
                    $ok = $stmtInsert->execute($data);

                    if ($ok) {
                        $idsRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$ok) {
                    $mensaje = 'No se pudo guardar un día de vencimiento';
                    $continuar = false;
                    break;
                }
            }

            if ($continuar) {
                $stmtActuales = $conexion->prepare("
                    SELECT idclientediasvencimiento
                    FROM t_clientediasvencimiento
                    WHERE idcliente = :idcliente
                ");

                $stmtActuales->execute([
                    ':idcliente' => $idcliente
                ]);

                $stmtDelete = $conexion->prepare("
                    DELETE FROM t_clientediasvencimiento
                    WHERE idclientediasvencimiento = :idclientediasvencimiento
                      AND idcliente = :idcliente
                ");

                while ($row = $stmtActuales->fetch(PDO::FETCH_ASSOC)) {
                    $idActual = (int)$row['idclientediasvencimiento'];

                    if (!in_array($idActual, $idsRecibidos)) {
                        $ok = $stmtDelete->execute([
                            ':idclientediasvencimiento' => $idActual,
                            ':idcliente' => $idcliente
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo eliminar un día de vencimiento';
                            $continuar = false;
                            break;
                        }
                    }
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Métodos de timbrado
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsRecibidos = [];

            $stmtUpdate = $conexion->prepare("
                UPDATE t_cleintemetodotimbrado
                SET
                    metodotimbrado = :metodotimbrado,
                    monto = :monto,
                    iddivisa = :iddivisa
                WHERE idcleintemetodotimbrado = :idcleintemetodotimbrado
                  AND idcliente = :idcliente
            ");

            $stmtInsert = $conexion->prepare("
                INSERT INTO t_cleintemetodotimbrado (
                    idcliente,
                    metodotimbrado,
                    monto,
                    iddivisa
                ) VALUES (
                    :idcliente,
                    :metodotimbrado,
                    :monto,
                    :iddivisa
                )
            ");

            foreach ($metodotimbrado as $item) {

                $idDetalle = $item["idcleintemetodotimbrado"] ?? 0;

                $data = [
                    ':idcliente' => $idcliente,
                    ':metodotimbrado' => $item["metodotimbrado"] ?? '',
                    ':monto' => $item["monto"] ?? 0,
                    ':iddivisa' => $item["iddivisa"] ?? null
                ];

                if ((int)$idDetalle > 0) {
                    $idsRecibidos[] = (int)$idDetalle;
                    $data[':idcleintemetodotimbrado'] = $idDetalle;
                    $ok = $stmtUpdate->execute($data);
                } else {
                    $ok = $stmtInsert->execute($data);

                    if ($ok) {
                        $idsRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$ok) {
                    $mensaje = 'No se pudo guardar un método de timbrado';
                    $continuar = false;
                    break;
                }
            }

            if ($continuar) {
                $stmtActuales = $conexion->prepare("
                    SELECT idcleintemetodotimbrado
                    FROM t_cleintemetodotimbrado
                    WHERE idcliente = :idcliente
                ");

                $stmtActuales->execute([
                    ':idcliente' => $idcliente
                ]);

                $stmtDelete = $conexion->prepare("
                    DELETE FROM t_cleintemetodotimbrado
                    WHERE idcleintemetodotimbrado = :idcleintemetodotimbrado
                      AND idcliente = :idcliente
                ");

                while ($row = $stmtActuales->fetch(PDO::FETCH_ASSOC)) {
                    $idActual = (int)$row['idcleintemetodotimbrado'];

                    if (!in_array($idActual, $idsRecibidos)) {
                        $ok = $stmtDelete->execute([
                            ':idcleintemetodotimbrado' => $idActual,
                            ':idcliente' => $idcliente
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo eliminar un método de timbrado';
                            $continuar = false;
                            break;
                        }
                    }
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Correos de facturación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsRecibidos = [];

            $stmtUpdate = $conexion->prepare("
                UPDATE t_clientecorreofacturacion
                SET correo = :correo
                WHERE idclientecorreofacturacion = :idclientecorreofacturacion
                  AND idcliente = :idcliente
            ");

            $stmtInsert = $conexion->prepare("
                INSERT INTO t_clientecorreofacturacion (
                    idcliente,
                    correo
                ) VALUES (
                    :idcliente,
                    :correo
                )
            ");

            foreach ($correosfacturacion as $item) {

                $idDetalle = $item["idclientecorreofacturacion"] ?? 0;
                $correo = $item["correo"] ?? '';

                if (trim($correo) === '') {
                    continue;
                }

                $data = [
                    ':idcliente' => $idcliente,
                    ':correo' => $correo
                ];

                if ((int)$idDetalle > 0) {
                    $idsRecibidos[] = (int)$idDetalle;
                    $data[':idclientecorreofacturacion'] = $idDetalle;
                    $ok = $stmtUpdate->execute($data);
                } else {
                    $ok = $stmtInsert->execute($data);

                    if ($ok) {
                        $idsRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$ok) {
                    $mensaje = 'No se pudo guardar un correo de facturación';
                    $continuar = false;
                    break;
                }
            }

            if ($continuar) {
                $stmtActuales = $conexion->prepare("
                    SELECT idclientecorreofacturacion
                    FROM t_clientecorreofacturacion
                    WHERE idcliente = :idcliente
                ");

                $stmtActuales->execute([
                    ':idcliente' => $idcliente
                ]);

                $stmtDelete = $conexion->prepare("
                    DELETE FROM t_clientecorreofacturacion
                    WHERE idclientecorreofacturacion = :idclientecorreofacturacion
                      AND idcliente = :idcliente
                ");

                while ($row = $stmtActuales->fetch(PDO::FETCH_ASSOC)) {
                    $idActual = (int)$row['idclientecorreofacturacion'];

                    if (!in_array($idActual, $idsRecibidos)) {
                        $ok = $stmtDelete->execute([
                            ':idclientecorreofacturacion' => $idActual,
                            ':idcliente' => $idcliente
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo eliminar un correo de facturación';
                            $continuar = false;
                            break;
                        }
                    }
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Servicios logísticos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsRecibidos = [];

            $stmtUpdate = $conexion->prepare("
                UPDATE t_clienteserviciologistico
                SET
                    idconcepto = :idconcepto,
                    monto = :monto,
                    iddivisa = :iddivisa,
                    montofijo = :montofijo
                WHERE idclienteserviciologistico = :idclienteserviciologistico
                  AND idcliente = :idcliente
            ");

            $stmtInsert = $conexion->prepare("
                INSERT INTO t_clienteserviciologistico (
                    idcliente,
                    idconcepto,
                    monto,
                    iddivisa,
                    montofijo
                ) VALUES (
                    :idcliente,
                    :idconcepto,
                    :monto,
                    :iddivisa,
                    :montofijo
                )
            ");

            foreach ($serviciologistico as $item) {

                $idDetalle = $item["idclienteserviciologistico"] ?? 0;
                $montofijog = 0;

                if (
                    (isset($item["montofijo"]) && (int)$item["montofijo"] === 1) ||
                    (isset($item["montofijo"]) && $item["montofijo"] === true)
                ) {
                    $montofijog = 1;
                }

                $data = [
                    ':idcliente' => $idcliente,
                    ':idconcepto' => $item["idconcepto"] ?? null,
                    ':monto' => $item["monto"] ?? 0,
                    ':iddivisa' => $item["iddivisa"] ?? null,
                    ':montofijo' => $montofijog
                ];

                if ((int)$idDetalle > 0) {
                    $idsRecibidos[] = (int)$idDetalle;
                    $data[':idclienteserviciologistico'] = $idDetalle;
                    $ok = $stmtUpdate->execute($data);
                } else {
                    $ok = $stmtInsert->execute($data);

                    if ($ok) {
                        $idsRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$ok) {
                    $mensaje = 'No se pudo guardar un servicio logístico';
                    $continuar = false;
                    break;
                }
            }

            if ($continuar) {
                $stmtActuales = $conexion->prepare("
                    SELECT idclienteserviciologistico
                    FROM t_clienteserviciologistico
                    WHERE idcliente = :idcliente
                ");

                $stmtActuales->execute([
                    ':idcliente' => $idcliente
                ]);

                $stmtDelete = $conexion->prepare("
                    DELETE FROM t_clienteserviciologistico
                    WHERE idclienteserviciologistico = :idclienteserviciologistico
                      AND idcliente = :idcliente
                ");

                while ($row = $stmtActuales->fetch(PDO::FETCH_ASSOC)) {
                    $idActual = (int)$row['idclienteserviciologistico'];

                    if (!in_array($idActual, $idsRecibidos)) {
                        $ok = $stmtDelete->execute([
                            ':idclienteserviciologistico' => $idActual,
                            ':idcliente' => $idcliente
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo eliminar un servicio logístico';
                            $continuar = false;
                            break;
                        }
                    }
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Gestión logística
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsRecibidos = [];

            $stmtUpdate = $conexion->prepare("
                UPDATE t_clientegestionlogistica
                SET
                    importacion_exportacion = :importacion_exportacion,
                    idmediotransporte = :idmediotransporte,
                    idtipocarga = :idtipocarga,
                    idaduana = :idaduana,
                    iddestino = :iddestino,
                    idtemperatura = :idtemperatura,
                    idhorario = :idhorario,
                    volumen = :volumen,
                    peso_desde = :peso_desde,
                    peso_hasta = :peso_hasta,
                    cantidad_pallets = :cantidad_pallets,
                    monto_fijo = :monto_fijo,
                    monto_por_peso = :monto_por_peso
                WHERE idclientegestionlogistica = :idclientegestionlogistica
                  AND idcliente = :idcliente
            ");

            $stmtInsert = $conexion->prepare("
                INSERT INTO t_clientegestionlogistica (
                    idcliente,
                    importacion_exportacion,
                    idmediotransporte,
                    idtipocarga,
                    idaduana,
                    iddestino,
                    idtemperatura,
                    idhorario,
                    volumen,
                    peso_desde,
                    peso_hasta,
                    cantidad_pallets,
                    monto_fijo,
                    monto_por_peso
                ) VALUES (
                    :idcliente,
                    :importacion_exportacion,
                    :idmediotransporte,
                    :idtipocarga,
                    :idaduana,
                    :iddestino,
                    :idtemperatura,
                    :idhorario,
                    :volumen,
                    :peso_desde,
                    :peso_hasta,
                    :cantidad_pallets,
                    :monto_fijo,
                    :monto_por_peso
                )
            ");

            foreach ($gestionlogistica as $item) {

                $idDetalle = $item["idclientegestionlogistica"] ?? 0;

                $data = [
                    ':idcliente' => $idcliente,
                    ':importacion_exportacion' => $item['importacion_exportacion'] ?? null,
                    ':idmediotransporte' => $item['idmediotransporte'] ?? null,
                    ':idtipocarga' => $item['idtipocarga'] ?? null,
                    ':idaduana' => $item['idaduana'] ?? null,
                    ':iddestino' => $item['iddestino'] ?? null,
                    ':idtemperatura' => $item['idtemperatura'] ?? null,
                    ':idhorario' => $item['idhorario'] ?? null,
                    ':volumen' => $item['volumen'] ?? '',
                    ':peso_desde' => $item['peso_desde'] ?? '',
                    ':peso_hasta' => $item['peso_hasta'] ?? '',
                    ':cantidad_pallets' => $item['cantidad_pallets'] ?? '',
                    ':monto_fijo' => $item['monto_fijo'] ?? '',
                    ':monto_por_peso' => $item['monto_por_peso'] ?? ''
                ];

                if ((int)$idDetalle > 0) {
                    $idsRecibidos[] = (int)$idDetalle;
                    $data[':idclientegestionlogistica'] = $idDetalle;
                    $ok = $stmtUpdate->execute($data);
                } else {
                    $ok = $stmtInsert->execute($data);

                    if ($ok) {
                        $idsRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$ok) {
                    $mensaje = 'No se pudo guardar una gestión logística';
                    $continuar = false;
                    break;
                }
            }

            if ($continuar) {
                $stmtActuales = $conexion->prepare("
                    SELECT idclientegestionlogistica
                    FROM t_clientegestionlogistica
                    WHERE idcliente = :idcliente
                ");

                $stmtActuales->execute([
                    ':idcliente' => $idcliente
                ]);

                $stmtDelete = $conexion->prepare("
                    DELETE FROM t_clientegestionlogistica
                    WHERE idclientegestionlogistica = :idclientegestionlogistica
                      AND idcliente = :idcliente
                ");

                while ($row = $stmtActuales->fetch(PDO::FETCH_ASSOC)) {
                    $idActual = (int)$row['idclientegestionlogistica'];

                    if (!in_array($idActual, $idsRecibidos)) {
                        $ok = $stmtDelete->execute([
                            ':idclientegestionlogistica' => $idActual,
                            ':idcliente' => $idcliente
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo eliminar una gestión logística';
                            $continuar = false;
                            break;
                        }
                    }
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(2, 2))->add($verifyToken);

$app->get('/entidades/transportistas', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $transportistas=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_transportistadireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_transportistadireccion (idtransportista INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_transportistadireccion (idtransportista, direcciones)
        SELECT
        t_transportistadireccion.idtransportista,
        GROUP_CONCAT(CONCAT('{\"idtransportistadireccion\": ',t_transportistadireccion.idtransportistadireccion,', \"direccion\": \"',IFNULL(t_transportistadireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_transportistadireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_transportistadireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_transportistadireccion
        LEFT JOIN t_transportista ON t_transportistadireccion.idtransportista=t_transportista.idtransportista
        WHERE
        t_transportista.idempresa=$idempresa
        GROUP BY
        t_transportistadireccion.idtransportista;");
    $conexion->query("ALTER TABLE tmp_transportistadireccion ADD INDEX idtransportista (idtransportista);");
    
    
    $result = $conexion->query("SELECT
        t_transportista.idtransportista, 
        t_transportista.transportista, 
        t_transportista.numeroidentificacion, 
        t_transportista.telefono, 
        t_transportista.fax, 
        t_transportista.email, 
        t_transportista.numerocuenta, 
        t_transportista.nombrecontacto, 
        IFNULL(t_transportista.plazo,0) as plazo, 
        IFNULL(t_transportista.id_OVPProv,0) as id_OVPProv,
        CONCAT('[',IFNULL(tmp_transportistadireccion.direcciones,''),']') as direcciones
        FROM 
        t_transportista
        LEFT JOIN tmp_transportistadireccion ON t_transportista.idtransportista=tmp_transportistadireccion.idtransportista
        WHERE
        t_transportista.idempresa=$idempresa
        ORDER BY
        t_transportista.transportista;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $transportistas[]=array(
            'idtransportista'=>(int)$row['idtransportista'],
            'transportista'=>$row['transportista'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'telefono'=>$row['telefono'],
            'fax'=>$row['fax'],
            'email'=>$row['email'],
            'numerocuenta'=>$row['numerocuenta'],
            'nombrecontacto'=>$row['nombrecontacto'],
            'plazo'=>(int)$row['plazo'],
            'id_OVPProv'=>(int)$row['id_OVPProv'],
            'direcciones'=>json_decode($row["direcciones"], true)
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'transportistas' => $transportistas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/entidades/transportistas', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $transportista = $params['transportista'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVPProv = $params['id_OVPProv'] ?? 0;
            $direcciones = $params['direcciones'] ?? [];
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

        if ($continuar && trim($transportista) === '') {
            $mensaje = 'No se recibió el transportista';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar transportista
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryTransportista = "
                INSERT INTO t_transportista (
                    idempresa,
                    transportista,
                    numeroidentificacion,
                    telefono,
                    fax,
                    email,
                    nombrecontacto,
                    numerocuenta,
                    plazo,
                    id_OVPProv
                ) VALUES (
                    :idempresa,
                    :transportista,
                    :numeroidentificacion,
                    :telefono,
                    :fax,
                    :email,
                    :nombrecontacto,
                    :numerocuenta,
                    :plazo,
                    :id_OVPProv
                )
            ";

            $stmtTransportista = $conexion->prepare($queryTransportista);

            $resultTransportista = $stmtTransportista->execute([
                ':idempresa' => $idempresa,
                ':transportista' => $transportista,
                ':numeroidentificacion' => $numeroidentificacion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVPProv' => $id_OVPProv
            ]);

            if (!$resultTransportista) {
                $mensaje = 'No se pudo registrar el transportista';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idtransportistaNuevo = (int)$conexion->lastInsertId();

                if ($idtransportistaNuevo <= 0) {
                    $mensaje = 'No se pudo obtener el transportista generado';
                    $continuar = false;

                    if ($conexion->inTransaction()) {
                        $conexion->rollBack();
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar direcciones
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccion = "
                INSERT INTO t_transportistadireccion (
                    idtransportista,
                    direccion,
                    ciudad,
                    idpais
                ) VALUES (
                    :idtransportista,
                    :direccion,
                    :ciudad,
                    :idpais
                )
            ";

            $stmtDireccion = $conexion->prepare($queryDireccion);

            foreach ($direcciones as $direccionItem) {

                $direccion = $direccionItem["direccion"] ?? '';
                $ciudad = $direccionItem["ciudad"] ?? '';
                $pais = $direccionItem["pais"] ?? '';

                if (trim($direccion) === '' && trim($ciudad) === '' && trim($pais) === '') {
                    continue;
                }

                $resultDireccion = $stmtDireccion->execute([
                    ':idtransportista' => $idtransportistaNuevo,
                    ':direccion' => $direccion,
                    ':ciudad' => $ciudad,
                    ':idpais' => $pais
                ]);

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo registrar una dirección del transportista';
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(3, 2))->add($verifyToken);

$app->put('/entidades/transportistas', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $idtransportista = $params['idtransportista'] ?? null;
            $transportista = $params['transportista'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVPProv = $params['id_OVPProv'] ?? 0;
            $direcciones = $params['direcciones'] ?? [];
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

        if ($continuar && empty($idtransportista)) {
            $mensaje = 'No se recibió el transportista';
            $continuar = false;
        }

        if ($continuar && trim($transportista) === '') {
            $mensaje = 'No se recibió el nombre del transportista';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar transportista
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryTransportista = "
                UPDATE t_transportista
                SET
                    transportista = :transportista,
                    numeroidentificacion = :numeroidentificacion,
                    telefono = :telefono,
                    fax = :fax,
                    email = :email,
                    nombrecontacto = :nombrecontacto,
                    numerocuenta = :numerocuenta,
                    plazo = :plazo,
                    id_OVPProv = :id_OVPProv
                WHERE idtransportista = :idtransportista
                  AND idempresa = :idempresa
            ";

            $stmtTransportista = $conexion->prepare($queryTransportista);

            $resultTransportista = $stmtTransportista->execute([
                ':transportista' => $transportista,
                ':numeroidentificacion' => $numeroidentificacion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVPProv' => $id_OVPProv,
                ':idtransportista' => $idtransportista,
                ':idempresa' => $idempresa
            ]);

            if (!$resultTransportista) {
                $mensaje = 'No se pudo actualizar el transportista';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Direcciones: actualizar / insertar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsDireccionesRecibidas = [];

            $queryUpdateDireccion = "
                UPDATE t_transportistadireccion
                SET
                    direccion = :direccion,
                    ciudad = :ciudad,
                    idpais = :idpais
                WHERE idtransportistadireccion = :idtransportistadireccion
                  AND idtransportista = :idtransportista
            ";

            $stmtUpdateDireccion = $conexion->prepare($queryUpdateDireccion);

            $queryInsertDireccion = "
                INSERT INTO t_transportistadireccion (
                    idtransportista,
                    direccion,
                    ciudad,
                    idpais
                ) VALUES (
                    :idtransportista,
                    :direccion,
                    :ciudad,
                    :idpais
                )
            ";

            $stmtInsertDireccion = $conexion->prepare($queryInsertDireccion);

            foreach ($direcciones as $direccionItem) {

                $idtransportistadireccion = $direccionItem["idtransportistadireccion"] ?? 0;
                $direccion = $direccionItem["direccion"] ?? '';
                $ciudad = $direccionItem["ciudad"] ?? '';
                $pais = $direccionItem["pais"] ?? '';

                if (trim($direccion) === '' && trim($ciudad) === '' && trim($pais) === '') {
                    continue;
                }

                if ((int)$idtransportistadireccion > 0) {

                    $idsDireccionesRecibidas[] = (int)$idtransportistadireccion;

                    $resultDireccion = $stmtUpdateDireccion->execute([
                        ':direccion' => $direccion,
                        ':ciudad' => $ciudad,
                        ':idpais' => $pais,
                        ':idtransportistadireccion' => $idtransportistadireccion,
                        ':idtransportista' => $idtransportista
                    ]);

                } else {

                    $resultDireccion = $stmtInsertDireccion->execute([
                        ':idtransportista' => $idtransportista,
                        ':direccion' => $direccion,
                        ':ciudad' => $ciudad,
                        ':idpais' => $pais
                    ]);

                    /*
                    Importante:
                    Si se inserta una dirección nueva, agregamos su ID
                    para que no sea eliminada en el bloque siguiente.
                    */
                    if ($resultDireccion) {
                        $idsDireccionesRecibidas[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo guardar una dirección del transportista';
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
        | Eliminar direcciones que ya no llegaron
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccionesActuales = "
                SELECT idtransportistadireccion
                FROM t_transportistadireccion
                WHERE idtransportista = :idtransportista
            ";

            $stmtDireccionesActuales = $conexion->prepare($queryDireccionesActuales);
            $stmtDireccionesActuales->execute([
                ':idtransportista' => $idtransportista
            ]);

            $queryDeleteDireccion = "
                DELETE FROM t_transportistadireccion
                WHERE idtransportistadireccion = :idtransportistadireccion
                  AND idtransportista = :idtransportista
            ";

            $stmtDeleteDireccion = $conexion->prepare($queryDeleteDireccion);

            while ($rowDireccion = $stmtDireccionesActuales->fetch(PDO::FETCH_ASSOC)) {

                $idDireccionActual = (int)$rowDireccion['idtransportistadireccion'];

                if (!in_array($idDireccionActual, $idsDireccionesRecibidas)) {

                    $resultDeleteDireccion = $stmtDeleteDireccion->execute([
                        ':idtransportistadireccion' => $idDireccionActual,
                        ':idtransportista' => $idtransportista
                    ]);

                    if (!$resultDeleteDireccion) {
                        $mensaje = 'No se pudo eliminar una dirección del transportista';
                        $continuar = false;
                        break;
                    }
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(3, 2))->add($verifyToken);

$app->get('/entidades/agentes-carga', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $agentescarga=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_agentecargadireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_agentecargadireccion (idagentecarga INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_agentecargadireccion (idagentecarga, direcciones)
        SELECT
        t_agentecargadireccion.idagentecarga,
        GROUP_CONCAT(CONCAT('{\"idagentecargadireccion\": ',t_agentecargadireccion.idagentecargadireccion,', \"direccion\": \"',IFNULL(t_agentecargadireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_agentecargadireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_agentecargadireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_agentecargadireccion
        LEFT JOIN t_agentecarga ON t_agentecargadireccion.idagentecarga=t_agentecarga.idagentecarga
        WHERE
        t_agentecarga.idempresa=$idempresa
        GROUP BY
        t_agentecargadireccion.idagentecarga;");
    $conexion->query("ALTER TABLE tmp_agentecargadireccion ADD INDEX idagentecarga (idagentecarga);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_correosfacturacion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_correosfacturacion (idagentecarga INT, correosfacturacion TEXT);");
    $conexion->query("INSERT INTO tmp_correosfacturacion (idagentecarga, correosfacturacion)
        select
        t_agentecargacorreofacturacion.idagentecarga,
        GROUP_CONCAT(CONCAT('{\"idagentecargacorreofacturacion\": ',t_agentecargacorreofacturacion.idagentecargacorreofacturacion,', \"correo\": \"',t_agentecargacorreofacturacion.correo,'\"}') SEPARATOR ',') as correosfacturacion
        FROM
        t_agentecargacorreofacturacion
        LEFT JOIN t_agentecarga ON t_agentecargacorreofacturacion.idagentecarga=t_agentecarga.idagentecarga
        WHERE
        t_agentecarga.idempresa=$idempresa
        GROUP BY
        t_agentecargacorreofacturacion.idagentecarga;");
    $conexion->query("ALTER TABLE tmp_correosfacturacion ADD INDEX idagentecarga (idagentecarga);");
    
    $result = $conexion->query("SELECT
        t_agentecarga.idagentecarga, 
        t_agentecarga.agentecarga, 
        t_agentecarga.numeroidentificacion, 
        t_agentecarga.telefono, 
        t_agentecarga.fax, 
        t_agentecarga.email, 
        t_agentecarga.numerocuenta, 
        t_agentecarga.nombrecontacto, 
        IFNULL(t_agentecarga.plazo,0) as plazo, 
        IFNULL(t_agentecarga.id_OVPProv,0) as id_OVPProv,
        t_agentecarga.idtipodocumento,
        t_agentecarga.numerofacturacion,
        t_agentecarga.razonsocial,
        CONCAT('[',IFNULL(tmp_agentecargadireccion.direcciones,''),']') as direcciones,
        CONCAT('[',IFNULL(tmp_correosfacturacion.correosfacturacion,''),']') as correosfacturacion
        FROM 
        t_agentecarga
        LEFT JOIN tmp_agentecargadireccion ON t_agentecarga.idagentecarga=tmp_agentecargadireccion.idagentecarga
        LEFT JOIN tmp_correosfacturacion ON t_agentecarga.idagentecarga=tmp_correosfacturacion.idagentecarga
        WHERE
        t_agentecarga.idempresa=$idempresa
        ORDER BY
        t_agentecarga.agentecarga;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){

        $agentescarga[]=array(
            'idagentecarga'=>(int)$row['idagentecarga'],
            'agentecarga'=>$row['agentecarga'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'telefono'=>$row['telefono'],
            'fax'=>$row['fax'],
            'email'=>$row['email'],
            'numerocuenta'=>$row['numerocuenta'],
            'nombrecontacto'=>$row['nombrecontacto'],
            'plazo'=>(int)$row['plazo'],
            'id_OVPProv'=>(int)$row['id_OVPProv'],
            'idtipodocumento'=>$row['idtipodocumento'],
            'numerofacturacion'=>$row['numerofacturacion'],
            'razonsocial'=>$row['razonsocial'],
            'direcciones'=>json_decode($row["direcciones"], true),
            'correosfacturacion'=>json_decode($row["correosfacturacion"], true)
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'agentescarga' => $agentescarga
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/entidades/agentes-carga', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $agentecarga = $params['agentecarga'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVPProv = $params['id_OVPProv'] ?? 0;
            $direcciones = $params['direcciones'] ?? [];

            $idtipodocumento = $params['idtipodocumento'] ?? '';
            $numerofacturacion = $params['numerofacturacion'] ?? '';
            $razonsocial = $params['razonsocial'] ?? '';
            $correosfacturacion = $params['correosfacturacion'] ?? [];
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

        if ($continuar && trim($agentecarga) === '') {
            $mensaje = 'No se recibió el agente de carga';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($correosfacturacion)) {
            $mensaje = 'Los correos de facturación recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar agente de carga
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryAgenteCarga = "
                INSERT INTO t_agentecarga (
                    idempresa,
                    agentecarga,
                    numeroidentificacion,
                    telefono,
                    fax,
                    email,
                    nombrecontacto,
                    numerocuenta,
                    plazo,
                    id_OVPProv,
                    idtipodocumento,
                    numerofacturacion,
                    razonsocial
                ) VALUES (
                    :idempresa,
                    :agentecarga,
                    :numeroidentificacion,
                    :telefono,
                    :fax,
                    :email,
                    :nombrecontacto,
                    :numerocuenta,
                    :plazo,
                    :id_OVPProv,
                    :idtipodocumento,
                    :numerofacturacion,
                    :razonsocial
                )
            ";

            $stmtAgenteCarga = $conexion->prepare($queryAgenteCarga);

            $resultAgenteCarga = $stmtAgenteCarga->execute([
                ':idempresa' => $idempresa,
                ':agentecarga' => $agentecarga,
                ':numeroidentificacion' => $numeroidentificacion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVPProv' => $id_OVPProv,
                ':idtipodocumento' => $idtipodocumento,
                ':numerofacturacion' => $numerofacturacion,
                ':razonsocial' => $razonsocial
            ]);

            if (!$resultAgenteCarga) {
                $mensaje = 'No se pudo registrar el agente de carga';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idagentecargaNuevo = (int)$conexion->lastInsertId();

                if ($idagentecargaNuevo <= 0) {
                    $mensaje = 'No se pudo obtener el agente de carga generado';
                    $continuar = false;

                    if ($conexion->inTransaction()) {
                        $conexion->rollBack();
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar direcciones
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccion = "
                INSERT INTO t_agentecargadireccion (
                    idagentecarga,
                    direccion,
                    ciudad,
                    idpais
                ) VALUES (
                    :idagentecarga,
                    :direccion,
                    :ciudad,
                    :idpais
                )
            ";

            $stmtDireccion = $conexion->prepare($queryDireccion);

            foreach ($direcciones as $direccionItem) {

                $direccion = $direccionItem["direccion"] ?? '';
                $ciudad = $direccionItem["ciudad"] ?? '';
                $pais = $direccionItem["pais"] ?? '';

                if (trim($direccion) === '' && trim($ciudad) === '' && trim($pais) === '') {
                    continue;
                }

                $resultDireccion = $stmtDireccion->execute([
                    ':idagentecarga' => $idagentecargaNuevo,
                    ':direccion' => $direccion,
                    ':ciudad' => $ciudad,
                    ':idpais' => $pais
                ]);

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo registrar una dirección del agente de carga';
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
        | Insertar correos de facturación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryCorreo = "
                INSERT INTO t_agentecargacorreofacturacion (
                    idagentecarga,
                    correo
                ) VALUES (
                    :idagentecarga,
                    :correo
                )
            ";

            $stmtCorreo = $conexion->prepare($queryCorreo);

            foreach ($correosfacturacion as $correoItem) {

                $correo = $correoItem["correo"] ?? '';

                if (trim($correo) === '') {
                    continue;
                }

                $resultCorreo = $stmtCorreo->execute([
                    ':idagentecarga' => $idagentecargaNuevo,
                    ':correo' => $correo
                ]);

                if (!$resultCorreo) {
                    $mensaje = 'No se pudo registrar un correo de facturación';
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(4, 2))->add($verifyToken);

$app->put('/entidades/agentes-carga', function(Request $request, Response $response, array $args) use ($conexion) {

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $continuar = true;

    try {

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
            $idagentecarga = $params['idagentecarga'] ?? null;
            $agentecarga = $params['agentecarga'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVPProv = $params['id_OVPProv'] ?? 0;
            $direcciones = $params['direcciones'] ?? [];

            $idtipodocumento = $params['idtipodocumento'] ?? '';
            $numerofacturacion = $params['numerofacturacion'] ?? '';
            $razonsocial = $params['razonsocial'] ?? '';
            $correosfacturacion = $params['correosfacturacion'] ?? [];
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idagentecarga)) {
            $mensaje = 'No se recibió el agente de carga';
            $continuar = false;
        }

        if ($continuar && trim($agentecarga) === '') {
            $mensaje = 'No se recibió el nombre del agente de carga';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($correosfacturacion)) {
            $mensaje = 'Los correos de facturación recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar agente de carga
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryAgenteCarga = "
                UPDATE t_agentecarga
                SET
                    agentecarga = :agentecarga,
                    numeroidentificacion = :numeroidentificacion,
                    telefono = :telefono,
                    fax = :fax,
                    email = :email,
                    nombrecontacto = :nombrecontacto,
                    numerocuenta = :numerocuenta,
                    plazo = :plazo,
                    id_OVPProv = :id_OVPProv,
                    idtipodocumento = :idtipodocumento,
                    numerofacturacion = :numerofacturacion,
                    razonsocial = :razonsocial
                WHERE idagentecarga = :idagentecarga
            ";

            $stmtAgenteCarga = $conexion->prepare($queryAgenteCarga);

            $resultAgenteCarga = $stmtAgenteCarga->execute([
                ':agentecarga' => $agentecarga,
                ':numeroidentificacion' => $numeroidentificacion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVPProv' => $id_OVPProv,
                ':idtipodocumento' => $idtipodocumento,
                ':numerofacturacion' => $numerofacturacion,
                ':razonsocial' => $razonsocial,
                ':idagentecarga' => $idagentecarga
            ]);

            if (!$resultAgenteCarga) {
                $mensaje = 'No se pudo actualizar el agente de carga';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Direcciones: actualizar / insertar / eliminar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsDireccionesRecibidas = [];

            $queryUpdateDireccion = "
                UPDATE t_agentecargadireccion
                SET
                    direccion = :direccion,
                    ciudad = :ciudad,
                    idpais = :idpais
                WHERE idagentecargadireccion = :idagentecargadireccion
                  AND idagentecarga = :idagentecarga
            ";

            $stmtUpdateDireccion = $conexion->prepare($queryUpdateDireccion);

            $queryInsertDireccion = "
                INSERT INTO t_agentecargadireccion (
                    idagentecarga,
                    direccion,
                    ciudad,
                    idpais
                ) VALUES (
                    :idagentecarga,
                    :direccion,
                    :ciudad,
                    :idpais
                )
            ";

            $stmtInsertDireccion = $conexion->prepare($queryInsertDireccion);

            foreach ($direcciones as $direccionItem) {

                $idagentecargadireccion = $direccionItem["idagentecargadireccion"] ?? 0;
                $direccion = $direccionItem["direccion"] ?? '';
                $ciudad = $direccionItem["ciudad"] ?? '';
                $pais = $direccionItem["pais"] ?? '';

                if (trim($direccion) === '' && trim($ciudad) === '' && trim($pais) === '') {
                    continue;
                }

                if ((int)$idagentecargadireccion > 0) {

                    $idsDireccionesRecibidas[] = (int)$idagentecargadireccion;

                    $resultDireccion = $stmtUpdateDireccion->execute([
                        ':direccion' => $direccion,
                        ':ciudad' => $ciudad,
                        ':idpais' => $pais,
                        ':idagentecargadireccion' => $idagentecargadireccion,
                        ':idagentecarga' => $idagentecarga
                    ]);

                } else {

                    $resultDireccion = $stmtInsertDireccion->execute([
                        ':idagentecarga' => $idagentecarga,
                        ':direccion' => $direccion,
                        ':ciudad' => $ciudad,
                        ':idpais' => $pais
                    ]);

                    if ($resultDireccion) {
                        $idsDireccionesRecibidas[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo guardar una dirección del agente de carga';
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
        | Eliminar direcciones que ya no llegaron
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccionesActuales = "
                SELECT idagentecargadireccion
                FROM t_agentecargadireccion
                WHERE idagentecarga = :idagentecarga
            ";

            $stmtDireccionesActuales = $conexion->prepare($queryDireccionesActuales);
            $stmtDireccionesActuales->execute([
                ':idagentecarga' => $idagentecarga
            ]);

            $queryDeleteDireccion = "
                DELETE FROM t_agentecargadireccion
                WHERE idagentecargadireccion = :idagentecargadireccion
                  AND idagentecarga = :idagentecarga
            ";

            $stmtDeleteDireccion = $conexion->prepare($queryDeleteDireccion);

            while ($rowDireccion = $stmtDireccionesActuales->fetch(PDO::FETCH_ASSOC)) {

                $idDireccionActual = (int)$rowDireccion['idagentecargadireccion'];

                if (!in_array($idDireccionActual, $idsDireccionesRecibidas)) {

                    $resultDeleteDireccion = $stmtDeleteDireccion->execute([
                        ':idagentecargadireccion' => $idDireccionActual,
                        ':idagentecarga' => $idagentecarga
                    ]);

                    if (!$resultDeleteDireccion) {
                        $mensaje = 'No se pudo eliminar una dirección del agente de carga';
                        $continuar = false;
                        break;
                    }
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Correos de facturación: actualizar / insertar / eliminar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsCorreosRecibidos = [];

            $queryUpdateCorreo = "
                UPDATE t_agentecargacorreofacturacion
                SET correo = :correo
                WHERE idagentecargacorreofacturacion = :idagentecargacorreofacturacion
                  AND idagentecarga = :idagentecarga
            ";

            $stmtUpdateCorreo = $conexion->prepare($queryUpdateCorreo);

            $queryInsertCorreo = "
                INSERT INTO t_agentecargacorreofacturacion (
                    idagentecarga,
                    correo
                ) VALUES (
                    :idagentecarga,
                    :correo
                )
            ";

            $stmtInsertCorreo = $conexion->prepare($queryInsertCorreo);

            foreach ($correosfacturacion as $correoItem) {

                /*
                OJO:
                El nombre correcto es idagentecargacorreofacturacion.
                Se deja fallback a idclientecorreofacturacion porque tu payload actual lo está enviando así.
                */
                $idagentecargacorreofacturacion = $correoItem["idagentecargacorreofacturacion"]
                    ?? ($correoItem["idclientecorreofacturacion"] ?? 0);

                $correo = $correoItem["correo"] ?? '';

                if (trim($correo) === '') {
                    continue;
                }

                if ((int)$idagentecargacorreofacturacion > 0) {

                    $idsCorreosRecibidos[] = (int)$idagentecargacorreofacturacion;

                    $resultCorreo = $stmtUpdateCorreo->execute([
                        ':correo' => $correo,
                        ':idagentecargacorreofacturacion' => $idagentecargacorreofacturacion,
                        ':idagentecarga' => $idagentecarga
                    ]);

                } else {

                    $resultCorreo = $stmtInsertCorreo->execute([
                        ':idagentecarga' => $idagentecarga,
                        ':correo' => $correo
                    ]);

                    if ($resultCorreo) {
                        $idsCorreosRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$resultCorreo) {
                    $mensaje = 'No se pudo guardar un correo de facturación';
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
        | Eliminar correos que ya no llegaron
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryCorreosActuales = "
                SELECT idagentecargacorreofacturacion
                FROM t_agentecargacorreofacturacion
                WHERE idagentecarga = :idagentecarga
            ";

            $stmtCorreosActuales = $conexion->prepare($queryCorreosActuales);
            $stmtCorreosActuales->execute([
                ':idagentecarga' => $idagentecarga
            ]);

            $queryDeleteCorreo = "
                DELETE FROM t_agentecargacorreofacturacion
                WHERE idagentecargacorreofacturacion = :idagentecargacorreofacturacion
                  AND idagentecarga = :idagentecarga
            ";

            $stmtDeleteCorreo = $conexion->prepare($queryDeleteCorreo);

            while ($rowCorreo = $stmtCorreosActuales->fetch(PDO::FETCH_ASSOC)) {

                $idCorreoActual = (int)$rowCorreo['idagentecargacorreofacturacion'];

                if (!in_array($idCorreoActual, $idsCorreosRecibidos)) {

                    $resultDeleteCorreo = $stmtDeleteCorreo->execute([
                        ':idagentecargacorreofacturacion' => $idCorreoActual,
                        ':idagentecarga' => $idagentecarga
                    ]);

                    if (!$resultDeleteCorreo) {
                        $mensaje = 'No se pudo eliminar un correo de facturación';
                        $continuar = false;
                        break;
                    }
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(4, 2))->add($verifyToken);

$app->get('/entidades/proveedores', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $proveedores=[];
    
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_proveedordireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_proveedordireccion (idproveedor INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_proveedordireccion (idproveedor, direcciones)
        SELECT
        t_proveedordireccion.idproveedor,
        GROUP_CONCAT(CONCAT('{\"idproveedordireccion\": ',t_proveedordireccion.idproveedordireccion,', \"direccion\": \"',IFNULL(t_proveedordireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_proveedordireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_proveedordireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_proveedordireccion
        LEFT JOIN t_proveedor ON t_proveedordireccion.idproveedor=t_proveedor.idproveedor
        WHERE
        t_proveedor.idempresa=$idempresa
        GROUP BY
        t_proveedordireccion.idproveedor;");
    $conexion->query("ALTER TABLE tmp_proveedordireccion ADD INDEX idproveedor (idproveedor);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_correosfacturacion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_correosfacturacion (idproveedor INT, correosfacturacion TEXT);");
    $conexion->query("INSERT INTO tmp_correosfacturacion (idproveedor, correosfacturacion)
        select
        t_proveedorcorreofacturacion.idproveedor,
        GROUP_CONCAT(CONCAT('{\"idproveedorcorreofacturacion\": ',t_proveedorcorreofacturacion.idproveedorcorreofacturacion,', \"correo\": \"',t_proveedorcorreofacturacion.correo,'\"}') SEPARATOR ',') as correosfacturacion
        FROM
        t_proveedorcorreofacturacion
        LEFT JOIN t_proveedor ON t_proveedorcorreofacturacion.idproveedor=t_proveedor.idproveedor
        WHERE
        t_proveedor.idempresa=$idempresa
        GROUP BY
        t_proveedorcorreofacturacion.idproveedor;");
    $conexion->query("ALTER TABLE tmp_correosfacturacion ADD INDEX idproveedor (idproveedor);");
    
    $result = $conexion->query("SELECT
        t_proveedor.idproveedor, 
        t_proveedor.proveedor, 
        t_proveedor.numeroidentificacion, 
        t_proveedor.telefono, 
        t_proveedor.fax, 
        t_proveedor.email, 
        t_proveedor.numerocuenta, 
        t_proveedor.nombrecontacto, 
        IFNULL(t_proveedor.plazo,0) as plazo, 
        IFNULL(t_proveedor.id_OVPProv,0) as id_OVPProv,
        t_proveedor.idtipodocumento,
        t_proveedor.numerofacturacion,
        t_proveedor.razonsocial,
        CONCAT('[',IFNULL(tmp_proveedordireccion.direcciones,''),']') as direcciones,
        CONCAT('[',IFNULL(tmp_correosfacturacion.correosfacturacion,''),']') as correosfacturacion
        FROM 
        t_proveedor
        LEFT JOIN tmp_proveedordireccion ON t_proveedor.idproveedor=tmp_proveedordireccion.idproveedor
        LEFT JOIN tmp_correosfacturacion ON t_proveedor.idproveedor=tmp_correosfacturacion.idproveedor
        WHERE
        t_proveedor.idempresa=$idempresa
        ORDER BY
        proveedor;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        /*
        $direcciones=[];
        $resultdireccion = $conexion->query("SELECT 
            idproveedordireccion,
            direccion,
            ciudad,
            idpais
            FROM 
            t_proveedordireccion
            WHERE idproveedor=".$row["idproveedor"].";");
        while ($rowdireccion =  $resultdireccion ->fetch(PDO::FETCH_ASSOC)){
            $direcciones[]=array(
                'idproveedordireccion'=>(int)$rowdireccion['idproveedordireccion'],
                'direccion'=>$rowdireccion['direccion'],
                'ciudad'=>$rowdireccion['ciudad'],
                'pais'=>$rowdireccion['idpais']
            );
        }
         * 
         */
        $proveedores[]=array(
            'idproveedor'=>(int)$row['idproveedor'],
            'proveedor'=>$row['proveedor'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'telefono'=>$row['telefono'],
            'fax'=>$row['fax'],
            'email'=>$row['email'],
            'numerocuenta'=>$row['numerocuenta'],
            'nombrecontacto'=>$row['nombrecontacto'],
            'plazo'=>(int)$row['plazo'],
            'id_OVPProv'=>(int)$row['id_OVPProv'],
            'idtipodocumento'=>$row['idtipodocumento'],
            'numerofacturacion'=>$row['numerofacturacion'],
            'razonsocial'=>$row['razonsocial'],
            'direcciones'=>json_decode($row["direcciones"], true),
            'correosfacturacion'=>json_decode($row["correosfacturacion"], true)
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'proveedores' => $proveedores
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/entidades/proveedores', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $proveedor = $params['proveedor'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVPProv = $params['id_OVPProv'] ?? 0;
            $direcciones = $params['direcciones'] ?? [];

            $idtipodocumento = $params['idtipodocumento'] ?? '';
            $numerofacturacion = $params['numerofacturacion'] ?? '';
            $razonsocial = $params['razonsocial'] ?? '';
            $correosfacturacion = $params['correosfacturacion'] ?? [];
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

        if ($continuar && trim($proveedor) === '') {
            $mensaje = 'No se recibió el proveedor';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($correosfacturacion)) {
            $mensaje = 'Los correos de facturación recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar proveedor
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryProveedor = "
                INSERT INTO t_proveedor (
                    idempresa,
                    proveedor,
                    numeroidentificacion,
                    telefono,
                    fax,
                    email,
                    nombrecontacto,
                    numerocuenta,
                    plazo,
                    id_OVPProv,
                    idtipodocumento,
                    numerofacturacion,
                    razonsocial
                ) VALUES (
                    :idempresa,
                    :proveedor,
                    :numeroidentificacion,
                    :telefono,
                    :fax,
                    :email,
                    :nombrecontacto,
                    :numerocuenta,
                    :plazo,
                    :id_OVPProv,
                    :idtipodocumento,
                    :numerofacturacion,
                    :razonsocial
                )
            ";

            $stmtProveedor = $conexion->prepare($queryProveedor);

            $resultProveedor = $stmtProveedor->execute([
                ':idempresa' => $idempresa,
                ':proveedor' => $proveedor,
                ':numeroidentificacion' => $numeroidentificacion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVPProv' => $id_OVPProv,
                ':idtipodocumento' => $idtipodocumento,
                ':numerofacturacion' => $numerofacturacion,
                ':razonsocial' => $razonsocial
            ]);

            if (!$resultProveedor) {
                $mensaje = 'No se pudo registrar el proveedor';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idproveedorNuevo = (int)$conexion->lastInsertId();

                if ($idproveedorNuevo <= 0) {
                    $mensaje = 'No se pudo obtener el proveedor generado';
                    $continuar = false;

                    if ($conexion->inTransaction()) {
                        $conexion->rollBack();
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar direcciones
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccion = "
                INSERT INTO t_proveedordireccion (
                    idproveedor,
                    direccion,
                    ciudad,
                    idpais
                ) VALUES (
                    :idproveedor,
                    :direccion,
                    :ciudad,
                    :idpais
                )
            ";

            $stmtDireccion = $conexion->prepare($queryDireccion);

            foreach ($direcciones as $direccionItem) {

                $direccion = $direccionItem["direccion"] ?? '';
                $ciudad = $direccionItem["ciudad"] ?? '';
                $pais = $direccionItem["pais"] ?? '';

                if (trim($direccion) === '' && trim($ciudad) === '' && trim($pais) === '') {
                    continue;
                }

                $resultDireccion = $stmtDireccion->execute([
                    ':idproveedor' => $idproveedorNuevo,
                    ':direccion' => $direccion,
                    ':ciudad' => $ciudad,
                    ':idpais' => $pais
                ]);

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo registrar una dirección del proveedor';
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
        | Insertar correos de facturación
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryCorreo = "
                INSERT INTO t_proveedorcorreofacturacion (
                    idproveedor,
                    correo
                ) VALUES (
                    :idproveedor,
                    :correo
                )
            ";

            $stmtCorreo = $conexion->prepare($queryCorreo);

            foreach ($correosfacturacion as $correoItem) {

                $correo = $correoItem["correo"] ?? '';

                if (trim($correo) === '') {
                    continue;
                }

                $resultCorreo = $stmtCorreo->execute([
                    ':idproveedor' => $idproveedorNuevo,
                    ':correo' => $correo
                ]);

                if (!$resultCorreo) {
                    $mensaje = 'No se pudo registrar un correo de facturación';
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(5, 2))->add($verifyToken);

$app->put('/entidades/proveedores', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $idproveedor = $params['idproveedor'] ?? null;
            $proveedor = $params['proveedor'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVPProv = $params['id_OVPProv'] ?? 0;
            $direcciones = $params['direcciones'] ?? [];

            $idtipodocumento = $params['idtipodocumento'] ?? '';
            $numerofacturacion = $params['numerofacturacion'] ?? '';
            $razonsocial = $params['razonsocial'] ?? '';
            $correosfacturacion = $params['correosfacturacion'] ?? [];
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

        if ($continuar && empty($idproveedor)) {
            $mensaje = 'No se recibió el proveedor';
            $continuar = false;
        }

        if ($continuar && trim($proveedor) === '') {
            $mensaje = 'No se recibió el nombre del proveedor';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($correosfacturacion)) {
            $mensaje = 'Los correos de facturación recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar proveedor
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryProveedor = "
                UPDATE t_proveedor
                SET
                    proveedor = :proveedor,
                    numeroidentificacion = :numeroidentificacion,
                    telefono = :telefono,
                    fax = :fax,
                    email = :email,
                    nombrecontacto = :nombrecontacto,
                    numerocuenta = :numerocuenta,
                    plazo = :plazo,
                    id_OVPProv = :id_OVPProv,
                    idtipodocumento = :idtipodocumento,
                    numerofacturacion = :numerofacturacion,
                    razonsocial = :razonsocial
                WHERE idproveedor = :idproveedor
                  AND idempresa = :idempresa
            ";

            $stmtProveedor = $conexion->prepare($queryProveedor);

            $resultProveedor = $stmtProveedor->execute([
                ':proveedor' => $proveedor,
                ':numeroidentificacion' => $numeroidentificacion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVPProv' => $id_OVPProv,
                ':idtipodocumento' => $idtipodocumento,
                ':numerofacturacion' => $numerofacturacion,
                ':razonsocial' => $razonsocial,
                ':idproveedor' => $idproveedor,
                ':idempresa' => $idempresa
            ]);

            if (!$resultProveedor) {
                $mensaje = 'No se pudo actualizar el proveedor';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Direcciones: actualizar / insertar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsDireccionesRecibidas = [];

            $queryUpdateDireccion = "
                UPDATE t_proveedordireccion
                SET
                    direccion = :direccion,
                    ciudad = :ciudad,
                    idpais = :idpais
                WHERE idproveedordireccion = :idproveedordireccion
                  AND idproveedor = :idproveedor
            ";

            $stmtUpdateDireccion = $conexion->prepare($queryUpdateDireccion);

            $queryInsertDireccion = "
                INSERT INTO t_proveedordireccion (
                    idproveedor,
                    direccion,
                    ciudad,
                    idpais
                ) VALUES (
                    :idproveedor,
                    :direccion,
                    :ciudad,
                    :idpais
                )
            ";

            $stmtInsertDireccion = $conexion->prepare($queryInsertDireccion);

            foreach ($direcciones as $direccionItem) {

                $idproveedordireccion = $direccionItem["idproveedordireccion"] ?? 0;
                $direccion = $direccionItem["direccion"] ?? '';
                $ciudad = $direccionItem["ciudad"] ?? '';
                $pais = $direccionItem["pais"] ?? '';

                if (trim($direccion) === '' && trim($ciudad) === '' && trim($pais) === '') {
                    continue;
                }

                if ((int)$idproveedordireccion > 0) {

                    $idsDireccionesRecibidas[] = (int)$idproveedordireccion;

                    $resultDireccion = $stmtUpdateDireccion->execute([
                        ':direccion' => $direccion,
                        ':ciudad' => $ciudad,
                        ':idpais' => $pais,
                        ':idproveedordireccion' => $idproveedordireccion,
                        ':idproveedor' => $idproveedor
                    ]);

                } else {

                    $resultDireccion = $stmtInsertDireccion->execute([
                        ':idproveedor' => $idproveedor,
                        ':direccion' => $direccion,
                        ':ciudad' => $ciudad,
                        ':idpais' => $pais
                    ]);

                    /*
                    Importante:
                    Si se inserta una dirección nueva, agregamos su ID
                    para que no sea eliminada en el bloque siguiente.
                    */
                    if ($resultDireccion) {
                        $idsDireccionesRecibidas[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo guardar una dirección del proveedor';
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
        | Eliminar direcciones que ya no llegaron
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccionesActuales = "
                SELECT idproveedordireccion
                FROM t_proveedordireccion
                WHERE idproveedor = :idproveedor
            ";

            $stmtDireccionesActuales = $conexion->prepare($queryDireccionesActuales);
            $stmtDireccionesActuales->execute([
                ':idproveedor' => $idproveedor
            ]);

            $queryDeleteDireccion = "
                DELETE FROM t_proveedordireccion
                WHERE idproveedordireccion = :idproveedordireccion
                  AND idproveedor = :idproveedor
            ";

            $stmtDeleteDireccion = $conexion->prepare($queryDeleteDireccion);

            while ($rowDireccion = $stmtDireccionesActuales->fetch(PDO::FETCH_ASSOC)) {

                $idDireccionActual = (int)$rowDireccion['idproveedordireccion'];

                if (!in_array($idDireccionActual, $idsDireccionesRecibidas)) {

                    $resultDeleteDireccion = $stmtDeleteDireccion->execute([
                        ':idproveedordireccion' => $idDireccionActual,
                        ':idproveedor' => $idproveedor
                    ]);

                    if (!$resultDeleteDireccion) {
                        $mensaje = 'No se pudo eliminar una dirección del proveedor';
                        $continuar = false;
                        break;
                    }
                }
            }

            if (!$continuar && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Correos: actualizar / insertar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsCorreosRecibidos = [];

            $queryUpdateCorreo = "
                UPDATE t_proveedorcorreofacturacion
                SET correo = :correo
                WHERE idproveedorcorreofacturacion = :idproveedorcorreofacturacion
                  AND idproveedor = :idproveedor
            ";

            $stmtUpdateCorreo = $conexion->prepare($queryUpdateCorreo);

            $queryInsertCorreo = "
                INSERT INTO t_proveedorcorreofacturacion (
                    idproveedor,
                    correo
                ) VALUES (
                    :idproveedor,
                    :correo
                )
            ";

            $stmtInsertCorreo = $conexion->prepare($queryInsertCorreo);

            foreach ($correosfacturacion as $correoItem) {

                $idproveedorcorreofacturacion = $correoItem["idproveedorcorreofacturacion"] ?? 0;
                $correo = $correoItem["correo"] ?? '';

                if (trim($correo) === '') {
                    continue;
                }

                if ((int)$idproveedorcorreofacturacion > 0) {

                    $idsCorreosRecibidos[] = (int)$idproveedorcorreofacturacion;

                    $resultCorreo = $stmtUpdateCorreo->execute([
                        ':correo' => $correo,
                        ':idproveedorcorreofacturacion' => $idproveedorcorreofacturacion,
                        ':idproveedor' => $idproveedor
                    ]);

                } else {

                    $resultCorreo = $stmtInsertCorreo->execute([
                        ':idproveedor' => $idproveedor,
                        ':correo' => $correo
                    ]);

                    /*
                    Importante:
                    Si se inserta un correo nuevo, agregamos su ID
                    para que no sea eliminado en el bloque siguiente.
                    */
                    if ($resultCorreo) {
                        $idsCorreosRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$resultCorreo) {
                    $mensaje = 'No se pudo guardar un correo de facturación';
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
        | Eliminar correos que ya no llegaron
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryCorreosActuales = "
                SELECT idproveedorcorreofacturacion
                FROM t_proveedorcorreofacturacion
                WHERE idproveedor = :idproveedor
            ";

            $stmtCorreosActuales = $conexion->prepare($queryCorreosActuales);
            $stmtCorreosActuales->execute([
                ':idproveedor' => $idproveedor
            ]);

            $queryDeleteCorreo = "
                DELETE FROM t_proveedorcorreofacturacion
                WHERE idproveedorcorreofacturacion = :idproveedorcorreofacturacion
                  AND idproveedor = :idproveedor
            ";

            $stmtDeleteCorreo = $conexion->prepare($queryDeleteCorreo);

            while ($rowCorreo = $stmtCorreosActuales->fetch(PDO::FETCH_ASSOC)) {

                $idCorreoActual = (int)$rowCorreo['idproveedorcorreofacturacion'];

                if (!in_array($idCorreoActual, $idsCorreosRecibidos)) {

                    $resultDeleteCorreo = $stmtDeleteCorreo->execute([
                        ':idproveedorcorreofacturacion' => $idCorreoActual,
                        ':idproveedor' => $idproveedor
                    ]);

                    if (!$resultDeleteCorreo) {
                        $mensaje = 'No se pudo eliminar un correo de facturación';
                        $continuar = false;
                        break;
                    }
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(5, 2))->add($verifyToken);

$app->get('/entidades/prestadores-servicio', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $prestadoresservicio=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_prestadordireccion;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_prestadordireccion (idprestador INT, direcciones TEXT);");
    $conexion->query("INSERT INTO tmp_prestadordireccion (idprestador, direcciones)
        SELECT
        t_prestadordireccion.idprestador,
        GROUP_CONCAT(CONCAT('{\"idprestadordireccion\": ',t_prestadordireccion.idprestadordireccion,', \"direccion\": \"',IFNULL(t_prestadordireccion.direccion,''),'\", \"ciudad\": \"',IFNULL(t_prestadordireccion.ciudad,''),'\", \"pais\": \"',IFNULL(t_prestadordireccion.idpais,''),'\"}') SEPARATOR ',') as direcciones
        from
        t_prestadordireccion
        LEFT JOIN t_prestador ON t_prestadordireccion.idprestador=t_prestador.idprestador
        WHERE
        t_prestador.idempresa=$idempresa
        GROUP BY
        t_prestadordireccion.idprestador;");
    $conexion->query("ALTER TABLE tmp_prestadordireccion ADD INDEX idprestador (idprestador);");
    
    $result = $conexion->query("SELECT
        t_prestador.idprestador, 
        t_prestador.prestador, 
        t_prestador.numeroidentificacion, 
        t_prestador.telefono, 
        t_prestador.fax, 
        t_prestador.email, 
        t_prestador.numerocuenta, 
        t_prestador.nombrecontacto, 
        IFNULL(t_prestador.plazo,0) as plazo, 
        IFNULL(t_prestador.id_OVPProv,0) as id_OVPProv,
        CONCAT('[',IFNULL(tmp_prestadordireccion.direcciones,''),']') as direcciones
        FROM 
        t_prestador
        LEFT JOIN tmp_prestadordireccion ON t_prestador.idprestador=tmp_prestadordireccion.idprestador
        WHERE
        t_prestador.idempresa=$idempresa
        ORDER BY
        t_prestador.prestador;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $prestadoresservicio[]=array(
            'idprestador'=>(int)$row['idprestador'],
            'prestador'=>$row['prestador'],
            'numeroidentificacion'=>$row['numeroidentificacion'],
            'telefono'=>$row['telefono'],
            'fax'=>$row['fax'],
            'email'=>$row['email'],
            'numerocuenta'=>$row['numerocuenta'],
            'nombrecontacto'=>$row['nombrecontacto'],
            'plazo'=>(int)$row['plazo'],
            'id_OVPProv'=>(int)$row['id_OVPProv'],
            'direcciones'=>json_decode($row["direcciones"], true)
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'prestadoresservicio' => $prestadoresservicio
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/entidades/prestadores-servicio', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $prestador = $params['prestador'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVPProv = $params['id_OVPProv'] ?? 0;
            $direcciones = $params['direcciones'] ?? [];
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

        if ($continuar && trim($prestador) === '') {
            $mensaje = 'No se recibió el prestador de servicio';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar prestador
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryPrestador = "
                INSERT INTO t_prestador (
                    idempresa,
                    prestador,
                    numeroidentificacion,
                    telefono,
                    fax,
                    email,
                    nombrecontacto,
                    numerocuenta,
                    plazo,
                    id_OVPProv
                ) VALUES (
                    :idempresa,
                    :prestador,
                    :numeroidentificacion,
                    :telefono,
                    :fax,
                    :email,
                    :nombrecontacto,
                    :numerocuenta,
                    :plazo,
                    :id_OVPProv
                )
            ";

            $stmtPrestador = $conexion->prepare($queryPrestador);

            $resultPrestador = $stmtPrestador->execute([
                ':idempresa' => $idempresa,
                ':prestador' => $prestador,
                ':numeroidentificacion' => $numeroidentificacion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVPProv' => $id_OVPProv
            ]);

            if (!$resultPrestador) {
                $mensaje = 'No se pudo registrar el prestador de servicio';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idprestadorNuevo = (int)$conexion->lastInsertId();

                if ($idprestadorNuevo <= 0) {
                    $mensaje = 'No se pudo obtener el prestador generado';
                    $continuar = false;

                    if ($conexion->inTransaction()) {
                        $conexion->rollBack();
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar direcciones
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccion = "
                INSERT INTO t_prestadordireccion (
                    idprestador,
                    direccion,
                    ciudad,
                    idpais
                ) VALUES (
                    :idprestador,
                    :direccion,
                    :ciudad,
                    :idpais
                )
            ";

            $stmtDireccion = $conexion->prepare($queryDireccion);

            foreach ($direcciones as $direccionItem) {

                $direccion = $direccionItem["direccion"] ?? '';
                $ciudad = $direccionItem["ciudad"] ?? '';
                $pais = $direccionItem["pais"] ?? '';

                if (trim($direccion) === '' && trim($ciudad) === '' && trim($pais) === '') {
                    continue;
                }

                $resultDireccion = $stmtDireccion->execute([
                    ':idprestador' => $idprestadorNuevo,
                    ':direccion' => $direccion,
                    ':ciudad' => $ciudad,
                    ':idpais' => $pais
                ]);

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo registrar una dirección del prestador';
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(6, 2))->add($verifyToken);

$app->put('/entidades/prestadores-servicio', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $idprestador = $params['idprestador'] ?? null;
            $prestador = $params['prestador'] ?? '';
            $numeroidentificacion = $params['numeroidentificacion'] ?? '';
            $telefono = $params['telefono'] ?? '';
            $fax = $params['fax'] ?? '';
            $email = $params['email'] ?? '';
            $nombrecontacto = $params['nombrecontacto'] ?? '';
            $numerocuenta = $params['numerocuenta'] ?? '';
            $plazo = $params['plazo'] ?? 0;
            $id_OVPProv = $params['id_OVPProv'] ?? 0;
            $direcciones = $params['direcciones'] ?? [];
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

        if ($continuar && empty($idprestador)) {
            $mensaje = 'No se recibió el prestador de servicio';
            $continuar = false;
        }

        if ($continuar && trim($prestador) === '') {
            $mensaje = 'No se recibió el nombre del prestador de servicio';
            $continuar = false;
        }

        if ($continuar && !is_array($direcciones)) {
            $mensaje = 'Las direcciones recibidas no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar prestador
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryPrestador = "
                UPDATE t_prestador
                SET
                    prestador = :prestador,
                    numeroidentificacion = :numeroidentificacion,
                    telefono = :telefono,
                    fax = :fax,
                    email = :email,
                    nombrecontacto = :nombrecontacto,
                    numerocuenta = :numerocuenta,
                    plazo = :plazo,
                    id_OVPProv = :id_OVPProv
                WHERE idprestador = :idprestador
                  AND idempresa = :idempresa
            ";

            $stmtPrestador = $conexion->prepare($queryPrestador);

            $resultPrestador = $stmtPrestador->execute([
                ':prestador' => $prestador,
                ':numeroidentificacion' => $numeroidentificacion,
                ':telefono' => $telefono,
                ':fax' => $fax,
                ':email' => $email,
                ':nombrecontacto' => $nombrecontacto,
                ':numerocuenta' => $numerocuenta,
                ':plazo' => $plazo,
                ':id_OVPProv' => $id_OVPProv,
                ':idprestador' => $idprestador,
                ':idempresa' => $idempresa
            ]);

            if (!$resultPrestador) {
                $mensaje = 'No se pudo actualizar el prestador de servicio';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Direcciones: actualizar / insertar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsDireccionesRecibidas = [];

            $queryUpdateDireccion = "
                UPDATE t_prestadordireccion
                SET
                    direccion = :direccion,
                    ciudad = :ciudad,
                    idpais = :idpais
                WHERE idprestadordireccion = :idprestadordireccion
                  AND idprestador = :idprestador
            ";

            $stmtUpdateDireccion = $conexion->prepare($queryUpdateDireccion);

            $queryInsertDireccion = "
                INSERT INTO t_prestadordireccion (
                    idprestador,
                    direccion,
                    ciudad,
                    idpais
                ) VALUES (
                    :idprestador,
                    :direccion,
                    :ciudad,
                    :idpais
                )
            ";

            $stmtInsertDireccion = $conexion->prepare($queryInsertDireccion);

            foreach ($direcciones as $direccionItem) {

                $idprestadordireccion = $direccionItem["idprestadordireccion"] ?? 0;
                $direccion = $direccionItem["direccion"] ?? '';
                $ciudad = $direccionItem["ciudad"] ?? '';
                $pais = $direccionItem["pais"] ?? '';

                if (trim($direccion) === '' && trim($ciudad) === '' && trim($pais) === '') {
                    continue;
                }

                if ((int)$idprestadordireccion > 0) {

                    $idsDireccionesRecibidas[] = (int)$idprestadordireccion;

                    $resultDireccion = $stmtUpdateDireccion->execute([
                        ':direccion' => $direccion,
                        ':ciudad' => $ciudad,
                        ':idpais' => $pais,
                        ':idprestadordireccion' => $idprestadordireccion,
                        ':idprestador' => $idprestador
                    ]);

                } else {

                    $resultDireccion = $stmtInsertDireccion->execute([
                        ':idprestador' => $idprestador,
                        ':direccion' => $direccion,
                        ':ciudad' => $ciudad,
                        ':idpais' => $pais
                    ]);

                    /*
                    Importante:
                    Si se inserta una dirección nueva, agregamos su ID a la lista,
                    para que no sea eliminada en el bloque siguiente.
                    */
                    if ($resultDireccion) {
                        $idsDireccionesRecibidas[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$resultDireccion) {
                    $mensaje = 'No se pudo guardar una dirección del prestador';
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
        | Eliminar direcciones que ya no llegaron
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryDireccionesActuales = "
                SELECT idprestadordireccion
                FROM t_prestadordireccion
                WHERE idprestador = :idprestador
            ";

            $stmtDireccionesActuales = $conexion->prepare($queryDireccionesActuales);
            $stmtDireccionesActuales->execute([
                ':idprestador' => $idprestador
            ]);

            $queryDeleteDireccion = "
                DELETE FROM t_prestadordireccion
                WHERE idprestadordireccion = :idprestadordireccion
                  AND idprestador = :idprestador
            ";

            $stmtDeleteDireccion = $conexion->prepare($queryDeleteDireccion);

            while ($rowDireccion = $stmtDireccionesActuales->fetch(PDO::FETCH_ASSOC)) {

                $idDireccionActual = (int)$rowDireccion['idprestadordireccion'];

                if (!in_array($idDireccionActual, $idsDireccionesRecibidas)) {

                    $resultDeleteDireccion = $stmtDeleteDireccion->execute([
                        ':idprestadordireccion' => $idDireccionActual,
                        ':idprestador' => $idprestador
                    ]);

                    if (!$resultDeleteDireccion) {
                        $mensaje = 'No se pudo eliminar una dirección del prestador';
                        $continuar = false;
                        break;
                    }
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
            $mensaje = 'Se guardó la información de Ruta';
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

})->add($verifyRole(6, 2))->add($verifyToken);
