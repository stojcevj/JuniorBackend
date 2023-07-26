<?php

require_once __DIR__ . '/vendor/autoload.php';

use Api\Controller\ProductController;
use Api\Main;
use Api\Config;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: *");
    header("HTTP/1.1 200 OK");
    die();
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$_POST = json_decode(file_get_contents("php://input"), true);

$router = new Api\Router\Router();

$router
    ->get('/backend/', [ProductController::class, 'index'])
    ->post('/backend/', [ProductController::class, 'massDelete'])
    ->post('/backend/addProduct', [ProductController::class, 'addProduct']);

(new Main($router,
            [
                'uri' => $_SERVER['REQUEST_URI'],
                'method' => $_SERVER['REQUEST_METHOD']
            ],
            new Config($_ENV)
))->run();


