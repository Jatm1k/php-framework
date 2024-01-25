<?php

namespace Jatmy\Framework\Http\Middleware;

use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

class Authenticate implements MiddlewareInterface
{
    private bool $isAuthenticated = true;
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if(!$this->isAuthenticated) {
            return new Response("Unauthorized", 401);
        }
        return $handler->handle($request);
    }
}
