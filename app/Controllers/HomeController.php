<?php

namespace App\Controllers;

use Jatmy\Framework\Http\Response;

class HomeController
{
    public function index(): Response
    {
        $content = 'Hello World';
        return new Response($content);
    }
}
