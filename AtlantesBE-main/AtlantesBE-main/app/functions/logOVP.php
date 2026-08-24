<?php
class logOVP
{
    public function saveLog($parametros,$query,$tipo,$tabla,$accion,$jsonovp,$idusuario,$conexion){
        $userInfo = $this->datosUsuario($idusuario,$conexion);
        
        //$jsonovp= str_replace("'", "\'", $jsonovp);

        $querylog = ("INSERT INTO log_ovp (TABLA, FECHA, NOMBRE, TEXTO, TIPO, IP_USUARIO, PC_USUARIO, ID_USUARIO, USERNAME, FILENAME, ACCION, JSON, JSONOVP, created_at,updated_at) VALUES (
        '".$tabla."', 
        CURRENT_TIMESTAMP(), 
        '".$userInfo['nombre']."', 
        '".addslashes($query)."', 
        '".$tipo."', 
        '".$userInfo['ip']."', 
        '".$userInfo['pc']."', 
        '".$userInfo['idusuario']."', 
        '".$userInfo['username']."', 
        1,
        '".$accion."', 
        '".json_encode($parametros)."', 
        '".str_replace("'", "\'", json_encode($jsonovp))."', 
        CURRENT_TIMESTAMP(),
        CURRENT_TIMESTAMP());");

        $result = $conexion->prepare($querylog);
        $result->execute();
        
        //$result = $conexion->exec($querylog);
        //$result = mysql_query($querylog);
        //return $result;
        return $querylog;
    }

    private function datosUsuario($idusuario,$conexion){
        $ip = $this->getUserIpAddr();
        $pcname = gethostname();
        $userInfo = $this->userInfo($idusuario,$conexion);
        $userInfo["pc"] = $pcname;
        $userInfo["ip"] = $ip;

        return $userInfo;
    }

    private function userInfo($idusuario,$conexion){
        $query = "SELECT idusuario,nombre,username,idciudad from t_usuario WHERE idusuario=$idusuario;";
        //$result = mysql_query($query);
        $result = $conexion->prepare($query);
        $result->execute();
        $datos = $result->fetch(PDO::FETCH_ASSOC);

        //$datos = mysql_fetch_assoc($result);
        return $datos;
    }

    private function getUserIpAddr(){
        $publicIP = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
        if(!empty($publicIP)){
            $ip = $publicIP;
        }elseif(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
