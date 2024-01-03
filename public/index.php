<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Jatmy\Framework\Http\Kernel;
use Jatmy\Framework\Http\Request;

$request = Request::createFromGlobals();
$kernel = new Kernel();
$response = $kernel->handle($request);
$response->send();
