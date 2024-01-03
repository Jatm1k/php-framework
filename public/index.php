<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

$request = Request::createFromGlobals();
$response = new Response('test');
$response->send();
