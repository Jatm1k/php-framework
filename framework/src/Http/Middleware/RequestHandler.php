<?php

namespace Jatmy\Framework\Http\Middleware;

use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [
        Authenticate::class,
        Success::class,
    ];
    public function handle(Request $request): Response
    {
        if(empty($this->middlewares)) {
            return new Response("Server error", 500);
        }
        /** @var MiddlewareInterface $middleware */
        $middleware = array_shift($this->middlewares);

        $response = (new $middleware)->process($request, $this);
        return $response;
    }
}
