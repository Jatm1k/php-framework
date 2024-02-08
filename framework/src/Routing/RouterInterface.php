<?php

namespace Jatmy\Framework\Routing;

use League\Container\Container;
use Jatmy\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request, Container $container): array;

}
