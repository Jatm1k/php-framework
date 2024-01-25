<?php

namespace Jatmy\Framework\Http\Middleware;

use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}
