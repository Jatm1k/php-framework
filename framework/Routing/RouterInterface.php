<?php

namespace Jatmy\Framework\Routing;

use Jatmy\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}
