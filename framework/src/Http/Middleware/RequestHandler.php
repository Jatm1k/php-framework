<?php

namespace Jatmy\Framework\Http\Middleware;

use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [
        ExtractRouteInfo::class,
        StartSession::class,
        RouterDispatch::class,
    ];

    public function __construct(
        private ContainerInterface $container,
    ) {
    }
    public function handle(Request $request): Response
    {
        if(empty($this->middlewares)) {
            return new Response("Server error", 500);
        }
        $middlewareClass = array_shift($this->middlewares);
        
        /** @var MiddlewareInterface $middleware */
        $middleware = $this->container->get($middlewareClass);

        $response = $middleware->process($request, $this);
        return $response;
    }

    public function injectMiddleware(array $middleware): void
    {
        $this->middlewares = array_merge($middleware, $this->middlewares);
    }
}
