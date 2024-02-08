<?php

namespace Jatmy\Framework\Http\Middleware;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

use function FastRoute\simpleDispatcher;
use Jatmy\Framework\Http\Exceptions\RouteNotFoundException;
use Jatmy\Framework\Http\Middleware\RequestHandlerInterface;
use Jatmy\Framework\Http\Exceptions\MethodNotAllowedException;

class ExtractRouteInfo implements MiddlewareInterface
{
    public function __construct(
        private array $routes,
    ) {
    }
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath()
        );
        switch($routeInfo[0]) {
            case Dispatcher::FOUND:
                $request->setRouteHandler($routeInfo[1][0]);
                $request->setRouteArgs($routeInfo[2]);
                $handler->injectMiddleware($routeInfo[1][1]);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $message = "Method not allowed. Allowed methods: {$allowedMethods}";
                $exception = new MethodNotAllowedException($message);
                $exception->setStatusCode(405);
                throw $exception;
            default:
                $exception = new RouteNotFoundException('Route not found');
                $exception->setStatusCode(404);
                throw $exception;
        }
        return $handler->handle($request);
    }
}
