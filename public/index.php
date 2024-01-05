<?php
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

use Jatmy\Framework\Http\Kernel;
use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Routing\Router;

$request = Request::createFromGlobals();
$router = new Router();
$kernel = new Kernel($router);
$response = $kernel->handle($request);
$response->send();
