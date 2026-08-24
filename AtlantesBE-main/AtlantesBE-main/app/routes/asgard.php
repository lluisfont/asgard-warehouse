<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/asgard/transporteAsgard', function(Request $request, Response $response, array $args) {
    $transporteAsgard=[];
    
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    $conexion_asgard->query("SET NAMES 'utf8'");
    
    
    
    
    $result = $conexion_asgard->query("select 
            dav_pagosdetalle.idpagosdetalle,
            dav_casos.idcasos,
            dav_casos.idciudad,
            dav_casos.carpeta,
            dav_facturaplanilla.idfacturaplanilla,
            dav_concepto.descripcion,
            dav_pagosdetalle.monto,
            dav_pagosdetalle.nro
            FROM
            dav_casos
            left join dav_facturaplanilla on dav_casos.idcasos = dav_facturaplanilla.idcasos AND 1 = dav_facturaplanilla.idestadoplanilla
            left join dav_pagosdetalle on dav_casos.idcasos = dav_pagosdetalle.idcasos
            left join dav_concepto on dav_pagosdetalle.idconcepto = dav_concepto.idconcepto
            WHERE dav_pagosdetalle.idconcepto in (22,61,126,133,134,135,138,137,136,115,29,189);");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $transporteAsgard[]=array(
            'idpagosdetalle'=>(int)$row['idpagosdetalle'],
            'idcasos'=>(int)$row['idcasos'],
            'idciudad'=>(int)$row['idciudad'],
            'carpeta'=>$row['carpeta'],
            'idfacturaplanilla'=>(int)$row['idfacturaplanilla'],
            'descripcion'=>$row['descripcion'],
            'monto'=>(float)$row['monto'],
            'nro'=>$row['nro']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'transporteAsgard' => $transporteAsgard
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/datosCarpeta/{carpeta}', function(Request $request, Response $response, array $args) use ($conexion) {
    $carpeta = $args['carpeta'];
    $carpetaAsgard=[];
    
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    //$conexion_asgard->query("SET NAMES 'utf8'");
    
    $carpeta= trim($carpeta);
    
    
    $result = $conexion_asgard->query("SELECT
        dav_casos.idcasos,
        dav_casos.carpeta,
        dav_casos.descripciongeneral,
        dav_casos.nodui,
        SUM(IFNULL(dav_partidas.pesobruto,0)) as pesobruto,
        SUM(IFNULL(dav_partidas.bultos,0)) as bultos,
        dav_casos.servicioSLG,
        dav_incoterms.codigo as incoterms,
        dav_casos.idtipotransporte,
        dav_casos.idtipocarga,
        dav_cliente.idcliente,
        dav_transportista.transportista,
        dav_casos.nroplaca,
        dav_aduana.codigo as aduana,
        dav_casos.fechalevante,
        CAST(dav_casos.fechaentregaalmacen as DATE) as fechaentregaalmacen,
        dav_casos.idlugardestino,
        dav_casos.idtemperatura,
        dav_casos.idhorario,
        dav_casos.idaduana_interiorscz,
        dav_casos.numero_precinto,
        dav_casos.estibadoresSLG,
        dav_casos.estibadores,
        dav_casos.costo_operador_transporte,
        dav_casos.idtransportista_slg,
        dav_unidad.codigo as tipobulto,
        dav_casos.pedido,
        dav_proveedor.idproveedor,
        dav_documentos_aereo.numero as documento_aereo,
        dav_documentos_terrestre.numero as documento_terrestre,
        dav_documentos_multimodal.numero as documento_multimodal
        FROM
        dav_casos
        LEFT JOIN dav_facturacomercial ON dav_casos.idcasos=dav_facturacomercial.idcasos
        LEFT JOIN dav_partidas ON dav_facturacomercial.idfacturacomercial=dav_partidas.idfacturacomercial
        LEFT JOIN dav_incoterms ON dav_casos.idincoterms=dav_incoterms.idincoterms
        LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
        LEFT JOIN dav_transportista ON dav_casos.idtransportista=dav_transportista.idtransportista
        LEFT JOIN dav_aduana ON dav_casos.idaduana=dav_aduana.idaduana
        LEFT JOIN dav_unidad ON dav_casos.idunidad=dav_unidad.idunidad
        LEFT JOIN dav_proveedor ON dav_casos.idproveedor=dav_proveedor.idproveedor
        LEFT JOIN dav_documentos as dav_documentos_aereo ON dav_documentos_aereo.iddocumentos=(SELECT iddocumentos FROM dav_documentos WHERE idcasos=dav_casos.idcasos AND idtipodocumento IN (41,147) ORDER BY fecha DESC LIMIT 1)
        LEFT JOIN dav_documentos as dav_documentos_terrestre ON dav_documentos_terrestre.iddocumentos=(SELECT iddocumentos FROM dav_documentos WHERE idcasos=dav_casos.idcasos AND idtipodocumento=40 ORDER BY fecha DESC LIMIT 1)
        LEFT JOIN dav_documentos as dav_documentos_multimodal ON dav_documentos_multimodal.iddocumentos=(SELECT iddocumentos FROM dav_documentos WHERE idcasos=dav_casos.idcasos AND idtipodocumento IN (44,40) ORDER BY idtipodocumento DESC, fecha DESC LIMIT 1)
        WHERE
        dav_casos.carpeta=$carpeta
        GROUP BY
        dav_casos.idcasos,
        dav_casos.carpeta,
        dav_casos.descripciongeneral,
        dav_casos.nodui,
        dav_casos.servicioSLG,
        dav_incoterms.codigo,
        dav_casos.idtipotransporte,
        dav_casos.idtipocarga,
        dav_cliente.idcliente,
        dav_transportista.transportista,
        dav_casos.nroplaca,
        dav_aduana.codigo,
        dav_casos.fechalevante,
        dav_casos.fechaentregaalmacen,
        dav_casos.idlugardestino,
        dav_casos.idtemperatura,
        dav_casos.idhorario,
        dav_casos.idaduana_interiorscz,
        dav_casos.numero_precinto,
        dav_casos.estibadoresSLG,
        dav_casos.estibadores,
        dav_casos.costo_operador_transporte,
        dav_casos.idtransportista_slg,
        dav_unidad.codigo,
        dav_casos.pedido,
        dav_proveedor.idproveedor,
        dav_documentos_aereo.numero,
        dav_documentos_terrestre.numero,
        dav_documentos_multimodal.numero;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        
        $id_ATLANTES=0;
        $result_clientes = $conexion->query("select
            idcliente
            FROM
            t_cliente
            WHERE
            id_asgard=".(int)$row["idcliente"].";");
        while ($row_clientes =  $result_clientes ->fetch(PDO::FETCH_ASSOC)){
            $id_ATLANTES=(int)$row_clientes["idcliente"];
        }
        
        $id_proveedor_atlantes=0;
        $result_clientes = $conexion->query("select
            idproveedor
            FROM
            t_proveedor
            WHERE
            id_asgard=".(int)$row["idproveedor"].";");
        while ($row_clientes =  $result_clientes ->fetch(PDO::FETCH_ASSOC)){
            $id_proveedor_atlantes=(int)$row_clientes["idproveedor"];
        }

        
        
        $carpetaAsgard=array(
            'idcasos'=>(int)$row['idcasos'],
            'carpeta'=>(int)$row['carpeta'],
            'descripciongeneral'=>$row['descripciongeneral'],
            'nodui'=>$row['nodui'],
            'pesobruto'=>(float)$row['pesobruto'],
            'bultos'=>(float)$row['bultos'],
            'servicioSLG'=> boolval($row['servicioSLG']),
            'incoterms'=>$row['incoterms'],
            'idtipotransporte'=>$row['idtipotransporte'],
            'idtipocarga'=>$row['idtipocarga'],
            'id_ATLANTES'=>$id_ATLANTES,
            'transportista'=>$row['transportista'],
            'nroplaca'=>$row['nroplaca'],
            'aduana'=>$row['aduana'],
            'fechalevante'=>$row['fechalevante'],
            'fechaentregaalmacen'=>$row['fechaentregaalmacen'],
            'idlugardestino'=>$row['idlugardestino'],
            'idtemperatura'=>$row['idtemperatura'],
            'idhorario'=>$row['idhorario'],
            'idaduana_interiorscz'=>$row['idaduana_interiorscz'],
            'numero_precinto'=>$row['numero_precinto'],
            'estibadoresSLG'=>$row['estibadoresSLG'],
            'estibadores'=>$row['estibadores'],
            'costo_operador_transporte'=>$row['costo_operador_transporte'],
            'idtransportista_slg'=>$row['idtransportista_slg'],
            'tipobulto'=>$row['tipobulto'],
            'pedido'=>$row['pedido'],
            'id_proveedor_atlantes'=>$id_proveedor_atlantes,
            'documento_aereo'=>$row['documento_aereo'],
            'documento_terrestre'=>$row['documento_terrestre'],
            'documento_multimodal'=>$row['documento_multimodal']
        );
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'carpetaAsgard' => $carpetaAsgard
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/datosPartida/{partida}/{idcliente}', function(Request $request, Response $response, array $args) use ($conexion) {
    $partida = $args['partida'];
    $idcliente = $args['idcliente'];
    $carpetaAsgard=[];
    
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    //$conexion_asgard->query("SET NAMES 'utf8'");
    
    $partida= trim($partida);
    
    $id_asgard=0;
    $result_clientes = $conexion->query("select
        id_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row_clientes =  $result_clientes ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row_clientes["id_asgard"];
    }
    
    $items=[];
    $proveedor='';
    $result = $conexion_asgard->query("select
        dav_proveedor.proveedor,
        dav_partidas.codigoproducto,
        dav_partidas.otroparametro10 as chasis,
        CASE IFNULL(tmp_marca.iddatodam,99999)
            WHEN 99999 THEN dav_partidas.otroparametro1
            ELSE tmp_marca.datodam
        END as marca,
        CASE IFNULL(tmp_modelo.iddatodam,99999)
            WHEN 99999 THEN dav_partidas.otroparametro4
            ELSE tmp_modelo.datodam
        END as modelo,
        CASE IFNULL(tmp_color.iddatodam,99999)
            WHEN 99999 THEN dav_partidas.otroparametro15
            ELSE tmp_color.datodam
        END as color,
        dav_casos.pedido,
        dav_facturacomercial.nofactura,
        dav_casos.nodui,
        IF(dav_casos.fecha_pase_salida='0000-00-00 00:00:00',NULL,dav_casos.fecha_pase_salida) as fecha_pase_salida,
        dav_casos.idcasos
        FROM
        dav_partidas
        LEFT JOIN dav_facturacomercial ON dav_partidas.idfacturacomercial=dav_facturacomercial.idfacturacomercial
        LEFT JOIN dav_casos ON dav_facturacomercial.idcasos=dav_casos.idcasos
        LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
        LEFT JOIN dav_dato as tmp_marca ON dav_partidas.idparametro1=tmp_marca.idadta
        LEFT JOIN dav_dato as tmp_modelo ON dav_partidas.idparametro4=tmp_modelo.idadta
        LEFT JOIN dav_dato as tmp_color ON dav_partidas.idparametro15=tmp_color.idadta
        LEFT JOIN dav_proveedor ON dav_casos.idproveedor=dav_proveedor.idproveedor
        WHERE
        IF(IF(SUBSTRING_INDEX(dav_casos.pedido, '-', -1)=dav_casos.pedido,dav_casos.pedido,LEFT(dav_casos.pedido,CHAR_LENGTH(dav_casos.pedido)-(CHAR_LENGTH(SUBSTRING_INDEX(dav_casos.pedido, '-', -1))+1))) = '',CONCAT('SOL-',dav_casos.idcasosprevios), IF(SUBSTRING_INDEX(dav_casos.pedido, '-', -1)=dav_casos.pedido,dav_casos.pedido,LEFT(dav_casos.pedido,CHAR_LENGTH(dav_casos.pedido)-(CHAR_LENGTH(SUBSTRING_INDEX(dav_casos.pedido, '-', -1))+1))))='$partida'
        AND IFNULL(dav_casos.anulado,0)=0
        AND dav_cliente.idcliente=$id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        
        $accesorios=[];
        $result_accesorios = $conexion_asgard->query("select
            accesorio_id, cantidad, observaciones
            FROM
            inventario_registros_accesorios
            WHERE
            casos_id=".$row["idcasos"]."
            AND tipo_inventario_id=1
            AND deleted_at IS NULL;");
        while ($row_accesorios =  $result_accesorios ->fetch(PDO::FETCH_ASSOC)){
            $accesorios[]=array(
                'accesorio_id'=>(int)$row_accesorios['accesorio_id'],
                'cantidad'=>(int)$row_accesorios['cantidad'],
                'observaciones'=>$row_accesorios['observaciones']
            );
        }
        
        
        
        $proveedor=$row['proveedor'];
        $items[]=array(
            'codigoproducto'=>$row['codigoproducto'],
            'chasis'=>$row['chasis'],
            'marca'=>$row['marca'],
            'modelo'=>$row['modelo'],
            'color'=>$row['color'],
            'pedido'=>$row['pedido'],
            'nofactura'=>$row['nofactura'],
            'nodui'=>$row['nodui'],
            'fecha_pase_salida'=>$row['fecha_pase_salida'],
            'accesorios'=>$accesorios
        );
    }
    
    $respuesta=array(
        'proveedor'=>$proveedor,
        'items'=>$items
    );
    
    $mensaje='Todo Correcto';
    $codigo=200;
    if(count($items)==0){
        $mensaje='No se encontro la partida';
        $codigo=400;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => $codigo,
        'mensaje' => $mensaje,
        'data' => $respuesta
    )));
    
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/buscar-chasis/{idcliente}/{chasis}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $chasis = $args['chasis'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/reportes/buscar-chasis',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        $detalle_puerto=array('data'=>[]);
        $detalle_dep_transitorio=array('data'=>[]);
        $detalle_post_nacional=array('data'=>[]);
        
        $detalle_despacho_nacional=array('data'=>[]);
        $detalle_recepcion_nacional=array('data'=>[]);
        $detalle_despacho_local=array('data'=>[]);
        $detalle_recepcion_local=array('data'=>[]);
        
        if(!$response_array["error"]){
            for($tipoinventario=1;$tipoinventario<=2; $tipoinventario++){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => URL_ASGARD_API.'/inventario/lista-vehiculos/'.$tipoinventario,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'embarque_id'=>'', 'partida'=>'' ),
                  CURLOPT_HTTPHEADER => array(
                    'Authorization: '.$token
                  ),
                ));

                $responselista = curl_exec($curl);
                switch ($tipoinventario){
                    case 1:
                        $detalle_dep_transitorio= json_decode($responselista,true);
                        break;
                    case 2:
                        $detalle_puerto= json_decode($responselista,true);
                        break;
                    case 3:
                        $detalle_post_nacional= json_decode($responselista,true);
                        break;
                }
                        
                curl_close($curl);
            }
            
            
            for($tipoinventario=1;$tipoinventario<=4; $tipoinventario++){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => URL_ASGARD_API.'/inventario/lista-inventarios-locales-chasis/'.$tipoinventario,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'embarque_id'=>'', 'partida'=>'' ),
                  CURLOPT_HTTPHEADER => array(
                    'Authorization: '.$token
                  ),
                ));

                $responselista = curl_exec($curl);
                switch ($tipoinventario){
                    case 1:
                        $detalle_despacho_nacional= json_decode($responselista,true);
                        break;
                    case 2:
                        $detalle_recepcion_nacional= json_decode($responselista,true);
                        break;
                    case 4:
                        $detalle_despacho_local= json_decode($responselista,true);
                        break;
                    case 3:
                        $detalle_recepcion_local= json_decode($responselista,true);
                        break;
                }
                        
                curl_close($curl);
            }
            
        }
        
            
        
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array,
        'detalle_puerto'=>$detalle_puerto,
        'detalle_dep_transitorio'=>$detalle_dep_transitorio,
        'detalle_post_nacional'=>$detalle_post_nacional,
        'detalle_despacho_nacional'=>$detalle_despacho_nacional,
        'detalle_recepcion_nacional'=>$detalle_recepcion_nacional,
        'detalle_despacho_local'=>$detalle_despacho_local,
        'detalle_recepcion_local'=>$detalle_recepcion_local
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/resumen-inventario/{idcliente}/{chasis}/{tipo_inventario_id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $chasis = $args['chasis'];
    $tipo_inventario_id = $args['tipo_inventario_id'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/reportes/resumen-inventario',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'tipo_inventario_id'=>$tipo_inventario_id),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/accesorios/lista/{idcliente}/{chasis}/{tipo_inventario_id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $chasis = $args['chasis'];
    $tipo_inventario_id = $args['tipo_inventario_id'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/accesorios/lista',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'tipo_inventario_id'=>$tipo_inventario_id),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/desperfectos/lista/{idcliente}/{chasis}/{tipo_inventario_id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $chasis = $args['chasis'];
    $tipo_inventario_id = $args['tipo_inventario_id'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/desperfectos/lista',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'tipo_inventario_id'=>$tipo_inventario_id),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/contaminacion/lista/{idcliente}/{chasis}/{tipo_inventario_id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $chasis = $args['chasis'];
    $tipo_inventario_id = $args['tipo_inventario_id'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/contaminacion/lista',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'tipo_inventario_id'=>$tipo_inventario_id),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/asgard/inventario/file/download/{idcliente}/{chasis}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $chasis = $args['chasis'];
    $params = json_decode((string) $request->getBody(),true);
    $tipo=$params["tipo"];
    $filename=$params["filename"];
    
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        if($tipo==1){ //Daño
            $path=$id_asgard."/inventario/desperfectos/$chasis/ubicacion";
        }
        
        if($tipo==11){ //accesorios nacional
            $path=$id_asgard."/inventario/accesorios/$chasis";
        }
        if($tipo==13){ //Contaminacion
            $path=$id_asgard."/inventario/contaminacion/$chasis";
        }
        
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/file/download',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('path' => $path, 'filename'=>$filename),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array,
        'send'=>array('path' => $path, 'filename'=>$filename)
    )));
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/nacional/resumen-inventario/{idcliente}/{embarque_id}/{chasis}/{tipo_inventario_id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $embarque_id = $args['embarque_id'];
    $chasis = $args['chasis'];
    $tipo_inventario_id = $args['tipo_inventario_id'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/reportes/resumen-inventario-local',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'tipo_inventario_cliente_local_id'=>$tipo_inventario_id,'embarque_id'=>$embarque_id),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/nacional/accesorios/lista/{idcliente}/{embarque_id}/{chasis}/{tipo_inventario_id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $embarque_id = $args['embarque_id'];
    $chasis = $args['chasis'];
    $tipo_inventario_id = $args['tipo_inventario_id'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/lista-inventarios-accesorios-locales',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'tipo_inventario_cliente_local_id'=>$tipo_inventario_id, 'embarque_id'=>$embarque_id),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/nacional/desperfectos/lista/{idcliente}/{embarque_id}/{chasis}/{tipo_inventario_id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $embarque_id = $args['embarque_id'];
    $chasis = $args['chasis'];
    $tipo_inventario_id = $args['tipo_inventario_id'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/lista-inventarios-desperfectos-locales',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'tipo_inventario_cliente_local_id'=>$tipo_inventario_id, 'embarque_id'=>$embarque_id),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/asgard/inventario/nacional/contaminacion/lista/{idcliente}/{embarque_id}/{chasis}/{tipo_inventario_id}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idcliente = $args['idcliente'];
    $embarque_id = $args['embarque_id'];
    $chasis = $args['chasis'];
    $tipo_inventario_id = $args['tipo_inventario_id'];
    $hostname_asgard=host_asgard;
    $username_asggard=user_asgard;
    $password_asgard=password_asgard;
    $dbname_asgard=database_asgard;
    
    $mensajeerror='';

        
    $conexion_asgard = null;
    try {
        $conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
        $conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    } catch (PDOException $ea) {
        $mensajeerror= $ea->getMessage();
    }
    
    $id_asgard=0;
    $id_cliente_usuario_asgard=0;
    $result = $conexion->query("select
        id_asgard,
        id_cliente_usuario_asgard
        FROM
        t_cliente
        WHERE
        idcliente=$idcliente;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $id_asgard=(int)$row["id_asgard"];
        $id_cliente_usuario_asgard=(int)$row["id_cliente_usuario_asgard"];
    }
    
    
    $result = $conexion_asgard->query("SELECT
        dav_cliente.cliente as clientName,
        dav_cliente.email as email
        FROM 
        dav_cliente
        WHERE dav_cliente.idcliente = $id_asgard;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $payload = array(
            "id" => $id_asgard,
            "name" => $row['clientName'],
            "email" => $row['email'],
            "company_id" => $id_asgard,
            "company_name" => $row['clientName'],
            "company_type" => '90ba488c-5dce-428d-ba0a-7a04248bd714',
            "client_user_id" => $id_cliente_usuario_asgard
        );
        $key = "@QEGTUI";
        $alg = 'HS256';

        $token = JWT::encode($payload, $key, $alg);
    }
    
    if(isset($token)){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => URL_ASGARD_API.'/inventario/lista-inventarios-contaminaciones-locales',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('chasis' => $chasis, 'tipo_inventario_cliente_local_id'=>$tipo_inventario_id, 'embarque_id'=>$embarque_id),
          CURLOPT_HTTPHEADER => array(
            'Authorization: '.$token
          ),
        ));

        $response = curl_exec($curl);

        $response_array= json_decode($response,true);
        curl_close($curl);
        
        //echo $response;
    }
    
    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'token' => $token,
        'response'=>$response_array
    )));
    
    
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);