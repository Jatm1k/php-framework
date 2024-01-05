<?php

namespace Jatmy\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Jatmy\Framework\Http\Exceptions\MethodNotAllowedException;
use Jatmy\Framework\Http\Exceptions\RouteNotFoundException;
use Jatmy\Framework\Http\Request;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    public function dispatch(Request $request): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);
        if(is_array($handler)) {
            [$controller, $method] = $handler;
            $handler = [new $controller, $method];
        } elseif(is_string($handler)) {
            $controller = $handler;
            $handler = [new $controller, '__invoke'];
        }
        return [$handler, $vars];
    }

    private function extractRouteInfo(Request $request)
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $routes = include BASE_PATH . '/routes/web.php';
            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath()
        );
        // dd($routeInfo);
        switch($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $message = "Method not allowed. Allowed methods: {$allowedMethods}";
                throw new MethodNotAllowedException($message);
            default:
                throw new RouteNotFoundException('Route not found');
        }
    }
}
