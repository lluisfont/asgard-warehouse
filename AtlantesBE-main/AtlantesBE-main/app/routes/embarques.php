<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/cotizaciones', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $cotizaciones=[];
    $result = $conexion->query("SELECT
        t_cotizacion.idcotizacion,
        CONCAT('COT-',t_cotizacion.numero,'-',t_cotizacion.gestion) as numero,
        IFNULL(t_cliente.cliente,t_cotizacion.otrocliente) as cliente,
        t_cotizacion.fecha,
        t_cotizacion.noidentificacion,
        t_cotizacion.descripcioncarga,
        t_cotizacion.idestadocotizacion
        FROM
        t_cotizacion
        LEFT JOIN t_cliente ON t_cotizacion.idcliente=t_cliente.idcliente
        WHERE
        t_cotizacion.idempresa=$idempresa
        ORDER BY 
        t_cotizacion.fecha DESC;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cotizaciones[]=array(
            'idcotizacion'=>(int)$row['idcotizacion'],
            'numero'=>$row['numero'],
            'cliente'=>$row['cliente'],
            'fecha'=>$row['fecha'],
            'noidentificacion'=>$row['noidentificacion'],
            'descripcioncarga'=>$row['descripcioncarga'],
            'idestadocotizacion'=>(int)$row['idestadocotizacion']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'cotizaciones' => $cotizaciones
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/cotizaciones', function(Request $request, Response $response, array $args) use ($conexion) {
    $params = json_decode((string) $request->getBody(),true);
    //$token = $app->request->headers->get('Authorization');
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    $idcotizacion=0;
    
    $idclienteg="NULL";
    $idcliente=$params['idcliente'];
    if((int)$idcliente>0){
        $idclienteg=$idcliente;
    }
    
    $query="INSERT INTO t_cotizacion (idempresa,    numero,                           gestion,                fecha,                  idcliente,      otrocliente,                    idciudad,                       idusuario,                          idestadocotizacion) 
                              select $idempresa,    IFNULL(MAX(numero),0)+1 as numero, YEAR(CURRENT_DATE()),   CURRENT_TIMESTAMP(),    $idclienteg,    '".$params["otrocliente"]."',   ".$decoded_array["idciudad"].", ".$decoded_array["idusuario"].",    1 FROM t_cotizacion WHERE gestion=YEAR(CURRENT_DATE()) AND idempresa=$idempresa;
            SELECT LAST_INSERT_ID() INTO @idcotizacion_nuevo;";
    
    $result = $conexion->exec($query);
    if($result){
        
        $result = $conexion->query("SELECT
            @idcotizacion_nuevo as idcotizacion;");
        while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
            $idcotizacion=$row['idcotizacion'];
        }
        
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información Correctamente';
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idcotizacion'=>$idcotizacion
    );
    
    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/cotizaciones/{idcotizacion}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcotizacion = $args['idcotizacion'];
    $cotizacion=[];
    $result = $conexion->query("SELECT 
        t_cotizacion.idcotizacion,
        t_cotizacion.numero,
        t_cotizacion.gestion,
        t_cotizacion.fecha,
        t_cotizacion.idcliente,
        IFNULL(t_cliente.cliente,t_cotizacion.otrocliente) as cliente,
        t_cotizacion.nombre,
        t_cotizacion.idtipoembarque as idtipoembarque,
        t_tipoembarque.tipoembarque,
        t_cotizacion.importacion_exportacion as importacion_exportacion,
        t_importacion_exportacion.importacion_exportacion_codigo,
        t_cotizacion.noidentificacion,
        t_cotizacion.idexpedidor,
        t_cotizacion.idtipoexpedidor,
        CASE t_cotizacion.idtipoexpedidor
            WHEN 1 THEN t_clienteexpedidor.cliente
            WHEN 2 THEN t_proveedorexpedidor.proveedor
            WHEN 3 THEN t_prestadorexpedidor.prestador
            WHEN 5 THEN t_agentecargaexpedidor.agentecarga
        END as expedidor,
        t_cotizacion.descripcioncarga,
        t_cotizacion.idorigen,
        t_origen.ciudad as origen,
        t_cotizacion.iddestino,
        t_destino.ciudad as destino,
        t_cotizacion.peso,
        t_cotizacion.volumen,
        t_cotizacion.piezas,
        t_cotizacion.idtipobulto,
        t_cotizacion.idincoterms,
        t_incoterms.incoterms,
        t_cotizacion.idestadocotizacion
        FROM 
        t_cotizacion 
        LEFT JOIN t_cliente ON t_cotizacion.idcliente=t_cliente.idcliente
        LEFT JOIN t_tipoembarque ON t_cotizacion.idtipoembarque=t_tipoembarque.idtipoembarque
        LEFT JOIN t_importacion_exportacion ON t_cotizacion.importacion_exportacion=t_importacion_exportacion.importacion_exportacion
        LEFT JOIN t_cliente as t_clienteexpedidor ON t_cotizacion.idexpedidor=t_clienteexpedidor.idcliente
        LEFT JOIN t_proveedor as t_proveedorexpedidor ON t_cotizacion.idexpedidor=t_proveedorexpedidor.idproveedor
        LEFT JOIN t_prestador as t_prestadorexpedidor ON t_cotizacion.idexpedidor=t_prestadorexpedidor.idprestador
        LEFT JOIN t_agentecarga as t_agentecargaexpedidor ON t_cotizacion.idexpedidor=t_agentecargaexpedidor.idagentecarga
        LEFT JOIN t_ciudad as t_origen ON t_cotizacion.idorigen=t_origen.idciudad
        LEFT JOIN t_ciudad as t_destino ON t_cotizacion.iddestino=t_destino.idciudad
        LEFT JOIN t_incoterms ON t_cotizacion.idincoterms=t_incoterms.idincoterms
        WHERE 
        t_cotizacion.idcotizacion=$idcotizacion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $costos=[];
        $resultcostos = $conexion->query("SELECT 
            t_costocotizacion.idcostocotizacion,
            t_costocotizacion.idconcepto,
            t_concepto.concepto,
            t_costocotizacion.cantidad,
            t_costocotizacion.montocargo,
            t_costocotizacion.montocosto,
            t_costocotizacion.iddivisa,
            t_divisa.codigo as codigodivisa
            FROM 
            t_costocotizacion 
            LEFT JOIN t_concepto ON t_costocotizacion.idconcepto=t_concepto.idconcepto
            LEFT JOIN t_divisa ON t_costocotizacion.iddivisa=t_divisa.iddivisa
            WHERE 
            t_costocotizacion.idcotizacion=$idcotizacion;");
        while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC)){
            $costos[]=array(
                'idcostocotizacion'=>(int)$rowcostos['idcostocotizacion'],
                'idconcepto'=>(int)$rowcostos['idconcepto'],
                'concepto'=>$rowcostos['concepto'],
                'cantidad'=>(float)$rowcostos['cantidad'],
                'montocargo'=>(float)$rowcostos['montocargo'],
                'montocosto'=>(float)$rowcostos['montocosto'],
                'iddivisa'=>(int)$rowcostos['iddivisa'],
                'codigodivisa'=>$rowcostos['codigodivisa']
            );
        }
        
        $eventos=[];
        $resulteventos = $conexion->query("SELECT 
            t_eventocotizacion.ideventocotizacion,
            t_eventocotizacion.idtipoevento,
            t_tipoevento.tipoevento,
            t_eventocotizacion.fechaplanificada,
            t_eventocotizacion.evento
            FROM 
            t_eventocotizacion 
            LEFT JOIN t_tipoevento ON t_eventocotizacion.idtipoevento=t_tipoevento.idtipoevento
            WHERE 
            t_eventocotizacion.idcotizacion=$idcotizacion;");
        while ($roweventos =  $resulteventos ->fetch(PDO::FETCH_ASSOC)){
            $eventos[]=array(
                'ideventocotizacion'=>(int)$roweventos['ideventocotizacion'],
                'idtipoevento'=>(int)$roweventos['idtipoevento'],
                'tipoevento'=>$roweventos['tipoevento'],
                'fechaplanificada'=>$roweventos['fechaplanificada'],
                'evento'=>$roweventos['evento']
            );
        }
        
        $contemplaciones=[];
        $resultcontemplaciones = $conexion->query("select 
            t_cotizacioncontemplacion.idcotizacioncontemplacion,
            t_cotizacioncontemplacion.idcontemplacion,
            t_contemplacion.contemplacion,
            t_cotizacioncontemplacion.estado
            from 
            t_cotizacioncontemplacion 
            LEFT JOIN t_contemplacion ON t_cotizacioncontemplacion.idcontemplacion=t_contemplacion.idcontemplacion
            WHERE 
            t_cotizacioncontemplacion.idcotizacion=$idcotizacion;");
        while ($rowcontemplaciones =  $resultcontemplaciones ->fetch(PDO::FETCH_ASSOC)){
            $contemplaciones[]=array(
                'idcotizacioncontemplacion'=>(int)$rowcontemplaciones['idcotizacioncontemplacion'],
                'idcontemplacion'=>(int)$rowcontemplaciones['idcontemplacion'],
                'contemplacion'=>$rowcontemplaciones['contemplacion'],
                'estado'=>(int)$rowcontemplaciones['estado']
            );
        }
        
        $consideraciones=[];
        $resultconsideraciones = $conexion->query("select 
            t_cotizacionconsideraciones.idcotizacionconsideraciones,
            t_cotizacionconsideraciones.idconsideraciones,
            t_consideraciones.consideraciones
            from 
            t_cotizacionconsideraciones
            LEFT JOIN t_consideraciones ON t_cotizacionconsideraciones.idconsideraciones=t_consideraciones.idconsideraciones
            WHERE 
            t_cotizacionconsideraciones.idcotizacion=$idcotizacion;");
        while ($rowconsideraciones =  $resultconsideraciones ->fetch(PDO::FETCH_ASSOC)){
            $consideraciones[]=array(
                'idcotizacionconsideraciones'=>(int)$rowconsideraciones['idcotizacionconsideraciones'],
                'idconsideraciones'=>(int)$rowconsideraciones['idconsideraciones'],
                'consideraciones'=>$rowconsideraciones['consideraciones']
            );
        }
        
        
        
        $cotizacion=array(
            'idcotizacion'=>(int)$row['idcotizacion'],
            'numero'=>(int)$row['numero'],
            'gestion'=>(int)$row['gestion'],
            'fecha'=>$row['fecha'],
            'idcliente'=>$row['idcliente'],
            'cliente'=>$row['cliente'],
            'nombre'=>$row['nombre'],
            'idtipoembarque'=>$row['idtipoembarque'],
            'tipoembarque'=>$row['tipoembarque'],
            'importacion_exportacion'=>$row['importacion_exportacion'],
            'importacion_exportacion_codigo'=>$row['importacion_exportacion_codigo'],
            'noidentificacion'=>$row['noidentificacion'],
            'idexpedidor'=>(int)$row['idexpedidor'],
            'idtipoexpedidor'=>(int)$row['idtipoexpedidor'],
            'expedidor'=>$row['expedidor'],
            'descripcioncarga'=>$row['descripcioncarga'],
            'idorigen'=>(int)$row['idorigen'],
            'origen'=>$row['origen'],
            'iddestino'=>(int)$row['iddestino'],
            'destino'=>$row['destino'],
            'peso'=>$row['peso'],
            'volumen'=>$row['volumen'],
            'piezas'=>$row['piezas'],
            'idtipobulto'=>$row['idtipobulto'],
            'idincoterms'=>(int)$row['idincoterms'],
            'incoterms'=>$row['incoterms'],
            'idestadocotizacion'=>(int)$row['idestadocotizacion'],
            'costos'=>$costos,
            'eventos'=>$eventos,
            'contemplaciones'=>$contemplaciones,
            'consideraciones'=>$consideraciones
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'cotizacion' => $cotizacion
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/cotizaciones/{idcotizacion}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcotizacion = $args['idcotizacion'];
    $params = json_decode((string) $request->getBody(),true);
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;

    $idclienteg="NULL";
    $idcliente=$params['idcliente'];
    if((int)$idcliente>0){
        $idclienteg=$idcliente;
    }
    $nombre=$params['nombre'];
    $idtipoembarque= isset($params['idtipoembarque']) ? $params['idtipoembarque'] : 'NULL';
    $importacion_exportacion=isset($params['importacion_exportacion']) ? $params['importacion_exportacion'] : 'NULL';;
    $noidentificacion=$params['noidentificacion'];
    $idexpedidor_split= explode("-", $params['idexpedidor']);
    if(count($idexpedidor_split)==2){
        $idexpedidor=$idexpedidor_split[1];
        $idtipoexpedidor=$idexpedidor_split[0];
    }else{
        $idexpedidor="null";
        $idtipoexpedidor="null";
    }
        
    $descripcioncarga=$params['descripcioncarga'];
    $idorigen=$params['idorigen'];
    $iddestino=$params['iddestino'];
    $peso=$params['peso'];
    $volumen=$params['volumen'];
    $piezas=$params['piezas'];
    $idtipobulto= isset($params['idtipobulto']) ? $params['idtipobulto'] : 'NULL';
    $idincoterms=$params['idincoterms'];
    $eventos=$params['eventos'] ?? [];
    $costos=$params['costos'] ?? [];
    $contemplaciones=$params['contemplaciones'] ?? [];
    $consideraciones=$params['consideraciones'] ?? [];
            
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="UPDATE t_cotizacion SET
        idcliente=$idclienteg,
        nombre='$nombre',
        idtipoembarque=$idtipoembarque,
        importacion_exportacion=$importacion_exportacion,
        noidentificacion='$noidentificacion',
        idexpedidor=$idexpedidor,
        idtipoexpedidor=$idtipoexpedidor,
        descripcioncarga='$descripcioncarga',
        idorigen='$idorigen',
        iddestino='$iddestino',
        peso='$peso',
        volumen='$volumen',
        piezas='$piezas',
        idtipobulto=$idtipobulto,
        idincoterms='$idincoterms'
        WHERE
        idcotizacion=$idcotizacion;";
    
    for($cc=0;$cc<count($eventos);$cc++){
        if((int)$eventos[$cc]['ideventocotizacion']>0){
            $query=$query."UPDATE t_eventocotizacion SET 
                idtipoevento='".$eventos[$cc]["idtipoevento"]."',
                fechaplanificada='".$eventos[$cc]["fechaplanificada"]."',
                evento='".$eventos[$cc]["evento"]."'
                WHERE
                ideventocotizacion=".$eventos[$cc]['ideventocotizacion'].";";
        }else{
            $query=$query."INSERT INTO t_eventocotizacion (idcotizacion,    idtipoevento,                           fechaplanificada,                           evento,                         idusuario,                          fecharegistro)
                                                   VALUES ($idcotizacion,   '".$eventos[$cc]["idtipoevento"]."',    '".$eventos[$cc]["fechaplanificada"]."',    '".$eventos[$cc]["evento"]."',  ".$decoded_array["idusuario"].",    CURRENT_TIMESTAMP());";
        }
    }

    $resulteventos = $conexion->query("SELECT
        ideventocotizacion
        FROM
        t_eventocotizacion
        WHERE
        idcotizacion=$idcotizacion;");
    while ($roweventos =  $resulteventos ->fetch(PDO::FETCH_ASSOC)){
        $key = array_search($roweventos['ideventocotizacion'], array_column($eventos, 'ideventocotizacion'));
        if(!is_numeric($key)){
            $query=$query."DELETE FROM t_eventocotizacion WHERE ideventocotizacion=".$roweventos['ideventocotizacion'].";";
        }
    }
    
    
    for($cc=0;$cc<count($costos);$cc++){
        if((int)$costos[$cc]['idcostocotizacion']>0){
            $query=$query."UPDATE t_costocotizacion SET 
                idconcepto='".$costos[$cc]["idconcepto"]."',
                cantidad='".$costos[$cc]["cantidad"]."',
                montocargo='".$costos[$cc]["montocargo"]."',
                montocosto='".$costos[$cc]["montocosto"]."',
                iddivisa='".$costos[$cc]["iddivisa"]."'
                WHERE
                idcostocotizacion=".$costos[$cc]['idcostocotizacion'].";";
        }else{
            $query=$query."INSERT INTO t_costocotizacion (idcotizacion,     idconcepto,                         cantidad,                       montocargo,                         montocosto,                         iddivisa)
                                                  VALUES ($idcotizacion,    '".$costos[$cc]["idconcepto"]."',   '".$costos[$cc]["cantidad"]."', '".$costos[$cc]["montocargo"]."',   '".$costos[$cc]["montocosto"]."',   '".$costos[$cc]["iddivisa"]."');";
        }
    }

    $resultcostos = $conexion->query("SELECT
        idcostocotizacion
        FROM
        t_costocotizacion
        WHERE
        idcotizacion=$idcotizacion;");
    while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC)){
        $key = array_search($rowcostos['idcostocotizacion'], array_column($costos, 'idcostocotizacion'));
        if(!is_numeric($key)){
            $query=$query."DELETE FROM t_costocotizacion WHERE idcostocotizacion=".$rowcostos['idcostocotizacion'].";";
        }
    }
    
    $contemplaciones_actuales=[];
    $resultcontemplaciones = $conexion->query("SELECT 
        idcotizacioncontemplacion, 
        idcontemplacion 
        FROM  
        t_cotizacioncontemplacion 
        WHERE 
        idcotizacion=$idcotizacion;");
    while ($rowcontemplaciones =  $resultcontemplaciones ->fetch(PDO::FETCH_ASSOC)){
        $contemplaciones_actuales[]=array(
            'idcotizacioncontemplacion'=>(int)$rowcontemplaciones['idcotizacioncontemplacion'],
            'idcontemplacion'=>(int)$rowcontemplaciones['idcontemplacion']
        );
    }
    for($cc=0;$cc<count($contemplaciones);$cc++){
        $key = array_search($contemplaciones[$cc]['idcontemplacion'], array_column($contemplaciones_actuales, 'idcontemplacion'));
        if(is_numeric($key)){
            $query=$query."UPDATE t_cotizacioncontemplacion SET estado=".$contemplaciones[$cc]['estado']." WHERE idcotizacioncontemplacion=".$contemplaciones_actuales[$key]["idcotizacioncontemplacion"].";";
        }else{
            $query=$query."INSERT INTO t_cotizacioncontemplacion (idcotizacion,   idcontemplacion,                              estado) 
                                                          VALUES ($idcotizacion,  ".$contemplaciones[$cc]['idcontemplacion'].", ".$contemplaciones[$cc]['estado'].");";
        }
    }
    for($cc=0;$cc<count($contemplaciones_actuales);$cc++){
        $key = array_search($contemplaciones_actuales[$cc]['idcontemplacion'], array_column($contemplaciones, 'idcontemplacion'));
        if(!is_numeric($key)){
            $query=$query."DELETE FROM t_cotizacioncontemplacion WHERE idcotizacioncontemplacion=".$contemplaciones_actuales[$cc]['idcotizacioncontemplacion'].";";
        }
    }
    
    
    $consideraciones_actuales=[];
    $resultconsideraciones = $conexion->query("SELECT
        idcotizacionconsideraciones,
        idconsideraciones
        FROM
        t_cotizacionconsideraciones
        WHERE
        idcotizacion=$idcotizacion;");
    while ($rowconsideraciones =  $resultconsideraciones ->fetch(PDO::FETCH_ASSOC)){
        $consideraciones_actuales[]=array(
            'idcotizacionconsideraciones'=>(int)$rowconsideraciones['idcotizacionconsideraciones'],
            'idconsideraciones'=>(int)$rowconsideraciones['idconsideraciones']
        );
    }
    for($cc=0;$cc<count($consideraciones);$cc++){
        $key = array_search($consideraciones[$cc]['idconsideraciones'], array_column($consideraciones_actuales, 'idconsideraciones'));
        if(!is_numeric($key)){
            $query=$query."INSERT INTO t_cotizacionconsideraciones (idcotizacion,   idconsideraciones) 
                                                            VALUES ($idcotizacion,  ".$consideraciones[$cc]['idconsideraciones'].");";
        }
    }
    for($cc=0;$cc<count($consideraciones_actuales);$cc++){
        $key = array_search($consideraciones_actuales[$cc]['idconsideraciones'], array_column($consideraciones, 'idconsideraciones'));
        if(!is_numeric($key)){
            $query=$query."DELETE FROM t_cotizacionconsideraciones WHERE idcotizacionconsideraciones=".$consideraciones_actuales[$cc]['idcotizacionconsideraciones'].";";
        }
    }
    
    
    
    $result = $conexion->exec($query);

    if($result===false){

    }else{
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

$app->get('/cotizaciones/{idcotizacion}/documento/{iddivisa}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcotizacion = $args['idcotizacion'];
    $iddivisa = $args['iddivisa'];
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
    
    $file=folder_files.$idempresa.DIRECTORY_SEPARATOR."documentos/cotizaciones/cotizacion$idcotizacion.pdf";
    if(file_exists($file)){
        unlink($file);
    }

    generarCotizacion($idcotizacion,$iddivisa,$conexion, false);

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

$app->post('/cotizaciones/{idcotizacion}/crearembarque', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcotizacion = $args['idcotizacion'];
    $params = json_decode((string) $request->getBody(),true);
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idusuario=$decoded_array["idusuario"];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    $idembarque=0;
    
    $query="INSERT INTO t_embarque (idcotizacion, idempresa, idcliente, noidentificacion, idexpedidor, idtipoexpedidor, descripcioncarga, piezas, idtipobulto, peso, volumen, idincoterms, idusuario, fecharealizacion, idorigen, iddestino, idciudad, idtipoembarque, importacion_exportacion, embarque,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       correlativo,                                                                                                                                                                                                                                                                                          gestion,                  idtipoultimoconsignatario,  idultimoconsignatario,  idultimoconsignatariodireccion) 
                             SELECT idcotizacion, idempresa, idcliente, noidentificacion, idexpedidor, idtipoexpedidor, descripcioncarga, piezas, idtipobulto, peso, volumen, idincoterms, idusuario, CURRENT_DATE(),   idorigen, iddestino, idciudad, idtipoembarque, importacion_exportacion, CONCAT((SELECT t_importacion_exportacion.importacion_exportacion_codigo FROM t_importacion_exportacion WHERE t_importacion_exportacion.importacion_exportacion=t_cotizacion.importacion_exportacion),'-',(SELECT t_tipoembarque.codigo FROM t_tipoembarque WHERE t_tipoembarque.idtipoembarque=t_cotizacion.idtipoembarque),'-',(SELECT IFNULL(MAX(t_embarque.correlativo),0)+1 FROM t_embarque WHERE t_embarque.gestion=YEAR(CURRENT_DATE()) AND t_embarque.idtipoembarque=t_cotizacion.idtipoembarque AND t_embarque.importacion_exportacion=t_cotizacion.importacion_exportacion AND t_embarque.idempresa=t_cotizacion.idempresa),'-',YEAR(CURRENT_DATE())), (SELECT IFNULL(MAX(t_embarque.correlativo),0)+1 FROM t_embarque WHERE t_embarque.gestion=YEAR(CURRENT_DATE()) AND t_embarque.idtipoembarque=t_cotizacion.idtipoembarque AND t_embarque.importacion_exportacion=t_cotizacion.importacion_exportacion AND t_embarque.idempresa=t_cotizacion.idempresa), YEAR(CURRENT_DATE()),     1,                          idcliente,              (SELECT t_clientedireccion.idclientedireccion FROM t_clientedireccion WHERE t_clientedireccion.idcliente=t_cotizacion.idcliente ORDER BY t_clientedireccion.idclientedireccion LIMIT 1) FROM t_cotizacion WHERE idcotizacion=$idcotizacion;
            SELECT LAST_INSERT_ID() INTO @idembarque_nuevo;";
    
    $query=$query."INSERT INTO t_costo (idembarque,        idconcepto, cantidad, monto,      iddivisa, idusuario,   created_at) 
                                SELECT @idembarque_nuevo,  idconcepto, cantidad, montocosto, iddivisa, $idusuario,  CURRENT_TIMESTAMP() from t_costocotizacion WHERE idcotizacion=$idcotizacion;";
    
    
    
    $query=$query."INSERT INTO t_cargo (idembarque, idconcepto, cantidad, monto, iddivisa) 
        SELECT @idembarque_nuevo,
        t_concepto.idconceptocargo as idconcepto, 
        t_costocotizacion.cantidad, 
        t_costocotizacion.montocargo, 
        t_costocotizacion.iddivisa 
        from 
        t_costocotizacion
        LEFT JOIN t_concepto ON t_costocotizacion.idconcepto=t_concepto.idconcepto
        WHERE t_costocotizacion.idcotizacion=$idcotizacion;";
    
    $query=$query."INSERT INTO t_evento (idembarque,        idtipoevento, fechaplanificada, evento, idusuario, fecharegistro) 
                                  SELECT @idembarque_nuevo, idtipoevento, fechaplanificada, evento, idusuario, fecharegistro FROM t_eventocotizacion WHERE idcotizacion=$idcotizacion;";
    
    $query=$query."UPDATE t_cotizacion SET idestadocotizacion=4 WHERE idcotizacion=$idcotizacion;";
    
    
    $result = $conexion->exec($query);
    if($result===false){

    }else{
        $result = $conexion->query("SELECT @idembarque_nuevo as idembarque;");
        while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
            $idembarque=$row['idembarque'];
        }

        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información de Ruta';
    }
    
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idembarque'=>$idembarque
    );
    
    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/embarques', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $params = json_decode((string) $request->getBody(),true);
    $embarques=[];
    
    
    $filtrocliente='';
    if((int)$params["idcliente"]>0){
        $filtrocliente=" AND t_embarque.idcliente=".$params["idcliente"];
    }
    $filtrofecha='';
    if(isset($params["fechainicial"]) && isset($params["fechafinal"])){
        $filtrofecha=" AND t_embarque.fecharealizacion BETWEEN '".$params["fechainicial"]."' AND '".$params["fechafinal"]."'";
    }
    
    
    $filtroimpexp='';
    if(isset($params["importacion_exportacion"])){
        $filtroimpexp=" AND t_embarque.importacion_exportacion=".$params["importacion_exportacion"];
    }
    
    
    
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_valorcargado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_valorcargado (idembarque INT, valorcargado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_valorcargado (idembarque, valorcargado)
        SELECT
        t_cargo.idembarque,
        SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as valorcargo
        FROM
        t_cargo
        LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
        LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
        LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE())) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE
        t_embarque.idempresa=$idempresa
        AND ifnull(t_factura.idestadofactura,0) <> 2
        AND ifnull(t_notadebito.idestadonotadebito,0) <> 2
        $filtrocliente
        $filtroimpexp
        $filtrofecha
        GROUP BY
        t_cargo.idembarque;");
    $conexion->query("ALTER TABLE tmp_valorcargado ADD INDEX idembarque (idembarque);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_valorcosteado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_valorcosteado (idembarque INT, valorcosteado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_valorcosteado (idembarque, valorcosteado)
        SELECT
        t_costo.idembarque,
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) as valorcosteado
	FROM
	t_costo
        LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
        LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE())) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
        t_embarque.idempresa=$idempresa
        AND ifnull(t_facturapago.idestadofacturapago,0) <> 2
        $filtrocliente
        $filtroimpexp
        $filtrofecha
        GROUP BY
        t_costo.idembarque;");
    $conexion->query("ALTER TABLE tmp_valorcosteado ADD INDEX idembarque (idembarque);");
    
    
    $result = $conexion->query("SELECT
        t_embarque.idembarque,
        t_embarque.fecharealizacion,
        t_embarque.importacion_exportacion,
        t_embarque.idtipoembarque,
        t_embarque.gestion,
        t_embarque.correlativo,
        CONCAT(t_importacion_exportacion.importacion_exportacion_codigo,'-',t_tipoembarque.codigo,'-',t_embarque.correlativo,'-',t_embarque.gestion) as embarque,
        t_embarque.idcliente,
        t_cliente.cliente,
        t_embarque.nodui,
        t_embarque.descripcioncarga,
        IF(IFNULL(t_embarque.fechafinalizacion,'0000-00-00')='0000-00-00',0,1) as finalizado,
        IFNULL(tmp_valorcargado.valorcargado,0) as valorcargado,
        IFNULL(tmp_valorcosteado.valorcosteado,0) as valorcosteado,
        IFNULL(tmp_valorcargado.valorcargado,0)-IFNULL(tmp_valorcosteado.valorcosteado,0) as balance
        FROM
        t_embarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_importacion_exportacion ON t_embarque.importacion_exportacion=t_importacion_exportacion.importacion_exportacion
        LEFT JOIN t_tipoembarque ON t_embarque.idtipoembarque=t_tipoembarque.idtipoembarque
        LEFT JOIN tmp_valorcargado ON t_embarque.idembarque=tmp_valorcargado.idembarque
        LEFT JOIN tmp_valorcosteado ON t_embarque.idembarque=tmp_valorcosteado.idembarque
        WHERE 
        t_embarque.idempresa=$idempresa
        $filtrocliente
        $filtroimpexp
        $filtrofecha;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        
        $costos=[];
        $cargos=[];

        $embarques[]=array(
            'idembarque'=>(int)$row['idembarque'],
            'fecharealizacion'=>$row['fecharealizacion'],
            'importacion_exportacion'=>(int)$row['importacion_exportacion'],
            'idtipoembarque'=>(int)$row['idtipoembarque'],
            'gestion'=>(int)$row['gestion'],
            'correlativo'=>(int)$row['correlativo'],
            'embarque'=>$row['embarque'],
            'idcliente'=>(int)$row['idcliente'],
            'cliente'=>$row['cliente'],
            'nodui'=>$row['nodui'],
            'descripcioncarga'=>$row['descripcioncarga'],
            'finalizado'=>boolval($row['finalizado']),
            'valorcargado'=>(float)$row['valorcargado'],
            'valorcosteado'=>(float)$row['valorcosteado'],
            'balance'=>(float)$row['balance'],
            'costos'=>$costos,
            'cargos'=>$cargos
            
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'embarques' => $embarques
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/embarques/crear', function(Request $request, Response $response, array $args) use ($conexion) {

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';
    $idembarque = 0;

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
        $idciudad = $decoded_array["idciudad"] ?? null;
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

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idempresa)) {
            $mensaje = 'No se recibió la empresa';
            $continuar = false;
        }

        if ($continuar && empty($idciudad)) {
            $mensaje = 'No se recibió la ciudad';
            $continuar = false;
        }

        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        if ($continuar && empty($params["idcliente"])) {
            $mensaje = 'No se recibió el cliente';
            $continuar = false;
        }

        if ($continuar && empty($params["idtipoembarque"])) {
            $mensaje = 'No se recibió el tipo de embarque';
            $continuar = false;
        }

        if ($continuar && !isset($params["importacion_exportacion"])) {
            $mensaje = 'No se recibió el tipo de operación';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Buscar dirección del último consignatario
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idultimoconsignatariodireccion = null;

            $queryDireccion = "
                SELECT idclientedireccion
                FROM t_clientedireccion
                WHERE idcliente = :idcliente
                ORDER BY idclientedireccion
                LIMIT 1
            ";

            $stmtDireccion = $conexion->prepare($queryDireccion);

            $stmtDireccion->execute([
                ':idcliente' => $params["idcliente"]
            ]);

            $rowDireccion = $stmtDireccion->fetch(PDO::FETCH_ASSOC);

            if ($rowDireccion) {
                $idultimoconsignatariodireccion = $rowDireccion['idclientedireccion'];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Crear embarque
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $query = "
                INSERT INTO t_embarque (
                    idempresa,
                    idcliente,
                    embarque,
                    correlativo,
                    gestion,
                    idtipoembarque,
                    importacion_exportacion,
                    idciudad,
                    idusuario,
                    fecharealizacion,
                    idtipoultimoconsignatario,
                    idultimoconsignatario,
                    idultimoconsignatariodireccion
                )
                SELECT
                    :idempresa,
                    :idcliente,
                    CONCAT(
                        ie.importacion_exportacion_codigo,
                        '-',
                        te.codigo,
                        '-',
                        correlativo_calc.nuevo_correlativo,
                        '-',
                        YEAR(CURRENT_DATE())
                    ) AS embarque,
                    correlativo_calc.nuevo_correlativo,
                    YEAR(CURRENT_DATE()),
                    :idtipoembarque,
                    :importacion_exportacion,
                    :idciudad,
                    :idusuario,
                    CURRENT_DATE(),
                    1,
                    :idultimoconsignatario,
                    :idultimoconsignatariodireccion
                FROM t_importacion_exportacion ie
                INNER JOIN t_tipoembarque te 
                    ON te.idtipoembarque = :idtipoembarque_te
                CROSS JOIN (
                    SELECT 
                        IFNULL(MAX(correlativo), 0) + 1 AS nuevo_correlativo
                    FROM t_embarque
                    WHERE gestion = YEAR(CURRENT_DATE())
                      AND idtipoembarque = :idtipoembarque_corr
                      AND importacion_exportacion = :importacion_exportacion_corr
                      AND idempresa = :idempresa_corr
                ) correlativo_calc
                WHERE ie.importacion_exportacion = :importacion_exportacion_ie
                LIMIT 1
            ";

            $stmt = $conexion->prepare($query);

            $result = $stmt->execute([
                ':idempresa' => $idempresa,
                ':idcliente' => $params["idcliente"],

                ':idtipoembarque' => $params["idtipoembarque"],
                ':idtipoembarque_te' => $params["idtipoembarque"],
                ':idtipoembarque_corr' => $params["idtipoembarque"],

                ':importacion_exportacion' => $params["importacion_exportacion"],
                ':importacion_exportacion_corr' => $params["importacion_exportacion"],
                ':importacion_exportacion_ie' => $params["importacion_exportacion"],

                ':idempresa_corr' => $idempresa,

                ':idciudad' => $idciudad,
                ':idusuario' => $idusuario,

                ':idultimoconsignatario' => $params["idcliente"],
                ':idultimoconsignatariodireccion' => $idultimoconsignatariodireccion
            ]);

            if (!$result) {
                $mensaje = 'No se pudo registrar el embarque';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idembarque = (int)$conexion->lastInsertId();

                if ($idembarque <= 0) {
                    $mensaje = 'No se pudo obtener el embarque generado';
                    $continuar = false;

                    if ($conexion->inTransaction()) {
                        $conexion->rollBack();
                    }
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
            $mensaje = 'Se guardó la información correctamente';
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
        'idembarque' => $idembarque
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->post('/embarques/{idembarque}/duplicar', function(Request $request, Response $response, array $args) use ($conexion,$archivospermitidos) {
    $idembarque = $args['idembarque'];
    $params = json_decode((string) $request->getBody(),true);
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="INSERT INTO t_embarque (idempresa,idcliente,embarque, correlativo, gestion, idtipoembarque,importacion_exportacion,numeroguia,idciudad,idusuario,fecharealizacion,noidentificacion,descripcioncarga,peso,volumen,piezas,nodui,idincoterms,servicio_logistico,idexpedidor, idexpedidordireccion,idtipoexpedidor,idultimoconsignatario,idultimoconsignatariodireccion,idtipoultimoconsignatario,idmediotransporte,idtipocarga,idtransportista,numerovehiculo,idsalida,fechasalida,idarribo,fechaarribo,idhorario,idtemperatura,numero_precinto,estibadoresSLG,estibadores,costo_operador_transporte) 
        SELECT
        idempresa,
        idcliente,
        CONCAT((SELECT importacion_exportacion_codigo FROM t_importacion_exportacion WHERE importacion_exportacion=t_embarque.importacion_exportacion),'-',(SELECT codigo FROM t_tipoembarque WHERE idtipoembarque=t_embarque.idtipoembarque),'-',(SELECT IFNULL(MAX(t_embarque_clon.correlativo),0)+1 FROM t_embarque as t_embarque_clon WHERE t_embarque_clon.gestion=YEAR(CURRENT_DATE()) AND t_embarque_clon.idtipoembarque=t_embarque.idtipoembarque AND t_embarque_clon.importacion_exportacion=t_embarque.importacion_exportacion AND t_embarque_clon.idempresa=t_embarque.idempresa),'-',YEAR(CURRENT_DATE())) as embarque,
        (SELECT IFNULL(MAX(t_embarque_clon.correlativo),0)+1 FROM t_embarque as t_embarque_clon WHERE t_embarque_clon.gestion=YEAR(CURRENT_DATE()) AND t_embarque_clon.idtipoembarque=t_embarque.idtipoembarque AND t_embarque_clon.importacion_exportacion=t_embarque.importacion_exportacion AND t_embarque_clon.idempresa=t_embarque.idempresa) as correlativo,
        YEAR(CURRENT_DATE()) as gestion,
        idtipoembarque,
        importacion_exportacion,
        numeroguia,
        idciudad,
        ".$decoded_array["idusuario"]." as idusuario,
        CURRENT_DATE() as fecharealizacion,
        noidentificacion,
        descripcioncarga,
        peso,
        volumen,
        piezas,
        nodui,
        idincoterms,
        servicio_logistico,
        idexpedidor, 
        idexpedidordireccion,
        idtipoexpedidor,
        idultimoconsignatario,
        idultimoconsignatariodireccion,
        idtipoultimoconsignatario,
        idmediotransporte,
        idtipocarga,
        idtransportista,
        numerovehiculo,
        idsalida,
        fechasalida,
        idarribo,
        fechaarribo,
        idhorario,
        idtemperatura,
        numero_precinto,
        estibadoresSLG,
        estibadores,
        costo_operador_transporte
        FROM
        t_embarque
        WHERE
        idembarque=$idembarque;
        SELECT LAST_INSERT_ID() INTO @idembarque_nuevo;";
    
    $query=$query."INSERT INTO t_costo (idembarque,         idconcepto, iddivisa, monto, cantidad, iddestinatario, idtipodestinatario, notas, esagente)
                                SELECT @idembarque_nuevo,   idconcepto, iddivisa, monto, cantidad, iddestinatario, idtipodestinatario, notas, esagente from t_costo WHERE idembarque=$idembarque;";
    $query=$query."INSERT INTO t_cargo (idembarque,         idconcepto, iddivisa, monto, cantidad, iddestinatario, idtipodestinatario, notas, esagente)
                                select @idembarque_nuevo,   idconcepto, iddivisa, monto, cantidad, iddestinatario, idtipodestinatario, notas, esagente from t_cargo WHERE idembarque=$idembarque;";
    
    
    $result = $conexion->exec($query);
    if($result===false){

    }else{
        $result = $conexion->query("SELECT
            @idembarque_nuevo as idembarque;");
        while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
            $idembarque_nuevo=$row['idembarque'];
        }

        $codigo=200;
        $status='Exito';
        $mensaje='Se duplico el embarque correctamente';
    }
    

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idembarque'=>$idembarque_nuevo,
        'query'=>$query
    );
    
    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/embarques/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $embarque=[];
    $result = $conexion->query("SELECT
        t_embarque.idempresa,
        t_embarque.idembarque,
        t_embarque.fecharealizacion,
        t_embarque.importacion_exportacion,
        t_embarque.idtipoembarque,
        t_embarque.gestion,
        t_embarque.correlativo,
        CONCAT(t_importacion_exportacion.importacion_exportacion_codigo,'-',t_tipoembarque.codigo,'-',t_embarque.correlativo,'-',t_embarque.gestion) as embarque,
        t_embarque.idcliente,
        t_cliente.cliente,
        t_embarque.numeroguia,
        t_embarque.idciudad,
        t_embarque.idusuario,
        t_usuario.nombre,
        t_embarque.valordeclarado,
        t_embarque.descripcioncarga,
        t_embarque.carpetapacena,
        IFNULL(t_embarque.servicio_logistico,0) as servicio_logistico,
        t_embarque.peso,
        t_embarque.volumen,
        t_embarque.piezas,
        t_embarque.idtipobulto,
        t_embarque.nodui,
        t_embarque.noidentificacion,
        t_embarque.idincoterms,
        IF(IFNULL(t_embarque.fechafinalizacion,'0000-00-00')='0000-00-00',0,1) as finalizado,
        t_embarque.fechafinalizacion,
        IFNULL(t_embarque.idtipoexpedidor,0) as idtipoexpedidor,
        IFNULL(t_embarque.idexpedidor,0) as idexpedidor,
        t_embarque.idexpedidordireccion,
        IFNULL(t_embarque.idtipoultimoconsignatario,0) as idtipoultimoconsignatario,
        IFNULL(t_embarque.idultimoconsignatario,0) as idultimoconsignatario,
        t_embarque.idultimoconsignatariodireccion,
        IFNULL(t_embarque.idtipoentidadnotificar,0) as idtipoentidadnotificar,
        IFNULL(t_embarque.identidadnotificar,0) as identidadnotificar,
        t_embarque.identidadnotificardireccion,
        t_embarque.idagentecarga,
        t_embarque.idagentecargadireccion,
        t_embarque.idagentedestino,
        t_embarque.idagentedestinodireccion,
        t_embarque.idmediotransporte,
        t_embarque.idtipocarga,
        t_embarque.idtransportista,
        t_embarque.numerovehiculo,
        t_embarque.idsalida,
        t_embarque.fechasalida,
        t_embarque.idarribo,
        t_embarque.fechaarribo,
        t_embarque.idorigen,
        t_embarque.iddestino,
        t_embarque.idhorario,
        t_embarque.idtemperatura,
        t_embarque.numero_precinto,
        t_embarque.estibadoresSLG,
        t_embarque.estibadores,
        t_embarque.costo_operador_transporte
        FROM
        t_embarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_importacion_exportacion ON t_embarque.importacion_exportacion=t_importacion_exportacion.importacion_exportacion
        LEFT JOIN t_tipoembarque ON t_embarque.idtipoembarque=t_tipoembarque.idtipoembarque
        LEFT JOIN t_usuario ON t_embarque.idusuario=t_usuario.idusuario
        WHERE
        t_embarque.idembarque=$idembarque;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idempresa=$row['idempresa'];
        
        
        $eventos=[];
        $resulteventos = $conexion->query("SELECT
            t_evento.idevento,
            t_evento.idtipoevento,
            t_tipoevento.tipoevento,
            t_evento.fecharegistro,
            t_evento.fechaplanificada,
            IFNULL(t_evento.con_observacion,0) as con_observacion,
            t_evento.ideventodescripcion,
            t_eventodescripcion.eventodescripcion,
            t_evento.evento,
            t_evento.idusuario,
            t_usuario.nombre,
            IFNULL(t_evento.enviado,0) as enviado
            FROM
            t_evento
            LEFT JOIN t_tipoevento ON t_evento.idtipoevento=t_tipoevento.idtipoevento
            LEFT JOIN t_usuario ON t_evento.idusuario=t_usuario.idusuario
            LEFT JOIN t_eventodescripcion ON t_evento.ideventodescripcion=t_eventodescripcion.ideventodescripcion
            WHERE
            t_evento.idembarque=$idembarque
            ORDER BY
            t_evento.fechaplanificada;");
        while ($roweventos =  $resulteventos ->fetch(PDO::FETCH_ASSOC)){
            $eventos[]=array(
                'idevento'=>(int)$roweventos['idevento'],
                'idtipoevento'=>(int)$roweventos['idtipoevento'],
                'tipoevento'=>$roweventos['tipoevento'],
                'fecharegistro'=>$roweventos['fecharegistro'],
                'fechaplanificada'=>$roweventos['fechaplanificada'],
                'con_observacion'=> boolval($roweventos['con_observacion']),
                'ideventodescripcion'=>(int)$roweventos['ideventodescripcion'],
                'eventodescripcion'=>$roweventos['eventodescripcion'],
                'evento'=>$roweventos['evento'],
                'idusuario'=>(int)$roweventos['idusuario'],
                'nombre'=>$roweventos['nombre'],
                'enviado'=>boolval($roweventos['enviado'])
            );
        }
        
        $correosembarque=[];
        $resultcorreosembarque = $conexion->query("SELECT idcorreosembarque, correo from t_correosembarque WHERE idembarque=$idembarque;");
        while ($rowcorreosembarque =  $resultcorreosembarque ->fetch(PDO::FETCH_ASSOC)){
            $correosembarque[]=array(
                'idcorreosembarque'=>(int)$rowcorreosembarque['idcorreosembarque'],
                'correo'=>$rowcorreosembarque['correo']
            );
        }
        
        $documentosembarque=[];
        $ruta = folder_files.$idempresa.DIRECTORY_SEPARATOR."embarques/".$idembarque."/";
        if(file_exists($ruta)){
            $directorio = opendir($ruta);
            $iddocumento=0;
            while ($documento = readdir($directorio)){
                if (!is_dir($documento)){
                    $iddocumento++;
                    $documentosembarque[] = array(
                        'iddocumento' => $iddocumento,
                        'documento' => $documento
                    );
                }
            }
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        $idexpedidordireccion=null;
        if((int)$row["idexpedidordireccion"]>0){
            $idexpedidordireccion=(int)$row["idexpedidordireccion"];
        }
        
        $idultimoconsignatariodireccion=null;
        if((int)$row["idultimoconsignatariodireccion"]>0){
            $idultimoconsignatariodireccion=(int)$row["idultimoconsignatariodireccion"];
        }
        
        /*
        $identidadnotificardireccion=null;
        if((int)$row["identidadnotificardireccion"]>0){
            $identidadnotificardireccion=(int)$row["identidadnotificardireccion"];
        }
        
        $idagentecargadireccion=null;
        if((int)$row["idagentecargadireccion"]>0){
            $idagentecargadireccion=(int)$row["idagentecargadireccion"];
        }
        
        $idagentedestinodireccion=null;
        if((int)$row["idagentedestinodireccion"]>0){
            $idagentedestinodireccion=(int)$row["idagentedestinodireccion"];
        }
        */
        
        $embarque=array(
            'idembarque'=>(int)$row['idembarque'],
            'fecharealizacion'=>$row['fecharealizacion'],
            'importacion_exportacion'=>(int)$row['importacion_exportacion'],
            'idtipoembarque'=>(int)$row['idtipoembarque'],
            'gestion'=>(int)$row['gestion'],
            'correlativo'=>(int)$row['correlativo'],
            'embarque'=>$row['embarque'],
            'idcliente'=>(int)$row['idcliente'],
            'cliente'=>$row['cliente'],
            'numeroguia'=>$row['numeroguia'],
            'idciudad'=>(int)$row['idciudad'],
            'idusuario'=>(int)$row['idusuario'],
            'nombre'=>$row['nombre'],
            //'valordeclarado'=>$row['valordeclarado'],
            'descripcioncarga'=>$row['descripcioncarga'],
            'carpetapacena'=>$row['carpetapacena'],
            'servicio_logistico'=> boolval($row['servicio_logistico']),
            'peso'=>$row['peso'],
            'volumen'=>$row['volumen'],
            'piezas'=>$row['piezas'],
            'idtipobulto'=>$row['idtipobulto'],
            'nodui'=>$row['nodui'],
            'noidentificacion'=>$row['noidentificacion'],
            'idincoterms'=>(int)$row['idincoterms'],
            'finalizado'=>boolval($row['finalizado']),
            'fechafinalizacion'=>$row['fechafinalizacion'],
            //'idtipoexpedidor'=>(int)$row['idtipoexpedidor'],
            //'idexpedidor'=>(int)$row['idexpedidor'],
            'idexpedidor'=>$row['idtipoexpedidor']."-".$row['idexpedidor'],
            'idexpedidordireccion'=>$idexpedidordireccion,
            //'idtipoultimoconsignatario'=>(int)$row['idtipoultimoconsignatario'],
            'idultimoconsignatario'=>$row['idtipoultimoconsignatario']."-".$row['idultimoconsignatario'],
            'idultimoconsignatariodireccion'=>$idultimoconsignatariodireccion,
            //'idtipoentidadnotificar'=>(int)$row['idtipoentidadnotificar'],
            //'identidadnotificar'=>$row['idtipoentidadnotificar']."-".$row['identidadnotificar'],
            //'identidadnotificardireccion'=>$identidadnotificardireccion,
            //'idagentecarga'=>(int)$row['idagentecarga'],
            //'idagentecargadireccion'=>$idagentecargadireccion,
            //'idagentedestino'=>(int)$row['idagentedestino'],
            //'idagentedestinodireccion'=>$idagentedestinodireccion,
            'idmediotransporte'=>$row['idmediotransporte'],
            'idtipocarga'=>$row['idtipocarga'],
            'idtransportista'=>(int)$row['idtransportista'],
            'numerovehiculo'=>$row['numerovehiculo'],
            'idsalida'=>(int)$row['idsalida'],
            'fechasalida'=>$row['fechasalida'],
            'idarribo'=>(int)$row['idarribo'],
            'fechaarribo'=>$row['fechaarribo'],
            'idorigen'=>(int)$row['idorigen'],
            'iddestino'=>(int)$row['iddestino'],
            'idhorario'=>$row['idhorario'],
            'idtemperatura'=>$row['idtemperatura'],
            'numero_precinto'=>$row['numero_precinto'],
            'estibadoresSLG'=>$row['estibadoresSLG'],
            'estibadores'=>$row['estibadores'],
            'costo_operador_transporte'=>$row['costo_operador_transporte'],
            'costos'=> getCostos($idembarque, $conexion),
            'cargos'=> getCargos($idembarque,$conexion),
            'eventos'=>$eventos,
            'correosembarque'=>$correosembarque,
            'documentosembarque'=>$documentosembarque,
            'facturas'=> getFacturas($idembarque, $conexion),
            'notascobranza'=> getNotasCobranza($idembarque, $conexion),
            'facturaspago'=> getFacturasPago($idembarque, $conexion),
            'planillas'=> getPlanillas($idembarque, $conexion),
            'invoices'=> getInvoices($idembarque, $conexion),
            'ordenserviciosi'=> getOrdenServicioI($idembarque, $conexion),
            'ordenserviciose'=> getOrdenServicioE($idembarque, $conexion)
            
        );
        
    }
    
    
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'embarque' => $embarque
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/embarques/{idembarque}/cargardocumento', function(Request $request, Response $response, array $args) use ($conexion,$archivospermitidos) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);

    $codigo=200;
    $status='Exito';
    $mensaje='El Archivo no se subio, probablemente sea mayor a 10MB';
    $mensaje='';
    $file_name=[];
    
    if(isset($_FILES['uploads'])){
        for($fi=0;$fi<count($_FILES['uploads']["name"]);$fi++){
            $nombredoc=$_FILES['uploads']["name"][$fi];
            //$random = bin2hex(openssl_random_pseudo_bytes(5));
            //$nombredoc=$random."_".$nombredoc;

            $piramideUploader=new PiramideUploader();
            $upload =$piramideUploader->upload($nombredoc, 'uploads', folder_files.$idempresa.DIRECTORY_SEPARATOR.'embarques/'.$idembarque, $archivospermitidos, true, $fi);

            $file=$piramideUploader->getInfoFile();
            
            if(isset($upload) && $upload['uploaded']==false){
                $codigo=200;
                $file_name[]=array(
                    'name'=>$file['complete_name'],
                    'error'=>true,
                    'mensaje'=>$upload['error']
                );
            }else{
                $file_name[]=array(
                    'name'=>$file['complete_name'],
                    'error'=>false,
                    'mensaje'=>''
                );
            }

        }

    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'file_name'=>$file_name
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/embarques/{idembarque}/download/{archivo}', function(Request $request, Response $response, array $args) {
    $idembarque = $args['idembarque'];
    $archivo = $args['archivo'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    
    $codigo=400;
    $status='Error';
    $mensaje='Documento inexistente';
    $data='';
    $pathinfo='';
    $file=folder_files.$idempresa.DIRECTORY_SEPARATOR.'embarques/'.$idembarque.'/'.$archivo;
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

$app->delete('/embarques/{idembarque}/eliminardocumento/{archivo}', function(Request $request, Response $response, array $args) {
    $idembarque = $args['idembarque'];
    $archivo = $args['archivo'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='El archivo no existe';
    $file=folder_files.$idempresa.DIRECTORY_SEPARATOR.'embarques/'.$idembarque.'/'.$archivo;
    if (file_exists($file)) {
        if(unlink($file)){
            $codigo=200;
            $status=400;
            $mensaje="Se elimino el archivo ".$archivo." correctamente";
        }
    }
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
    );
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/embarques/{idembarque}/general', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $params = json_decode((string) $request->getBody(),true);

    $numeroguia=$params['numeroguia'];
    $idciudad=$params['idciudad'];
    $fecharealizacion=$params['fecharealizacion'];
    $noidentificacion=$params['noidentificacion'];
    //$valordeclarado=$params['valordeclarado'];
    $descripcioncarga=$params['descripcioncarga'];
    $peso=$params['peso'];
    $volumen=$params['volumen'];
    $piezas=$params['piezas'];
    $carpetapacena=$params['carpetapacena'];
    $servicio_logistico=$params['servicio_logistico'];
    $nodui=$params['nodui'];
    $idincoterms=$params['idincoterms'];
    $idtipobulto= isset($params['idtipobulto']) ? $params['idtipobulto'] : 'NULL';
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="UPDATE t_embarque SET
        numeroguia='$numeroguia',
        idciudad='$idciudad',
        fecharealizacion='$fecharealizacion',
        noidentificacion='$noidentificacion',
        descripcioncarga='$descripcioncarga',
        peso='$peso',
        volumen='$volumen',
        piezas='$piezas',
        idtipobulto=$idtipobulto,
        carpetapacena='$carpetapacena',
        servicio_logistico='$servicio_logistico',
        nodui='$nodui',
        idincoterms='$idincoterms'
        WHERE
        idembarque=$idembarque;";
    
    $queryejecutar = $conexion->prepare($query);
    $result=$queryejecutar->execute();
    
    if($result){
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información General';
    }
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'servicio_logistico'=>$servicio_logistico
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/embarques/{idembarque}/entidades', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $params = json_decode((string) $request->getBody(),true);

    $idexpedidorg="NULL";
    $idtipoexpedidorg="NULL";
    $idexpedidordirecciong="NULL";
    $idexpedidor=$params['idexpedidor'];
    $idexpedidor_explode= explode("-", $idexpedidor);
    if(count($idexpedidor_explode)==2){
        $idexpedidorg=$idexpedidor_explode[1];
        $idtipoexpedidorg=$idexpedidor_explode[0];
        $idexpedidordirecciong=$params['idexpedidordireccion'];
    }
    
    $idultimoconsignatariog="NULL";
    $idtipoultimoconsignatariog="NULL";
    $idultimoconsignatariodirecciong="NULL";
    $idultimoconsignatario=$params['idultimoconsignatario'];
    $idultimoconsignatario_explode= explode("-", $idultimoconsignatario);
    if(count($idultimoconsignatario_explode)==2){
        $idultimoconsignatariog=$idultimoconsignatario_explode[1];
        $idtipoultimoconsignatariog=$idultimoconsignatario_explode[0];
        $idultimoconsignatariodirecciong=$params['idultimoconsignatariodireccion'];
    }
    
    /*
    $identidadnotificarg="NULL";
    $idtipoentidadnotificarg="NULL";
    $identidadnotificardirecciong="NULL";
    $identidadnotificar=$params['identidadnotificar'];
    $identidadnotificar_explode= explode("-", $identidadnotificar);
    if(count($identidadnotificar_explode)==2){
        $identidadnotificarg=$identidadnotificar_explode[1];
        $idtipoentidadnotificarg=$identidadnotificar_explode[0];
        $identidadnotificardirecciong=$params['identidadnotificardireccion'];
    }
    
    $idagentecarga=$params['idagentecarga'];
    $idagentecargadireccion=$params['idagentecargadireccion'];
    
    $idagentedestino=$params['idagentedestino'];
    $idagentedestinodireccion=$params['idagentedestinodireccion'];
    */
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="UPDATE t_embarque SET
        idexpedidor='$idexpedidorg',
        idtipoexpedidor='$idtipoexpedidorg',
        idexpedidordireccion='$idexpedidordirecciong',
        idultimoconsignatario='$idultimoconsignatariog',
        idtipoultimoconsignatario='$idtipoultimoconsignatariog',
        idultimoconsignatariodireccion='$idultimoconsignatariodirecciong'
        WHERE
        idembarque=$idembarque;";
    
    $queryejecutar = $conexion->prepare($query);
    $result=$queryejecutar->execute();
    
    if($result){
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información Entidades';
    }
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/embarques/{idembarque}/ruta', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $params = json_decode((string) $request->getBody(),true);

    $idmediotransporte=$params['idmediotransporte'];
    $idtipocarga=$params['idtipocarga'];
    $idtransportista=$params['idtransportista'];
    $numerovehiculo=$params['numerovehiculo'];
    $idsalida=$params['idsalida'];
    $fechasalida=$params['fechasalida'];
    $idarribo=$params['idarribo'];
    $fechaarribo=$params['fechaarribo'];
    $idorigen=$params['idorigen'];
    $iddestino=$params['iddestino'];
    $idhorario=$params['idhorario'];
    $idtemperatura=$params['idtemperatura'];
    $numero_precinto=$params['numero_precinto'];
    $estibadoresSLG=$params['estibadoresSLG'];
    $estibadores=$params['estibadores'];
    $costo_operador_transporte=$params['costo_operador_transporte'];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="UPDATE t_embarque SET
        idmediotransporte='$idmediotransporte',
        idtipocarga='$idtipocarga',
        idtransportista='$idtransportista',
        numerovehiculo='$numerovehiculo',
        idsalida='$idsalida',
        fechasalida='$fechasalida',
        idarribo='$idarribo',
        fechaarribo='$fechaarribo',
        idorigen='$idorigen',
        iddestino='$iddestino',
        idhorario='$idhorario',
        idtemperatura='$idtemperatura',
        numero_precinto='$numero_precinto',
        estibadoresSLG='$estibadoresSLG',
        estibadores='$estibadores',
        costo_operador_transporte='$costo_operador_transporte'
        WHERE
        idembarque=$idembarque;";
    
    $queryejecutar = $conexion->prepare($query);
    $result=$queryejecutar->execute();
    
    if($result){
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información de Ruta';
    }
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/embarques/{idembarque}/cargos', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    
    $idusuario=$decoded_array["idusuario"];
    
    $params = json_decode((string) $request->getBody(),true);
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    $nuevoscargos=[];
    $query='';
    $querynuevos='';
    for($cc=0;$cc<count($params);$cc++){
        if(!$params[$cc]['tienedocumento'] && !$params[$cc]['esagente']){
            $iddestinocargo= isset($params[$cc]["iddestinocargo"]) ? $params[$cc]["iddestinocargo"] : "NULL";
            if((int)$params[$cc]['idcargo']>0){
                $query=$query."UPDATE t_cargo SET 
                    idconcepto=".$params[$cc]["idconcepto"].",
                    iddivisa=".$params[$cc]["iddivisa"].",
                    monto=".$params[$cc]["monto"].",
                    cantidad=".$params[$cc]["cantidad"].",
                    iddestinatario='".$params[$cc]["iddestinatario"]."',
                    idtipodestinatario='".$params[$cc]["idtipodestinatario"]."',
                    notas='".$params[$cc]["notas"]."',
                    factura='".$params[$cc]["factura"]."',
                    iddestinocargo=$iddestinocargo
                    WHERE
                    idcargo=".$params[$cc]['idcargo'].";";
                $querynuevos=$querynuevos."SELECT 
                        ".$params[$cc]['idcargo']." as idcargo_nuevo, 
                        ".$params[$cc]['idcargo']." as idcargo_viejo,
                        t_tipocambio.tipocambio,
                        t_tipocambious.tipocambio as tipocambious
                        FROM 
                        t_cargo
                        LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
                        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_embarque.idempresa
                        LEFT JOIN t_tipocambio as t_tipocambious ON t_cargo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CURRENT_DATE()) AND t_tipocambious.idempresa=t_embarque.idempresa
                        WHERE
                        t_cargo.idcargo=".$params[$cc]['idcargo']."
                        UNION ALL ";
            }else{
                $query=$query."INSERT INTO t_cargo (idembarque,     idconcepto,                     iddivisa,                       monto,                      cantidad,                       iddestinatario,                         idtipodestinatario,                         notas,                          factura,                        iddestinocargo,     idusuario,  created_at)
                                            VALUES ($idembarque,    ".$params[$cc]["idconcepto"].", ".$params[$cc]["iddivisa"].",   ".$params[$cc]["monto"].",  ".$params[$cc]["cantidad"].",   '".$params[$cc]["iddestinatario"]."',   '".$params[$cc]["idtipodestinatario"]."',   '".$params[$cc]["notas"]."',    '".$params[$cc]["factura"]."',  $iddestinocargo,    $idusuario, CURRENT_TIMESTAMP());";
                $query=$query."SELECT LAST_INSERT_ID() INTO @idcargo_nuevo$cc;";
                $querynuevos=$querynuevos."SELECT 
                        @idcargo_nuevo$cc as idcargo_nuevo, 
                        ".$params[$cc]['idcargo']." as idcargo_viejo,
                        t_tipocambio.tipocambio,
                        t_tipocambious.tipocambio as tipocambious
                        FROM 
                        t_cargo
                        LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
                        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_embarque.idempresa
                        LEFT JOIN t_tipocambio as t_tipocambious ON t_cargo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CURRENT_DATE()) AND t_tipocambious.idempresa=t_embarque.idempresa
                        WHERE
                        t_cargo.idcargo=@idcargo_nuevo$cc
                        UNION ALL ";
            }
        }
    }
    
    
    
    $resultcargos = $conexion->query("SELECT
        idcargo
        FROM
        t_cargo
        WHERE
        IFNULL(esagente,0)=0
        and idembarque=$idembarque;");
    while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC)){
        $key = array_search($rowcargos['idcargo'], array_column($params, 'idcargo'));
        if(!is_numeric($key)){
            $query=$query."DELETE FROM t_cargo WHERE idcargo=".$rowcargos['idcargo'].";";
        }
    }
    
    if(strlen($query)>0){
        $result = $conexion->exec($query);

        if($result===false){

        }else{
            if(strlen($querynuevos)>0){
                $querynuevos=substr($querynuevos,0,-10);
                $resultcargos = $conexion->query($querynuevos);
                while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC)){
                    $nuevoscargos[]=array(
                        'idcargo_nuevo'=>(int)$rowcargos['idcargo_nuevo'],
                        'idcargo_viejo'=>(int)$rowcargos['idcargo_viejo'],
                        'tipocambio'=>(float)$rowcargos['tipocambio'],
                        'tipocambious'=>(float)$rowcargos['tipocambious']
                    );
                }
            }

            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información de Ruta';
        }
    }else{
        $mensaje="No hay datos para guardar";
    }
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'nuevoscargos'=>$nuevoscargos
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/embarques/{idembarque}/importarcargos', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    
    $idusuario=$decoded_array["idusuario"];
    
    $params = json_decode((string) $request->getBody(),true);
    
    $codigo=200;
    $status='Exito';
    $mensaje='Se obtuvieron los datos';
    $nuevoscargos=[];
    
    $resultcargos = $conexion->query("SELECT
        t_tipocarga.idconceptocargo as idconcepto,
        1 as cantidad,
        IFNULL(t_clientegestionlogistica.monto_fijo,0)+IFNULL(t_clientegestionlogistica.monto_por_peso,0)*t_embarque.peso as monto,
        1 as iddivisa,
        t_embarque.idcliente as iddestinatario,
        1 as idtipodestinatario,
        'N/A' as factura,
        1 as iddestinocargo
        FROM
        t_clientegestionlogistica
        LEFT JOIN t_embarque ON 
        t_clientegestionlogistica.idcliente=t_embarque.idcliente
        AND t_clientegestionlogistica.importacion_exportacion=t_embarque.importacion_exportacion
        AND t_clientegestionlogistica.idmediotransporte=t_embarque.idmediotransporte
        AND t_clientegestionlogistica.idtipocarga=t_embarque.idtipocarga
        AND t_clientegestionlogistica.idaduana=(SELECT t_ciudad.idaduana FROM t_embarque as tmp_aduana LEFT JOIN t_ciudad ON t_embarque.idsalida=t_ciudad.idciudad WHERE tmp_aduana.idembarque=t_embarque.idembarque)
        AND t_clientegestionlogistica.iddestino=t_embarque.idarribo
        AND t_clientegestionlogistica.idtemperatura=t_embarque.idtemperatura
        AND t_clientegestionlogistica.idhorario=t_embarque.idhorario
        AND t_embarque.peso BETWEEN t_clientegestionlogistica.peso_desde AND t_clientegestionlogistica.peso_hasta
        LEFT JOIN t_tipocarga ON t_clientegestionlogistica.idtipocarga=t_tipocarga.idtipocarga
        WHERE
        t_embarque.idembarque=$idembarque
        UNION ALL
        SELECT 
        t_clienteserviciologistico.idconcepto,
        CASE t_clienteserviciologistico.idconcepto
          WHEN 268 THEN t_embarque.estibadoresSLG
          ELSE 1
        END
         as cantidad,
        t_clienteserviciologistico.monto,
        t_clienteserviciologistico.iddivisa,
        t_embarque.idcliente as iddestinatario,
        1 as idtipodestinatario,
        'N/A' as factura,
        1 as iddestinocargo
        FROM 
        t_clienteserviciologistico
        LEFT JOIN t_embarque ON t_clienteserviciologistico.idcliente=t_embarque.idcliente
        WHERE
        t_embarque.idembarque=$idembarque
        AND (
        CASE t_clienteserviciologistico.idconcepto
          WHEN 268 THEN IF(IFNULL(t_embarque.estibadoresSLG,0)>0,1,0)
          WHEN 258 THEN IF(IFNULL(t_embarque.servicio_logistico,0)=1,1,0)
          ELSE 0
        END
        )=1;");
    while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC)){
        $nuevoscargos[]=array(
            'idconcepto'=>(int)$rowcargos['idconcepto'],
            'cantidad'=>$rowcargos['cantidad'],
            'monto'=>$rowcargos['monto'],
            'iddivisa'=>$rowcargos['iddivisa'],
            'iddestinatario'=>$rowcargos['iddestinatario'],
            'idtipodestinatario'=>$rowcargos['idtipodestinatario'],
            'factura'=>$rowcargos['factura'],
            'iddestinocargo'=>$rowcargos['iddestinocargo']
        );
    }
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'nuevoscargos'=>$nuevoscargos
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/embarques/{idembarque}/costos', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    
    $idusuario=$decoded_array["idusuario"];
    
    $params = json_decode((string) $request->getBody(),true);
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    $nuevoscostos=[];
    $querynuevos='';
    $query='';
    for($cc=0;$cc<count($params);$cc++){
        if(!$params[$cc]['tienedocumento'] && !$params[$cc]['esagente']){
            if((int)$params[$cc]['idcosto']>0){
                $query=$query."UPDATE t_costo SET 
                    idconcepto=".$params[$cc]["idconcepto"].",
                    iddivisa=".$params[$cc]["iddivisa"].",
                    monto=".$params[$cc]["monto"].",
                    cantidad=".$params[$cc]["cantidad"].",
                    iddestinatario='".$params[$cc]["iddestinatario"]."',
                    idtipodestinatario='".$params[$cc]["idtipodestinatario"]."',
                    notas='".$params[$cc]["notas"]."',
                    factura='".$params[$cc]["factura"]."',
                    nota_entrega='".$params[$cc]["nota_entrega"]."'
                    WHERE
                    idcosto=".$params[$cc]['idcosto'].";";
                
                $querynuevos=$querynuevos."SELECT 
                        ".$params[$cc]['idcosto']." as idcosto_nuevo,
                        ".$params[$cc]['idcosto']." as idcosto_viejo,
                        t_tipocambio.tipocambio,
                        t_tipocambious.tipocambio as tipocambious
                        FROM
                        t_costo
                        LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
                        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_embarque.idempresa
                        LEFT JOIN t_tipocambio as t_tipocambious ON t_costo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CURRENT_DATE()) AND t_tipocambious.idempresa=t_embarque.idempresa
                        WHERE
                        t_costo.idcosto=".$params[$cc]['idcosto']."
                        UNION ALL ";
            }else{
                $query=$query."INSERT INTO t_costo (idembarque,     idconcepto,                     iddivisa,                       monto,                      cantidad,                       iddestinatario,                         idtipodestinatario,                         notas,                          factura,                        nota_entrega,                       idusuario,  created_at)
                                            VALUES ($idembarque,    ".$params[$cc]["idconcepto"].", ".$params[$cc]["iddivisa"].",   ".$params[$cc]["monto"].",  ".$params[$cc]["cantidad"].",   '".$params[$cc]["iddestinatario"]."',   '".$params[$cc]["idtipodestinatario"]."',   '".$params[$cc]["notas"]."',    '".$params[$cc]["factura"]."',  '".$params[$cc]["nota_entrega"]."', $idusuario, CURRENT_TIMESTAMP());";
                $query=$query."SELECT LAST_INSERT_ID() INTO @idcosto_nuevo$cc;";
                $querynuevos=$querynuevos."SELECT 
                        @idcosto_nuevo$cc as idcosto_nuevo,
                        ".$params[$cc]['idcosto']." as idcosto_viejo,
                        t_tipocambio.tipocambio,
                        t_tipocambious.tipocambio as tipocambious
                        FROM
                        t_costo
                        LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
                        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_embarque.idempresa
                        LEFT JOIN t_tipocambio as t_tipocambious ON t_costo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CURRENT_DATE()) AND t_tipocambious.idempresa=t_embarque.idempresa
                        WHERE
                        t_costo.idcosto=@idcosto_nuevo$cc
                        UNION ALL ";
            }
        }
    }
    
    $resultcostos = $conexion->query("SELECT
        idcosto
        FROM
        t_costo
        WHERE
        IFNULL(esagente,0)=0
        and idembarque=$idembarque;");
    while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC)){
        $key = array_search($rowcostos['idcosto'], array_column($params, 'idcosto'));
        if(!is_numeric($key)){
            $query=$query."DELETE FROM t_costo WHERE idcosto=".$rowcostos['idcosto'].";";
        }
    }
    
    if(strlen($query)>0){
        $result = $conexion->exec($query);

        if($result===false){

        }else{
            if(strlen($querynuevos)>0){
                $querynuevos=substr($querynuevos,0,-10);
                $resultcostos = $conexion->query($querynuevos);
                while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC)){
                    $nuevoscostos[]=array(
                        'idcosto_nuevo'=>(int)$rowcostos['idcosto_nuevo'],
                        'idcosto_viejo'=>(int)$rowcostos['idcosto_viejo'],
                        'tipocambio'=>(float)$rowcostos['tipocambio'],
                        'tipocambious'=>(float)$rowcostos['tipocambious']
                    );
                }

            }
            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información de Ruta';
        }
    }else{
        $mensaje="No hay datos para guardar";
    }
    
        
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'nuevoscostos'=> $nuevoscostos
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/embarques/{idembarque}/cargoscostosagente', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idusuario=$decoded_array["idusuario"];
    
    $params = json_decode((string) $request->getBody(),true);
    $cargos=$params["cargos"] ?? [];
    $costos=$params["costos"] ?? [];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $nuevoscargos=[];
    $nuevoscostos=[];
    $query='';
    $querynuevos='';
    $querynuevoscostos='';
    for($cc=0;$cc<count($cargos);$cc++){
        $iddestinocargo= isset($cargos[$cc]["iddestinocargo"]) ? $cargos[$cc]["iddestinocargo"] : "NULL";
        if(!$cargos[$cc]['tienedocumento'] && $cargos[$cc]['esagente']){
            if((int)$cargos[$cc]['idcargo']>0){
                $query=$query."UPDATE t_cargo SET 
                    idconcepto=".$cargos[$cc]["idconcepto"].",
                    iddivisa=".$cargos[$cc]["iddivisa"].",
                    monto=".$cargos[$cc]["monto"].",
                    cantidad=".$cargos[$cc]["cantidad"].",
                    iddestinatario='".$cargos[$cc]["iddestinatario"]."',
                    idtipodestinatario='".$cargos[$cc]["idtipodestinatario"]."',
                    notas='".$cargos[$cc]["notas"]."',
                    factura='".$cargos[$cc]["factura"]."',
                    iddestinocargo=$iddestinocargo
                    WHERE
                    idcargo=".$cargos[$cc]['idcargo'].";";
                $querynuevos=$querynuevos."SELECT
                    ".$cargos[$cc]['idcargo']." as idcargo_nuevo,
                    ".$cargos[$cc]['idcargo']." as idcargo_viejo,
                    t_tipocambio.tipocambio,
                    t_tipocambious.tipocambio as tipocambious
                    FROM 
                    t_cargo
                    LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
                    LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_embarque.idempresa
                    LEFT JOIN t_tipocambio as t_tipocambious ON t_cargo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CURRENT_DATE()) AND t_tipocambious.idempresa=t_embarque.idempresa
                    WHERE
                    t_cargo.idcargo=".$cargos[$cc]['idcargo']."
                    UNION ALL ";
            }else{
                $query=$query."INSERT INTO t_cargo (idembarque,     idconcepto,                                 iddivisa,                                   monto,                                  cantidad,                                   iddestinatario,                                     idtipodestinatario,                                 notas,                                  esagente,   factura,                                    iddestinocargo,     idusuario,  created_at)
                                            VALUES ($idembarque,    ".$cargos[$cc]["idconcepto"].",   ".$cargos[$cc]["iddivisa"].",     ".$cargos[$cc]["monto"].",    ".$cargos[$cc]["cantidad"].",     '".$cargos[$cc]["iddestinatario"]."',     '".$cargos[$cc]["idtipodestinatario"]."', '".$cargos[$cc]["notas"]."',  1,          '".$cargos[$cc]["factura"]."',    $iddestinocargo,    $idusuario, CURRENT_TIMESTAMP());";
                $query=$query."SELECT LAST_INSERT_ID() INTO @idcargo_nuevo$cc;";
                $querynuevos=$querynuevos."SELECT
                    @idcargo_nuevo$cc as idcargo_nuevo,
                    ".$cargos[$cc]['idcargo']." as idcargo_viejo,
                    t_tipocambio.tipocambio,
                    t_tipocambious.tipocambio as tipocambious
                    FROM 
                    t_cargo
                    LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
                    LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_embarque.idempresa
                    LEFT JOIN t_tipocambio as t_tipocambious ON t_cargo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CURRENT_DATE()) AND t_tipocambious.idempresa=t_embarque.idempresa
                    WHERE
                    t_cargo.idcargo=@idcargo_nuevo$cc
                    UNION ALL ";
            }
        }
    }
    
    $resultcargos = $conexion->query("SELECT
        idcargo
        FROM
        t_cargo
        WHERE
        IFNULL(esagente,0)=1
        and idembarque=$idembarque;");
    while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC)){
        $key = array_search($rowcargos['idcargo'], array_column($cargos, 'idcargo'));
        if(!is_numeric($key)){
            $query=$query."DELETE FROM t_cargo WHERE idcargo=".$rowcargos['idcargo'].";";
        }
    }
    
    
    for($cc=0;$cc<count($costos);$cc++){
        if(!$costos[$cc]['tienedocumento'] && $costos[$cc]['esagente']){
            if((int)$costos[$cc]['idcosto']>0){
                $query=$query."UPDATE t_costo SET 
                    idconcepto=".$costos[$cc]["idconcepto"].",
                    iddivisa=".$costos[$cc]["iddivisa"].",
                    monto=".$costos[$cc]["monto"].",
                    cantidad=".$costos[$cc]["cantidad"].",
                    iddestinatario='".$costos[$cc]["iddestinatario"]."',
                    idtipodestinatario='".$costos[$cc]["idtipodestinatario"]."',
                    notas='".$costos[$cc]["notas"]."',
                    factura='".$costos[$cc]["factura"]."',
                    nota_entrega='".$costos[$cc]["nota_entrega"]."'
                    WHERE
                    idcosto=".$costos[$cc]['idcosto'].";";
                $querynuevoscostos=$querynuevoscostos."SELECT 
                    ".$costos[$cc]['idcosto']." as idcosto_nuevo,
                    ".$costos[$cc]['idcosto']." as idcosto_viejo,
                    t_tipocambio.tipocambio,
                    t_tipocambious.tipocambio as tipocambious
                    FROM
                    t_costo
                    LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
                    LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_embarque.idempresa
                    LEFT JOIN t_tipocambio as t_tipocambious ON t_costo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CURRENT_DATE()) AND t_tipocambious.idempresa=t_embarque.idempresa
                    WHERE
                    t_costo.idcosto=".$costos[$cc]['idcosto']."
                    UNION ALL ";
            }else{
                $query=$query."INSERT INTO t_costo (idembarque,     idconcepto,                                 iddivisa,                                   monto,                                  cantidad,                                   iddestinatario,                                     idtipodestinatario,                                     notas,                                      esagente,   factura,                                    nota_entrega,                                   idusuario,  created_at)
                                            VALUES ($idembarque,    ".$costos[$cc]["idconcepto"].",   ".$costos[$cc]["iddivisa"].",     ".$costos[$cc]["monto"].",    ".$costos[$cc]["cantidad"].",     '".$costos[$cc]["iddestinatario"]."',     '".$costos[$cc]["idtipodestinatario"]."',     '".$costos[$cc]["notas"]."',      1,          '".$costos[$cc]["factura"]."',    '".$costos[$cc]["nota_entrega"]."',   $idusuario, CURRENT_TIMESTAMP());";
                $query=$query."SELECT LAST_INSERT_ID() INTO @idcosto_nuevo$cc;";
                $querynuevoscostos=$querynuevoscostos."SELECT 
                    @idcosto_nuevo$cc as idcosto_nuevo,
                    ".$costos[$cc]['idcosto']." as idcosto_viejo,
                    t_tipocambio.tipocambio,
                    t_tipocambious.tipocambio as tipocambious
                    FROM
                    t_costo
                    LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
                    LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_embarque.idempresa
                    LEFT JOIN t_tipocambio as t_tipocambious ON t_costo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,CURRENT_DATE()) AND t_tipocambious.idempresa=t_embarque.idempresa
                    WHERE
                    t_costo.idcosto=@idcosto_nuevo$cc
                    UNION ALL ";
            }
        }
    }
    
    $resultcostos = $conexion->query("SELECT
        idcosto
        FROM
        t_costo
        WHERE
        IFNULL(esagente,0)=1
        and idembarque=$idembarque;");
    while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC)){
        $key = array_search($rowcostos['idcosto'], array_column($costos, 'idcosto'));
        if(!is_numeric($key)){
            $query=$query."DELETE FROM t_costo WHERE idcosto=".$rowcostos['idcosto'].";";
        }
    }
    
    if(strlen($query)>0){
        $result = $conexion->exec($query);

        if($result===false){

        }else{
            if(strlen($querynuevos)>0){
                $querynuevos=substr($querynuevos,0,-10);
                $resultcargos = $conexion->query($querynuevos);
                while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC)){
                    $nuevoscargos[]=array(
                        'idcargo_nuevo'=>$rowcargos['idcargo_nuevo'],
                        'idcargo_viejo'=>$rowcargos['idcargo_viejo'],
                        'tipocambio'=>$rowcargos['tipocambio'],
                        'tipocambious'=>$rowcargos['tipocambious']
                    );
                }
            }

            if(strlen($querynuevoscostos)>0){
                $querynuevoscostos=substr($querynuevoscostos,0,-10);
                $resultcostos = $conexion->query($querynuevoscostos);
                while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC)){
                    $nuevoscostos[]=array(
                        'idcosto_nuevo'=>$rowcostos['idcosto_nuevo'],
                        'idcosto_viejo'=>$rowcostos['idcosto_viejo'],
                        'tipocambio'=>$rowcargos['tipocambio'],
                        'tipocambious'=>$rowcargos['tipocambious']
                    );
                }
            }

            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información de Ruta';
        }
    }else{
        $mensaje="No hay datos para guardar";
    }
    
        
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'nuevoscargos'=>$nuevoscargos,
        'nuevoscostos'=>$nuevoscostos,
        'query'=>$query,
        'params'=>$params
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/embarques/{idembarque}/eventos', function(Request $request, Response $response, array $args) use ($conexion) {

    $idembarque = $args['idembarque'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $nuevoseventos = [];
    $nuevoscorreos = [];

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
            $eventos = $params["eventos"] ?? [];
            $correos = $params["correos"] ?? [];
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idembarque)) {
            $mensaje = 'No se recibió el embarque';
            $continuar = false;
        }

        if ($continuar && !is_array($eventos)) {
            $mensaje = 'Los eventos recibidos no tienen un formato válido';
            $continuar = false;
        }

        if ($continuar && !is_array($correos)) {
            $mensaje = 'Los correos recibidos no tienen un formato válido';
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
            | Preparar consultas de eventos
            |--------------------------------------------------------------------------
            */
            $queryUpdateEvento = "
                UPDATE t_evento
                SET
                    idtipoevento = :idtipoevento,
                    fechaplanificada = :fechaplanificada,
                    con_observacion = :con_observacion,
                    ideventodescripcion = :ideventodescripcion,
                    evento = :evento,
                    idusuario = :idusuario
                WHERE idevento = :idevento
                  AND idembarque = :idembarque
                  AND IFNULL(enviado, 0) = 0
            ";

            $stmtUpdateEvento = $conexion->prepare($queryUpdateEvento);

            $queryInsertEvento = "
                INSERT INTO t_evento (
                    idembarque,
                    idtipoevento,
                    fechaplanificada,
                    con_observacion,
                    ideventodescripcion,
                    evento,
                    idusuario,
                    fecharegistro
                ) VALUES (
                    :idembarque,
                    :idtipoevento,
                    :fechaplanificada,
                    :con_observacion,
                    :ideventodescripcion,
                    :evento,
                    :idusuario,
                    CURRENT_DATE()
                )
            ";

            $stmtInsertEvento = $conexion->prepare($queryInsertEvento);

            /*
            |--------------------------------------------------------------------------
            | Insertar / actualizar eventos
            |--------------------------------------------------------------------------
            */
            $idsEventosRecibidos = [];

            foreach ($eventos as $eventoItem) {

                $enviado = $eventoItem['enviado'] ?? false;

                /*
                Igual que tu lógica original:
                si ya fue enviado, no se actualiza ni se inserta.
                */
                if ($enviado) {
                    $ideventoEnviado = $eventoItem['idevento'] ?? 0;

                    if ((int)$ideventoEnviado > 0) {
                        $idsEventosRecibidos[] = (int)$ideventoEnviado;
                    }

                    continue;
                }

                $idevento = $eventoItem['idevento'] ?? 0;
                $idtipoevento = $eventoItem["idtipoevento"] ?? null;
                $fechaplanificada = $eventoItem["fechaplanificada"] ?? null;
                $con_observacion = !empty($eventoItem["con_observacion"]) ? 1 : 0;
                $ideventodescripcion = $eventoItem["ideventodescripcion"] ?? null;
                $eventoTexto = $eventoItem["evento"] ?? '';
                $idusuario = $eventoItem["idusuario"] ?? null;

                if (empty($idtipoevento)) {
                    $mensaje = 'Un evento no tiene tipo de evento';
                    $continuar = false;
                    break;
                }

                if (empty($fechaplanificada)) {
                    $mensaje = 'Un evento no tiene fecha planificada';
                    $continuar = false;
                    break;
                }

                if (empty($idusuario)) {
                    $mensaje = 'Un evento no tiene usuario';
                    $continuar = false;
                    break;
                }

                if (!$con_observacion) {
                    $ideventodescripcion = null;
                }

                if ((int)$idevento > 0) {

                    $idsEventosRecibidos[] = (int)$idevento;

                    $resultEvento = $stmtUpdateEvento->execute([
                        ':idtipoevento' => $idtipoevento,
                        ':fechaplanificada' => $fechaplanificada,
                        ':con_observacion' => $con_observacion,
                        ':ideventodescripcion' => $ideventodescripcion,
                        ':evento' => $eventoTexto,
                        ':idusuario' => $idusuario,
                        ':idevento' => $idevento,
                        ':idembarque' => $idembarque
                    ]);

                } else {

                    /*
                    Conserva el ID viejo/temporal que puede venir desde frontend.
                    Normalmente será 0 o negativo.
                    */
                    $ideventoViejo = $idevento;

                    $resultEvento = $stmtInsertEvento->execute([
                        ':idembarque' => $idembarque,
                        ':idtipoevento' => $idtipoevento,
                        ':fechaplanificada' => $fechaplanificada,
                        ':con_observacion' => $con_observacion,
                        ':ideventodescripcion' => $ideventodescripcion,
                        ':evento' => $eventoTexto,
                        ':idusuario' => $idusuario
                    ]);

                    if ($resultEvento) {
                        $ideventoNuevo = (int)$conexion->lastInsertId();

                        $idsEventosRecibidos[] = $ideventoNuevo;

                        $nuevoseventos[] = [
                            'idevento_nuevo' => $ideventoNuevo,
                            'idevento_viejo' => $ideventoViejo
                        ];
                    }
                }

                if (!$resultEvento) {
                    $mensaje = 'No se pudo guardar un evento';
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
        | Eliminar eventos no enviados que ya no llegaron
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryEventosActuales = "
                SELECT idevento
                FROM t_evento
                WHERE IFNULL(enviado, 0) = 0
                  AND idembarque = :idembarque
            ";

            $stmtEventosActuales = $conexion->prepare($queryEventosActuales);

            $stmtEventosActuales->execute([
                ':idembarque' => $idembarque
            ]);

            $queryDeleteEvento = "
                DELETE FROM t_evento
                WHERE idevento = :idevento
                  AND idembarque = :idembarque
                  AND IFNULL(enviado, 0) = 0
            ";

            $stmtDeleteEvento = $conexion->prepare($queryDeleteEvento);

            while ($rowEvento = $stmtEventosActuales->fetch(PDO::FETCH_ASSOC)) {

                $ideventoActual = (int)$rowEvento['idevento'];

                if (!in_array($ideventoActual, $idsEventosRecibidos)) {

                    $resultDeleteEvento = $stmtDeleteEvento->execute([
                        ':idevento' => $ideventoActual,
                        ':idembarque' => $idembarque
                    ]);

                    if (!$resultDeleteEvento) {
                        $mensaje = 'No se pudo eliminar un evento';
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
        | Preparar consultas de correos
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryUpdateCorreo = "
                UPDATE t_correosembarque
                SET correo = :correo
                WHERE idcorreosembarque = :idcorreosembarque
                  AND idembarque = :idembarque
            ";

            $stmtUpdateCorreo = $conexion->prepare($queryUpdateCorreo);

            $queryInsertCorreo = "
                INSERT INTO t_correosembarque (
                    idembarque,
                    correo
                ) VALUES (
                    :idembarque,
                    :correo
                )
            ";

            $stmtInsertCorreo = $conexion->prepare($queryInsertCorreo);

            /*
            |--------------------------------------------------------------------------
            | Insertar / actualizar correos
            |--------------------------------------------------------------------------
            */
            $idsCorreosRecibidos = [];

            foreach ($correos as $correoItem) {

                $idcorreosembarque = $correoItem['idcorreosembarque'] ?? 0;
                $correo = $correoItem["correo"] ?? '';

                if (trim($correo) === '') {
                    continue;
                }

                if ((int)$idcorreosembarque > 0) {

                    $idsCorreosRecibidos[] = (int)$idcorreosembarque;

                    $resultCorreo = $stmtUpdateCorreo->execute([
                        ':correo' => $correo,
                        ':idcorreosembarque' => $idcorreosembarque,
                        ':idembarque' => $idembarque
                    ]);

                } else {

                    $idcorreosembarqueViejo = $idcorreosembarque;

                    $resultCorreo = $stmtInsertCorreo->execute([
                        ':idembarque' => $idembarque,
                        ':correo' => $correo
                    ]);

                    if ($resultCorreo) {
                        $idcorreosembarqueNuevo = (int)$conexion->lastInsertId();

                        $idsCorreosRecibidos[] = $idcorreosembarqueNuevo;

                        $nuevoscorreos[] = [
                            'idcorreosembarque_nuevo' => $idcorreosembarqueNuevo,
                            'idcorreosembarque_viejo' => $idcorreosembarqueViejo
                        ];
                    }
                }

                if (!$resultCorreo) {
                    $mensaje = 'No se pudo guardar un correo';
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
                SELECT idcorreosembarque
                FROM t_correosembarque
                WHERE idembarque = :idembarque
            ";

            $stmtCorreosActuales = $conexion->prepare($queryCorreosActuales);

            $stmtCorreosActuales->execute([
                ':idembarque' => $idembarque
            ]);

            $queryDeleteCorreo = "
                DELETE FROM t_correosembarque
                WHERE idcorreosembarque = :idcorreosembarque
                  AND idembarque = :idembarque
            ";

            $stmtDeleteCorreo = $conexion->prepare($queryDeleteCorreo);

            while ($rowCorreo = $stmtCorreosActuales->fetch(PDO::FETCH_ASSOC)) {

                $idCorreoActual = (int)$rowCorreo['idcorreosembarque'];

                if (!in_array($idCorreoActual, $idsCorreosRecibidos)) {

                    $resultDeleteCorreo = $stmtDeleteCorreo->execute([
                        ':idcorreosembarque' => $idCorreoActual,
                        ':idembarque' => $idembarque
                    ]);

                    if (!$resultDeleteCorreo) {
                        $mensaje = 'No se pudo eliminar un correo';
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

            if ($conexion->inTransaction()) {
                $conexion->commit();
            }

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
        'mensaje' => $mensaje,
        'nuevoseventos' => $nuevoseventos,
        'nuevoscorreos' => $nuevoscorreos
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->post('/embarques/{idembarque}/eventos/enviarcorreo', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    $params = json_decode((string) $request->getBody(),true);
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $cuerpo='';
    
    $result = $conexion->query("SELECT
        t_cliente.cliente,
        t_embarque.embarque,
        t_embarque.numeroguia
        FROM
        t_embarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        WHERE
        t_embarque.idembarque=$idembarque;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cliente=$row['cliente'];
        $embarque=$row['embarque'];
        $numeroguia=$row['numeroguia'];
    }
    $cuerpo=$cuerpo.'<p>'.$cliente.':</p>';
    $cuerpo=$cuerpo.'<p>Se actualiza el embarque para el pedido</p>';
    $cuerpo=$cuerpo.'<table border="1" cellpadding="0" cellspacing="0">';
    $cuerpo=$cuerpo.'<tr>';
    $cuerpo=$cuerpo.'<td width="100" style="font-family: Candara; font-size: 10pt; background-color: #CCCCCC">Fecha Registro</td>';
    $cuerpo=$cuerpo.'<td width="100" style="font-family: Candara; font-size: 10pt; background-color: #CCCCCC">Fecha Planificada</td>';
    $cuerpo=$cuerpo.'<td width="150" style="font-family: Candara; font-size: 10pt; background-color: #CCCCCC">Tipo de evento</td>';
    $cuerpo=$cuerpo.'<td width="200" style="font-family: Candara; font-size: 10pt; background-color: #CCCCCC">Observacion</td>';
    $cuerpo=$cuerpo.'<td width="200" style="font-family: Candara; font-size: 10pt; background-color: #CCCCCC">Descripcion</td>';
    $cuerpo=$cuerpo.'</tr>';
    $result = $conexion->query("select
                            DATE_FORMAT(t_evento.fecharegistro,'%d/%m/%Y') as fecharegistro,
                            DATE_FORMAT(t_evento.fechaplanificada,'%d/%m/%Y') as fechaplanificada,
                            t_tipoevento.tipoevento,
                            t_eventodescripcion.eventodescripcion,
                            t_evento.evento
                            from
                            t_evento
                            LEFT JOIN t_tipoevento ON t_evento.idtipoevento=t_tipoevento.idtipoevento
                            LEFT JOIN t_eventodescripcion ON t_evento.ideventodescripcion=t_eventodescripcion.ideventodescripcion
                            WHERE
                            t_evento.idembarque=$idembarque
                            AND IFNULL(t_evento.enviado,0)=0;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cuerpo=$cuerpo.'<tr>';
        $cuerpo=$cuerpo.'<td width="100" align="center" style="font-family: Candara; font-size: 10pt;">'.$row["fecharegistro"].'</td>';
        $cuerpo=$cuerpo.'<td width="100" align="center" style="font-family: Candara; font-size: 10pt;">'.$row["fechaplanificada"].'</td>';
        $cuerpo=$cuerpo.'<td width="150" align="center" style="font-family: Candara; font-size: 10pt;">'.$row["tipoevento"].'</td>';
        $cuerpo=$cuerpo.'<td width="200" style="font-family: Candara; font-size: 10pt;">'.$row["eventodescripcion"].'</td>';
        $cuerpo=$cuerpo.'<td width="200" style="font-family: Candara; font-size: 10pt;">'.$row["evento"].'</td>';
        $cuerpo=$cuerpo.'</tr>';
    }
    $cuerpo .= '</table><br /><br />';
    
    $subject = "Carpeta $embarque con el numero de guia $numeroguia";
    $preview = 'Nuevos eventos agregados';
    
    $to=array();
    $result = $conexion->query("select 
        correo
        FROM 
        t_correosembarque
        WHERE
        idembarque=$idembarque;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        array_push($to,$row['correo']);
    }
    
    $mail=new SendMail();
    
    $respuesta=$mail->enviarMail($to, array(), $subject, $preview, $cuerpo);
    
    if($respuesta["statusCode"]==202){
        $codigo=200;
        $status='Exito';
        $mensaje="Mensaje enviado";
        $conexion->query("UPDATE t_evento SET enviado=1 WHERE idembarque=$idembarque AND IFNULL(enviado,0)=0;");
    }else{
        $mensaje=$respuesta["headers"];
    }

        
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/embarques/finalizar/{idembarque}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="UPDATE t_embarque SET fechafinalizacion=CURRENT_DATE() WHERE idembarque=$idembarque;";
    
    $queryejecutar = $conexion->prepare($query);
    $result=$queryejecutar->execute();
    
    if($result){
        $codigo=200;
        $status='Exito';
        $mensaje='Se finalizó el embarque exitosamente';
    }
        
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/embarques/{idembarque}/documentocierre', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
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
    
    $file=folder_files.$idempresa.DIRECTORY_SEPARATOR."documentos/documentoscierre/cierre_$idembarque.pdf";
    if(file_exists($file)){
        unlink($file);
    }

    generarDocumentoCierre($idembarque,$conexion, false);

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

$app->get('/embarques/{idembarque}/caratula', function(Request $request, Response $response, array $args) use ($conexion) {
    $idembarque = $args['idembarque'];
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
    
    $file=folder_files.$idempresa.DIRECTORY_SEPARATOR."documentos/caratulas/caratula_$idembarque.pdf";
    if(file_exists($file)){
        unlink($file);
    }

    generarCaratula($idembarque,$conexion, false);

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

$app->get('/reporte-embarques/{idcliente}/', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    
    $embarques=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_valorcargado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_valorcargado (idembarque INT, valorcargado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_valorcargado (idembarque, valorcargado)
        SELECT
        t_cargo.idembarque,
        SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as valorcargo
        FROM
        t_cargo
        LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
        LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
        LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE())) AND t_tipocambio.idempresa=t_embarque.idempresa
        WHERE
        ifnull(t_factura.idestadofactura,0) <> 2
        AND ifnull(t_notadebito.idestadonotadebito,0) <> 2
        GROUP BY
        t_cargo.idembarque;");
    $conexion->query("ALTER TABLE tmp_valorcargado ADD INDEX idembarque (idembarque);");
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_valorcosteado;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_valorcosteado (idembarque INT, valorcosteado DECIMAL(13,2));");
    $conexion->query("INSERT INTO tmp_valorcosteado (idembarque, valorcosteado)
        SELECT
        t_costo.idembarque,
	SUM(t_costo.monto*t_costo.cantidad*t_tipocambio.tipocambio) as valorcosteado
	FROM
	t_costo
        LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
        LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
	LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE())) AND t_tipocambio.idempresa=t_embarque.idempresa
	WHERE 
        ifnull(t_facturapago.idestadofacturapago,0) <> 2
        GROUP BY
        t_costo.idembarque;");
    $conexion->query("ALTER TABLE tmp_valorcosteado ADD INDEX idembarque (idembarque);");
    
    
    $result = $conexion->query("SELECT
        t_embarque.idembarque,
        t_embarque.fecharealizacion,
        t_embarque.importacion_exportacion,
        t_embarque.idtipoembarque,
        t_embarque.gestion,
        t_embarque.correlativo,
        CONCAT(t_importacion_exportacion.importacion_exportacion_codigo,'-',t_tipoembarque.codigo,'-',t_embarque.correlativo,'-',t_embarque.gestion) as embarque,
        t_embarque.idcliente,
        t_cliente.cliente,
        t_embarque.nodui,
        t_embarque.descripcioncarga,
        IF(IFNULL(t_embarque.fechafinalizacion,'0000-00-00')='0000-00-00',0,1) as finalizado,
        IFNULL(tmp_valorcargado.valorcargado,0) as valorcargado,
        IFNULL(tmp_valorcosteado.valorcosteado,0) as valorcosteado
        FROM
        t_embarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_importacion_exportacion ON t_embarque.importacion_exportacion=t_importacion_exportacion.importacion_exportacion
        LEFT JOIN t_tipoembarque ON t_embarque.idtipoembarque=t_tipoembarque.idtipoembarque
        LEFT JOIN tmp_valorcargado ON t_embarque.idembarque=tmp_valorcargado.idembarque
        LEFT JOIN tmp_valorcosteado ON t_embarque.idembarque=tmp_valorcosteado.idembarque;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        
        $costos=[];
        $cargos=[];

        $embarques[]=array(
            'idembarque'=>(int)$row['idembarque'],
            'fecharealizacion'=>$row['fecharealizacion'],
            'importacion_exportacion'=>(int)$row['importacion_exportacion'],
            'idtipoembarque'=>(int)$row['idtipoembarque'],
            'gestion'=>(int)$row['gestion'],
            'correlativo'=>(int)$row['correlativo'],
            'embarque'=>$row['embarque'],
            'idcliente'=>(int)$row['idcliente'],
            'cliente'=>$row['cliente'],
            'nodui'=>$row['nodui'],
            'descripcioncarga'=>$row['descripcioncarga'],
            'finalizado'=>boolval($row['finalizado']),
            'valorcargado'=>(float)$row['valorcargado'],
            'valorcosteado'=>(float)$row['valorcosteado'],
            'costos'=>$costos,
            'cargos'=>$cargos
            
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'embarques' => $embarques
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

function getCargos($idembarque, $conexion){
    $cargos=[];
    $resultcargos = $conexion->query("SELECT
        t_cargo.idcargo,
        t_cargo.idconcepto,
        t_concepto.concepto,
        t_concepto.id_OVP,
        t_cargo.cantidad,
        t_cargo.monto,
        t_cargo.iddivisa,
        t_divisa.codigo as codigodivisa,
        t_tipocambio.tipocambio,
        t_tipocambious.tipocambio as tipocambious,
        t_cargo.idtipodestinatario,
        t_cargo.iddestinatario,
        v_entidades.entidad as destinatario,
        t_cargo.notas,
        IFNULL(t_cargo.esagente,0) as esagente,
        IFNULL(t_cargo.idfacturanotadebito,0) as idfacturanotadebito,
        CASE IFNULL(t_cargo.idfacturanotadebito,0)
            WHEN 0 THEN NULL
            ELSE 
                CASE t_cargo.idtipofacturanotadebito
                    WHEN 1 THEN 'Factura'
                    WHEN 2 THEN 'Nota de Cobranza'
                END
        END as documento,
        IFNULL(t_factura.idestadofactura,t_notadebito.idestadonotadebito) AS idestadofacturanotadebito,
        CASE t_cargo.idtipofacturanotadebito
            WHEN 1 THEN t_factura.nrofactura
            WHEN 2 THEN CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion)
        END as facturanotadebito,
        IFNULL(t_cargo.idplanilla,0) as idplanilla,
        IFNULL(t_cargo.idinvoice,0) as idinvoice,
        IFNULL(t_cargo.idordenservicioi,0) as idordenservicioi,
        IFNULL(t_cargo.idfacturanotadebito,0)+IFNULL(t_cargo.idplanilla,0)+IFNULL(t_cargo.idinvoice,0)+IFNULL(t_cargo.idordenservicioi,0) as tienedocumento,
        t_cargo.factura,
        t_cargo.iddestinocargo,
        t_destinocargo.destinocargo,
        t_usuario.nombre as usuario,
        t_cargo.created_at
        from
        t_cargo
        LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_divisa ON t_cargo.iddivisa=t_divisa.iddivisa
        LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1
        LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2
        LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE())) AND t_tipocambio.idempresa=t_embarque.idempresa
        LEFT JOIN t_tipocambio as t_tipocambious ON t_cargo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE()) BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,IFNULL(IFNULL(t_factura.fecha,t_notadebito.fecha), CURRENT_DATE())) AND t_tipocambious.idempresa=t_embarque.idempresa
        LEFT JOIN v_entidades ON t_cargo.idtipodestinatario=v_entidades.idtipoentidad AND t_cargo.iddestinatario=v_entidades.identidad
        LEFT JOIN t_destinocargo ON t_cargo.iddestinocargo=t_destinocargo.iddestinocargo
        LEFT JOIN t_usuario ON t_cargo.idusuario=t_usuario.idusuario
        WHERE
        t_cargo.idembarque=$idembarque;");
    while ($rowcargos =  $resultcargos ->fetch(PDO::FETCH_ASSOC)){
        $concepto_ovp=$rowcargos['concepto'];
        if((int)$rowcargos['id_OVP']>0){
            $concepto_ovp=$rowcargos['concepto']." (".$rowcargos["id_OVP"].")";
        }
        
        
        $cargos[]=array(
            'idcargo'=>(int)$rowcargos['idcargo'],
            'idconcepto'=>(int)$rowcargos['idconcepto'],
            'concepto'=>$rowcargos['concepto'],
            'concepto_ovp'=>$concepto_ovp,
            'id_OVP'=>$rowcargos['id_OVP'],
            'cantidad'=>(float)$rowcargos['cantidad'],
            'monto'=>(float)$rowcargos['monto'],
            'iddivisa'=>(int)$rowcargos['iddivisa'],
            'codigodivisa'=>$rowcargos['codigodivisa'],
            'tipocambio'=>(float)$rowcargos['tipocambio'],
            'tipocambious'=>(float)$rowcargos['tipocambious'],
            'idtipodestinatario'=>(int)$rowcargos['idtipodestinatario'],
            'iddestinatario'=>(int)$rowcargos['iddestinatario'],
            'identidad'=>(int)$rowcargos['idtipodestinatario']."-".(int)$rowcargos['iddestinatario'],
            'destinatario'=>$rowcargos['destinatario'],
            'notas'=>$rowcargos['notas'],
            'esagente'=>boolval($rowcargos['esagente']),
            'idfacturanotadebito'=>(int)$rowcargos['idfacturanotadebito'],
            'documento'=>$rowcargos['documento'],
            'facturanotadebito'=>$rowcargos['facturanotadebito'],
            'idestadofacturanotadebito'=>$rowcargos['idestadofacturanotadebito'],
            'idplanilla'=>(int)$rowcargos['idplanilla'],
            'idinvoice'=>(int)$rowcargos['idinvoice'],
            'idordenservicioi'=>(int)$rowcargos['idordenservicioi'],
            'tienedocumento'=>boolval($rowcargos['tienedocumento']),
            'factura'=>$rowcargos['factura'],
            'iddestinocargo'=>$rowcargos['iddestinocargo'],
            'destinocargo'=>$rowcargos['destinocargo'],
            'usuario'=>$rowcargos['usuario'],
            'created_at'=>$rowcargos['created_at']
        );
    }
    
    return $cargos;
}

function getCostos($idembarque, $conexion){
    $costos=[];
    $resultcostos = $conexion->query("SELECT
        t_costo.idcosto,
        t_costo.idconcepto,
        t_concepto.concepto,
        IFNULL(t_concepto.id_OVPRef,'') as id_OVPRef,
        t_costo.cantidad,
        t_costo.monto,
        t_costo.iddivisa,
        t_divisa.codigo as codigodivisa,
        t_tipocambio.tipocambio,
        t_tipocambious.tipocambio as tipocambious,
        t_costo.idtipodestinatario,
        t_costo.iddestinatario,
        v_entidades.entidad as destinatario,
        t_costo.notas,
        IFNULL(t_costo.esagente,0) as esagente,
        ifnull(t_costo.idfacturanotadebito,0) AS idfacturanotadebito,
        CASE ifnull(t_costo.idfacturanotadebito,0)
            WHEN 0 THEN NULL
            ELSE 'Orden de Pago'
        END as documento,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as facturanotadebito,
        t_facturapago.idestadofacturapago,
        IFNULL(t_costo.idordenservicioe,0) as idordenservicioe,
        ifnull(t_costo.idfacturanotadebito,0) AS tienedocumento,
        t_costo.factura,
        t_costo.nota_entrega,
        t_usuario.nombre as usuario,
        t_costo.created_at
        FROM
        t_costo
        LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
        LEFT JOIN t_divisa ON t_costo.iddivisa=t_divisa.iddivisa
        LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
        LEFT JOIN t_embarque ON t_costo.idembarque=t_embarque.idembarque
        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE())) AND t_tipocambio.idempresa=t_embarque.idempresa
        LEFT JOIN t_tipocambio as t_tipocambious ON t_costo.iddivisa=t_tipocambious.iddivisaorigen AND 2=t_tipocambious.iddivisadestino AND IFNULL(t_facturapago.fecha, CURRENT_DATE()) BETWEEN t_tipocambious.fechainicio AND ifnull(t_tipocambious.fechafin,IFNULL(t_facturapago.fecha, CURRENT_DATE())) AND t_tipocambious.idempresa=t_embarque.idempresa
        LEFT JOIN v_entidades ON t_costo.idtipodestinatario=v_entidades.idtipoentidad AND t_costo.iddestinatario=v_entidades.identidad
        LEFT JOIN t_usuario ON t_costo.idusuario=t_usuario.idusuario
        WHERE
        t_costo.idembarque=$idembarque;");
    while ($rowcostos =  $resultcostos ->fetch(PDO::FETCH_ASSOC)){
        $concepto_ovp=$rowcostos['concepto'];
        if(strlen($rowcostos["id_OVPRef"])>0){
            $concepto_ovp=$rowcostos['concepto']." (".$rowcostos["id_OVPRef"].")";
        }
        
        $costos[]=array(
            'idcosto'=>(int)$rowcostos['idcosto'],
            'idconcepto'=>(int)$rowcostos['idconcepto'],
            'concepto'=>$rowcostos['concepto'],
            'concepto_ovp'=>$concepto_ovp,
            'id_OVPRef'=>$rowcostos['id_OVPRef'],
            'cantidad'=>(float)$rowcostos['cantidad'],
            'monto'=>(float)$rowcostos['monto'],
            'iddivisa'=>(int)$rowcostos['iddivisa'],
            'codigodivisa'=>$rowcostos['codigodivisa'],
            'tipocambio'=>(float)$rowcostos['tipocambio'],
            'tipocambious'=>(float)$rowcostos['tipocambious'],
            'idtipodestinatario'=>(int)$rowcostos['idtipodestinatario'],
            'iddestinatario'=>(int)$rowcostos['iddestinatario'],
            'identidad'=>(int)$rowcostos['idtipodestinatario']."-".(int)$rowcostos['iddestinatario'],
            'destinatario'=>$rowcostos['destinatario'],
            'notas'=>$rowcostos['notas'],
            'esagente'=>boolval($rowcostos['esagente']),
            'idfacturanotadebito'=>(int)$rowcostos['idfacturanotadebito'],
            'documento'=>$rowcostos['documento'],
            'facturanotadebito'=>$rowcostos['facturanotadebito'],
            'idestadofacturanotadebito'=>$rowcostos['idestadofacturapago'],
            'idordenservicioe'=>(int)$rowcostos['idordenservicioe'],
            'tienedocumento'=>boolval($rowcostos['tienedocumento']),
            'factura'=>$rowcostos['factura'],
            'nota_entrega'=>$rowcostos['nota_entrega'],
            'usuario'=>$rowcostos['usuario'],
            'created_at'=>$rowcostos['created_at']
        );
    }
    
    return $costos;
}

function getFacturas($idembarque, $conexion){
    $facturas=[];
    $resultfacturas = $conexion->query("SELECT
        t_factura.idfactura,
        CONCAT('1-',t_factura.idfactura) as iddocumento,
        t_factura.nrofactura as numero,
        t_factura.fecha,
        CASE t_factura.idestadofactura WHEN 2 THEN 0 ELSE valorfacturado(t_factura.idfactura) END as monto,
        t_factura.idestadofactura as idestado,
        t_estadofactura.estadofactura as estado
        FROM
        t_factura
        LEFT JOIN t_estadofactura ON t_factura.idestadofactura=t_estadofactura.idestadofactura
        WHERE
        t_factura.idembarque=".$idembarque.";");
    while ($rowfacturas =  $resultfacturas ->fetch(PDO::FETCH_ASSOC)){
        $facturas[]=array(
            'idfactura'=>(int)$rowfacturas['idfactura'],
            'iddocumento'=>$rowfacturas['iddocumento'],
            'numero'=>(int)$rowfacturas['numero'],
            'fecha'=>$rowfacturas['fecha'],
            'monto'=>(float)$rowfacturas['monto'],
            'idestado'=>(int)$rowfacturas['idestado'],
            'estado'=>$rowfacturas['estado']
        );
    }
    
    return $facturas;
}

function getNotasCobranza($idembarque, $conexion){
    $notascobranza=[];
    $resultnotascobranza = $conexion->query("SELECT
        t_notadebito.idnotadebito,
        CONCAT('2-',t_notadebito.idnotadebito) as iddocumento,
        CONCAT(t_notadebito.nronotadebito,'/',t_notadebito.gestion) as numero,
        t_notadebito.fecha,
        CASE t_notadebito.idestadonotadebito WHEN 2 THEN 0 ELSE valordebitado(t_notadebito.idnotadebito) END as monto,
        t_notadebito.idestadonotadebito as idestado,
        t_estadofactura.estadonotadebito as estado
        FROM
        t_notadebito
        LEFT JOIN t_estadofactura ON t_notadebito.idestadonotadebito=t_estadofactura.idestadofactura
        WHERE
        t_notadebito.idembarque=".$idembarque.";");
    while ($rownotascobranza =  $resultnotascobranza ->fetch(PDO::FETCH_ASSOC)){
        $notascobranza[]=array(
            'idnotadebito'=>(int)$rownotascobranza['idnotadebito'],
            'iddocumento'=>$rownotascobranza['iddocumento'],
            'numero'=>$rownotascobranza['numero'],
            'fecha'=>$rownotascobranza['fecha'],
            'monto'=>(float)$rownotascobranza['monto'],
            'idestado'=>(int)$rownotascobranza['idestado'],
            'estado'=>$rownotascobranza['estado']
        );
    }
    
    return $notascobranza;
}

function getInvoices($idembarque, $conexion){
    $invoices=[];
    $resultinvoices = $conexion->query("SELECT
        t_invoice.idinvoice,
        CONCAT('5-',t_invoice.idinvoice) as iddocumento,
        CONCAT(t_invoice.numero,'/',t_invoice.gestion) as numero,
        t_invoice.fecha,
        CASE t_invoice.idestadoinvoice WHEN 2 THEN 0 ELSE valorinvoice(t_invoice.idinvoice) END as monto,
        t_invoice.idestadoinvoice as idestado,
        t_estadofactura.estadonotadebito as estado
        FROM
        t_invoice
        LEFT JOIN t_estadofactura ON t_invoice.idestadoinvoice=t_estadofactura.idestadofactura
        WHERE t_invoice.idembarque=".$idembarque.";");
    while ($rowinvoices =  $resultinvoices ->fetch(PDO::FETCH_ASSOC)){
        $invoices[]=array(
            'idinvoice'=>(int)$rowinvoices['idinvoice'],
            'iddocumento'=>$rowinvoices['iddocumento'],
            'numero'=>$rowinvoices['numero'],
            'fecha'=>$rowinvoices['fecha'],
            'monto'=>(float)$rowinvoices['monto'],
            'idestado'=>(int)$rowinvoices['idestado'],
            'estado'=>$rowinvoices['estado']
        );
    }
    
    return $invoices;
}

function getPlanillas($idembarque, $conexion){
    $planillas=[];
    $resultplanillas = $conexion->query("select 
        t_planilla.idplanilla,
        CONCAT('4-',t_planilla.idplanilla) AS iddocumento,
        t_planilla.numero,
        t_planilla.fecha,
        CASE t_planilla.idestadoplanilla WHEN 2 THEN 0 ELSE valorplanillado(t_planilla.idplanilla) END as monto,
        t_planilla.idestadoplanilla as idestado,
        t_estadofactura.estadonotadebito as estado
        from 
        t_planilla
        LEFT JOIN t_estadofactura ON t_planilla.idestadoplanilla=t_estadofactura.idestadofactura
        WHERE t_planilla.idembarque=".$idembarque.";");
    while ($rowplanillas =  $resultplanillas ->fetch(PDO::FETCH_ASSOC)){
        $planillas[]=array(
            'idplanilla'=>(int)$rowplanillas['idplanilla'],
            'iddocumento'=>$rowplanillas['iddocumento'],
            'numero'=>$rowplanillas['numero'],
            'fecha'=>$rowplanillas['fecha'],
            'monto'=>(float)$rowplanillas['monto'],
            'idestado'=>(int)$rowplanillas['idestado'],
            'estado'=>$rowplanillas['estado']
        );
    }
    
    return $planillas;
}

function getFacturasPago($idembarque, $conexion){
    $facturaspago=[];
    $resultfacturaspago = $conexion->query("SELECT
        t_facturapago.idfacturapago,
        CONCAT('3-',t_facturapago.idfacturapago) as iddocumento,
        t_tipofacturapago.tipofacturapago as tipodocumento,
        CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as numero,
        t_facturapago.fecha,
        CASE t_facturapago.idestadofacturapago WHEN 2 THEN 0 ELSE valorpagofacturado(t_facturapago.idfacturapago) END as monto,
        t_facturapago.idestadofacturapago as idestado,
        t_estadofactura.estadonotadebito as estado
        FROM
        t_facturapago
        LEFT JOIN t_tipofacturapago ON t_facturapago.idtipofacturapago=t_tipofacturapago.idtipofacturapago
        LEFT JOIN t_estadofactura ON t_facturapago.idestadofacturapago=t_estadofactura.idestadofactura
        WHERE t_facturapago.idembarque=".$idembarque.";");
    while ($rowfacturaspago =  $resultfacturaspago ->fetch(PDO::FETCH_ASSOC)){
        $facturaspago[]=array(
            'idfacturapago'=>(int)$rowfacturaspago['idfacturapago'],
            'iddocumento'=>$rowfacturaspago['iddocumento'],
            'tipodocumento'=>$rowfacturaspago['tipodocumento'],
            'numero'=>$rowfacturaspago['numero'],
            'fecha'=>$rowfacturaspago['fecha'],
            'monto'=>(float)$rowfacturaspago['monto'],
            'idestado'=>(int)$rowfacturaspago['idestado'],
            'estado'=>$rowfacturaspago['estado']
        );
    }
    return $facturaspago;
}

function getOrdenServicioI($idembarque, $conexion){
    $ordenserviciosi=[];
    $resultordenserviciosi = $conexion->query("SELECT
        t_ordenservicioi.idordenservicioi,
        CONCAT('7-',t_ordenservicioi.idordenservicioi) as iddocumento,
        CONCAT(t_ordenservicioi.numero,'/',t_ordenservicioi.gestion) as numero,
        t_ordenservicioi.fecha,
        CASE t_ordenservicioi.idestado WHEN 2 THEN 0 ELSE valorordenservicioi(t_ordenservicioi.idordenservicioi) END as monto,
        t_ordenservicioi.idestado as idestado,
        t_estadofactura.estadonotadebito as estado
        FROM
        t_ordenservicioi
        LEFT JOIN t_estadofactura ON t_ordenservicioi.idestado=t_estadofactura.idestadofactura
        WHERE t_ordenservicioi.idembarque=".$idembarque.";");
    while ($rowordenserviciosi =  $resultordenserviciosi ->fetch(PDO::FETCH_ASSOC)){
        $ordenserviciosi[]=array(
            'idordenservicioi'=>(int)$rowordenserviciosi['idordenservicioi'],
            'iddocumento'=>$rowordenserviciosi['iddocumento'],
            'numero'=>$rowordenserviciosi['numero'],
            'fecha'=>$rowordenserviciosi['fecha'],
            'monto'=>(float)$rowordenserviciosi['monto'],
            'idestado'=>(int)$rowordenserviciosi['idestado'],
            'estado'=>$rowordenserviciosi['estado']
        );
    }
    return $ordenserviciosi;
}

function getOrdenServicioE($idembarque, $conexion){
    $ordenserviciose=[];
    $resultordenserviciose = $conexion->query("SELECT
        t_ordenservicioe.idordenservicioe,
        CONCAT('8-',t_ordenservicioe.idordenservicioe) as iddocumento,
        CONCAT(t_ordenservicioe.numero,'/',t_ordenservicioe.gestion) as numero,
        t_ordenservicioe.fecha,
        CASE t_ordenservicioe.idestado WHEN 2 THEN 0 ELSE valorordenservicioe(t_ordenservicioe.idordenservicioe) END as monto,
        t_ordenservicioe.idestado as idestado,
        t_estadofactura.estadonotadebito as estado
        FROM
        t_ordenservicioe
        LEFT JOIN t_estadofactura ON t_ordenservicioe.idestado=t_estadofactura.idestadofactura
        WHERE t_ordenservicioe.idembarque=".$idembarque.";");
    while ($rowordenserviciose =  $resultordenserviciose ->fetch(PDO::FETCH_ASSOC)){
        $ordenserviciose[]=array(
            'idordenservicioe'=>(int)$rowordenserviciose['idordenservicioe'],
            'iddocumento'=>$rowordenserviciose['iddocumento'],
            'numero'=>$rowordenserviciose['numero'],
            'fecha'=>$rowordenserviciose['fecha'],
            'monto'=>(float)$rowordenserviciose['monto'],
            'idestado'=>(int)$rowordenserviciose['idestado'],
            'estado'=>$rowordenserviciose['estado']
        );
    }
    
    return $ordenserviciose;
}

function generarCotizacion($idcotizacion, $iddivisa = 1, $conexion, $membretado = false){
    $result = $conexion->query("SELECT
                            COUNT(idcotizacioncontemplacion) as cantidad1
                            FROM
                            t_cotizacioncontemplacion
                            WHERE 
                            idcotizacion=".$idcotizacion."
                            AND estado=1;") or die("SQL Error 1: " . mysql_error());
    while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
        $cantidad1=$row["cantidad1"];
    }

    $result = $conexion->query("SELECT
                            COUNT(idcotizacioncontemplacion) as cantidad2
                            FROM
                            t_cotizacioncontemplacion
                            WHERE 
                            idcotizacion=".$idcotizacion."
                            AND estado=2;");
    while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
        $cantidad2=$row["cantidad2"];
    }

    $result = $conexion->query("select COUNT(idcotizacionconsideraciones) as cantidadcon from t_cotizacionconsideraciones WHERE idcotizacion=".$idcotizacion.";");
    while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
        $cantidadcon=$row["cantidadcon"];
    }

    $result = $conexion->query("SELECT
        t_cotizacion.idempresa,
        t_empresa.empresa,
        t_cliente.cliente,
        t_cotizacion.nombre,
        t_cotizacion.numero,
        t_cotizacion.gestion,
        t_usuario.nombre as usuario,
        t_tipoembarque.tipoembarque,
        t_origen.ciudad as origen,
        t_destino.ciudad as destino,
        t_cotizacion.descripcioncarga,
        t_incoterms.incoterms,
        t_cotizacion.peso,
        t_cotizacion.volumen,
        t_cotizacion.piezas,
        t_divisa.codigo as divisa
        FROM
        t_cotizacion
        LEFT JOIN t_cliente ON t_cotizacion.idcliente=t_cliente.idcliente
        LEFT JOIN t_usuario ON t_cotizacion.idusuario=t_usuario.idusuario
        LEFT JOIN t_tipoembarque ON t_cotizacion.idtipoembarque=t_tipoembarque.idtipoembarque
        LEFT JOIN t_ciudad as t_origen ON t_cotizacion.idorigen=t_origen.idciudad
        LEFT JOIN t_ciudad as t_destino ON t_cotizacion.iddestino=t_destino.idciudad
        LEFT JOIN t_incoterms ON t_cotizacion.idincoterms=t_incoterms.idincoterms
        LEFT JOIN t_divisa ON ".$iddivisa."=t_divisa.iddivisa
        LEFT JOIN t_empresa ON t_cotizacion.idempresa=t_empresa.idempresa
        WHERE
        t_cotizacion.idcotizacion=".$idcotizacion.";");
    while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
        $idempresa=$row['idempresa'];
        $empresa=$row['empresa'];
        $cliente=$row["cliente"];
        $numero=$row["numero"];
        $gestion=$row["gestion"];
        $nombre=$row["nombre"];
        $usuario=$row["usuario"];
        $tipoembarque=$row["tipoembarque"];
        $origen=$row["origen"];
        $destino=$row["destino"];
        $descripcioncarga=$row["descripcioncarga"];
        $incoterms=$row["incoterms"];
        $peso=$row["peso"];
        $volumen=$row["volumen"];
        $piezas=$row["piezas"];
        $divisa=$row["divisa"];
    }

    

    
    $html='
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <head>
        <meta equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
        ';
    //PARA INCLUIR MEMBRETADO
    /*
    $html=$html.'
        @page {
        background: url("'.__DIR__.'/../assets/cotizaciontransporte.png");
        background-repeat: no-repeat;
        background-position: left top;
        background-image-resize:2;
        margin-top: 120px;
        margin-bottom: 100px;
        }
        ';
     * 
     */
    $html=$html.'</style>
        </head>
        <body>
        <table border="0" cellpadding="0" cellspacing="0" width="725">
            <tr>
                <td align="right" valign="top"><span style="font-size: 13pt; font-family: verdana">COT-'.$numero.'-'.$gestion.'</span></td>
            </tr>
            <br />
            <br />
            <br />
        </table>
        <table border="1" cellpadding="0" cellspacing="0" width="725">
            <tr>
                <td align="left" width="100"><span style="font-size: 9pt; font-family: verdana">PARA:<br />ATT:<br />DE:<br /></span></td>
                <td align="center" width="313"><span style="font-size: 9pt; font-family: verdana">'.$cliente.'<br />'.$nombre.'<br />'.$empresa.'</span></td>
                <td align="left" width="312"><span style="font-size: 9pt; font-family: verdana">Fecha: '.fechaliteral(date('Y-m-d')).'<br />PAG 1 DE 1<br />DE: '.$usuario.'</span></td>
            </tr>
        </table>
        <br />
        <span style="font-size: 9pt; font-family: verdana">Distinguido(a) Señor(a):</span><br />
        <span style="font-size: 9pt; font-family: verdana">Conforme a lo solicitado, le hago llegar nuestra cotización por el servicio de transporte para carga general no peligrosa.</span><br />
        <br />
        <span style="font-size: 9pt; font-family: verdana"><strong>Transporte '.$tipoembarque.'</strong></span><br />
        <br />
        <table border="0" cellpadding="0" cellspacing="6" width="725">
            <tr>
                <td width="100"><span style="font-size: 9pt; font-family: verdana">ORIGEN</span></td>
                <td width="263"><span style="font-size: 9pt; font-family: verdana">'.$origen.'</span></td>
                <td width="100"><span style="font-size: 9pt; font-family: verdana">PESO:</span></td>
                <td width="262"><span style="font-size: 9pt; font-family: verdana">'.$peso.'</span></td>
            </tr>
            <tr>
                <td width="100"><span style="font-size: 9pt; font-family: verdana">DESTINO</span></td>
                <td width="263"><span style="font-size: 9pt; font-family: verdana">'.$destino.'</span></td>
                <td width="100"><span style="font-size: 9pt; font-family: verdana">VOLUMEN:</span></td>
                <td width="262"><span style="font-size: 9pt; font-family: verdana">'.$volumen.'</span></td>
            </tr>
            <tr>
                <td width="100"><span style="font-size: 9pt; font-family: verdana">MERCADERIA</span></td>
                <td width="263"><span style="font-size: 9pt; font-family: verdana">'.$descripcioncarga.'</span></td>
                <td width="100"><span style="font-size: 9pt; font-family: verdana">PIEZAS:</span></td>
                <td width="262"><span style="font-size: 9pt; font-family: verdana">'.$piezas.'</span></td>
            </tr>
            <tr>
                <td width="100"><span style="font-size: 9pt; font-family: verdana">INCOTERM:</span></td>
                <td width="263"><span style="font-size: 9pt; font-family: verdana">'.$incoterms.'</span></td>
                <td width="100"><span style="font-size: 9pt; font-family: verdana">VALIDEZ:</span></td>
                <td width="262"><span style="font-size: 9pt; font-family: verdana">15 dias</span></td>
            </tr>
        </table>
        <br />
        <span style="font-size: 9pt; font-family: verdana">DETALLE:</span>
        <table border="1" cellpadding="2" cellspacing="2" width="725">
            <tr>
                <td width="400"><span style="font-size: 9pt; font-family: verdana"><strong>CONCEPTO</strong></span></td>
                <td width="100"><span style="font-size: 9pt; font-family: verdana"><strong>CANTIDAD</strong></span></td>
                <td width="100"><span style="font-size: 9pt; font-family: verdana"><strong>PRECIO/U</strong></span></td>
                <td width="125"><span style="font-size: 9pt; font-family: verdana"><strong>SUBTOTAL</strong></span></td>
            </tr>
            <tr>
                <td width="725" colspan="4">
                    <table border="0" cellpadding="2" cellspacing="2" width="725">
                    ';
                    $cantitems=0;
                    $totalfactura=0;
                    $result = $conexion->query("SELECT 
                                        t_concepto.concepto,
                                        t_costocotizacion.cantidad,
                                        t_costocotizacion.montocargo*t_tipocambio.tipocambio as monto,
                                        t_costocotizacion.cantidad*t_costocotizacion.montocargo*t_tipocambio.tipocambio as subtotal
                                        FROM
                                        t_costocotizacion
                                        LEFT JOIN t_concepto ON t_costocotizacion.idconcepto=t_concepto.idconcepto
                                        LEFT JOIN t_cotizacion ON t_costocotizacion.idcotizacion=t_cotizacion.idcotizacion
                                        LEFT JOIN t_tipocambio ON t_costocotizacion.iddivisa=t_tipocambio.iddivisaorigen AND ".$iddivisa."=t_tipocambio.iddivisadestino AND CURRENT_DATE() BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,CURRENT_DATE()) AND t_tipocambio.idempresa=t_cotizacion.idempresa
                                        WHERE t_costocotizacion.idcotizacion=".$idcotizacion.";");
                    while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
                        $cantitems++;
                        $html=$html.'
                        <tr>
                            <td width="400"><span style="font-size: 9pt; font-family: verdana">'.$row["concepto"].'</span></td>
                            <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["cantidad"], 2, ",", ".").'</span></td>
                            <td width="100" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["monto"], 2, ",", ".").'</span></td>
                            <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["subtotal"], 2, ",", ".").'</span></td>
                        </tr>';
                        $totalfactura=$totalfactura+$row["subtotal"];
                    }
                    /*
                    $html=$html.'<tr>
                        <td width="725" colspan="4">&nbsp;</td>
                    </tr>';
                     * 
                     */


                $html=$html.'
                    </table>
                </td>
            </tr>
            <tr>
                <td width="600" colspan="3" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>TOTAL '.$divisa.':</strong></span></td>
                <td width="125" align="right"><span style="font-size: 9pt; font-family: verdana"><strong>'.  number_format($totalfactura, 2, ",", ".").'</strong></span></td>
            </tr>
        </table>
        <br />';

    if((int)$cantidad1>0 || (int)$cantidad2>0){
        $html=$html.'<span style="font-size: 9pt; font-family: verdana"><strong>Nota:</strong><br />'.
            '<table border="0" cellpadding="0" cellspacing="6" width="725"><tr>';
        if((int)$cantidad1>0){
            $html=$html.'<td width="50%" valign="top"><span style="font-size: 9pt; font-family: verdana">'.
                '<strong>Nuestra propuesta contempla:</strong>';
            $result = $conexion->query("SELECT
                                    t_contemplacion.contemplacion
                                    FROM
                                    t_cotizacioncontemplacion
                                    LEFT JOIN t_contemplacion ON t_cotizacioncontemplacion.idcontemplacion=t_contemplacion.idcontemplacion
                                    WHERE 
                                    t_cotizacioncontemplacion.idcotizacion=".$idcotizacion."
                                    AND t_cotizacioncontemplacion.estado=1;") or die("SQL Error 1: " . mysql_error());
            while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
                $html=$html.'<br />'.$row["contemplacion"];
            }
            $html=$html.'</td>';
        }
        if((int)$cantidad2>0){
            $html=$html.'<td width="50%" valign="top"><span style="font-size: 9pt; font-family: verdana">'.
                '<strong>No Incluye:</strong>';
            $result = $conexion->query("SELECT
                                    t_contemplacion.contemplacion
                                    FROM
                                    t_cotizacioncontemplacion
                                    LEFT JOIN t_contemplacion ON t_cotizacioncontemplacion.idcontemplacion=t_contemplacion.idcontemplacion
                                    WHERE 
                                    t_cotizacioncontemplacion.idcotizacion=".$idcotizacion."
                                    AND t_cotizacioncontemplacion.estado=2;");
            while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
                $html=$html.'<br />'.$row["contemplacion"];
            }
            $html=$html.'</td>';
        }
        $html=$html.'</tr></table><br />';

    }

    if((int)$cantidadcon>0){
        $html=$html.'<span style="font-size: 9pt; font-family: verdana"><strong>CONSIDERACIONES GENERALES</strong><br />'.
            '<table border="0" cellpadding="0" cellspacing="6" width="725">';
        $result = $conexion->query("select
                                t_consideraciones.consideraciones
                                from
                                t_cotizacionconsideraciones
                                LEFT JOIN t_consideraciones ON t_cotizacionconsideraciones.idconsideraciones=t_consideraciones.idconsideraciones
                                WHERE
                                t_cotizacionconsideraciones.idcotizacion=".$idcotizacion.";") or die("SQL Error 1: " . mysql_error());
        while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
            $html=$html.'<tr><td style="font-size: 9pt; font-family: verdana">** '.$row["consideraciones"].'</td></tr>';
        }
        $html=$html.'</table>';
    }
        
    //include('MPDF57/mpdf.php');
    
    //$mpdf=new mPDF('','Letter',0,'Helvetica',15,15,12,16,9,12,'P');
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);
    
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right'=> 15,
        'margin_top'=> 15,
        'margin_bottom'=> 16,
        'margin_header'=> 9,
        'margin_footer'=> 12
    ]);
    
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa.DIRECTORY_SEPARATOR."documentos/cotizaciones/cotizacion$idcotizacion.pdf");

}

function generarDocumentoCierre($idembarque, $conexion, $membretado = false){
    $result = $conexion->query("SELECT
        t_embarque.idempresa,
        t_tipoembarque.codigo as modo,
        t_embarque.embarque,
        t_embarque.gestion,
        t_embarque.fechafinalizacion,
        DATE_FORMAT(t_embarque.fechafinalizacion,'%d/%m/%Y') as fecha,
        t_cliente.cliente,
        t_transportista.transportista,
        t_embarque.peso,
        t_embarque.descripcioncarga,
        t_embarque.noidentificacion,
        t_embarque.nodui
        FROM
        t_embarque
        LEFT JOIN t_tipoembarque ON t_embarque.idtipoembarque=t_tipoembarque.idtipoembarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_transportista ON t_embarque.idtransportista=t_transportista.idtransportista
        WHERE
        t_embarque.idembarque=$idembarque;");
    while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
        $idempresa=$row['idempresa'];
        $modo=$row["modo"];
        $embarque=$row["embarque"];
        $gestion=$row["gestion"];
        $fechafinalizacion=$row["fechafinalizacion"];
        $fecha=$row["fecha"];
        $cliente=$row["cliente"];
        $transportista=$row["transportista"];
        $peso=$row["peso"];
        $descripcioncarga=$row["descripcioncarga"];
        $noidentificacion=$row["noidentificacion"];
        $nodui=$row['nodui'];
    }
    


    $html='<br />
    <table border="0" cellpadding="0" cellspacing="0" width="725">
        <tr>
            <td width="750"><img src="'.__DIR__.'/../assets/LogoSLG.png" width="176" height="85" border="0" /></td>
        </tr>
    </table><br /><br /><br /><br />
    <table border="0" cellpadding="2" cellspacing="2" width="750">
        <tr>
            <td width="400" colspan="2">&nbsp;</td>
            <td width="175" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">MODO:</span></td>
            <td width="175" style="border-style: solid; border-width: 1px;" align="center"><span style="font-size: 9pt; font-family: verdana">'.$modo.'</td>
        </tr>
        <tr>
            <td width="400" colspan="2">&nbsp;</td>
            <td width="175" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">No CARPETA:</span></td>
            <td width="175" style="border-style: solid; border-width: 1px;" align="center"><span style="font-size: 9pt; font-family: verdana">'.$embarque.'</td>
        </tr>
        <tr>
            <td width="400" colspan="2">&nbsp;</td>
            <td width="175" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">GESTION:</span></td>
            <td width="175" style="border-style: solid; border-width: 1px;" align="center"><span style="font-size: 9pt; font-family: verdana">'.$gestion.'</td>
        </tr>
        <tr>
            <td width="400" colspan="2">&nbsp;</td>
            <td width="175" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">FECHA:</span></td>
            <td width="175" style="border-style: solid; border-width: 1px;" align="center"><span style="font-size: 9pt; font-family: verdana">'.$fecha.'</td>
        </tr>
        <tr>
            <td width="400" colspan="2">&nbsp;</td>
            <td width="175" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">TC:</span></td>
            <td width="175" style="border-style: solid; border-width: 1px;" align="center"><span style="font-size: 9pt; font-family: verdana">6.96</td>
        </tr>
        <tr>
            <td width="150" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">CLIENTE:</span></td>
            <td width="600" colspan="3" align="center" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana"><strong>'.$cliente.'</strong></span></td>
        </tr>
        <tr>
            <td width="150" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">CARRIER:</span></td>
            <td width="600" colspan="3" align="center" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">'.$transportista.'</span></td>
        </tr>
    </table><br /><br /><br />
    <span style="font-size: 9pt; font-family: verdana"><strong>DOCUMENTOS:</strong></span><br />
    <table border="0" cellpadding="2" cellspacing="2" width="500" style="margin:0 auto;">
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">PESO:</span></td>
            <td width="250" align="right" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">'.$peso.'</span></td>
        </tr>
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">CANTIDAD DE MERCADERIA:</span></td>
            <td width="250" align="right" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">'.$descripcioncarga.'</span></td>
        </tr>
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">NO DUI:</span></td>
            <td width="250" align="right" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">'.$nodui.'</span></td>
        </tr>
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">NOTA DE DEBITO SLG:</span></td>
            <td width="250" align="right" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">';

            $result = $conexion->query("SELECT
                                    CONCAT(nronotadebito,'/',gestion) as nronotadebito
                                    FROM
                                    t_notadebito
                                    WHERE
                                    idembarque=".$idembarque.";");
            while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
                $html=$html.$row["nronotadebito"]."<br />";
            }


    $html=$html.'
            </span></td>
        </tr>
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">DOC DE EMBARQUE:</span></td>
            <td width="250" align="right" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">'.$noidentificacion.'</span></td>
        </tr>';
        $montototal=0;
        $result = $conexion->query("SELECT
                            CASE IFNULL(t_concepto.flete,0)
                                WHEN 1 THEN 'TRANSPORTE INT'
                                ELSE 'GASTOS ADICIONALES'
                            END as flete,
                            SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio) as monto
                            FROM
                            t_cargo
                            LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
                            LEFT JOIN t_factura ON t_cargo.idfacturanotadebito=t_factura.idfactura AND t_cargo.idtipofacturanotadebito=1 AND 1=t_factura.idestadofactura
                            LEFT JOIN t_notadebito ON t_cargo.idfacturanotadebito=t_notadebito.idnotadebito AND t_cargo.idtipofacturanotadebito=2 AND 1=t_notadebito.idestadonotadebito
                            LEFT JOIN t_embarque ON t_cargo.idembarque=t_embarque.idembarque
                            LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 2=t_tipocambio.iddivisadestino AND IFNULL(t_factura.fecha,t_notadebito.fecha) BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,IFNULL(t_factura.fecha,t_notadebito.fecha)) AND t_tipocambio.idempresa=t_embarque.idempresa
                            WHERE t_cargo.idembarque=".$idembarque."
                            GROUP BY
                            IFNULL(t_concepto.flete,0)
                            ORDER BY IFNULL(t_concepto.flete,0) DESC;");
        while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
            $montototal=$montototal+$row["monto"];
            $html=$html.'<tr>
                            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">'.$row["flete"].':</span></td>
                            <td width="250" align="right" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">'.  number_format($row["monto"], 2, ".", ",").'</span></td>
                        </tr>';
        }
    
    $html=$html.'<tr>
                    <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">TOTAL FLETES:</span></td>
                    <td width="250" align="right" style="border-style: solid; border-width: 1px;"><span style="font-size: 9pt; font-family: verdana">'.  number_format($montototal, 2, ".", ",").'</span></td>
                </tr>';
    
    $html=$html.'
    </table>
    ';
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);
    
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right'=> 15,
        'margin_top'=> 15,
        'margin_bottom'=> 16,
        'margin_header'=> 9,
        'margin_footer'=> 12
    ]);
    
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa.DIRECTORY_SEPARATOR."documentos/documentoscierre/cierre_$idembarque.pdf");

}

function generarCaratula($idembarque, $conexion, $membretado = false){
    $result = $conexion->query("select
        t_embarque.idempresa,
        t_embarque.embarque,
        t_cliente.cliente,
        t_embarque.numeroguia,
        t_ciudad.ciudad,
        t_usuario.nombre,
        t_embarque.fecharealizacion,
        t_embarque.noidentificacion,
        t_embarque.descripcioncarga,
        t_embarque.peso,
        t_embarque.volumen,
        t_embarque.piezas,
        t_embarque.carpetapacena,
        t_embarque.nodui,
        t_incoterms.incoterms
        FROM
        t_embarque
        LEFT JOIN t_cliente ON t_embarque.idcliente=t_cliente.idcliente
        LEFT JOIN t_ciudad ON t_embarque.idciudad=t_ciudad.idciudad
        LEFT JOIN t_usuario ON t_embarque.idusuario=t_usuario.idusuario
        LEFT JOIN t_incoterms ON t_embarque.idincoterms=t_incoterms.idincoterms
        WHERE
        t_embarque.idembarque=$idembarque;");
    while ($row = $result ->fetch(PDO::FETCH_ASSOC)){
        $idempresa=$row['idempresa'];
        $embarque=$row["embarque"];
        $cliente=$row["cliente"];
        $numeroguia=$row["numeroguia"];
        $ciudad=$row["ciudad"];
        $nombre=$row['nombre'];
        $fecharealizacion=$row["fecharealizacion"];
        $noidentificacion=$row["noidentificacion"];
        //$valordeclarado=$row["valordeclarado"];
        $descripcioncarga=$row["descripcioncarga"];
        $peso=$row["peso"];
        $volumen=$row['volumen'];
        $piezas=$row['piezas'];
        $carpetapacena=$row['carpetapacena'];
        $nodui=$row['nodui'];
        $incoterms=$row['incoterms'];
    }
    


    $html='<br />
    <table border="0" cellpadding="0" cellspacing="0" width="725">
        <tr>
            <td width="750"><img src="'.__DIR__.'/../assets/LogoSLG.png" width="176" height="85" border="0" /></td>
        </tr>
    </table><br /><br /><br /><br />
    <table border="0" cellpadding="2" cellspacing="2" width="750">
        <tr>
            <td width="750"><span style="font-size: 11pt; font-family: verdana">EMBARQUE: <strong>'.$embarque.' - '.$cliente.'</strong></span></td>
        </tr>
    </table><br /><br /><br />
    
    <table border="0" cellpadding="2" cellspacing="2" width="500" style="margin:0 auto;">
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>No de Guia</strong><br />'.$numeroguia.'&nbsp;</span></td>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Lugar de realización</strong><br />'.$ciudad.'&nbsp;</span></td>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Realizado por</strong><br />'.$nombre.'&nbsp;</span></td>
        </tr>
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Fecha de Realización</strong><br />'.$fecharealizacion.'&nbsp;</span></td>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>No de Identificación/Ref.</strong><br />'.$noidentificacion.'&nbsp;</span></td>
            <td width="250" style="border-style: solid; border-width: 1px;"></td>
        </tr>
        <tr>
            <td width="720" colspan="3" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Descripción de la Carga</strong><br />'.$descripcioncarga.'&nbsp;</span></td>
        </tr>
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Peso</strong><br />'.$peso.'&nbsp;</span></td>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Volumen</strong><br />'.$volumen.'&nbsp;</span></td>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Piezas</strong><br />'.$piezas.'&nbsp;</span></td>
        </tr>
        <tr>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Carpeta Paceña</strong><br />'.$carpetapacena.'&nbsp;</span></td>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>No DUI</strong><br />'.$nodui.'&nbsp;</span></td>
            <td width="250" style="border-style: solid; border-width: 1px;"><span style="font-size: 11pt; font-family: verdana"><strong>Incoterms</strong><br />'.$incoterms.'&nbsp;</span></td>
        </tr>
    </table>
    ';
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);
    
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'Letter',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right'=> 15,
        'margin_top'=> 15,
        'margin_bottom'=> 16,
        'margin_header'=> 9,
        'margin_footer'=> 12
    ]);
    
    $mpdf->WriteHTML($html);
    $mpdf->Output(folder_files.$idempresa.DIRECTORY_SEPARATOR."documentos/caratulas/caratula_$idembarque.pdf");

}
