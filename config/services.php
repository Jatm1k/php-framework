<?php

use Jatmy\Framework\Http\Kernel;
use League\Container\Container;
use Jatmy\Framework\Routing\Router;
use Jatmy\Framework\Routing\RouterInterface;

$container = new Container();

$container->add(RouterInterface::class, Router::class);
$container->add(Kernel::class)->addArgument(RouterInterface::class);

return $container;
