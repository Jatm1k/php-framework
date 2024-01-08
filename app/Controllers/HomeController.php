<?php

namespace App\Controllers;

use App\Services\Hello;
use Jatmy\Framework\Http\Response;

class HomeController
{
    public function __construct(
        private Hello $hello,
    ) {
    }
    public function index(): Response
    {
        $content = $this->hello->sayHello();
        return new Response($content);
    }
}
