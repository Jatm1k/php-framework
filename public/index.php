<?php
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

use Jatmy\Framework\Http\Kernel;
use Jatmy\Framework\Http\Request;

$request = Request::createFromGlobals();

/** @var Container $container */
$container = require BASE_PATH . '/config/services.php';

$kernel = $container->get(Kernel::class);
$response = $kernel->handle($request);
$response->send();
