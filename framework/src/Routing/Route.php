<?php

namespace Jatmy\Framework\Routing;

class Route
{
    public static function get(string $uri, array|callable|string $handler): array
    {
        return ['GET', $uri, $handler];
    }

    public static function post(string $uri, array|callable|string $handler): array
    {
        return ['POST', $uri, $handler];
    }
}