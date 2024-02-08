<?php

namespace Jatmy\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Jatmy\Framework\Controller\AbstractController;
use Jatmy\Framework\Http\Exceptions\MethodNotAllowedException;
use Jatmy\Framework\Http\Exceptions\RouteNotFoundException;
use Jatmy\Framework\Http\Request;
use League\Container\Container;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    public function dispatch(Request $request, Container $container): array
    {
        $handler = $request->getRouteHandler();
        $vars = $request->getRouteArgs();
        if(is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);
            $handler = [$controller, $method];
        } elseif(is_string($handler)) {
            $controller = $container->get($handler);
            $handler = [$controller, '__invoke'];
        }
        if(is_subclass_of($controller, AbstractController::class)) {
            $controller->setRequest($request);
        }
        return [$handler, $vars];
    }
}
