<?php

require '../vendor/autoload.php';
include_once __DIR__ . '/.env.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Auto-detect base path so routes work under /atlantes-api/public/ on WAMP
if (PHP_SAPI !== 'cli') {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $requestUri = str_replace('\\', '/', $_SERVER['REQUEST_URI'] ?? '');

    /*
     * Caso 1:
     * URL con index.php:
     * /sistemas/atlantes-api/public/index.php/login
     */
    if ($scriptName && strpos($requestUri, $scriptName) === 0) {
        $app->setBasePath($scriptName);
    } else {
        /*
         * Caso 2:
         * URL sin index.php:
         * /sistemas/atlantes-api/public/login
         */
        $basePath = dirname($scriptName);

        if ($basePath !== '/' && $basePath !== '.' && $basePath !== '\\') {
            $app->setBasePath($basePath);
        }
    }
}

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$hostname  = host;
$username  = user;
$password  = password;
$dbname    = database;
$region_db = REGION_DB;

$conexion = null;
try {
    $conexion = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
    if (!isset($region_db)) {
        $region_db = 'America/La_Paz';
    }
    $conexion->query("SET time_zone = '$region_db';");
} catch (PDOException $e) {
    echo $e->getMessage();
}
$conexion->query("SET NAMES 'utf8'");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die();
}

$archivospermitidos = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/pdf',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'text/plain',
    'application/x-zip-compressed',
    'application/octet-stream',
];
$archivospermitidos_imagenes = ['image/jpeg', 'image/png'];

require 'services/DateTimeService.php';
require 'middleware/jwt.php';

require 'functions/generarcodigocontrol.php';
require 'functions/numerosliteral.php';
require 'functions/ovp.php';
require 'functions/logOVP.php';
require 'functions/sendmail.php';
require 'functions/generarcarpetas.php';
require 'functions/integraciones_asgard.php';
require 'functions/common.php';
require 'services/BlobStorageService.php';

require 'routes.php';
