<?php

class PiramideUploader {

	private $info_file;

	public function __construct() {
		$this->info_file = array();
	}

	public function upload($name, $file, $directory, $types_allowed, $force_name = NULL, $indice = 0) {
		$this->info_file = array(
			"name"			=> $_FILES["$file"]["name"][$indice],
			//"complete_name" => $name . "-" . time() . "-" . $_FILES["$file"]["name"][0],
                        "complete_name" => $_FILES["$file"]["name"][$indice],
			"temporal_name" => $_FILES["$file"]["tmp_name"][$indice],
			"type"			=> $_FILES["$file"]["type"][$indice],
			"size"			=> $_FILES["$file"]["size"][$indice],
			"error"			=> $_FILES["$file"]["error"][$indice]
		);

		if ($force_name != NULL) {
			$this->info_file["complete_name"] = $name;
		}

		if (is_uploaded_file($this->info_file["temporal_name"])) {
			if (is_array($types_allowed) && in_array($this->info_file["type"], $types_allowed)) {
                            if($this->info_file["size"]<=10485760){
                                if(!is_dir($directory)){
					$dir = mkdir($directory, 0777, true);
				}else{
					$dir = true;
				}
				
				if($dir){
					$mpf = move_uploaded_file($this->info_file["temporal_name"], $directory . "/" . $this->info_file["complete_name"]);
			
					if($mpf){
						$uploaded = true;
					}else{
						$uploaded = false;
						$error = "The file has not moved";
					}
				}else{
					$upload = false;
					$error = "The directory does not exist";
				}
                            }else{
                                $uploaded = false;
				$error = "El limite de tamaño es de 10MB";
                            }
				
					
			} else {
				$uploaded = false;
				$error = "El tipo de archivo no es permitido ".$this->info_file["type"];
			}
		} else {
			$uploaded = false;
			$error = "The file has not been uploaded";
		}
		
		$response = array("uploaded" => $uploaded, "error" => null);
		
		if(isset($uploaded) && isset($error)){
			$response = array("uploaded" => $uploaded, "error" => $error);
		}
		
		return $response;
	}

	public function getInfoFile() {
		return $this->info_file;
	}

}

?>