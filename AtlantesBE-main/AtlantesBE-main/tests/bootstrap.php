<?php

// Carga el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Carga el servicio a testear directamente (sin arrancar Slim)
require_once __DIR__ . '/../app/services/BlobStorageService.php';
require_once __DIR__ . '/../app/functions/common.php';

// Constantes mínimas para que BlobStorageService no falle al instanciarse
// Los tests que necesiten configuración real definen sus propias constantes.
if (!defined('azure_blob_auth_mode'))         define('azure_blob_auth_mode',         'connection_string');
if (!defined('azure_blob_connection_string')) define('azure_blob_connection_string', '');
if (!defined('azure_blob_account_name'))      define('azure_blob_account_name',      '');
if (!defined('azure_blob_sas_token'))         define('azure_blob_sas_token',         '');
if (!defined('azure_blob_container'))         define('azure_blob_container',         '');
if (!defined('azure_blob_enabled'))           define('azure_blob_enabled',           false);
if (!defined('folder_files'))                 define('folder_files',                 sys_get_temp_dir() . DIRECTORY_SEPARATOR);
