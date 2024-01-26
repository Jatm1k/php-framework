<?php

namespace Jatmy\Framework\Http\Middleware;

use App\Services\Hello;
use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

class Success implements MiddlewareInterface
{
    public function __construct(
        private Hello $hello,
    ) {
    }
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return new Response($this->hello->sayHello());
    }
}
