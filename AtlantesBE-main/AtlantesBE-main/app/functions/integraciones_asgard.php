<?php
class IntegracionesASAGRD
{
    private $conexion_asgard;
    private $mensajeerror_conexion;
    
    private $idusuario_asgard_atlantes;
    
    private $ftpHost_asgard;
    private $ftpUser_asgard;
    private $ftpPass_asgard;
    private $ftpPort_asgard;
    private $ftpLocal_asgard;
    
    private $debug = true;
    
    public function __construct()
    {
        if ($this->debug) {
            @ini_set('display_errors', 1);
            @ini_set('display_startup_errors', 1);
            @error_reporting(E_ALL);
        }
        
        require_once __DIR__ . "/../.env.php";
        
        $hostname_asgard    = host_asgard;
        $username_asggard   = user_asgard;
        $password_asgard    = password_asgard;
        $dbname_asgard      = database_asgard;
        
        $this->idusuario_asgard_atlantes=idusuario_asgard_atlantes;
        
        $this->conexion_asgard = null;
        $this->mensajeerror_conexion='';
        try {
            $this->conexion_asgard = new PDO("mysql:host=$hostname_asgard;dbname=$dbname_asgard", $username_asggard, $password_asgard);
            $this->conexion_asgard->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
        } catch (PDOException $ea) {
            $this->mensajeerror_conexion= $ea->getMessage();
        }
        
        $this->ftpHost_asgard=ftpHost_asgard;
        $this->ftpUser_asgard=ftpUser_asgard;
        $this->ftpPass_asgard=ftpPass_asgard;
        $this->ftpPort_asgard=ftpPort_asgard;
        $this->ftpLocal_asgard=ftpLocal_asgard;
    }
    
    
    public function lecturaPRM($datosOCR, $numero_prm, $partida, $idcliente_asgard){
        $error=false;
        $mensajeerror="";
        $idocr_prm_atlantes=0;
        $respuesta=[];
        if(isset($this->conexion_asgard)){
            
            $idprm_atlantes=0;
            $result = $this->conexion_asgard->query("SELECT
                idprm_atlantes
                FROM 
                ocr_prm_atlantes
                WHERE 
                idcliente = $idcliente_asgard
                AND partida='$partida'
                AND numero_prm='$numero_prm';");
            while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
                $idprm_atlantes=(int)$row['idprm_atlantes'];
            }
            
            if($idprm_atlantes>0){
                $error=true;
                $mensajeerror="Ya existe un registro con los datos del PRM";
            }else{
                $query="INSERT INTO ocr_prm_atlantes (idcliente,    partida,    numero_prm,     factura,    descripcion,    valor,  created_at) 
                                              VALUES (:idcliente,   :partida,   :numero_prm,    :factura,   :descripcion,   :valor, CURRENT_TIMESTAMP());";

                $stmt = $this->conexion_asgard->prepare($query);
                $stmt->bindParam(':idcliente', $idcliente_asgard);
                $stmt->bindParam(':partida', $partida);
                $stmt->bindParam(':numero_prm', $numero_prm);
                $stmt->bindParam(':factura', $datosOCR['factura']);
                $stmt->bindParam(':descripcion', $datosOCR['descripcion']);
                $stmt->bindParam(':valor', $datosOCR['total']);

                $stmt->execute();
                
                

                if ($stmt) {
                    $idocr_prm_atlantes = $this->conexion_asgard->lastInsertId(); // ← aquí
                } else {
                    $error=true;
                    $mensajeerror="No se pudo insertar la informacion";
                }
            }
            
                
        }else{
            $error=true;
            $mensajeerror=$this->mensajeerror_conexion;
        }
        
        return array(
            'error'=>$error,
            'mensajeerror'=>$mensajeerror,
            'respuesta'>=$respuesta,
            'idocr_prm_atlantes'=>$idocr_prm_atlantes
        );
    }
    
    public function copyPRM_file($origin, $destination)
    {
        if ($this->ftpLocal_asgard) {
            return $this->copyLocal($origin, $destination);
        }

        $resp = ['error'=>true,'mensaje_error'=>''];

        if (empty($this->ftpHost_asgard) || empty($this->ftpUser_asgard) || empty($this->ftpPass_asgard)) {
            $resp['mensaje_error'] = 'Configuración SFTP incompleta (host/usuario/contraseña).';
            return $resp;
        }
        if (!is_readable($origin)) {
            $resp['mensaje_error'] = "El archivo de origen no existe o no es legible: {$origin}";
            return $resp;
        }

        // Autoload si hace falta
        if (!class_exists('\phpseclib\Net\SFTP')) {
            $autoload = __DIR__ . '/../vendor/autoload.php';
            if (is_file($autoload)) require_once $autoload;
        }
        if (!class_exists('\phpseclib\Net\SFTP')) {
            $resp['mensaje_error'] = 'phpseclib no encontrado (\\phpseclib\\Net\\SFTP).';
            return $resp;
        }

        $sftp = new \phpseclib\Net\SFTP($this->ftpHost_asgard, (int)($this->ftpPort_asgard ?: 22), 20);
        if (!$sftp || !$sftp->login($this->ftpUser_asgard, $this->ftpPass_asgard)) {
            $resp['mensaje_error'] = 'No se pudo iniciar sesión SFTP.';
            return $resp;
        }

        $remotePath = str_replace("\\", "/", $destination);
        $remoteDir  = rtrim(dirname($remotePath), "/");

        // 1) Si el dir ya existe (absoluto), no intentes crearlo
        $dirOK = true;
        if ($remoteDir !== '' && $remoteDir !== '.') {
            if (!$this->sftp_dir_exists($sftp, $remoteDir)) {
                // 2) Intentar crearlo (absoluto)
                $dirOK = $sftp->mkdir($remoteDir, -1, true);
                if (!$dirOK) {
                    // 3) Fallback: probar la MISMA ruta pero RELATIVA (sin '/')
                    $relativeDir = ltrim($remoteDir, '/');
                    if ($relativeDir !== $remoteDir) {
                        if ($this->sftp_dir_exists($sftp, $relativeDir) || $sftp->mkdir($relativeDir, -1, true)) {
                            // si el relativo funciona, cambiemos también el path destino a relativo
                            $remotePath = ltrim($remotePath, '/');
                            $remoteDir  = $relativeDir;
                            $dirOK = true;
                        }
                    }
                }
            }
        }

        if (!$dirOK) {
            $resp['mensaje_error'] = "No se pudo acceder/crear el directorio remoto: {$remoteDir}. ".
                                     "Prueba con ruta relativa al home del usuario (sin el slash inicial) ".
                                     "o verifica permisos/chroot en el servidor SFTP.";
            return $resp;
        }

        // Subir (si dir existe, directo)
        if (!$sftp->put($remotePath, $origin, \phpseclib\Net\SFTP::SOURCE_LOCAL_FILE)) {
            $resp['mensaje_error'] = "Fallo al subir por SFTP {$origin} -> {$remotePath}. ".
                                     "Si la ruta empieza con '/', prueba sin el slash inicial (relativa al home).";
            return $resp;
        }

        return ['error'=>false,'mensaje_error'=>''];
    }

    private function copyLocal($origin, $destination)
    {
        $resp = ['error' => true, 'mensaje_error' => ''];

        if (!is_readable($origin)) {
            $resp['mensaje_error'] = "El archivo de origen no existe o no es legible: {$origin}";
            return $resp;
        }

        $destDir = rtrim(dirname($destination), DIRECTORY_SEPARATOR);
        if ($destDir !== '' && $destDir !== '.' && !is_dir($destDir)) {
            if (!@mkdir($destDir, 0775, true)) {
                $resp['mensaje_error'] = "No se pudo crear la carpeta destino: {$destDir}";
                return $resp;
            }
        }

        if (!@copy($origin, $destination)) {
            $resp['mensaje_error'] = "No se pudo copiar {$origin} a {$destination}";
            return $resp;
        }

        @chmod($destination, 0664);
        return ['error' => false, 'mensaje_error' => ''];
    }

    // Ya no se usa en SFTP, pero lo dejo por si vuelves a FTP en el futuro.
    private function ftp_mkdir_recursive($ftp, $path)
    {
        $path = ltrim($path, '/');
        if ($path === '' || $path === '.') return true;
        $parts = array_filter(explode('/', $path));
        $cwd   = @ftp_pwd($ftp);

        foreach ($parts as $part) {
            if (!@ftp_chdir($ftp, $part)) {
                if (!@ftp_mkdir($ftp, $part)) { if ($cwd) @ftp_chdir($ftp, $cwd); return false; }
                if (!@ftp_chdir($ftp, $part)) { if ($cwd) @ftp_chdir($ftp, $cwd); return false; }
            }
        }
        if ($cwd !== false) @ftp_chdir($ftp, $cwd);
        return true;
    }
    
    private function sftp_dir_exists(\phpseclib\Net\SFTP $sftp, $path) {
        $path = rtrim($path, '/');
        if ($path === '' || $path === '.') return true;

        $cwd = $sftp->pwd();
        if ($sftp->chdir($path)) {
            // volver a donde estábamos
            if ($cwd !== false) { $sftp->chdir($cwd); }
            return true;
        }
        // volver por si cambió parcialmente
        if ($cwd !== false) { $sftp->chdir($cwd); }
        return false;
    }
    
    public function guardarFechaIngresoVehiculos($id_asgard, $partida, $fecha){
        if(isset($this->conexion_asgard)){
            $this->conexion_asgard->query("UPDATE dav_casos SET fechaverificacionfrv='$fecha', verificacionfrv=1, idusuariofechaverificacionfrvmodificado='".$this->idusuario_asgard_atlantes."', fechaverificacionfrvmodificado=CURRENT_TIMESTAMP() WHERE idcliente=$id_asgard AND IF(SUBSTRING_INDEX(dav_casos.pedido, '-', -1)=dav_casos.pedido,dav_casos.pedido,LEFT(dav_casos.pedido,CHAR_LENGTH(dav_casos.pedido)-(CHAR_LENGTH(SUBSTRING_INDEX(dav_casos.pedido, '-', -1))+1)))='$partida';");
        }
        
        //return "UPDATE dav_casos SET fechaverificacionfrv='$fecha', verificacionfrv=1, idusuariofechaverificacionfrvmodificado='".$this->idusuario_asgard_atlantes."', fechaverificacionfrvmodificado=CURRENT_TIMESTAMP() WHERE idcliente=$id_asgard AND IF(SUBSTRING_INDEX(dav_casos.pedido, '-', -1)=dav_casos.pedido,dav_casos.pedido,LEFT(dav_casos.pedido,CHAR_LENGTH(dav_casos.pedido)-(CHAR_LENGTH(SUBSTRING_INDEX(dav_casos.pedido, '-', -1))+1)))='$partida';";
    }
    
}