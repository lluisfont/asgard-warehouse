<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/login', function(Request $request, Response $response, array $args) use ($conexion){
    $body = $request->getParsedBody();
    $username = $body['username'] ?? null;
    $contrasena = $body['contrasena'] ?? null;



    $codigo=0;
    $status='Error';
    $token='';
    $usuario='';
    $cambiocontrasena=false;
    $querylogin='';
    $jwt='';

    $pass_master=false;
    $idcontrasenamaestra=0;

    $result = $conexion->query("SELECT
        idcontrasenamaestra
        FROM
        t_contrasenamaestra
        WHERE
        contrasena=md5('$contrasena')
        AND IFNULL(fechavencimiento, CURRENT_DATE())<=CURRENT_DATE();");
    if(($row = $result->fetch(PDO::FETCH_ASSOC))){
        $idcontrasenamaestra=(int)$row['idcontrasenamaestra'];
    }

    if($idcontrasenamaestra>0){
        $pass_master=true;
    }


    $result = $conexion->query("SELECT
        t_usuario.idusuario,
        t_usuario.idempresa,
        t_usuario.nombre,
        t_usuario.idciudad,
        t_usuario.idalmacen,
        t_usuario.idtipousuario,
        t_usuario.contrasena,
        IFNULL(t_ciudad.timezone_name, '".DateTimeService::defaultTimezoneName()."') as timezone_name,
        IFNULL(t_ciudad.utc_offset_minutos, ".DateTimeService::defaultOffsetMinutes().") as utc_offset_minutos,
        md5('".$contrasena."') as contrasenaint,
        IFNULL(t_usuario.activo,0) as activo,
        md5(IFNULL(t_usuario.idcliente_almacen,0)) as idcliente_almacen,
        IFNULL(t_empresa.operaciones,0) as operaciones,
        IFNULL(t_empresa.contabilidad,0) as contabilidad,
        IFNULL(t_empresa.almacen,0) as almacen,
        IFNULL(t_empresa.asgard_operaciones,0) as asgard_operaciones,
        IFNULL(t_empresa.asgard_almacen,0) as asgard_almacen,
        t_usuario.fecha_contrasena
        FROM
        t_usuario
        LEFT JOIN t_empresa ON t_usuario.idempresa=t_empresa.idempresa
        LEFT JOIN t_ciudad ON t_usuario.idciudad=t_ciudad.idciudad
        WHERE t_usuario.username='$username';");
    if(($row = $result->fetch(PDO::FETCH_ASSOC))){
        if($row["contrasena"] != $row["contrasenaint"] && !$pass_master){
            $codigo=400;
            $mensaje='Contraseña Incorrecta';
        }else{
            if((int)$row["activo"]==1 || $pass_master){
                $timezoneName = DateTimeService::normalizeTimezoneName($row['timezone_name']);
                $utcOffsetMinutos = DateTimeService::offsetMinutesForTimezone($timezoneName);
                $diasContrasena = DateTimeService::daysSinceLocalDate($row['fecha_contrasena'], $timezoneName);

                if($diasContrasena>90 && !$pass_master){
                    $cambiocontrasena=true;
                }

                $permisos=[];



                $resultpermisos = $conexion->query("SELECT
                    idmodulo,
                    IFNULL(lectura,0) as lectura,
                    IFNULL(escritura,0) as escritura
                    FROM
                    t_usuariomodulo
                    WHERE idusuario=".$row["idusuario"].";");
                while ($rowpermisos =  $resultpermisos ->fetch(PDO::FETCH_ASSOC)){
                    $permisos[]=array(
                        'idmodulo'=>(int)$rowpermisos['idmodulo'],
                        'lectura'=> boolval($rowpermisos['lectura']),
                        'escritura'=> boolval($rowpermisos['escritura'])
                    );
                }


                $key = jwt_key;
                $payload = array(
                    "idusuario" => $row["idusuario"],
                    "nombre" => $row["nombre"],
                    'idempresa' => (int)$row['idempresa'],
                    'idciudad' => (int)$row['idciudad'],
                    'idalmacen' => $row['idalmacen'] === null ? null : (int)$row['idalmacen'],
                    'idtipousuario' => (int)$row['idtipousuario'],
                    'timezone_name' => $timezoneName,
                    'utc_offset_minutos' => $utcOffsetMinutos,
                    'idcliente_almacen' => $row['idcliente_almacen'],
                    'operaciones' => boolval($row['operaciones']),
                    'contabilidad' => boolval($row['contabilidad']),
                    'almacen' => boolval($row['almacen']),
                    'asgard_operaciones' => boolval($row['asgard_operaciones']),
                    'asgard_almacen' => boolval($row['asgard_almacen']),
                    'cambiocontrasena'=>$cambiocontrasena,
                    'permisos'=>$permisos
                );
                $token = JWT::encode($payload, $key, 'HS256');
                //$token = bin2hex(openssl_random_pseudo_bytes(32));
                if($token<>''){
                    $status="Exito";
                    $codigo=200;
                    $mensaje="Ingreso Existoso";
                    $usuario=$row['nombre'];
                }else{
                    //$app->response->setStatus(500);
                    $codigo=500;
                    $mensaje="Ocurrio un problema, vuelva a intentarlo mas tarde";
                }



            }else{
                //$app->response->setStatus(400);
                $codigo=400;
                $mensaje='Usuario inactivo';
            }
        }
    }else{
        $codigo=400;
        //$app->response->setStatus(400);
        $mensaje='Usuario Inexistente';
    }

    $resultado=array(
        'estado'=>$status,
        'codigo'=>$codigo,
        'mensaje'=>$mensaje,
        'usuario'=>$usuario,
        'token'=>$token,
        'cambiocontrasena'=>$cambiocontrasena
    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');

});

$app->post('/recuperarconstrasena', function(Request $request, Response $response, array $args) use ($conexion){
    $params = json_decode((string) $request->getBody(),true);

    $username_email=$params['username_email'];

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';
    $email='';
    $idcodigo_nuevo='';

    $idusuario=0;
    $result = $conexion->query("select idusuario, username, email FROM t_usuario WHERE username='$username_email' OR email='$username_email' AND IFNULL(activo,0)=1;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idusuario=$row['idusuario'];
        $username=$row['username'];
        $email=$row['email'];
    }
    if((int)$idusuario>0){
        $codigo=generateCode(6);

        $query="UPDATE t_codigosrecuperarcontrasena SET activo=0 WHERE idusuario=$idusuario AND IFNULL(activo,0)=1;";
        $query=$query."INSERT INTO t_codigosrecuperarcontrasena (codigo,    idusuario,      fecha,                  activo)
                                                        VALUES  ('$codigo', $idusuario,     CURRENT_TIMESTAMP(),    1);";
        $query=$query."SELECT LAST_INSERT_ID() INTO @idcodigo_nuevo;";

        $result = $conexion->exec($query);

        if($result===false){

        }else{
            if($email<>''){
                $to= array($email);

                $subject="Código de Verificación - ASGARD WareHouse";

                $mensaje_mail="<p>Estimado/a</p>";
                $mensaje_mail=$mensaje_mail."<p>Hemos recibido una solicitud para restablecer la contraseña de su cuenta. A continuación, encontrará los datos necesarios para continuar con el proceso:</p>";
                $mensaje_mail=$mensaje_mail."<ul>";
                $mensaje_mail=$mensaje_mail."<li><strong>Username:</strong> $username</li>";
                $mensaje_mail=$mensaje_mail."<li><strong>Código de verificación:</strong> <span style='font-size: 18px; font-weight: bold; color: #2E86C1;'>$codigo</span></li>";
                $mensaje_mail=$mensaje_mail."</ul>";
                $mensaje_mail=$mensaje_mail."<p><strong>Importante:</strong> Este código tiene una vigencia de <strong>10 minutos</strong> y solo puede ser utilizado una vez. Si usted no solicitó el restablecimiento de la contraseña, puede ignorar este mensaje.</p>";
                $mensaje_mail=$mensaje_mail."<p>Si necesita asistencia adicional, no dude en contactarnos.</p>";

                $mail=new SendMail();


                $respuesta[]=$mail->enviarMail($to, array(), $subject, '', $mensaje_mail);


                $codigo=200;
                $status='Exito';
                $mensaje='Se guardo la información Correctamente';

                $resultid = $conexion->query("SELECT @idcodigo_nuevo as idcodigo_nuevo;");
                while ($rowid =  $resultid ->fetch(PDO::FETCH_ASSOC)){
                    $idcodigo_nuevo=$rowid['idcodigo_nuevo'];
                }
            }else{
                $mensaje='No hay un correo electrónico registrado a su cuenta, comuniquese con el área de administración';
            }


        }
    }else{
        $mensaje='No existe ningun usuario con el dato proporcionado';
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'idcodigo_nuevo'=>$idcodigo_nuevo,
        'email'=>$email

    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');


});

$app->post('/verificarcodigo', function(Request $request, Response $response, array $args) use ($conexion){
    $params = json_decode((string) $request->getBody(),true);

    $idcodigo = $params['idcodigo'] ?? null;
    $codigo_verificacion = $params['codigo_verificacion'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $codigocorrecto = false;
    $activo = 0;
    $minutos = 0;

    if (empty($idcodigo)) {
        $mensaje = 'No se recibió el código de recuperación';
    } elseif (empty($codigo_verificacion)) {
        $mensaje = 'No se recibió el código de verificación';
    } else {

        $query = "
            SELECT 
                IFNULL(activo, 0) AS activo,
                TIMESTAMPDIFF(MINUTE, fecha, NOW()) AS minutos
            FROM t_codigosrecuperarcontrasena
            WHERE idcodigosrecuperarcontrasena = :idcodigo
            AND codigo = :codigo_verificacion
            LIMIT 1
        ";

        $stmt = $conexion->prepare($query);

        $stmt->execute([
            ':idcodigo' => $idcodigo,
            ':codigo_verificacion' => $codigo_verificacion
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $codigocorrecto = true;
            $activo = (int) $row['activo'];
            $minutos = (int) $row['minutos'];
        }

        if ($codigocorrecto) {
            if ($activo === 1 && $minutos <= 10) {
                $codigo = 200;
                $status = 'Exito';
                $mensaje = 'Código verificado correctamente';
            } else {
                $mensaje = 'El código ya caducó';
            }
        } else {
            $mensaje = 'El código es incorrecto';
        }
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje

    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');


});

$app->post('/resetearcontrasena', function(Request $request, Response $response, array $args) use ($conexion){
    $params = json_decode((string) $request->getBody(),true);

    $idcodigo = $params['idcodigo'] ?? null;
    $nuevacontrasena = $params['nuevacontrasena'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    if (empty($idcodigo)) {
        $mensaje = 'No se recibió el código de recuperación';
    } elseif (empty($nuevacontrasena)) {
        $mensaje = 'No se recibió la nueva contraseña';
    } else {

        $query = "
            UPDATE t_usuario a
            INNER JOIN t_codigosrecuperarcontrasena b 
                ON a.idusuario = b.idusuario
            SET
                a.contrasena = MD5(:nuevacontrasena),
                a.fecha_contrasena = CURRENT_DATE()
            WHERE
                b.idcodigosrecuperarcontrasena = :idcodigo
        ";

        $stmt = $conexion->prepare($query);

        $result = $stmt->execute([
            ':nuevacontrasena' => $nuevacontrasena,
            ':idcodigo' => $idcodigo
        ]);

        if ($result && $stmt->rowCount() > 0) {
            $codigo = 200;
            $status = 'Exito';
            $mensaje = 'Se guardó la información correctamente';
        } else {
            $mensaje = 'No se encontró un usuario asociado al código de recuperación';
        }
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje

    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');


});

$app->post('/verificardoblefactor', function(Request $request, Response $response, array $args) use ($conexion){
    $body = $request->getParsedBody();
    $username = $body['username'] ?? null;
    $contrasena = $body['contrasena'] ?? null;

    $codigo=400;
    $status='Error';
    $mensaje='Ocurrio un problema';

    $pass_master=false;
    $idcontrasenamaestra=0;

    $result = $conexion->query("SELECT
        idcontrasenamaestra
        FROM
        t_contrasenamaestra
        WHERE
        contrasena=md5('$contrasena')
        AND IFNULL(fechavencimiento, CURRENT_DATE())<=CURRENT_DATE();");
    if(($row = $result->fetch(PDO::FETCH_ASSOC))){
        $idcontrasenamaestra=(int)$row['idcontrasenamaestra'];
    }

    if($idcontrasenamaestra>0){
        $pass_master=true;
    }



    $doblefactor=false;
    $email='';
    $idusuario=0;
    $result = $conexion->query("SELECT
        t_usuario.idusuario,
        t_usuario.contrasena,
        t_usuario.email,
        md5('".$contrasena."') as contrasenaint,
        IFNULL(t_usuario.activo,0) as activo,
        IFNULL(t_usuario.doble_factor,0) as doble_factor
        FROM
        t_usuario
        WHERE t_usuario.username='$username';");
    if(($row = $result->fetch(PDO::FETCH_ASSOC))){
        if($row["contrasena"] != $row["contrasenaint"] && !$pass_master){
            $codigo=400;
            $mensaje='Contraseña Incorrecta';
        }else{
            if((int)$row["activo"]==1 || $pass_master){
                $status="Exito";
                $codigo=200;
                $mensaje="Ingreso Existoso";
                $idusuario=(int)$row['idusuario'];
                $email=$row['email'];
                $doblefactor= boolval($row['doble_factor']);
                if($doblefactor && !$pass_master){
                    $codigo=generateCode(6);

                    $query="UPDATE t_usuario SET codigo_doble_factor='$codigo', fecha_doble_factor=CURRENT_TIMESTAMP() WHERE idusuario=$idusuario;";

                    $result = $conexion->exec($query);

                    if($result===false){

                    }else{
                        if($email<>''){
                            $to= array($email);

                            $subject="Código de Verificación - ASGARD WareHouse";

                            $mensaje_mail="<p>Estimado/a</p>";
                            $mensaje_mail=$mensaje_mail."<p>Para completar su inicio de sesión, por favor ingrese el siguiente código de verificación:</p>";
                            $mensaje_mail=$mensaje_mail."<p style='font-size: 20px; font-weight: bold; color: #2E86C1;'>$codigo</p>";
                            $mensaje_mail=$mensaje_mail."<p><strong>Importante:</strong> Este código de autenticación tiene una vigencia de <strong>10 minutos</strong> y solo puede ser utilizado una vez.</p>";
                            $mensaje_mail=$mensaje_mail."<p>Si usted no está intentando acceder a su cuenta, le recomendamos cambiar su contraseña de inmediato y comunicarse con nuestro equipo de soporte.</p>";

                            $mail=new SendMail();


                            $respuesta[]=$mail->enviarMail($to, array(), $subject, '', $mensaje_mail);


                            $codigo=200;
                            $status='Exito';
                            $mensaje='Se guardo la información Correctamente';

                        }else{
                            $mensaje='No hay un correo electrónico registrado a su cuenta, comuniquese con el área de administración';
                        }


                    }
                }
            }else{
                $codigo=400;
                $mensaje='Usuario inactivo';
            }
        }
    }else{
        $codigo=400;
        $mensaje='Usuario Inexistente';
    }

    $resultado=array(
        'estado'=>$status,
        'codigo'=>$codigo,
        'mensaje'=>$mensaje,
        'doblefactor'=>$doblefactor,
        'email'=>$email,
        'idusuario'=>$idusuario
    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');

});

$app->post('/verificarcodigodoblefactor', function(Request $request, Response $response, array $args) use ($conexion){
    $params = json_decode((string) $request->getBody(),true);

    $idusuario = $params['idusuario'] ?? null;
    $codigo_verificacion = $params['codigo_verificacion'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $codigocorrecto = false;
    $minutos = 0;

    if (empty($idusuario)) {
        $mensaje = 'No se recibió el usuario';
    } elseif (empty($codigo_verificacion)) {
        $mensaje = 'No se recibió el código de verificación';
    } else {

        $query = "
            SELECT 
                TIMESTAMPDIFF(MINUTE, fecha_doble_factor, NOW()) AS minutos
            FROM t_usuario
            WHERE idusuario = :idusuario
            AND codigo_doble_factor = :codigo_verificacion
            LIMIT 1
        ";

        $stmt = $conexion->prepare($query);

        $stmt->execute([
            ':idusuario' => $idusuario,
            ':codigo_verificacion' => $codigo_verificacion
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $codigocorrecto = true;
            $minutos = (int) $row['minutos'];
        }

        if ($codigocorrecto) {
            if ($minutos <= 10) {
                $codigo = 200;
                $status = 'Exito';
                $mensaje = 'Código verificado correctamente';
            } else {
                $mensaje = 'El código ya caducó';
            }
        } else {
            $mensaje = 'El código es incorrecto';
        }
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje

    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');


});

$app->get('/usuarios', function(Request $request, Response $response, array $args) use ($conexion) {
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];

    $usuarios=[];
    $result = $conexion->query("SELECT
        t_usuario.idusuario,
        t_usuario.nombre,
        t_usuario.ci,
        t_usuario.idempresa,
        t_empresa.empresa,
        t_usuario.idciudad,
        t_ciudad.ciudad,
        t_usuario.idalmacen,
        t_almacen.almacen,
        t_usuario.username,
        t_usuario.idtipousuario,
        t_tipousuario.tipousuario,
        t_usuario.email,
        t_usuario.telefono,
        IFNULL(t_usuario.almacen,0) as almacen,
        IFNULL(t_usuario.activo,0) as activo
        from
        t_usuario
        LEFT JOIN t_ciudad ON t_usuario.idciudad=t_ciudad.idciudad
        LEFT JOIN t_almacen ON t_usuario.idalmacen=t_almacen.idalmacen
        LEFT JOIN t_tipousuario ON t_usuario.idtipousuario=t_tipousuario.idtipousuario
        LEFT JOIN t_empresa ON t_usuario.idempresa=t_empresa.idempresa
        WHERE
        t_usuario.idempresa=$idempresa
        ORDER BY
        t_usuario.nombre;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $usuarios[]=array(
            'idusuario'=>(int)$row['idusuario'],
            'idempresa'=>$row['idempresa'],
            'empresa'=>$row['empresa'],
            'nombre'=>$row['nombre'],
            'ci'=>$row['ci'],
            'idciudad'=>$row['idciudad'],
            'ciudad'=>$row['ciudad'],
            'idalmacen'=>$row['idalmacen'],
            'almacen'=>$row['almacen'],
            'username'=>$row['username'],
            'idtipousuario'=>$row['idtipousuario'],
            'tipousuario'=>$row['tipousuario'],
            'email'=>$row['email'],
            'telefono'=>$row['telefono'],
            'almacen'=>boolval($row['almacen']),
            'activo'=>boolval($row['activo'])
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'usuarios' => $usuarios
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/usuario/{idusuario}', function(Request $request, Response $response, array $args) use ($conexion) {
    $idusuario = $args['idusuario'];
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];

    $usuario=[];
    $result = $conexion->query("SELECT
        t_usuario.idusuario,
        t_usuario.nombre,
        t_usuario.ci,
        t_usuario.idempresa,
        t_empresa.empresa,
        t_usuario.idciudad,
        t_ciudad.ciudad,
        t_usuario.idalmacen,
        t_almacen.almacen,
        t_usuario.username,
        t_usuario.idtipousuario,
        t_tipousuario.tipousuario,
        t_usuario.email,
        t_usuario.telefono,
        IFNULL(t_usuario.almacen,0) as usuario_almacen,
        IFNULL(t_usuario.activo,0) as activo
        from
        t_usuario
        LEFT JOIN t_ciudad ON t_usuario.idciudad=t_ciudad.idciudad
        LEFT JOIN t_almacen ON t_usuario.idalmacen=t_almacen.idalmacen
        LEFT JOIN t_tipousuario ON t_usuario.idtipousuario=t_tipousuario.idtipousuario
        LEFT JOIN t_empresa ON t_usuario.idempresa=t_empresa.idempresa
        WHERE
        t_usuario.idusuario=$idusuario
        ORDER BY
        t_usuario.nombre;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){

        $columnas_moverdividir=[];
        $resultmoverdividir = $conexion->query("SELECT
            t_columnas_moverdividir.field,
            t_columnas_moverdividir.header,
            t_columnas_moverdividir.type
            FROM
            t_usuario_columnas_moverdividir
            LEFT JOIN t_columnas_moverdividir ON t_usuario_columnas_moverdividir.field=t_columnas_moverdividir.field
            WHERE
            t_usuario_columnas_moverdividir.idusuario=$idusuario
            ORDER BY
            t_columnas_moverdividir.idcolumnas_moverdividir;");
        while ($rowmoverdividir =  $resultmoverdividir ->fetch(PDO::FETCH_ASSOC)){
            $columnas_moverdividir[]=array(
                'field'=>$rowmoverdividir['field'],
                'header'=>$rowmoverdividir['header'],
                'type'=>$rowmoverdividir['type']
            );
        }

        $columnas_pedido=[];
        $resultpedido = $conexion->query("SELECT
            t_columnas_pedido.field,
            t_columnas_pedido.header,
            t_columnas_pedido.type
            FROM
            t_usuario_columnas_pedido
            LEFT JOIN t_columnas_pedido ON t_usuario_columnas_pedido.field=t_columnas_pedido.field
            WHERE
            t_usuario_columnas_pedido.idusuario=$idusuario
            ORDER BY
            t_columnas_pedido.idcolumnas_pedido;");
        while ($rowpedido =  $resultpedido ->fetch(PDO::FETCH_ASSOC)){
            $columnas_pedido[]=array(
                'field'=>$rowpedido['field'],
                'header'=>$rowpedido['header'],
                'type'=>$rowpedido['type']
            );
        }

        $almacenes=[];
        $resultalmacen = $conexion->query("SELECT
            t_almacen.idalmacen,
            t_almacen.codigo_almacen,
            t_almacen.almacen,
            IF(t_usuario_almacenes.idusuario_almacenes IS NULL,0,1) as almacen_marcado
            FROM
            t_almacen
            LEFT JOIN t_ciudad ON t_almacen.idciudad=t_ciudad.idciudad
            LEFT JOIN t_usuario_almacenes ON t_almacen.idalmacen=t_usuario_almacenes.idalmacen AND $idusuario=t_usuario_almacenes.idusuario
            WHERE
            t_ciudad.idempresa=$idempresa;");
        while ($rowalmacen =  $resultalmacen ->fetch(PDO::FETCH_ASSOC)){
            $almacenes[]=array(
                'idalmacen'=>(int)$rowalmacen['idalmacen'],
                'codigo_almacen'=>$rowalmacen['codigo_almacen'],
                'almacen'=>$rowalmacen['almacen'],
                'almacen_marcado'=> boolval($rowalmacen['almacen_marcado'])
            );
        }

        $usuario=array(
            'idusuario'=>(int)$row['idusuario'],
            'nombre'=>$row['nombre'],
            'ci'=>$row['ci'],
            'idempresa'=>(int)$row['idempresa'],
            'empresa'=>$row['empresa'],
            'idciudad'=>$row['idciudad'],
            'ciudad'=>$row['ciudad'],
            'idalmacen'=>$row['idalmacen'],
            'almacen'=>$row['almacen'],
            'username'=>$row['username'],
            'idtipousuario'=>$row['idtipousuario'],
            'tipousuario'=>$row['tipousuario'],
            'email'=>$row['email'],
            'telefono'=>$row['telefono'],
            'usuario_almacen'=>boolval($row['usuario_almacen']),
            'activo'=>boolval($row['activo']),
            'columnas_moverdividir'=>$columnas_moverdividir,
            'columnas_pedido'=>$columnas_pedido,
            'almacenes'=>$almacenes,
            'permisos'=> getPermisos($row['idusuario'], $conexion)
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'usuario' => $usuario
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/tiposusuario', function(Request $request, Response $response, array $args) use ($conexion) {
    $tiposusuario=[];
    $result = $conexion->query("select idtipousuario, tipousuario FROM t_tipousuario;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $tiposusuario[]=array(
            'idtipousuario'=>(int)$row['idtipousuario'],
            'tipousuario'=>$row['tipousuario']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'tiposusuario' => $tiposusuario
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->get('/usuarios/{username}', function(Request $request, Response $response, array $args) use ($conexion) {
    $username = $args['username'];
    $idusuario=0;
    $existeusername=false;
    $result = $conexion->query("select idusuario FROM t_usuario WHERE username='$username';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idusuario=$row['idusuario'];
    }
    if((int)$idusuario>0){
        $existeusername=true;
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'existeusername' => $existeusername
    )));
    return $response->withHeader('Content-Type', 'application/json');
})->add($verifyToken);

$app->post('/usuarios', function(Request $request, Response $response, array $args) use ($conexion){
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idempresa=$decoded_array["idempresa"];
    $params = json_decode((string) $request->getBody(),true);

    $nombre=$params['nombre'];
    $idtipousuario=$params['idtipousuario'];
    $email=$params['email'];
    $ci=$params['ci'];
    $telefono=$params['telefono'];
    //$idempresa=$params['idempresa'];
    $idciudad=$params['idciudad'];
    $idalmacen= isset($params['idalmacen']) ? $params['idalmacen'] : 'NULL';
    $username=$params['username'];
    $contrasena="Atlantes_".rand(1000,9999);
    $permisos=$params['permisos'];
    $almacenes=$params['almacenes'];



    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';

    $idusuario=0;
    $existeusername=false;
    $result = $conexion->query("select idusuario FROM t_usuario WHERE username='$username';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idusuario=$row['idusuario'];
    }
    if((int)$idusuario>0){
        $existeusername=true;
    }

    $idusuarioemail=0;
    $existeemail=false;
    $result = $conexion->query("select idusuario FROM t_usuario WHERE email='$email';");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idusuarioemail=$row['idusuario'];
    }
    if((int)$idusuarioemail>0){
        $existeemail=true;
    }

    if(!$existeusername && !$existeemail){
        $query="INSERT INTO t_usuario (nombre,      ci,     idempresa,  idciudad,   idalmacen,    username,       contrasena,         idtipousuario,  email,      telefono,       activo,   fecha_contrasena)
                               VALUES ('$nombre',   '$ci',  $idempresa, $idciudad,  $idalmacen,   '$username',    MD5('$contrasena'), $idtipousuario, '$email',   '$telefono',    1,        '".(date("Y")-1)."-".date("m-d")."');";
        $query=$query."SELECT LAST_INSERT_ID() INTO @idusuario_nuevo;";

        $result = $conexion->exec($query);

        if($result===false){

        }else{

            $to= array($email);

            $subject="Confirmación de Creación de Acceso al sistema ASGARD - WareHouse";

            $mensaje_mail="<p>Estimado/a ".$nombre."</p>";
            $mensaje_mail=$mensaje_mail."<p>Le informamos que se ha creado correctamente su acceso al sistema ASGARD WAREHOUSE. A continuación, encontrará los datos necesarios para ingresar:</p>";
            $mensaje_mail=$mensaje_mail."<ul>";
            $mensaje_mail=$mensaje_mail."<li><strong>Username:</strong> $username</li>";
            $mensaje_mail=$mensaje_mail."<li><strong>Contraseña temporal:</strong> $contrasena</li>";
            $mensaje_mail=$mensaje_mail."</ul>";
            $mensaje_mail=$mensaje_mail."<p><strong>Importante:</strong> Por motivos de seguridad, esta contraseña es temporal y deberá ser modificada en su primer ingreso al sistema.</p>";
            $mensaje_mail=$mensaje_mail."<p>Puede acceder al sistema a través del siguiente enlace:<br>";
            $mensaje_mail=$mensaje_mail."<a href='".link_sistema."' target='_blank'>".link_sistema."</a></p>";
            $mensaje_mail=$mensaje_mail."<p>Si tiene alguna dificultad para ingresar o necesita asistencia, no dude en contactarnos.</p>";

            $mail=new SendMail();


            $respuesta[]=$mail->enviarMail($to, array(), $subject, '', $mensaje_mail);


            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información Correctamente';

            $resultid = $conexion->query("SELECT @idusuario_nuevo as idusuario_nuevo;");
            while ($rowid =  $resultid ->fetch(PDO::FETCH_ASSOC)){
                $idusuario_nuevo=$rowid['idusuario_nuevo'];
            }

            if((int)$idusuario_nuevo>0){
                $querypermisos=savePermisos($idusuario_nuevo, $permisos, $conexion);
                $resultpermisos = $conexion->exec($querypermisos);
                if($resultpermisos===false){
                    $mensaje='Se creo el usuario, sin embargo no se pudo asignar permisos';
                }else{

                }
            }else{
                $mensaje='Se creo el usuario, sin embargo no se pudo asignar permisos';
            }
        }
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'existeusername'=>$existeusername,
        'existeemail'=>$existeemail

    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');


})->add($verifyRole(10,2))->add($verifyToken);

$app->put('/usuarios/{idusuario}', function(Request $request, Response $response, array $args) use ($conexion){
    $idusuario = $args['idusuario'];
    $params = json_decode((string) $request->getBody(),true);

    $nombre=$params['nombre'];
    $idtipousuario=$params['idtipousuario'];
    $email=$params['email'];
    $ci=$params['ci'];
    $telefono=$params['telefono'];
    //$idempresa=$params['idempresa'];
    $idciudad=$params['idciudad'];
    $idalmacen= isset($params['idalmacen']) ? $params['idalmacen'] : 'NULL';
    $activo=$params['activo'];
    $permisos=$params['permisos'];
    $almacenes=$params['almacenes'];



    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';


    $idusuarioemail=0;
    $existeemail=false;
    $result = $conexion->query("select idusuario FROM t_usuario WHERE email='$email' AND idusuario<>$idusuario;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idusuarioemail=$row['idusuario'];
    }
    if((int)$idusuarioemail>0){
        $existeemail=true;
    }

    if(!$existeemail){

        $query="UPDATE t_usuario SET
            nombre='$nombre',
            idtipousuario='$idtipousuario',
            email='$email',
            telefono='$telefono',
            ci='$ci',
            idciudad='$idciudad',
            idalmacen=$idalmacen,
            activo='$activo'
            WHERE
            idusuario=$idusuario;";

        $query=$query.savePermisos($idusuario, $permisos, $conexion);

        $query=$query.saveUsuarioAlmacenes($idusuario, $almacenes, $conexion);

        $result = $conexion->exec($query);

        if($result===false){

        }else{
            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información de Ruta';
        }
    }

    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'existeemail'=>$existeemail
    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');


})->add($verifyRole(10,2))->add($verifyToken);

$app->put('/editarperfil', function(Request $request, Response $response, array $args) use ($conexion){
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idusuario=$decoded_array["idusuario"];

    $params = json_decode((string) $request->getBody(),true);



    $nombre=$params['nombre'];
    $email=$params['email'];
    $ci=$params['ci'];
    $telefono=$params['telefono'];

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';

    $idusuarioemail=0;
    $existeemail=false;
    $result = $conexion->query("select idusuario FROM t_usuario WHERE email='$email' AND idusuario<>$idusuario;");
    while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
        $idusuarioemail=$row['idusuario'];
    }
    if((int)$idusuarioemail>0){
        $existeemail=true;
    }

    if(!$existeemail){
        $query="UPDATE t_usuario SET
            nombre='$nombre',
            email='$email',
            telefono='$telefono',
            ci='$ci'
            WHERE
            idusuario=$idusuario;";

        $result = $conexion->exec($query);

        if($result===false){

        }else{
            $codigo=200;
            $status='Exito';
            $mensaje='Se guardo la información de Ruta';
        }
    }



    $resultado=array(
        'codigo'=> $codigo,
        'estado'=> $status,
        'mensaje'=> $mensaje,
        'existeemail'=>$existeemail

    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');


})->add($verifyToken);

$app->put('/cambiarcontrasena', function(Request $request, Response $response, array $args) use ($conexion){
    $headers = apache_request_headers();
    $token=$headers['Authorization'];
    $decoded = JWT::decode($token, new Key(jwt_key, 'HS256'));
    $decoded_array = (array) $decoded;
    $idusuario=$decoded_array["idusuario"];

    $params = json_decode((string) $request->getBody(),true);

    $contrasenaactual=$params['contrasenaactual'];
    $nuevacontrasena=$params['nuevacontrasena'];

    $codigo=400;
    $status='Error';
    $mensaje='No se guardo la información';

    $resultpass = $conexion->query("SELECT
        md5('$contrasenaactual') as contrasenaactual,
        md5('$nuevacontrasena') as nuevacontrasena,
        contrasena
        FROM
        t_usuario
        WHERE
        idusuario=$idusuario;");
    while ($rowpass =  $resultpass ->fetch(PDO::FETCH_ASSOC)){
        if($rowpass['contrasenaactual']==$rowpass['contrasena']){
            if($rowpass['contrasenaactual']==$rowpass['nuevacontrasena']){
                $mensaje='La nueva contraseña no puede ser la misma que la anterior';
            }else{
                $query="UPDATE t_usuario SET
                    contrasena=md5('$nuevacontrasena'),
                    fecha_contrasena=CURRENT_DATE()
                    WHERE
                    idusuario=$idusuario;";

                $result = $conexion->exec($query);

                if($result===false){

                }else{
                    $codigo=200;
                    $status='Exito';
                    $mensaje='Se guardo la información Correctamente';
                }
            }

        }else{
            $mensaje='La contraseña es incorrecta';
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

$app->put('/usuarios/columnas_moverdividir/{idusuario}', function(Request $request, Response $response, array $args) use ($conexion) {

    $idusuario = $args['idusuario'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $columnas = [];
    $columnas_moverdividir = [];

    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros
        |--------------------------------------------------------------------------
        */
        $params = json_decode((string) $request->getBody(), true);

        if (!is_array($params)) {
            $mensaje = 'No se recibieron columnas válidas';
            $continuar = false;
        }

        if ($continuar) {
            $columnas = $params;
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener columnas actuales del usuario
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryActuales = "
                SELECT field
                FROM t_usuario_columnas_moverdividir
                WHERE idusuario = :idusuario
            ";

            $stmtActuales = $conexion->prepare($queryActuales);

            $stmtActuales->execute([
                ':idusuario' => $idusuario
            ]);

            while ($row = $stmtActuales->fetch(PDO::FETCH_ASSOC)) {
                $columnas_moverdividir[] = [
                    'field' => $row['field']
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Preparar arrays simples para comparar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $fieldsNuevos = [];
            $fieldsActuales = [];

            foreach ($columnas as $columna) {
                $field = $columna["field"] ?? '';

                if (trim($field) !== '') {
                    $fieldsNuevos[] = $field;
                }
            }

            foreach ($columnas_moverdividir as $columnaActual) {
                $field = $columnaActual["field"] ?? '';

                if (trim($field) !== '') {
                    $fieldsActuales[] = $field;
                }
            }

            /*
            Evita duplicados si el frontend manda el mismo field más de una vez.
            */
            $fieldsNuevos = array_values(array_unique($fieldsNuevos));
            $fieldsActuales = array_values(array_unique($fieldsActuales));
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar y eliminar diferencias
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryInsert = "
                INSERT INTO t_usuario_columnas_moverdividir (
                    idusuario,
                    field
                ) VALUES (
                    :idusuario,
                    :field
                )
            ";

            $stmtInsert = $conexion->prepare($queryInsert);

            $queryDelete = "
                DELETE FROM t_usuario_columnas_moverdividir
                WHERE idusuario = :idusuario
                  AND field = :field
            ";

            $stmtDelete = $conexion->prepare($queryDelete);

            /*
            Insertar campos nuevos
            */
            foreach ($fieldsNuevos as $fieldNuevo) {

                if (!in_array($fieldNuevo, $fieldsActuales)) {

                    $resultInsert = $stmtInsert->execute([
                        ':idusuario' => $idusuario,
                        ':field' => $fieldNuevo
                    ]);

                    if (!$resultInsert) {
                        $mensaje = 'No se pudo registrar una columna';
                        $continuar = false;
                        break;
                    }
                }
            }

            /*
            Eliminar campos que ya no llegaron
            */
            if ($continuar) {
                foreach ($fieldsActuales as $fieldActual) {

                    if (!in_array($fieldActual, $fieldsNuevos)) {

                        $resultDelete = $stmtDelete->execute([
                            ':idusuario' => $idusuario,
                            ':field' => $fieldActual
                        ]);

                        if (!$resultDelete) {
                            $mensaje = 'No se pudo eliminar una columna';
                            $continuar = false;
                            break;
                        }
                    }
                }
            }

            if (!$continuar) {
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
        'mensaje' => $mensaje,
        'columnas' => $columnas,
        'columnas_moverdividir' => $columnas_moverdividir
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->get('/usuario/almacenes/{idusuario}', function(Request $request, Response $response, array $args) use ($conexion){
    $idusuario = $args['idusuario'];

    $almacenes=[];
    $resultalmacen = $conexion->query("SELECT
        t_usuario_almacenes.idalmacen,
        t_almacen.codigo_almacen,
        t_almacen.almacen
        FROM
        t_usuario_almacenes
        LEFT JOIN t_almacen ON t_usuario_almacenes.idalmacen=t_almacen.idalmacen
        WHERE
        t_usuario_almacenes.idusuario=$idusuario
        UNION
        SELECT
        t_usuario.idalmacen,
        t_almacen.codigo_almacen,
        t_almacen.almacen
        FROM
        t_usuario
        INNER JOIN t_almacen ON t_usuario.idalmacen=t_almacen.idalmacen
        WHERE
        t_usuario.idusuario=$idusuario
        ORDER BY
        almacen;");
    while ($rowalmacen =  $resultalmacen ->fetch(PDO::FETCH_ASSOC)){
        $almacenes[]=array(
            'idalmacen'=>(int)$rowalmacen['idalmacen'],
            'codigo_almacen'=>$rowalmacen['codigo_almacen'],
            'almacen'=>$rowalmacen['almacen']
        );
    }

    $response->getBody()->write(json_encode(array(
        'estado' => 'Exito',
        'codigo' => 200,
        'mensaje' => 'Todo correcto',
        'almacenes' => $almacenes
    )));
    return $response->withHeader('Content-Type', 'application/json');


})->add($verifyToken);

$app->put('/usuarios/columnas_pedido/{idusuario}', function(Request $request, Response $response, array $args) use ($conexion) {

    $idusuario = $args['idusuario'] ?? null;

    $codigo = 400;
    $status = 'Error';
    $mensaje = 'No se guardó la información';

    $columnas = [];
    $columnas_pedido = [];

    $continuar = true;

    try {

        /*
        |--------------------------------------------------------------------------
        | Leer parámetros
        |--------------------------------------------------------------------------
        */
        $params = json_decode((string) $request->getBody(), true);

        if (!is_array($params)) {
            $mensaje = 'No se recibieron columnas válidas';
            $continuar = false;
        }

        if ($continuar) {
            $columnas = $params;
        }

        /*
        |--------------------------------------------------------------------------
        | Validaciones básicas
        |--------------------------------------------------------------------------
        */
        if ($continuar && empty($idusuario)) {
            $mensaje = 'No se recibió el usuario';
            $continuar = false;
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener columnas actuales del usuario
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $queryActuales = "
                SELECT field
                FROM t_usuario_columnas_pedido
                WHERE idusuario = :idusuario
            ";

            $stmtActuales = $conexion->prepare($queryActuales);

            $stmtActuales->execute([
                ':idusuario' => $idusuario
            ]);

            while ($row = $stmtActuales->fetch(PDO::FETCH_ASSOC)) {
                $columnas_pedido[] = [
                    'field' => $row['field']
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Preparar arrays simples para comparar
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $fieldsNuevos = [];
            $fieldsActuales = [];

            foreach ($columnas as $columna) {
                $field = $columna["field"] ?? '';

                if (trim($field) !== '') {
                    $fieldsNuevos[] = $field;
                }
            }

            foreach ($columnas_pedido as $columnaActual) {
                $field = $columnaActual["field"] ?? '';

                if (trim($field) !== '') {
                    $fieldsActuales[] = $field;
                }
            }

            /*
            Evita duplicados si el frontend manda el mismo field más de una vez.
            */
            $fieldsNuevos = array_values(array_unique($fieldsNuevos));
            $fieldsActuales = array_values(array_unique($fieldsActuales));
        }

        /*
        |--------------------------------------------------------------------------
        | Insertar y eliminar diferencias
        |--------------------------------------------------------------------------
        */
        if ($continuar) {

            $conexion->beginTransaction();

            $queryInsert = "
                INSERT INTO t_usuario_columnas_pedido (
                    idusuario,
                    field
                ) VALUES (
                    :idusuario,
                    :field
                )
            ";

            $stmtInsert = $conexion->prepare($queryInsert);

            $queryDelete = "
                DELETE FROM t_usuario_columnas_pedido
                WHERE idusuario = :idusuario
                  AND field = :field
            ";

            $stmtDelete = $conexion->prepare($queryDelete);

            /*
            Insertar campos nuevos
            */
            foreach ($fieldsNuevos as $fieldNuevo) {

                if (!in_array($fieldNuevo, $fieldsActuales)) {

                    $resultInsert = $stmtInsert->execute([
                        ':idusuario' => $idusuario,
                        ':field' => $fieldNuevo
                    ]);

                    if (!$resultInsert) {
                        $mensaje = 'No se pudo registrar una columna';
                        $continuar = false;
                        break;
                    }
                }
            }

            /*
            Eliminar campos que ya no llegaron
            */
            if ($continuar) {
                foreach ($fieldsActuales as $fieldActual) {

                    if (!in_array($fieldActual, $fieldsNuevos)) {

                        $resultDelete = $stmtDelete->execute([
                            ':idusuario' => $idusuario,
                            ':field' => $fieldActual
                        ]);

                        if (!$resultDelete) {
                            $mensaje = 'No se pudo eliminar una columna';
                            $continuar = false;
                            break;
                        }
                    }
                }
            }

            if (!$continuar) {
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
        'mensaje' => $mensaje,
        'columnas' => $columnas,
        'columnas_pedido' => $columnas_pedido
    );

    $response->getBody()->write(json_encode($resultado));

    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->put('/cambiarclientealmacen', function(Request $request, Response $response, array $args) use ($conexion){
    $decoded_array = $request->getAttribute('auth') ?: [];

    $params = json_decode((string) $request->getBody(),true);

    $decoded_array["idcliente_almacen"]=$params['idcliente_almacen'];

    $key = jwt_key;
    $token = JWT::encode($decoded_array, $key, 'HS256');

    $status="Exito";
    $codigo=200;
    $mensaje="Cambio de cliente de exitoso";

    $resultado=array(
        'estado'=>$status,
        'codigo'=>$codigo,
        'mensaje'=>$mensaje,
        'token'=>$token
    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

$app->put('/cambiaralmacen', function(Request $request, Response $response, array $args) use ($conexion){
    $decoded_array = $request->getAttribute('auth') ?: [];

    $params = json_decode((string) $request->getBody(),true);

    $decoded_array["idalmacen"]=$params['idalmacen'];
    $timezoneContext = DateTimeService::warehouseTimezoneContext(
        $conexion,
        $params['idalmacen'],
        $decoded_array["idempresa"] ?? null
    );
    $decoded_array["idciudad"]=$timezoneContext['idciudad'] ?: $params['idciudad'];
    $decoded_array["timezone_name"]=$timezoneContext['timezone_name'];
    $decoded_array["utc_offset_minutos"]=$timezoneContext['utc_offset_minutos'];

    $key = jwt_key;
    $token = JWT::encode($decoded_array, $key, 'HS256');

    $status="Exito";
    $codigo=200;
    $mensaje="Cambio de almacen exitoso";

    $resultado=array(
        'estado'=>$status,
        'codigo'=>$codigo,
        'mensaje'=>$mensaje,
        'token'=>$token
    );

    $response->getBody()->write(json_encode($resultado));
    return $response->withHeader('Content-Type', 'application/json');

})->add($verifyToken);

function getPermisos($idusuario, $conexion){
    $permisos=[];
    $resultpermisos = $conexion->query("SELECT
        idmodulo,
        IFNULL(lectura,0) as lectura,
        IFNULL(escritura,0) as escritura
        FROM
        t_usuariomodulo
        WHERE idusuario=$idusuario;");
    while ($rowpermisos =  $resultpermisos ->fetch(PDO::FETCH_ASSOC)){
        $permisos[]=array(
            'idmodulo'=>(int)$rowpermisos['idmodulo'],
            'lectura'=> boolval($rowpermisos['lectura']),
            'escritura'=> boolval($rowpermisos['escritura'])
        );
    }

    return $permisos;
}

function procesarPermisos(array $nodes) {
    $data=[];
    foreach ($nodes as $node) {
        $data[]=array(
            'idmodulo'=>$node['data']['id'],
            'modulo'=>$node['data']['name'],
            'lectura'=>$node['data']['lectura'],
            'escritura'=>$node['data']['escritura']
        );
        if (isset($node['children']) && is_array($node['children'])) {
            $data = array_merge($data, procesarPermisos($node['children']));
        }
    }
    return $data;
}

function savePermisos($idusuario, $permisos, $conexion){
    $query_permisos='';
    $cargado= procesarPermisos($permisos);
    $procesar=[];
    for($pp=0;$pp<count($cargado);$pp++){
        if($cargado[$pp]["lectura"]){
            $escriturag= isset($cargado[$pp]["escritura"]) ? (int)$cargado[$pp]["escritura"] : 0;


            $idusuariomodulo=0;
            $resultpermisos = $conexion->query("SELECT idusuariomodulo FROM t_usuariomodulo WHERE idusuario=$idusuario AND idmodulo=".$cargado[$pp]["idmodulo"].";");
            while ($rowpermisos =  $resultpermisos ->fetch(PDO::FETCH_ASSOC)){
                $idusuariomodulo=(int)$rowpermisos['idusuariomodulo'];
            }

            if($idusuariomodulo==0){
                $query_permisos=$query_permisos."INSERT INTO t_usuariomodulo (idusuario,    idmodulo,                      lectura, escritura)
                                                                      VALUES ($idusuario,   ".$cargado[$pp]["idmodulo"].", 1,       $escriturag);";
            }else{
                $query_permisos=$query_permisos."UPDATE t_usuariomodulo SET escritura=$escriturag WHERE idusuariomodulo=$idusuariomodulo;";
            }



            array_push($procesar, $cargado[$pp]);
        }else{
            $idusuariomodulo=0;
            $resultpermisos = $conexion->query("SELECT idusuariomodulo FROM t_usuariomodulo WHERE idusuario=$idusuario AND idmodulo=".$cargado[$pp]["idmodulo"].";");
            while ($rowpermisos =  $resultpermisos ->fetch(PDO::FETCH_ASSOC)){
                $idusuariomodulo=(int)$rowpermisos['idusuariomodulo'];
            }
            if($idusuariomodulo>0){
                $query_permisos=$query_permisos."DELETE FROM t_usuariomodulo WHERE idusuariomodulo=$idusuariomodulo;";
            }
        }
    }

    return $query_permisos;
}

function saveUsuarioAlmacenes($idusuario, $almacenes, $conexion){
    $query_usuarios_almacenes='';
    for($aa=0;$aa<count($almacenes);$aa++){
        if($almacenes[$aa]["almacen_marcado"]=='true'){
            $existe=0;
            $resultexiste = $conexion->query("SELECT idusuario_almacenes FROM t_usuario_almacenes WHERE idusuario=$idusuario AND idalmacen=".$almacenes[$aa]["idalmacen"].";");
            while ($rowexiste =  $resultexiste ->fetch(PDO::FETCH_ASSOC)){
                $existe=(int)$rowexiste['idusuario_almacenes'];
            }
            if($existe==0){
                $query_usuarios_almacenes=$query_usuarios_almacenes."INSERT INTO t_usuario_almacenes (idusuario, idalmacen) VALUES ($idusuario, ".$almacenes[$aa]["idalmacen"].");";
            }
        }else{
            $existe=0;
            $resultexiste = $conexion->query("SELECT idusuario_almacenes FROM t_usuario_almacenes WHERE idusuario=$idusuario AND idalmacen=".$almacenes[$aa]["idalmacen"].";");
            while ($rowexiste =  $resultexiste ->fetch(PDO::FETCH_ASSOC)){
                $existe=(int)$rowexiste['idusuario_almacenes'];
            }
            if($existe>0){
                $query_usuarios_almacenes=$query_usuarios_almacenes."DELETE FROM t_usuario_almacenes WHERE idusuario_almacenes=$existe;";
            }
        }

    }
    return $query_usuarios_almacenes;
}

function generateCode ($length) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $code = substr(str_shuffle($permitted_chars), 0, $length);
    return strtoupper($code);
}
