<?php

namespace App\Controllers;

use Jatmy\Framework\Http\Response;

class InvokeController
{
    public function __invoke(): Response
    {
        $content = 'it works!';
        return new Response($content);
    }
}
