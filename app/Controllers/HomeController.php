<?php

namespace App\Controllers;

use App\Services\Hello;
use Jatmy\Framework\Controller\AbstractController;
use Jatmy\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function __construct(
        private Hello $hello,
    ) {
    }
    public function index(): Response
    {
        return $this->render('home', ['name' => 'Jatmy']);
    }
}
