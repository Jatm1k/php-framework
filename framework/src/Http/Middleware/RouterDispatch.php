<?php

namespace Jatmy\Framework\Http\Middleware;

use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;
use Jatmy\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container,
    ) {
    }
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
        return call_user_func_array($routeHandler, $vars);
    }
}
