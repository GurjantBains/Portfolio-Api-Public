<?php


header('content-type:application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods:GET,POST,OPTIONS');
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once("app\core\Router.php");
require_once("app\core\Route.php");
require_once("routes\web.php");
require_once("app\Database\Database.php");
require_once("app\controllers\AdminController.php");
require_once("app/view/view.php");

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
if(str_contains($url, 'API/Portfolio%20Api/Portfolio-Api')){
    $url = str_replace('API/Portfolio%20Api/Portfolio-Api/','',$url);
}
try {
    Router::handle($url, $method);
    exit();
}catch(Exception $e) {
    http_response_code(500);
    echo "Error : " . $e->getMessage();
}