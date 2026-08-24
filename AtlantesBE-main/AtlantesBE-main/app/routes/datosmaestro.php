<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$archivosmasivo=array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

$app->get('/importacion_exportacion', function(Request $request, Response $response, array $args) use ($conexion) {
    $importacion_exportacion=[];
    $result = $conexion->query("SELECT
        importacion_exportacion,
        importacion_exportacion_texto,
        importacion_exportacion_codigo,
        IFNULL(parametrizacion,0) as parametrizacion
        from
        t_importacion_exportacion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $importacion_exportacion[]=array(
            'importacion_exportacion'=>(int)$row['importacion_exportacion'],
            'importacion_exportacion_texto'=>$row['importacion_exportacion_texto'],
            'importacion_exportacion_codigo'=>$row['importacion_exportacion_codigo'],
            'parametrizacion'=> boolval($row['parametrizacion'])
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'importacion_exportacion' => $importacion_exportacion
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposembarque', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposembarque=[];
    $result = $conexion->query("SELECT
        idtipoembarque,
        tipoembarque,
        codigo,
        tipoembarque_en
        from
        t_tipoembarque;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposembarque[]=array(
            'idtipoembarque'=>(int)$row['idtipoembarque'],
            'tipoembarque'=>$row['tipoembarque'],
            'codigo'=>$row['codigo'],
            'tipoembarque_en'=>$row['tipoembarque_en']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposembarque' => $tiposembarque
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/contemplaciones', function(Request $request, Response $response, array $args) use ($conexion) {
    $decoded_array = $request->getAttribute('auth') ?: [];
    $idempresa=$decoded_array["idempresa"];
    
    $contemplaciones=[];
    $result = $conexion->query("SELECT
        idcontemplacion,
        contemplacion
        FROM
        t_contemplacion
        WHERE
        idempresa=$idempresa
        ORDER BY
        contemplacion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $contemplaciones[]=array(
            'idcontemplacion'=>(int)$row['idcontemplacion'],
            'contemplacion'=>$row['contemplacion']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'contemplaciones' => $contemplaciones
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/contemplaciones', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $contemplacion=$params['contemplacion'];

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="INSERT INTO t_contemplacion (idempresa,    contemplacion)
                                 VALUES ($idempresa,   '$contemplacion');";
    $result = $conexion->exec($query);

    if($result===false){

    }else{
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información correctamente';
    }
    
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/contemplaciones/{idcontemplacion}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcontemplacion = $args['idcontemplacion'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $contemplacion=$params['contemplacion'];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    

    $query="UPDATE t_contemplacion SET 
        contemplacion='$contemplacion'
        WHERE 
        idcontemplacion=$idcontemplacion;";

    $result = $conexion->exec($query);

    if($result===false){

    }else{
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información correctamente';
    }
    
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/consideraciones', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $consideraciones=[];
    $result = $conexion->query("SELECT
        idconsideraciones,
        consideraciones
        FROM
        t_consideraciones
        WHERE idempresa=$idempresa
        ORDER BY
        consideraciones");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $consideraciones[]=array(
            'idconsideraciones'=>(int)$row['idconsideraciones'],
            'consideraciones'=>$row['consideraciones']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'consideraciones' => $consideraciones
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/consideraciones', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $consideraciones=$params['consideraciones'];

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="INSERT INTO t_consideraciones (idempresa,    consideraciones)
                                   VALUES ($idempresa,   '$consideraciones');";
    $result = $conexion->exec($query);

    if($result===false){

    }else{
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información correctamente';
    }
    
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/consideraciones/{idconsideraciones}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idconsideraciones = $args['idconsideraciones'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $consideraciones=$params['consideraciones'];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    

    $query="UPDATE t_consideraciones SET 
        consideraciones='$consideraciones'
        WHERE 
        idconsideraciones=$idconsideraciones;";

    $result = $conexion->exec($query);

    if($result===false){

    }else{
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información correctamente';
    }
    
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/incoterms', function(Request $request, Response $response, array $args) use ($conexion) {
    $incoterms=[];
    $result = $conexion->query("SELECT * FROM t_incoterms;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $incoterms[]=array(
            'idincoterms'=>(int)$row['idincoterms'],
            'incoterms'=>$row['incoterms']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'incoterms' => $incoterms
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/empresas', function(Request $request, Response $response, array $args) use ($conexion) {
    $empresas=[];
    $result = $conexion->query("SELECT idempresa, empresa, titulo FROM t_empresa ORDER BY empresa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $empresas[]=array(
            'idempresa'=>(int)$row['idempresa'],
            'empresa'=>$row['empresa'],
            'titulo'=>$row['titulo']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'empresas' => $empresas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);


$app->get('/timezones', function(Request $request, Response $response, array $args) use ($conexion) {
    $timezones = DateTimeService::timezonesFromDatabase($conexion);

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'timezones' => $timezones
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);


$app->get('/ciudades', function(Request $request, Response $response, array $args) use ($conexion) {
    $decoded_array = $request->getAttribute('auth') ?: [];
    $idempresa=$decoded_array["idempresa"];

    $ciudades=[];
    $result = $conexion->query("SELECT 
            idciudad, 
            codigo, 
            ciudad, 
            modotransporte, 
            pais, 
            IFNULL(timezone_name, '".DateTimeService::defaultTimezoneName()."') as timezone_name,
            IFNULL(utc_offset_minutos, ".DateTimeService::defaultOffsetMinutes().") as utc_offset_minutos,
            IFNULL(parametrizacion,0) as parametrizacion, 
            IFNULL(idaduana,0) as idaduana 
            FROM 
            t_ciudad 
            WHERE 
            idempresa=$idempresa
            ORDER BY ciudad;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $timezoneName = DateTimeService::normalizeTimezoneName($row['timezone_name']);
        $ciudades[]=array(
            'idciudad'=>(int)$row['idciudad'],
            'codigo'=>$row['codigo'],
            'ciudad'=>$row['ciudad'],
            'modotransporte'=>$row['modotransporte'],
            'pais'=>$row['pais'],
            'timezone_name'=>$timezoneName,
            'utc_offset_minutos'=>DateTimeService::offsetMinutesForTimezone($timezoneName),
            'parametrizacion'=> boolval($row['parametrizacion']),
            'idaduana'=>(int)$row['idaduana']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'ciudades' => $ciudades
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/ciudades', function(Request $request, Response $response, array $args) use ($conexion) {
    $decoded_array = $request->getAttribute('auth') ?: [];
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $ciudad=$params['ciudad'];
    $codigociudad=$params['codigo'];
    $modotransporte=$params['modotransporte'];
    $pais=$params['pais'];
    $timezoneDefault = DateTimeService::defaultTimezoneForCountry($pais);
    $timezone_name = trim($params['timezone_name'] ?? $timezoneDefault['timezone_name']);
    $utc_offset_minutos = DateTimeService::offsetMinutesForTimezone(DateTimeService::normalizeTimezoneName($timezone_name));
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la informacion';
    $timezoneError=false;

    if(!DateTimeService::isValidTimezoneName($timezone_name)){
        $codigo=402;
        $timezoneError=true;
        $mensaje='Zona horaria invalida';
    }else{
        $timezone_name = DateTimeService::normalizeTimezoneName($timezone_name);
        $utc_offset_minutos = DateTimeService::offsetMinutesForTimezone($timezone_name);
    }

    $idciudad=0;
    $existeciudad=false;
    $result = $conexion->query("select idciudad FROM t_ciudad WHERE codigo='$codigociudad' AND idempresa=$idempresa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idciudad=$row['idciudad'];
    }
    if($timezoneError){
    }else if((int)$idciudad>0){
        $codigo=401;
        $existeciudad=true;
        $mensaje='Ya existe el codigo';
    }else{
        $query="INSERT INTO t_ciudad (idempresa,    codigo,             ciudad,     modotransporte,     pais,   timezone_name,    utc_offset_minutos)
                              VALUES ($idempresa,   '$codigociudad',    '$ciudad',  '$modotransporte',  '$pais', '$timezone_name',  $utc_offset_minutos);";
        $result = $conexion->exec($query);

        if($result===false){

        }else{
            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información correctamente';
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

$app->put('/ciudades/{idciudad}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idciudad = $args['idciudad'];
    $decoded_array = $request->getAttribute('auth') ?: [];
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $ciudad=$params['ciudad'];
    $codigociudad=$params['codigo'];
    $modotransporte=$params['modotransporte'];
    $pais=$params['pais'];
    $timezoneDefault = DateTimeService::defaultTimezoneForCountry($pais);
    $timezone_name = trim($params['timezone_name'] ?? $timezoneDefault['timezone_name']);
    $utc_offset_minutos = DateTimeService::offsetMinutesForTimezone(DateTimeService::normalizeTimezoneName($timezone_name));
    $timezoneError=false;

    if(!DateTimeService::isValidTimezoneName($timezone_name)){
        $timezoneError=true;
    }else{
        $timezone_name = DateTimeService::normalizeTimezoneName($timezone_name);
        $utc_offset_minutos = DateTimeService::offsetMinutesForTimezone($timezone_name);
    }

    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    $query='';
    
    if($timezoneError){
        $codigo=402;
        $mensaje='Zona horaria invalida';
    }

    $idciudadexistente=0;
    $existeciudad=false;
    $result = $conexion->query("select idciudad FROM t_ciudad WHERE codigo='$codigociudad' AND idempresa=$idempresa AND idciudad<>$idciudad;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idciudadexistente=$row['idciudad'];
    }
    if($timezoneError){
    }else if((int)$idciudadexistente>0){
        $codigo=401;
        $existeciudad=true;
        $mensaje='Ya existe el codigo';
    }else{
        
        $query="UPDATE t_ciudad SET 
            ciudad='$ciudad',
            codigo='$codigociudad',
            modotransporte='$modotransporte',
            pais='$pais',
            timezone_name='$timezone_name',
            utc_offset_minutos=$utc_offset_minutos
            WHERE idciudad=$idciudad;";

        $result = $conexion->exec($query);

        if($result===false){

        }else{
            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información correctamente';
        }
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

$app->get('/mediostransporte', function(Request $request, Response $response, array $args) use ($conexion) {
    $mediostransporte=[];
    $result = $conexion->query("select idmediotransporte, mediotransporte, IFNULL(parametrizacion,0) as parametrizacion from t_mediotransporte;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $mediostransporte[]=array(
            'idmediotransporte'=>(int)$row['idmediotransporte'],
            'mediotransporte'=>$row['mediotransporte'],
            'parametrizacion'=> boolval($row['parametrizacion'])
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'mediostransporte' => $mediostransporte
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposcarga', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposcarga=[];
    $result = $conexion->query("select idtipocarga, tipocarga, idtemperatura, activo from t_tipocarga;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposcarga[]=array(
            'idtipocarga'=>(int)$row['idtipocarga'],
            'tipocarga'=>$row['tipocarga'],
            'idtemperatura'=>$row['idtemperatura'],
            'activo'=>(int)$row['activo']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposcarga' => $tiposcarga
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/aduanas', function(Request $request, Response $response, array $args) use ($conexion) {
    $aduanas=[];
    $result = $conexion->query("select idaduana, aduana from t_aduana;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $aduanas[]=array(
            'idaduana'=>(int)$row['idaduana'],
            'aduana'=>$row['aduana']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'aduanas' => $aduanas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/temperaturas', function(Request $request, Response $response, array $args) use ($conexion) {
    $temperaturas=[];
    $result = $conexion->query("select idtemperatura, temperatura, activo from t_temperatura;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $temperaturas[]=array(
            'idtemperatura'=>(int)$row['idtemperatura'],
            'temperatura'=>$row['temperatura'],
            'activo'=>(int)$row['activo']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'temperaturas' => $temperaturas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/horarios', function(Request $request, Response $response, array $args) use ($conexion) {
    $horarios=[];
    $result = $conexion->query("select idhorario, horario from t_horario;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $horarios[]=array(
            'idhorario'=>(int)$row['idhorario'],
            'horario'=>$row['horario']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'horarios' => $horarios
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/conceptos', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $conceptos=[];
    $result = $conexion->query("SELECT
        idconcepto, 
        concepto, 
        codigo, 
        tipo, 
        idconceptocargo, 
        concepto_en, 
        ifnull(activo,0) as activo, 
        id_OVP, 
        id_OVPRef 
        FROM 
        t_concepto 
        WHERE idempresa=$idempresa
        ORDER BY concepto;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $concepto_ovp=$row['concepto'];
        if((int)$row["tipo"]==1){
            if(strlen($row["id_OVPRef"])>0){
                $concepto_ovp=$row['concepto']." (".$row["id_OVPRef"].")";
            }
        }else{
            if((int)$row['id_OVP']>0){
                $concepto_ovp=$row['concepto']." (".$row["id_OVP"].")";
            }
        }
        
            
        
        
        $conceptos[]=array(
            'idconcepto'=>(int)$row['idconcepto'],
            'concepto'=>$row['concepto'],
            'concepto_ovp'=>$concepto_ovp,
            'concepto_en'=>$row['concepto_en'],
            'codigo'=>$row['codigo'],
            'tipo'=>(int)$row['tipo'],
            'idconceptocargo'=>(int)$row['idconceptocargo'],
            'activo'=>boolval($row['activo']),
            'id_OVP'=>$row['id_OVP'],
            'id_OVPRef'=>$row['id_OVPRef']
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

$app->post('/conceptos', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $params = json_decode((string) $request->getBody(),true);

    $concepto=$params['concepto'];
    $codigoconcepto=$params['codigo'];
    $concepto_en=$params['concepto_en'];
    $id_OVP=$params['id_OVP'];
    $id_OVPRef=$params['id_OVPRef'];
    $conceptocosto=$params['conceptocosto'];
    $codigocosto=$params['codigocosto'];
    $concepto_encosto=$params['concepto_encosto'];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $idconcepto=0;
    $existeconcepto=false;
    $result = $conexion->query("select idconcepto FROM t_concepto WHERE codigo='$codigoconcepto';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idconcepto=$row['idconcepto'];
    }
    if((int)$idconcepto>0){
        $codigo=401;
        $existeconcepto=true;
        $mensaje='Ya existe el codigo';
    }else{
        $query="INSERT INTO t_concepto (idempresa,  concepto,       codigo,             concepto_en,    activo, id_OVP)
                                VALUES ($idempresa, '$concepto',    '$codigoconcepto',  '$concepto_en', 1,      '$id_OVP');";
        $query=$query."SELECT LAST_INSERT_ID() INTO @idconcepto_nuevo;";

        $query=$query."INSERT INTO t_concepto (idempresa,   concepto,           codigo,         concepto_en,            tipo,   activo, idconceptocargo,     id_OVPRef)
                                       VALUES ($idempresa,  '$conceptocosto',   '$codigocosto', '$concepto_encosto',    1,      1,      @idconcepto_nuevo,   '$id_OVPRef');";

        $result = $conexion->exec($query);

        if($result===false){

        }else{
            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información correctamente';
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

$app->put('/conceptos/{idconcepto}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idconcepto = $args['idconcepto'];
    $params = json_decode((string) $request->getBody(),true);

    $concepto=$params['concepto'];
    $codigoconcepto=$params['codigo'];
    $concepto_en=$params['concepto_en'];
    $id_OVP=$params['id_OVP'];
    $id_OVPRef=$params['id_OVPRef'];
    $conceptocosto=$params['conceptocosto'];
    $codigocosto=$params['codigocosto'];
    $concepto_encosto=$params['concepto_encosto'];
    $activo=$params['activo'];

    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $idconceptoexistente=0;
    $existeconcepto=false;
    $result = $conexion->query("select idconcepto FROM t_concepto WHERE codigo='$codigoconcepto' AND idconcepto<>$idconcepto;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idconceptoexistente=$row['idconcepto'];
    }
    if((int)$idconceptoexistente>0){
        $codigo=401;
        $existeconcepto=true;
        $mensaje='Ya existe el codigo';
    }else{
        
        $query="UPDATE t_concepto SET 
            concepto='$concepto',
            codigo='$codigoconcepto',
            concepto_en='$concepto_en',
            activo='$activo',
            id_OVP='$id_OVP'
            WHERE idconcepto=$idconcepto;";

        $query=$query."UPDATE t_concepto SET 
            concepto='$conceptocosto',
            codigo='$codigocosto',
            concepto_en='$concepto_encosto',
            activo='$activo',
            id_OVPRef='$id_OVPRef'
            WHERE 
            idconceptocargo=$idconcepto;";

        $result = $conexion->exec($query);

        if($result===false){

        }else{
            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información correctamente';
        }
    }
    
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'query'=>$query,
        'params'=>$id_OVPRef
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/lista-divisas', function(Request $request, Response $response, array $args) use ($conexion) {
    $divisas=[];
    $result = $conexion->query("SELECT iddivisa, divisa, codigo FROM t_divisa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $divisas[]=array(
            'iddivisa'=>(int)$row['iddivisa'],
            'divisa'=>$row['divisa'],
            'codigo'=>$row['codigo']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'divisas' => $divisas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/divisas', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $divisas=[];
    $result = $conexion->query("select 
        t_divisa.iddivisa,
        t_divisa.divisa,
        t_divisa.codigo 
        FROM 
        t_empresadivisa
        LEFT JOIN t_divisa ON t_empresadivisa.iddivisa=t_divisa.iddivisa
        WHERE
        t_empresadivisa.idempresa=$idempresa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $divisas[]=array(
            'iddivisa'=>(int)$row['iddivisa'],
            'divisa'=>$row['divisa'],
            'codigo'=>$row['codigo']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'divisas' => $divisas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/divisas', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $params = json_decode((string) $request->getBody(),true);

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $actuales=array();
    $result = $conexion->query("select iddivisa FROM t_empresadivisa WHERE idempresa=$idempresa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        array_push($actuales, (int)$row["iddivisa"]);
    }
    
    $query='';
    for($pp=0;$pp<count($params);$pp++){
        if(!is_numeric(array_search($params[$pp], $actuales))){
            $query=$query."INSERT INTO t_empresadivisa (idempresa, iddivisa) VALUES ($idempresa, ".$params[$pp].");";
        }
    }
    for($pp=0;$pp<count($actuales);$pp++){
        if(!is_numeric(array_search($actuales[$pp], $params))){
            $query=$query."DELETE FROM t_empresadivisa WHERE idempresa=$idempresa AND iddivisa=".$actuales[$pp].";";
        }
    }
    
    if($query<>''){
        $result = $conexion->exec($query);

        if($result===false){

        }else{
            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información correctamente';
        }
    } else {
        $mensaje='No hay data para procesar';
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/divisas/ordenservicio', function(Request $request, Response $response, array $args) use ($conexion) {
    $divisas=[];
    $result = $conexion->query("SELECT iddivisaordenservicio, divisaordenservicio FROM t_divisaordenservicio;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $divisas[]=array(
            'iddivisaordenservicio'=>(int)$row['iddivisaordenservicio'],
            'divisaordenservicio'=>$row['divisaordenservicio']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'divisas' => $divisas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/tipo-cambio/{fecha}', function(Request $request, Response $response, array $args) use ($conexion) {

    $fecha = $args['fecha'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se pudo obtener la información';

    $tiposcambio = [];
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
            $mensaje = 'No se recibieron divisas válidas';
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

        if ($continuar && empty($fecha)) {
            $mensaje = 'No se recibió la fecha';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Validar formato de fecha
        |--------------------------------------------------------------------------
        */
        if ($continuar) {
            $fechaValida = DateTime::createFromFormat('Y-m-d', $fecha);

            if (!$fechaValida || $fechaValida->format('Y-m-d') !== $fecha) {
                $mensaje = 'La fecha no tiene un formato válido';
                $continuar = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Normalizar divisas
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $iddivisas = [];

            foreach ($params as $iddivisa) {
                $iddivisaNum = (int)$iddivisa;

                if ($iddivisaNum > 0) {
                    $iddivisas[] = $iddivisaNum;
                }
            }

            $iddivisas = array_values(array_unique($iddivisas));

            if (count($iddivisas) === 0) {
                /*
                Mantiene el comportamiento anterior:
                si no llega ninguna divisa válida, no devuelve tipos de cambio.
                */
                $iddivisas = [0];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Consultar tipos de cambio
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $placeholders = [];

            foreach ($iddivisas as $index => $iddivisa) {
                $placeholders[] = $iddivisa;
            }

            $inDivisas = implode(',', $placeholders);

            $query = "
                SELECT 
                    iddivisaorigen,
                    iddivisadestino,
                    tipocambio
                FROM t_tipocambio
                WHERE :fecha BETWEEN fechainicio AND IFNULL(fechafin, :fecha_fin)
                  AND iddivisaorigen IN ($inDivisas)
                  AND iddivisadestino IN ($inDivisas)
                  AND idempresa = :idempresa
            ";

            $stmt = $conexion->prepare($query);

            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':fecha_fin', $fecha);
            $stmt->bindValue(':idempresa', $idempresa, PDO::PARAM_INT);

            /*
            foreach ($iddivisas as $index => $iddivisa) {
                $stmt->bindValue(':iddivisa_' . $index, $iddivisa, PDO::PARAM_INT);
            }
            */
            $result = $stmt->execute();

            if (!$result) {
                $mensaje = 'No se pudo consultar los tipos de cambio';
                $continuar = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Armar respuesta
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tiposcambio[] = array(
                    'iddivisaorigen' => (int)$row['iddivisaorigen'],
                    'iddivisadestino' => (int)$row['iddivisadestino'],
                    'tipocambio' => (float)$row['tipocambio']
                );
            }

            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Todo correcto';
        }

    } catch (PDOException $e) {

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error de base de datos: ' . $e->getMessage();

    } catch (Exception $e) {

        $codigo = 400;
        $status = 'Error';
        $mensaje = 'Error general: ' . $e->getMessage();
    }

    $response->getBody()->write(json_encode(array(
        'estado' => $status,
        'codigo' => $codigo,
        'mensaje' => $mensaje,
        'tiposcambio' => $tiposcambio,
        'query'=>$query
    )));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->put('/tipo-cambio/{fecha}', function(Request $request, Response $response, array $args) use ($conexion) {

    $fecha = $args['fecha'] ?? null;

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
            $mensaje = 'No se recibieron tipos de cambio válidos';
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

        if ($continuar && empty($fecha)) {
            $mensaje = 'No se recibió la fecha';
            $continuar = false;
        }

        if ($continuar) {
            $fechaValida = DateTime::createFromFormat('Y-m-d', $fecha);

            if (!$fechaValida || $fechaValida->format('Y-m-d') !== $fecha) {
                $mensaje = 'La fecha no tiene un formato válido';
                $continuar = false;
            }
        }

        if ($continuar && count($params) === 0) {
            $mensaje = 'No se recibieron tipos de cambio para guardar';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Preparar consultas
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $esFechaActual = ($fecha === date("Y-m-d"));

            $stmtBuscarActual = $conexion->prepare("
                SELECT idtipocambio
                FROM t_tipocambio
                WHERE iddivisaorigen = :iddivisaorigen
                  AND iddivisadestino = :iddivisadestino
                  AND idempresa = :idempresa
                  AND fechainicio = :fecha
                  AND fechafin IS NULL
                LIMIT 1
            ");

            $stmtBuscarDiaExacto = $conexion->prepare("
                SELECT idtipocambio
                FROM t_tipocambio
                WHERE iddivisaorigen = :iddivisaorigen
                  AND iddivisadestino = :iddivisadestino
                  AND idempresa = :idempresa
                  AND fechainicio = :fecha
                  AND fechafin = :fecha_fin
                LIMIT 1
            ");

            $stmtBuscarInicioFecha = $conexion->prepare("
                SELECT idtipocambio, fechafin
                FROM t_tipocambio
                WHERE iddivisaorigen = :iddivisaorigen
                  AND iddivisadestino = :iddivisadestino
                  AND idempresa = :idempresa
                  AND fechainicio = :fecha
                LIMIT 1
            ");

            $stmtBuscarRango = $conexion->prepare("
                SELECT idtipocambio, fechafin
                FROM t_tipocambio
                WHERE iddivisaorigen = :iddivisaorigen
                  AND iddivisadestino = :iddivisadestino
                  AND idempresa = :idempresa
                  AND :fecha BETWEEN fechainicio AND IFNULL(fechafin, :fecha_fin)
                LIMIT 1
            ");

            $stmtUpdateTipoCambio = $conexion->prepare("
                UPDATE t_tipocambio
                SET tipocambio = :tipocambio
                WHERE idtipocambio = :idtipocambio
            ");

            $stmtCerrarVigente = $conexion->prepare("
                UPDATE t_tipocambio
                SET fechafin = DATE_SUB(:fecha, INTERVAL 1 DAY)
                WHERE iddivisaorigen = :iddivisaorigen
                  AND iddivisadestino = :iddivisadestino
                  AND idempresa = :idempresa
                  AND fechafin IS NULL
            ");

            $stmtCerrarPorId = $conexion->prepare("
                UPDATE t_tipocambio
                SET fechafin = DATE_SUB(:fecha, INTERVAL 1 DAY)
                WHERE idtipocambio = :idtipocambio
            ");

            $stmtMoverInicioPorId = $conexion->prepare("
                UPDATE t_tipocambio
                SET fechainicio = DATE_ADD(fechainicio, INTERVAL 1 DAY)
                WHERE idtipocambio = :idtipocambio
            ");

            $stmtInsertAbierto = $conexion->prepare("
                INSERT INTO t_tipocambio (
                    idempresa,
                    iddivisaorigen,
                    iddivisadestino,
                    tipocambio,
                    fechainicio,
                    fechafin
                ) VALUES (
                    :idempresa,
                    :iddivisaorigen,
                    :iddivisadestino,
                    :tipocambio,
                    :fechainicio,
                    NULL
                )
            ");

            $stmtInsertDia = $conexion->prepare("
                INSERT INTO t_tipocambio (
                    idempresa,
                    iddivisaorigen,
                    iddivisadestino,
                    tipocambio,
                    fechainicio,
                    fechafin
                ) VALUES (
                    :idempresa,
                    :iddivisaorigen,
                    :iddivisadestino,
                    :tipocambio,
                    :fechainicio,
                    :fechafin
                )
            ");

            $stmtDuplicarPosterior = $conexion->prepare("
                INSERT INTO t_tipocambio (
                    idempresa,
                    iddivisaorigen,
                    iddivisadestino,
                    tipocambio,
                    fechainicio,
                    fechafin
                )
                SELECT
                    idempresa,
                    iddivisaorigen,
                    iddivisadestino,
                    tipocambio,
                    DATE_ADD(:fecha, INTERVAL 1 DAY),
                    fechafin
                FROM t_tipocambio
                WHERE idtipocambio = :idtipocambio
            ");
        }

        /*
        |--------------------------------------------------------------------------
        | Procesar tipos de cambio
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            foreach ($params as $tcItem) {

                $iddivisaorigen = $tcItem["iddivisaorigen"] ?? null;
                $iddivisadestino = $tcItem["iddivisadestino"] ?? null;
                $tipocambio = $tcItem["tipocambio"] ?? null;

                if (empty($iddivisaorigen)) {
                    $mensaje = 'Un tipo de cambio no tiene divisa origen';
                    $continuar = false;
                    break;
                }

                if (empty($iddivisadestino)) {
                    $mensaje = 'Un tipo de cambio no tiene divisa destino';
                    $continuar = false;
                    break;
                }

                if ($tipocambio === null || $tipocambio === '') {
                    $mensaje = 'Un tipo de cambio no tiene valor';
                    $continuar = false;
                    break;
                }

                $iddivisaorigen = (int)$iddivisaorigen;
                $iddivisadestino = (int)$iddivisadestino;
                $tipocambio = (float)$tipocambio;

                if ($tipocambio <= 0) {
                    $mensaje = 'El tipo de cambio debe ser mayor a cero';
                    $continuar = false;
                    break;
                }

                /*
                |--------------------------------------------------------------------------
                | Caso 1: fecha actual
                |--------------------------------------------------------------------------
                */
                if ($esFechaActual) {

                    $stmtBuscarActual->execute([
                        ':iddivisaorigen' => $iddivisaorigen,
                        ':iddivisadestino' => $iddivisadestino,
                        ':idempresa' => $idempresa,
                        ':fecha' => $fecha
                    ]);

                    $rowActual = $stmtBuscarActual->fetch(PDO::FETCH_ASSOC);
                    $idtipocambio = $rowActual ? (int)$rowActual['idtipocambio'] : 0;

                    if ($idtipocambio > 0) {

                        $ok = $stmtUpdateTipoCambio->execute([
                            ':tipocambio' => $tipocambio,
                            ':idtipocambio' => $idtipocambio
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo actualizar el tipo de cambio actual';
                            $continuar = false;
                            break;
                        }

                    } else {

                        $ok = $stmtCerrarVigente->execute([
                            ':fecha' => $fecha,
                            ':iddivisaorigen' => $iddivisaorigen,
                            ':iddivisadestino' => $iddivisadestino,
                            ':idempresa' => $idempresa
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo cerrar el tipo de cambio anterior';
                            $continuar = false;
                            break;
                        }

                        $ok = $stmtInsertAbierto->execute([
                            ':idempresa' => $idempresa,
                            ':iddivisaorigen' => $iddivisaorigen,
                            ':iddivisadestino' => $iddivisadestino,
                            ':tipocambio' => $tipocambio,
                            ':fechainicio' => $fecha
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo registrar el nuevo tipo de cambio';
                            $continuar = false;
                            break;
                        }
                    }

                /*
                |--------------------------------------------------------------------------
                | Caso 2: fecha histórica o diferente a hoy
                |--------------------------------------------------------------------------
                */
                } else {

                    /*
                    Buscar si ya existe tipo de cambio exacto para ese día.
                    */
                    $stmtBuscarDiaExacto->execute([
                        ':iddivisaorigen' => $iddivisaorigen,
                        ':iddivisadestino' => $iddivisadestino,
                        ':idempresa' => $idempresa,
                        ':fecha' => $fecha,
                        ':fecha_fin' => $fecha
                    ]);

                    $rowDiaExacto = $stmtBuscarDiaExacto->fetch(PDO::FETCH_ASSOC);
                    $idtipocambio = $rowDiaExacto ? (int)$rowDiaExacto['idtipocambio'] : 0;

                    if ($idtipocambio > 0) {

                        $ok = $stmtUpdateTipoCambio->execute([
                            ':tipocambio' => $tipocambio,
                            ':idtipocambio' => $idtipocambio
                        ]);

                        if (!$ok) {
                            $mensaje = 'No se pudo actualizar el tipo de cambio del día';
                            $continuar = false;
                            break;
                        }

                    } else {

                        /*
                        Buscar registro que inicia en esa fecha.
                        */
                        $stmtBuscarInicioFecha->execute([
                            ':iddivisaorigen' => $iddivisaorigen,
                            ':iddivisadestino' => $iddivisadestino,
                            ':idempresa' => $idempresa,
                            ':fecha' => $fecha
                        ]);

                        $rowInicioFecha = $stmtBuscarInicioFecha->fetch(PDO::FETCH_ASSOC);
                        $idtipocambioInicio = $rowInicioFecha ? (int)$rowInicioFecha['idtipocambio'] : 0;

                        if ($idtipocambioInicio > 0) {

                            /*
                            Se mueve el inicio un día adelante y se crea el registro exacto del día.
                            */
                            $ok = $stmtMoverInicioPorId->execute([
                                ':idtipocambio' => $idtipocambioInicio
                            ]);

                            if (!$ok) {
                                $mensaje = 'No se pudo ajustar el inicio del tipo de cambio existente';
                                $continuar = false;
                                break;
                            }

                            $ok = $stmtInsertDia->execute([
                                ':idempresa' => $idempresa,
                                ':iddivisaorigen' => $iddivisaorigen,
                                ':iddivisadestino' => $iddivisadestino,
                                ':tipocambio' => $tipocambio,
                                ':fechainicio' => $fecha,
                                ':fechafin' => $fecha
                            ]);

                            if (!$ok) {
                                $mensaje = 'No se pudo registrar el tipo de cambio del día';
                                $continuar = false;
                                break;
                            }

                        } else {

                            /*
                            Buscar rango que contenga la fecha.
                            */
                            $stmtBuscarRango->execute([
                                ':iddivisaorigen' => $iddivisaorigen,
                                ':iddivisadestino' => $iddivisadestino,
                                ':idempresa' => $idempresa,
                                ':fecha' => $fecha,
                                ':fecha_fin' => $fecha
                            ]);

                            $rowRango = $stmtBuscarRango->fetch(PDO::FETCH_ASSOC);

                            $idtipocambioRango = $rowRango ? (int)$rowRango['idtipocambio'] : 0;
                            $fechafinRango = $rowRango['fechafin'] ?? null;

                            if ($idtipocambioRango > 0) {

                                if ($fechafinRango === null || $fechafinRango === '') {

                                    /*
                                    Si el rango era abierto, se cierra el día anterior
                                    y se crea nuevo rango abierto desde la fecha.
                                    */
                                    $ok = $stmtCerrarPorId->execute([
                                        ':fecha' => $fecha,
                                        ':idtipocambio' => $idtipocambioRango
                                    ]);

                                    if (!$ok) {
                                        $mensaje = 'No se pudo cerrar el rango abierto anterior';
                                        $continuar = false;
                                        break;
                                    }

                                    $ok = $stmtInsertAbierto->execute([
                                        ':idempresa' => $idempresa,
                                        ':iddivisaorigen' => $iddivisaorigen,
                                        ':iddivisadestino' => $iddivisadestino,
                                        ':tipocambio' => $tipocambio,
                                        ':fechainicio' => $fecha
                                    ]);

                                    if (!$ok) {
                                        $mensaje = 'No se pudo registrar el nuevo rango abierto';
                                        $continuar = false;
                                        break;
                                    }

                                } else {

                                    /*
                                    Si la fecha cae dentro de un rango cerrado,
                                    se divide el rango en:
                                    - antes de la fecha
                                    - día exacto
                                    - después de la fecha, si corresponde
                                    */
                                    if ($fechafinRango !== $fecha) {

                                        $ok = $stmtDuplicarPosterior->execute([
                                            ':fecha' => $fecha,
                                            ':idtipocambio' => $idtipocambioRango
                                        ]);

                                        if (!$ok) {
                                            $mensaje = 'No se pudo crear el rango posterior';
                                            $continuar = false;
                                            break;
                                        }
                                    }

                                    $ok = $stmtCerrarPorId->execute([
                                        ':fecha' => $fecha,
                                        ':idtipocambio' => $idtipocambioRango
                                    ]);

                                    if (!$ok) {
                                        $mensaje = 'No se pudo cerrar el rango anterior';
                                        $continuar = false;
                                        break;
                                    }

                                    $ok = $stmtInsertDia->execute([
                                        ':idempresa' => $idempresa,
                                        ':iddivisaorigen' => $iddivisaorigen,
                                        ':iddivisadestino' => $iddivisadestino,
                                        ':tipocambio' => $tipocambio,
                                        ':fechainicio' => $fecha,
                                        ':fechafin' => $fecha
                                    ]);

                                    if (!$ok) {
                                        $mensaje = 'No se pudo registrar el tipo de cambio del día';
                                        $continuar = false;
                                        break;
                                    }
                                }

                            } else {

                                /*
                                Si no existe ningún rango, se crea uno abierto desde la fecha.
                                */
                                $ok = $stmtInsertAbierto->execute([
                                    ':idempresa' => $idempresa,
                                    ':iddivisaorigen' => $iddivisaorigen,
                                    ':iddivisadestino' => $iddivisadestino,
                                    ':tipocambio' => $tipocambio,
                                    ':fechainicio' => $fecha
                                ]);

                                if (!$ok) {
                                    $mensaje = 'No se pudo registrar el tipo de cambio';
                                    $continuar = false;
                                    break;
                                }
                            }
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

            if ($conexion->inTransaction()) {
                $conexion->commit();
            }

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
        'mensaje' => $mensaje
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->get('/tiposevento', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposevento=[];
    $result = $conexion->query("SELECT idtipoevento, tipoevento from t_tipoevento ORDER BY tipoevento;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposevento[]=array(
            'idtipoevento'=>(int)$row['idtipoevento'],
            'tipoevento'=>$row['tipoevento']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposevento' => $tiposevento
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/eventodescripcion', function(Request $request, Response $response, array $args) use ($conexion) {
    $eventodescripcion=[];
    $result = $conexion->query("SELECT ideventodescripcion, eventodescripcion from t_eventodescripcion ORDER BY eventodescripcion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $eventodescripcion[]=array(
            'ideventodescripcion'=>(int)$row['ideventodescripcion'],
            'eventodescripcion'=>$row['eventodescripcion']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'eventodescripcion' => $eventodescripcion
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/nombrefactura/{idtipodocumento}/{nit}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipodocumento = $args['idtipodocumento'];
    $nit = $args['nit'];
    $nombre='';
    $result = $conexion->query("SELECT DISTINCT nombre, idtipodocumento, nit from t_factura WHERE nit='$nit' AND IFNULL(idtipodocumento,5)=$idtipodocumento ORDER BY idfactura DESC LIMIT 0,1;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idtipodocumento=$row['idtipodocumento'];
        $nit=$row["nit"];
        $nombre=$row["nombre"];
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'nit' => $nit,
        'nombre' => $nombre,
        'idtipodocumento'=> $idtipodocumento
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/correosfactura/{idtipodocumento}/{numero}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idtipodocumento = $args['idtipodocumento'];
    $numero = $args['numero'];
    $correos=[];
    $result = $conexion->query("select idcorreonit, correo FROM t_correonit WHERE numero='$numero' AND idtipodocumento=$idtipodocumento;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $correos[]=array(
            'idcorreonit'=>$row['idcorreonit'],
            'correo'=>$row['correo'],
            'error'=>false
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'numero' => $numero,
        'idtipodocumento'=> $idtipodocumento,
        'correos' => $correos
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/cuentas', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $cuentas=[];
    $result = $conexion->query("SELECT * FROM t_cuenta WHERE idempresa=$idempresa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $cuentas[]=array(
            'idcuenta'=>(int)$row['idcuenta'],
            'banco'=>$row['banco'],
            'cuenta'=>$row['cuenta'],
            'moneda'=>$row['moneda']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'cuentas' => $cuentas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/cuentas', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $banco=$params['banco'];
    $cuenta=$params['cuenta'];
    $moneda=$params['moneda'];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="INSERT INTO t_cuenta (idempresa,    banco,      cuenta,     moneda)
                          VALUES ($idempresa,   '$banco',   '$cuenta',  '$moneda');";
    $result = $conexion->exec($query);

    if($result===false){

    }else{
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información correctamente';
    }
    
    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
            
    );
    
    $response->getBody()->write(json_encode($resultado));
    
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->put('/cuentas/{idcuenta}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcuenta = $args['idcuenta'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $banco=$params['banco'];
    $cuenta=$params['cuenta'];
    $moneda=$params['moneda'];
    
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    

    $query="UPDATE t_cuenta SET 
        banco='$banco',
        cuenta='$cuenta',
        moneda='$moneda'
        WHERE 
        idcuenta=$idcuenta;";

    $result = $conexion->exec($query);

    if($result===false){

    }else{
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información correctamente';
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

$app->get('/tiposplanilla', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposplanilla=[];
    $result = $conexion->query("SELECT idtipoplanilla, tipoplanilla, orden from t_tipoplanilla;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposplanilla[]=array(
            'idtipoplanilla'=>(int)$row['idtipoplanilla'],
            'tipoplanilla'=>$row['tipoplanilla'],
            'orden'=>(int)$row['orden']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposplanilla' => $tiposplanilla
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposdescarga', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposdescarga=[];
    $result = $conexion->query("select idtipodescarga, tipodescarga FROM t_tipodescarga;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposdescarga[]=array(
            'idtipodescarga'=>(int)$row['idtipodescarga'],
            'tipodescarga'=>$row['tipodescarga']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposdescarga' => $tiposdescarga
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposcontenedor', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposcontenedor=[];
    $result = $conexion->query("SELECT idtipocontenedor, tipocontenedor from t_tipocontenedor;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposcontenedor[]=array(
            'idtipocontenedor'=>(int)$row['idtipocontenedor'],
            'tipocontenedor'=>$row['tipocontenedor']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposcontenedor' => $tiposcontenedor
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposproducto', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposproducto=[];
    $result = $conexion->query("select idtipoproducto, tipoproducto, IFNULL(es_vehiculo,0) as es_vehiculo FROM t_tipoproducto;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposproducto[]=array(
            'idtipoproducto'=>(int)$row['idtipoproducto'],
            'tipoproducto'=>$row['tipoproducto'],
            'es_vehiculo'=> boolval($row['es_vehiculo'])
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposproducto' => $tiposproducto
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposingreso', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposingreso=[];
    $result = $conexion->query("SELECT idtipoingreso, tipoingreso from t_tipoingreso;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposingreso[]=array(
            'idtipoingreso'=>(int)$row['idtipoingreso'],
            'tipoingreso'=>$row['tipoingreso']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposingreso' => $tiposingreso
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/embalajes', function(Request $request, Response $response, array $args) use ($conexion) {
    $embalajes=[];
    $result = $conexion->query("SELECT idembalaje, codigoembalaje, embalaje, IFNULL(divisible,0) as divisible from t_embalaje;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $embalajes[]=array(
            'idembalaje'=>(int)$row['idembalaje'],
            'codigoembalaje'=>$row['codigoembalaje'],
            'embalaje'=>$row['embalaje'],
            'divisible'=>boolval($row['divisible'])
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'embalajes' => $embalajes
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/no_confs', function(Request $request, Response $response, array $args) use ($conexion) {
    $no_confs=[];
    $result = $conexion->query("select idno_conf, no_conf FROM t_no_conf ORDER BY no_conf;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $no_confs[]=array(
            'idno_conf'=>(int)$row['idno_conf'],
            'no_conf'=>$row['no_conf']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'no_confs' => $no_confs
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/clasificaciones', function(Request $request, Response $response, array $args) use ($conexion) {
    $clasificaciones=[];
    $result = $conexion->query("select idclasificacion, clasificacion FROM t_clasificacion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $clasificaciones[]=array(
            'idclasificacion'=>(int)$row['idclasificacion'],
            'clasificacion'=>$row['clasificacion']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'clasificaciones' => $clasificaciones
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/mermas', function(Request $request, Response $response, array $args) use ($conexion) {
    $mermas=[];
    $result = $conexion->query("SELECT idmerma, merma FROM t_merma;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $mermas[]=array(
            'idmerma'=>(int)$row['idmerma'],
            'merma'=>$row['merma']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'mermas' => $mermas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);


$app->get('/tipostransferencia', function(Request $request, Response $response, array $args) use ($conexion) {
    $tipostransferencia=[];
    $result = $conexion->query("SELECT idtipotransferencia, tipotransferencia from t_tipotransferencia;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tipostransferencia[]=array(
            'idtipotransferencia'=>(int)$row['idtipotransferencia'],
            'tipotransferencia'=>$row['tipotransferencia']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tipostransferencia' => $tipostransferencia
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposliquidacion', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposliquidacion=[];
    $result = $conexion->query("SELECT idtipoliquidacion, tipoliquidacion from t_tipoliquidacion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposliquidacion[]=array(
            'idtipoliquidacion'=>(int)$row['idtipoliquidacion'],
            'tipoliquidacion'=>$row['tipoliquidacion']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposliquidacion' => $tiposliquidacion
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/productos_cliente', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $productos_cliente=[];
    
    $conexion->query("DROP TEMPORARY TABLE IF EXISTS tmp_preciotimbradoproducto;");
    $conexion->query("CREATE TEMPORARY TABLE tmp_preciotimbradoproducto (idbaseproductos INT, preciotimbrado TEXT);");
    $conexion->query("INSERT INTO tmp_preciotimbradoproducto (idbaseproductos, preciotimbrado)
        SELECT 
        t_preciotimbradoproducto.idbaseproductos,
        GROUP_CONCAT(CONCAT('{\"idpreciotimbradoproducto\": ',t_preciotimbradoproducto.idpreciotimbradoproducto,', \"idtimbradoturno\": ',t_preciotimbradoproducto.idtimbradoturno,', \"precio\": ',t_preciotimbradoproducto.precio,'}') SEPARATOR ',') as preciotimbrado
        from 
        t_preciotimbradoproducto
        LEFT JOIN t_baseproductos ON t_preciotimbradoproducto.idbaseproductos=t_baseproductos.idbaseproductos
        LEFT JOIN t_cliente ON t_baseproductos.idcliente=t_cliente.idcliente
        WHERE
        t_cliente.idempresa=$idempresa
        GROUP BY
        t_preciotimbradoproducto.idbaseproductos;");
    $conexion->query("ALTER TABLE tmp_preciotimbradoproducto ADD INDEX idbaseproductos (idbaseproductos);");
    
    
    $result = $conexion->query("SELECT
        t_baseproductos.idbaseproductos,
        t_baseproductos.idcliente,
        t_cliente.cliente,
        t_baseproductos.rubro,
        t_baseproductos.codigo,
        t_baseproductos.serie,
        t_baseproductos.descripcion,
        t_baseproductos.categoria,
        t_baseproductos.idembalaje,
        t_embalaje.codigoembalaje,
        t_baseproductos.umcompra,
        t_baseproductos.umalterna,
        IFNULL(t_baseproductos.alto,0) as alto,
        IFNULL(t_baseproductos.ancho,0) as ancho,
        IFNULL(t_baseproductos.largo,0) as largo,
        IFNULL(t_baseproductos.alto,0)*IFNULL(t_baseproductos.ancho,0)*IFNULL(t_baseproductos.largo,0) as volumen,
        t_baseproductos.centro_distribucion,
        t_baseproductos.color,
        IFNULL(t_baseproductos.idembalaje_salida,t_baseproductos.idembalaje) as idembalaje_salida,
        IFNULL(t_embalaje_salida.codigoembalaje,t_embalaje.codigoembalaje) as codigoembalaje_salida,
        IFNULL(t_baseproductos.factor_conversion,1) as factor_conversion,
        t_baseproductos.meta_timbrado,
        CONCAT('[',IFNULL(tmp_preciotimbradoproducto.preciotimbrado,''),']') as preciotimbrado
        FROM
        t_baseproductos
        LEFT JOIN t_cliente ON t_baseproductos.idcliente=t_cliente.idcliente
        LEFT JOIN t_embalaje ON t_baseproductos.idembalaje=t_embalaje.idembalaje
        LEFT JOIN t_embalaje as t_embalaje_salida ON t_baseproductos.idembalaje_salida=t_embalaje_salida.idembalaje
        LEFT JOIN tmp_preciotimbradoproducto ON t_baseproductos.idbaseproductos=tmp_preciotimbradoproducto.idbaseproductos
        WHERE
        t_cliente.idempresa=$idempresa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        
        $productos_cliente[]=array(
            'idbaseproductos'=>(int)$row['idbaseproductos'],
            'idcliente'=>$row['idcliente'],
            'cliente'=>$row['cliente'],
            'rubro'=>$row['rubro'],
            'codigo'=>$row['codigo'],
            'serie'=>$row['serie'],
            'descripcion'=>$row['descripcion'],
            'categoria'=>$row['categoria'],
            'idembalaje'=>$row['idembalaje'],
            'codigoembalaje'=>$row['codigoembalaje'],
            'umcompra'=>$row['umcompra'],
            'umalterna'=>$row['umalterna'],
            'alto'=>(float)$row['alto'],
            'ancho'=>(float)$row['ancho'],
            'largo'=>(float)$row['largo'],
            'volumen'=>(float)$row['volumen'],
            'centro_distribucion'=>$row['centro_distribucion'],
            'color'=>$row['color'],
            'idembalaje_salida'=>$row['idembalaje_salida'],
            'codigoembalaje_salida'=>$row['codigoembalaje_salida'],
            'factor_conversion'=>$row['factor_conversion'],
            'meta_timbrado'=>$row['meta_timbrado'],
            'preciotimbrado'=> json_decode($row["preciotimbrado"], true)
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'productos_cliente' => $productos_cliente
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/productos_cliente', function(Request $request, Response $response, array $args) use ($conexion) {

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
            $idcliente = $params['idcliente'] ?? null;
            $rubro = $params['rubro'] ?? '';
            $codigoproducto = $params['codigo'] ?? '';
            $serie = $params['serie'] ?? '';
            $descripcion = $params['descripcion'] ?? '';
            $categoria = $params['categoria'] ?? '';
            $idembalaje = $params['idembalaje'] ?? null;
            $umcompra = $params['umcompra'] ?? '';
            $umalterna = $params['umalterna'] ?? '';
            $alto = $params['alto'] ?? 0;
            $ancho = $params['ancho'] ?? 0;
            $largo = $params['largo'] ?? 0;
            $centro_distribucion = $params['centro_distribucion'] ?? '';
            $color = $params['color'] ?? null;
            $idembalaje_salida = $params['idembalaje_salida'] ?? null;
            $factor_conversion = $params['factor_conversion'] ?? 0;
            $meta_timbrado = $params['meta_timbrado'] ?? 0;
            $preciotimbrado = $params['preciotimbrado'] ?? [];
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idcliente)) {
            $mensaje = 'No se recibió el cliente';
            $continuar = false;
        }

        if ($continuar && trim($codigoproducto) === '') {
            $mensaje = 'No se recibió el código del producto';
            $continuar = false;
        }

        if ($continuar && trim($descripcion) === '') {
            $mensaje = 'No se recibió la descripción del producto';
            $continuar = false;
        }

        if ($continuar && empty($idembalaje)) {
            $mensaje = 'No se recibió el embalaje';
            $continuar = false;
        }

        if ($continuar && !is_array($preciotimbrado)) {
            $mensaje = 'Los precios de timbrado recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar producto
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryProducto = "
                INSERT INTO t_baseproductos (
                    idcliente,
                    rubro,
                    codigo,
                    serie,
                    descripcion,
                    categoria,
                    idembalaje,
                    umcompra,
                    umalterna,
                    alto,
                    ancho,
                    largo,
                    centro_distribucion,
                    color,
                    idembalaje_salida,
                    factor_conversion,
                    meta_timbrado
                ) VALUES (
                    :idcliente,
                    :rubro,
                    :codigo,
                    :serie,
                    :descripcion,
                    :categoria,
                    :idembalaje,
                    :umcompra,
                    :umalterna,
                    :alto,
                    :ancho,
                    :largo,
                    :centro_distribucion,
                    :color,
                    :idembalaje_salida,
                    :factor_conversion,
                    :meta_timbrado
                )
            ";

            $stmtProducto = $conexion->prepare($queryProducto);

            $resultProducto = $stmtProducto->execute([
                ':idcliente' => $idcliente,
                ':rubro' => $rubro,
                ':codigo' => $codigoproducto,
                ':serie' => $serie,
                ':descripcion' => $descripcion,
                ':categoria' => $categoria,
                ':idembalaje' => $idembalaje,
                ':umcompra' => $umcompra,
                ':umalterna' => $umalterna,
                ':alto' => $alto,
                ':ancho' => $ancho,
                ':largo' => $largo,
                ':centro_distribucion' => $centro_distribucion,
                ':color' => $color,
                ':idembalaje_salida' => $idembalaje_salida,
                ':factor_conversion' => $factor_conversion,
                ':meta_timbrado' => $meta_timbrado
            ]);

            if (!$resultProducto) {
                $mensaje = 'No se pudo registrar el producto';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            } else {
                $idbaseproductosNuevo = (int)$conexion->lastInsertId();

                if ($idbaseproductosNuevo <= 0) {
                    $mensaje = 'No se pudo obtener el producto generado';
                    $continuar = false;

                    if ($conexion->inTransaction()) {
                        $conexion->rollBack();
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar precios de timbrado
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryPrecio = "
                INSERT INTO t_preciotimbradoproducto (
                    idbaseproductos,
                    idtimbradoturno,
                    precio
                ) VALUES (
                    :idbaseproductos,
                    :idtimbradoturno,
                    :precio
                )
            ";

            $stmtPrecio = $conexion->prepare($queryPrecio);

            foreach ($preciotimbrado as $precioItem) {

                $idtimbradoturno = $precioItem["idtimbradoturno"] ?? null;
                $precio = $precioItem["precio"] ?? 0;

                if (empty($idtimbradoturno)) {
                    $mensaje = 'Un precio de timbrado no tiene turno';
                    $continuar = false;
                    break;
                }

                $resultPrecio = $stmtPrecio->execute([
                    ':idbaseproductos' => $idbaseproductosNuevo,
                    ':idtimbradoturno' => $idtimbradoturno,
                    ':precio' => $precio
                ]);

                if (!$resultPrecio) {
                    $mensaje = 'No se pudo registrar un precio de timbrado';
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
        'mensaje' => $mensaje
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyRole(9, 2))->add($verifyToken);

$app->put('/productos_cliente/{idbaseproductos}', function(Request $request, Response $response, array $args) use ($conexion) {

    $idbaseproductos = $args['idbaseproductos'] ?? null;

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
            $idcliente = $params['idcliente'] ?? null;
            $rubro = $params['rubro'] ?? '';
            $codigoproducto = $params['codigo'] ?? '';
            $serie = $params['serie'] ?? '';
            $descripcion = $params['descripcion'] ?? '';
            $categoria = $params['categoria'] ?? '';
            $idembalaje = $params['idembalaje'] ?? null;
            $umcompra = $params['umcompra'] ?? '';
            $umalterna = $params['umalterna'] ?? '';
            $alto = $params['alto'] ?? 0;
            $ancho = $params['ancho'] ?? 0;
            $largo = $params['largo'] ?? 0;
            $centro_distribucion = $params['centro_distribucion'] ?? '';
            $color = $params['color'] ?? null;
            $idembalaje_salida = $params['idembalaje_salida'] ?? null;
            $factor_conversion = $params['factor_conversion'] ?? 0;
            $meta_timbrado = $params['meta_timbrado'] ?? 0;
            $preciotimbrado = $params['preciotimbrado'] ?? [];
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idbaseproductos)) {
            $mensaje = 'No se recibió el producto';
            $continuar = false;
        }

        if ($continuar && empty($idcliente)) {
            $mensaje = 'No se recibió el cliente';
            $continuar = false;
        }

        if ($continuar && trim($codigoproducto) === '') {
            $mensaje = 'No se recibió el código del producto';
            $continuar = false;
        }

        if ($continuar && trim($descripcion) === '') {
            $mensaje = 'No se recibió la descripción del producto';
            $continuar = false;
        }

        if ($continuar && empty($idembalaje)) {
            $mensaje = 'No se recibió el embalaje';
            $continuar = false;
        }

        if ($continuar && !is_array($preciotimbrado)) {
            $mensaje = 'Los precios de timbrado recibidos no tienen un formato válido';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar producto
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryProducto = "
                UPDATE t_baseproductos
                SET
                    idcliente = :idcliente,
                    rubro = :rubro,
                    codigo = :codigo,
                    serie = :serie,
                    descripcion = :descripcion,
                    categoria = :categoria,
                    idembalaje = :idembalaje,
                    umcompra = :umcompra,
                    umalterna = :umalterna,
                    alto = :alto,
                    ancho = :ancho,
                    largo = :largo,
                    centro_distribucion = :centro_distribucion,
                    color = :color,
                    idembalaje_salida = :idembalaje_salida,
                    factor_conversion = :factor_conversion,
                    meta_timbrado = :meta_timbrado
                WHERE idbaseproductos = :idbaseproductos
            ";

            $stmtProducto = $conexion->prepare($queryProducto);

            $resultProducto = $stmtProducto->execute([
                ':idcliente' => $idcliente,
                ':rubro' => $rubro,
                ':codigo' => $codigoproducto,
                ':serie' => $serie,
                ':descripcion' => $descripcion,
                ':categoria' => $categoria,
                ':idembalaje' => $idembalaje,
                ':umcompra' => $umcompra,
                ':umalterna' => $umalterna,
                ':alto' => $alto,
                ':ancho' => $ancho,
                ':largo' => $largo,
                ':centro_distribucion' => $centro_distribucion,
                ':color' => $color,
                ':idembalaje_salida' => $idembalaje_salida,
                ':factor_conversion' => $factor_conversion,
                ':meta_timbrado' => $meta_timbrado,
                ':idbaseproductos' => $idbaseproductos
            ]);

            if (!$resultProducto) {
                $mensaje = 'No se pudo actualizar el producto';
                $continuar = false;

                if ($conexion->inTransaction()) {
                    $conexion->rollBack();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Precios de timbrado: actualizar / insertar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $idsPreciosRecibidos = [];

            $queryUpdatePrecio = "
                UPDATE t_preciotimbradoproducto
                SET
                    idtimbradoturno = :idtimbradoturno,
                    precio = :precio
                WHERE idpreciotimbradoproducto = :idpreciotimbradoproducto
                  AND idbaseproductos = :idbaseproductos
            ";

            $stmtUpdatePrecio = $conexion->prepare($queryUpdatePrecio);

            $queryInsertPrecio = "
                INSERT INTO t_preciotimbradoproducto (
                    idbaseproductos,
                    idtimbradoturno,
                    precio
                ) VALUES (
                    :idbaseproductos,
                    :idtimbradoturno,
                    :precio
                )
            ";

            $stmtInsertPrecio = $conexion->prepare($queryInsertPrecio);

            foreach ($preciotimbrado as $precioItem) {

                $idpreciotimbradoproducto = $precioItem["idpreciotimbradoproducto"] ?? 0;
                $idtimbradoturno = $precioItem["idtimbradoturno"] ?? null;
                $precio = $precioItem["precio"] ?? 0;

                if (empty($idtimbradoturno)) {
                    $mensaje = 'Un precio de timbrado no tiene turno';
                    $continuar = false;
                    break;
                }

                if ((int)$idpreciotimbradoproducto > 0) {

                    $idsPreciosRecibidos[] = (int)$idpreciotimbradoproducto;

                    $resultPrecio = $stmtUpdatePrecio->execute([
                        ':idtimbradoturno' => $idtimbradoturno,
                        ':precio' => $precio,
                        ':idpreciotimbradoproducto' => $idpreciotimbradoproducto,
                        ':idbaseproductos' => $idbaseproductos
                    ]);

                } else {

                    $resultPrecio = $stmtInsertPrecio->execute([
                        ':idbaseproductos' => $idbaseproductos,
                        ':idtimbradoturno' => $idtimbradoturno,
                        ':precio' => $precio
                    ]);

                    /*
                    Importante:
                    Si se inserta un precio nuevo, agregamos su ID
                    para que no sea eliminado en el bloque siguiente.
                    */
                    if ($resultPrecio) {
                        $idsPreciosRecibidos[] = (int)$conexion->lastInsertId();
                    }
                }

                if (!$resultPrecio) {
                    $mensaje = 'No se pudo guardar un precio de timbrado';
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
        | Eliminar precios de timbrado que ya no llegaron
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryPreciosActuales = "
                SELECT idpreciotimbradoproducto
                FROM t_preciotimbradoproducto
                WHERE idbaseproductos = :idbaseproductos
            ";

            $stmtPreciosActuales = $conexion->prepare($queryPreciosActuales);

            $stmtPreciosActuales->execute([
                ':idbaseproductos' => $idbaseproductos
            ]);

            $queryDeletePrecio = "
                DELETE FROM t_preciotimbradoproducto
                WHERE idpreciotimbradoproducto = :idpreciotimbradoproducto
                  AND idbaseproductos = :idbaseproductos
            ";

            $stmtDeletePrecio = $conexion->prepare($queryDeletePrecio);

            while ($rowPrecio = $stmtPreciosActuales->fetch(PDO::FETCH_ASSOC)) {

                $idPrecioActual = (int)$rowPrecio['idpreciotimbradoproducto'];

                if (!in_array($idPrecioActual, $idsPreciosRecibidos)) {

                    $resultDeletePrecio = $stmtDeletePrecio->execute([
                        ':idpreciotimbradoproducto' => $idPrecioActual,
                        ':idbaseproductos' => $idbaseproductos
                    ]);

                    if (!$resultDeletePrecio) {
                        $mensaje = 'No se pudo eliminar un precio de timbrado';
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
        'mensaje' => $mensaje
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyRole(9, 2))->add($verifyToken);

$app->delete('/productos_cliente/{idbaseproductos}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idbaseproductos = $args['idbaseproductos'];
    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    
    $query="delete from t_baseproductos where idbaseproductos=$idbaseproductos;";
    
    
    $result = $conexion->exec($query);
    if($result){
        $codigo=200;
        $status='Exito';
        $mensaje='Se guardo la información Correctamente';
    }

    
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje
    );
    $response->getBody()->write(json_encode($resultado));
    

    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyRole(9, 2))->add($verifyToken);

$app->post('/productos_cliente/{idcliente}/cargamasiva', function(Request $request, Response $response, array $args) use ($conexion,$archivosmasivo) {
    $idcliente = $args['idcliente'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);
    
    $codigo=400;
    $status='Error';
    $mensaje='El Archivo no se subio, probablemente sea mayor a 10MB';
    $xls_data=[];
    $mensajes_error=[];
    $nuevosdatos=[];
    //$embalaje=[];
    
    if(isset($_FILES['uploads'])){
        $nombredoc=$_FILES['uploads']["name"][0];
        //$random = bin2hex(openssl_random_pseudo_bytes(5));
        //$nombredoc=$random."_".$nombredoc;
        
        $piramideUploader=new PiramideUploader();
        $upload =$piramideUploader->upload($nombredoc, 'uploads', folder_files.$idempresa.DIRECTORY_SEPARATOR.'almacen/productos_cliente/'.$idcliente, $archivosmasivo, true);
        
        $file=$piramideUploader->getInfoFile();
        $file_name=$file['complete_name'];
        
        if(isset($upload) && $upload['uploaded']==false){
            $mensaje=$upload['error'];
        }else{
            $inputFileName = folder_files.$idempresa.DIRECTORY_SEPARATOR.'almacen/productos_cliente/'.$idcliente.'/'.$file_name;
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow(); 
            $highestColumn = $sheet->getHighestColumn();
            $xls_data = $sheet->rangeToArray(
                'A2:' . $highestColumn . $highestRow,
                '',TRUE,TRUE
            );
            
            $embalajes=[];
            $resultembalaje = $conexion->query("select 
                idembalaje,
                codigoembalaje
                from 
                t_embalaje;");
            while ($rowembalaje =  $resultembalaje ->fetch(PDO::FETCH_ASSOC)){
                $embalajes[]=array(
                    'idembalaje'=>(int)$rowembalaje['idembalaje'],
                    'codigoembalaje'=>$rowembalaje['codigoembalaje']
                );
            }
            
            $baseproductos=[];
            $resultbaseproductos = $conexion->query("select 
                codigo
                from 
                t_baseproductos
                WHERE
                idcliente=$idcliente;");
            while ($rowbaseproductos =  $resultbaseproductos ->fetch(PDO::FETCH_ASSOC)){
                $baseproductos[]=array(
                    'codigo'=>$rowbaseproductos['codigo']
                    
                );
            }

            
            //$datosinsertar='';
            
            for($ex=0;$ex<count($xls_data);$ex++){
                
                if($xls_data[$ex][1]==''){
                    $mensajes_error[]=array(
                        'fila'=>$ex+2,
                        'mensaje'=>'El campo Código es Obligatorio'
                    );
                }else{
                    $key = array_search(ltrim($xls_data[$ex][1]), array_column($baseproductos, 'codigo'));
                    if(is_numeric($key)){
                        $mensajes_error[]=array(
                            'fila'=>$ex+2,
                            'mensaje'=>'El Código ya existe'
                        );
                    }
                }
                if($xls_data[$ex][4]==''){
                    $mensajes_error[]=array(
                        'fila'=>$ex+2,
                        'mensaje'=>'El campo U/M Inventario es Obligatorio'
                    );
                }else{
                    $key = array_search($xls_data[$ex][4], array_column($embalajes, 'codigoembalaje'));
                    if(!is_numeric($key)){
                        $mensajes_error[]=array(
                            'fila'=>$ex+2,
                            'mensaje'=>'El campo U/M Inventario no es válido'
                        );
                    }else{
                        $xls_data[$ex][4]=$embalajes[$key]["idembalaje"];
                    }
                }
                
                if($xls_data[$ex][13]<>'' || $xls_data[$ex][12]<>''){
                    $key = array_search($xls_data[$ex][13], array_column($embalajes, 'codigoembalaje'));
                    if(!is_numeric($key)){
                        $mensajes_error[]=array(
                            'fila'=>$ex+2,
                            'mensaje'=>'El campo U/M Salida no es válido'
                        );
                    }else{
                        $xls_data[$ex][13]=$embalajes[$key]["idembalaje"];
                    }
                    if(!is_numeric($xls_data[$ex][12])){
                        $mensajes_error[]=array(
                            'fila'=>$ex+2,
                            'mensaje'=>'El campo factor de Conversión debe ser numérico debe ser numérico'
                        );
                    }
                }
                
                if($xls_data[$ex][8]<>''){
                    if(!is_numeric($xls_data[$ex][8])){
                        $mensajes_error[]=array(
                            'fila'=>$ex+2,
                            'mensaje'=>'El campo Alto debe ser numérico'
                        );
                    }
                }
                if($xls_data[$ex][9]<>''){
                    if(!is_numeric($xls_data[$ex][9])){
                        $mensajes_error[]=array(
                            'fila'=>$ex+2,
                            'mensaje'=>'El campo Ancho debe ser numérico'
                        );
                    }
                }
                if($xls_data[$ex][10]<>''){
                    if(!is_numeric($xls_data[$ex][10])){
                        $mensajes_error[]=array(
                            'fila'=>$ex+2,
                            'mensaje'=>'El campo Largo debe ser numérico'
                        );
                    }
                }
                
                
            }
            
            if(count($mensajes_error)==0){
                
                $stmt = $conexion->prepare("INSERT INTO t_baseproductos (idcliente,rubro,codigo,serie,descripcion,categoria,idembalaje,umcompra,umalterna,alto,ancho,largo,centro_distribucion,idembalaje_salida,factor_conversion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                for($ex=0;$ex<count($xls_data);$ex++){
                    $stmt->execute([$idcliente, ltrim($xls_data[$ex][0]), ltrim($xls_data[$ex][1]), ltrim($xls_data[$ex][2]), ltrim($xls_data[$ex][3]), ltrim($xls_data[$ex][7]), $xls_data[$ex][4], ltrim($xls_data[$ex][5]), ltrim($xls_data[$ex][6]), $xls_data[$ex][8], $xls_data[$ex][9], $xls_data[$ex][10], ltrim($xls_data[$ex][11]), $xls_data[$ex][13], $xls_data[$ex][12] ]);
                }
                
                $codigo=200;
                $status='Exito';
                $mensaje='El Archivo se subio correctamente';
            }
            
            
                
        }

    }
    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'mensajes_error'=>$mensajes_error,
        'xls_data'=>$xls_data
    );
    
    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/referencia_salida/{idcliente}/{contrato_no}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $contrato_no = $args['contrato_no'];
    $referencia_salida=array();
    $result = $conexion->query("SELECT
        idreferencia_salida,
        contrato_no,
        proyecto_no,
        solicitado_por,
        autorizado_por,
        rubro_producto,
        ciudad,
        direccion_entrega,
        transporte,
        placa,
        hora_inicio_a,
        hora_fin_a,
        hora_inicio_b,
        hora_fin_b,
        empresa_recibido,
        tipo_pedido
        FROM
        t_referencia_salida
        WHERE
        md5(idcliente)='$idcliente'
        AND contrato_no='$contrato_no';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $referencia_salida=array(
            'idreferencia_salida'=>$row['idreferencia_salida'],
            'contrato_no'=>$row['contrato_no'],
            'proyecto_no'=>$row['proyecto_no'],
            'solicitado_por'=>$row['solicitado_por'],
            'autorizado_por'=>$row['autorizado_por'],
            'rubro_producto'=>$row['rubro_producto'],
            'ciudad'=>$row['ciudad'],
            'direccion_entrega'=>$row['direccion_entrega'],
            'transporte'=>$row['transporte'],
            'placa'=>$row['placa'],
            'hora_inicio_a'=>$row['hora_inicio_a'],
            'hora_fin_a'=>$row['hora_fin_a'],
            'hora_inicio_b'=>$row['hora_inicio_b'],
            'hora_fin_b'=>$row['hora_fin_b'],
            'empresa_recibido'=>$row['empresa_recibido'],
            'tipo_pedido'=>$row['tipo_pedido']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'referencia_salida' => $referencia_salida
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);



$app->post('/cargardocumento', function(Request $request, Response $response, array $args) use ($conexion,$archivospermitidos) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='El Archivo no se subio, probablemente sea mayor a 10MB';
    $file_name='';
    
    $ubicacion=$_POST['ubicacion'];
    
    $creacion=new Carpetas();
    $respuesta=$creacion->procesarCarpeta($idempresa);
    
    if(isset($_FILES['uploads'])){
        $nombredoc=$_FILES['uploads']["name"][0];
        //$random = bin2hex(openssl_random_pseudo_bytes(5));
        //$nombredoc=$random."_".$nombredoc;
        
        $piramideUploader=new PiramideUploader();
        $upload =$piramideUploader->upload($nombredoc, 'uploads', folder_files.$idempresa.DIRECTORY_SEPARATOR.$ubicacion, $archivospermitidos, true);
        
        $file=$piramideUploader->getInfoFile();
        $file_name=$file['complete_name'];
        
        if(isset($upload) && $upload['uploaded']==false){
            $mensaje=$upload['error'];
        }else{
            $codigo=200;
            $status='Exito';
            $mensaje='El Archivo se subio correctamente';
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

$app->post('/downloaddocumento', function(Request $request, Response $response, array $args) {
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
    $ubicacion=$_POST['ubicacion'];
    $filename=$_POST['filename'];
    $file=folder_files.$idempresa.DIRECTORY_SEPARATOR.$ubicacion.'/'.$filename;
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

$app->post('/eliminardocumento', function(Request $request, Response $response, array $args) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $codigo=400;
    $status='Error';
    $mensaje='El archivo no existe';
    
    $ubicacion=$_POST['ubicacion'];
    $filename=$_POST['filename'];
    
    $file=folder_files.$idempresa.DIRECTORY_SEPARATOR.$ubicacion.'/'.$filename;
    if (file_exists($file)) {
        if(unlink($file)){
            $codigo=200;
            $status=400;
            $mensaje="Se elimino el archivo ".$filename." correctamente";
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

$app->get('/columnas_mover_dividir', function(Request $request, Response $response, array $args) use ($conexion) {
    $columnas_mover_dividir=[];
    $result = $conexion->query("select
        field,
        header,
        type
        FROM
        t_columnas_moverdividir
        ORDER BY
        idcolumnas_moverdividir;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $columnas_mover_dividir[]=array(
            'field'=>$row['field'],
            'header'=>$row['header'],
            'type'=>$row['type']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'columnas_mover_dividir' => $columnas_mover_dividir
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/columnas_pedido', function(Request $request, Response $response, array $args) use ($conexion) {
    $columnas_pedido=[];
    $result = $conexion->query("select
        field,
        header,
        type
        FROM
        t_columnas_pedido
        ORDER BY
        idcolumnas_pedido;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $columnas_pedido[]=array(
            'field'=>$row['field'],
            'header'=>$row['header'],
            'type'=>$row['type']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'columnas_pedido' => $columnas_pedido
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposdocumento', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposdocumento=[];
    $result = $conexion->query("SELECT
        idtipodocumento,
        tipodocumento
        FROM
        t_tipodocumento;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposdocumento[]=array(
            'idtipodocumento'=>(int)$row['idtipodocumento'],
            'tipodocumento'=>$row['tipodocumento']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposdocumento' => $tiposdocumento
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/centros_rubro/{idcliente}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $centros_rubro=[];
    $result = $conexion->query("select idcentro_rubro, centro_distribucion, rubro FROM t_centro_rubro WHERE md5(idcliente)='$idcliente';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $centros_rubro[]=array(
            'idcentro_rubro'=>(int)$row['idcentro_rubro'],
            'centro_distribucion'=>$row['centro_distribucion'],
            'rubro'=>$row['rubro']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'centros_rubro' => $centros_rubro
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tipospedido', function(Request $request, Response $response, array $args) use ($conexion) {
    $tipospedido=array();
    $result = $conexion->query("select idtipopedido, tipopedido FROM t_tipopedido;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        array_push($tipospedido, $row['tipopedido']);
        /*
        $tipospedido[]=array(
            'idtipopedido'=>(int)$row['idtipopedido'],
            'tipopedido'=>$row['tipopedido']
        );
         * 
         */
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tipospedido' => $tipospedido
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/motivosanulacion', function(Request $request, Response $response, array $args) use ($conexion) {
    $motivosanulacion=[];
    $result = $conexion->query("SELECT idmotivoanulacion, motivoanulacion FROM t_motivoanulacion;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $motivosanulacion[]=array(
            'idmotivoanulacion'=>(int)$row['idmotivoanulacion'],
            'motivoanulacion'=>$row['motivoanulacion']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'motivosanulacion' => $motivosanulacion
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/status', function(Request $request, Response $response, array $args) use ($conexion) {
    $status=[];
    $result = $conexion->query("SELECT idstatus, status FROM t_status;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $status[]=array(
            'idstatus'=>(int)$row['idstatus'],
            'status'=>$row['status']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'status' => $status
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/accesorios_vehiculos/{idcliente}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $accesorios_vehiculos=[];
    $result = $conexion->query("SELECT idaccesorios_vehiculos, accesorios_vehiculos, requiere_cantidad, requiere_texto FROM t_accesorios_vehiculos WHERE idcliente=$idcliente AND deleted_at IS NULL;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $accesorios_vehiculos[]=array(
            'idaccesorios_vehiculos'=>(int)$row['idaccesorios_vehiculos'],
            'accesorios_vehiculos'=>$row['accesorios_vehiculos'],
            'requiere_cantidad'=> boolval($row['requiere_cantidad']),
            'requiere_texto'=> boolval($row['requiere_texto'])
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'accesorios_vehiculos' => $accesorios_vehiculos
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/accesorios_vehiculos_salidas_borrar', function(Request $request, Response $response, array $args) use ($conexion) {
    $accesorios_vehiculos_salidas=[];
    $result = $conexion->query("SELECT idaccesorios_vehiculos_salidas, accesorios_vehiculos_salidas, requiere_cantidad, requiere_texto FROM t_accesorios_vehiculos_salidas;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $accesorios_vehiculos_salidas[]=array(
            'idaccesorios_vehiculos_salidas'=>(int)$row['idaccesorios_vehiculos_salidas'],
            'accesorios_vehiculos_salidas'=>$row['accesorios_vehiculos_salidas'],
            'requiere_cantidad'=> boolval($row['requiere_cantidad']),
            'requiere_texto'=> boolval($row['requiere_texto']),
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'accesorios_vehiculos_salidas' => $accesorios_vehiculos_salidas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/docs_errada', function(Request $request, Response $response, array $args) use ($conexion) {
    $docs_errada=[];
    $result = $conexion->query("SELECT iddoc_errada, doc_errada FROM t_doc_errada;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $docs_errada[]=array(
            'iddoc_errada'=>(int)$row['iddoc_errada'],
            'doc_errada'=>$row['doc_errada']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'docs_errada' => $docs_errada
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/destinos_cargo', function(Request $request, Response $response, array $args) use ($conexion) {
    $destinos_cargo=[];
    $result = $conexion->query("SELECT iddestinocargo, destinocargo FROM t_destinocargo;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $destinos_cargo[]=array(
            'iddestinocargo'=>(int)$row['iddestinocargo'],
            'destinocargo'=>$row['destinocargo']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'destinos_cargo' => $destinos_cargo
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tipos_bulto', function(Request $request, Response $response, array $args) use ($conexion) {
    $tipos_bulto=[];
    $result = $conexion->query("SELECT idtipobulto, codigo, tipobulto FROM t_tipobulto ORDER BY tipobulto;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tipos_bulto[]=array(
            'idtipobulto'=>(int)$row['idtipobulto'],
            'codigo'=>$row['codigo'],
            'tipobulto'=>$row['tipobulto']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tipos_bulto' => $tipos_bulto
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/timbrados_turno', function(Request $request, Response $response, array $args) use ($conexion) {
    $timbrados_turno=[];
    $result = $conexion->query("SELECT idtimbradoturno, timbradoturno FROM t_timbradoturno;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $timbrados_turno[]=array(
            'idtimbradoturno'=>(int)$row['idtimbradoturno'],
            'timbradoturno'=>$row['timbradoturno']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'timbrados_turno' => $timbrados_turno
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/listado-permisos', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    
    $result = $conexion->query("SELECT IFNULL(operaciones,0) as operaciones, IFNULL(contabilidad,0) as contabilidad, IFNULL(almacen,0) as almacen FROM t_empresa WHERE idempresa=$idempresa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $operaciones=(int)$row['operaciones'];
        $contabilidad=(int)$row['contabilidad'];
        $almacen=(int)$row['almacen'];
    }
    
    
    $rootQuery = $conexion->prepare("SELECT idmodulo, modulo FROM t_modulo WHERE parent_id IS NULL AND (IF(IFNULL(almacen,0)=1 AND $almacen=1,1,0)=1 OR IF(IFNULL(contabilidad,0)=1 AND $contabilidad=1,1,0)=1 OR IF(IFNULL(operaciones,0)=1 AND $operaciones=1,1,0)=1) ORDER BY modulo;");
    $rootQuery->execute();
    $roots = $rootQuery->fetchAll(PDO::FETCH_ASSOC);

    $permisos = [];
    foreach ($roots as $root) {
        $rootNode = [
            'data' => [
                'id' => $root['idmodulo'],
                'name' => $root['modulo'],
                'lectura' => false,
                'escritura' => false
            ]
        ];
        $children = buildTree($root['idmodulo'], $operaciones, $contabilidad, $almacen, $conexion);
        if (!empty($children)) {
            $rootNode['children'] = $children;
        }
        $permisos[] = $rootNode;
    }

    /*
    $permisos=[];
    $result = $conexion->query("SELECT idmodulo, modulo FROM t_modulo WHERE parent_id IS NULL ORDER BY modulo;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        
        $children=[];
        $result_children = $conexion->query("SELECT idmodulo, modulo FROM t_modulo WHERE parent_id=".$row["idmodulo"]." ORDER BY modulo;");
        while ($row_children =  $result_children ->fetch(PDO::FETCH_ASSOC)){
            $children[]=array(
                'idmodulo'=>(int)$row_children['idmodulo'],
                'modulo'=>$row_children['modulo']
            );
        }
        
        $permisos[]=array(
            'idmodulo'=>(int)$row['idmodulo'],
            'modulo'=>$row['modulo'],
            'children'=>$children
        );
    }
    */
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'permisos' => $permisos
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/inventariofisico_etiquetas', function(Request $request, Response $response, array $args) use ($conexion) {
    $inventariofisico_etiquetas=[];
    $result = $conexion->query("SELECT idinventariofisicoetiqueta, inventariofisicoetiqueta FROm t_inventariofisicoetiqueta;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $inventariofisico_etiquetas[]=array(
            'idinventariofisicoetiqueta'=>(int)$row['idinventariofisicoetiqueta'],
            'inventariofisicoetiqueta'=>$row['inventariofisicoetiqueta']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'inventariofisico_etiquetas' => $inventariofisico_etiquetas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/solicitantes/{idcliente}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $solicitantes=[];
    $result = $conexion->query("SELECT idsolicitante, nombre FROM t_solicitante WHERE md5(idcliente)='$idcliente' AND deleted_at IS NULL ORDER BY nombre;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $solicitantes[]=array(
            'idsolicitante'=>(int)$row['idsolicitante'],
            'nombre'=>$row['nombre']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'solicitantes' => $solicitantes
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/movilizadores/{idcliente}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $movilizadores=[];
    $result = $conexion->query("SELECT idmovilizador, movilizador FROM t_movilizador WHERE md5(idcliente)='$idcliente' AND deleted_at IS NULL ORDER BY movilizador;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $movilizadores[]=array(
            'idmovilizador'=>(int)$row['idmovilizador'],
            'movilizador'=>$row['movilizador']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'movilizadores' => $movilizadores
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/etapas', function(Request $request, Response $response, array $args) use ($conexion) {
    $etapas=[];
    $result = $conexion->query("select idetapa, etapa FROM t_etapa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $etapas[]=array(
            'idetapa'=>(int)$row['idetapa'],
            'etapa'=>$row['etapa']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'etapas' => $etapas
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/ate_gas_motivos_pausa', function(Request $request, Response $response, array $args) use ($conexion) {
    $ate_gas_motivos_pausa=[];
    $result = $conexion->query("select idate_gas_motivo_pausa, ate_gas_motivo_pausa FROM t_ate_gas_motivo_pausa;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $ate_gas_motivos_pausa[]=array(
            'idate_gas_motivo_pausa'=>(int)$row['idate_gas_motivo_pausa'],
            'ate_gas_motivo_pausa'=>$row['ate_gas_motivo_pausa']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'ate_gas_motivos_pausa' => $ate_gas_motivos_pausa
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

function buildTree($parent_id, $operaciones, $contabilidad, $almacen, $conexion) {
    $query = $conexion->prepare("SELECT idmodulo, modulo FROM t_modulo WHERE parent_id = :parent_id  AND (IF(IFNULL(almacen,0)=1 AND $almacen=1,1,0)=1 OR IF(IFNULL(contabilidad,0)=1 AND $contabilidad=1,1,0)=1 OR IF(IFNULL(operaciones,0)=1 AND $operaciones=1,1,0)=1) ORDER BY modulo");
    $query->execute([':parent_id' => $parent_id]);
    $nodes = $query->fetchAll(PDO::FETCH_ASSOC);

    $tree = [];
    foreach ($nodes as $node) {
        $treeNode = [
            'data' => [
                'id' => $node['idmodulo'],
                'name' => $node['modulo'],
                'lectura' => false,
                'escritura' => false
            ]
        ];

        // Llamada recursiva para los hijos
        $children = buildTree($node['idmodulo'], $operaciones, $contabilidad, $almacen, $conexion);
        if (!empty($children)) {
            $treeNode['children'] = $children;
        }

        $tree[] = $treeNode;
    }

    return $tree;
}
