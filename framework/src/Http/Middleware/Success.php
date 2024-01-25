<?php

namespace Jatmy\Framework\Http\Middleware;

use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

class Success implements MiddlewareInterface
{
    private bool $isAuthenticated = false;
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return new Response('Hello!!');
    }
}
