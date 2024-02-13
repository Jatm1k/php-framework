<?php
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

use App\Listeners\InternalErrorListener;
use League\Container\Container;
use Jatmy\Framework\Http\Kernel;
use Jatmy\Framework\Http\Request;
use App\Listeners\ContentLenghtListener;
use App\Listeners\HandleEntityListener;
use Jatmy\Framework\Dbal\Event\EntityPersist;
use Jatmy\Framework\Http\Events\ResponseEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

$request = Request::createFromGlobals();

/** @var Container $container */
$container = require BASE_PATH . '/config/services.php';

$eventDispatcher = $container->get(EventDispatcherInterface::class);
$eventDispatcher
->addListener(ResponseEvent::class, new InternalErrorListener())
->addListener(ResponseEvent::class, new ContentLenghtListener())
->addListener(EntityPersist::class, new HandleEntityListener());

$kernel = $container->get(Kernel::class);
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
