<?php

namespace Jatmy\Framework\Http\Middleware;

use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
    public function injectMiddleware(array $middleware): void;
}
