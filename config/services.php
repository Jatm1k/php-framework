<?php

use Jatmy\Framework\Http\Kernel;
use League\Container\Container;
use Jatmy\Framework\Routing\Router;
use Jatmy\Framework\Routing\RouterInterface;
use League\Container\Argument\Literal\ArrayArgument;

// Application parametrs
$routes = include BASE_PATH . '/routes/web.php';

// Application services
$container = new Container();

$container->add(RouterInterface::class, Router::class);
$container->extend(RouterInterface::class)->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

$container->add(Kernel::class)->addArgument(RouterInterface::class);

return $container;
