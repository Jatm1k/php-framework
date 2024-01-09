<?php

namespace App\Controllers;

use App\Services\Hello;
use Jatmy\Framework\Http\Response;
use Twig\Environment;

class HomeController
{
    public function __construct(
        private Hello $hello,
        private readonly Environment $twig,
    ) {
    }
    public function index(): Response
    {
        $content = $this->hello->sayHello();
        return new Response($content);
    }
}
