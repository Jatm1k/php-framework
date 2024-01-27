<?php

namespace App\Controllers;

use Jatmy\Framework\Controller\AbstractController;
use Jatmy\Framework\Http\Response;

class RegisterController extends AbstractController
{
    public function __construct(
    ) {
    }
    public function index(): Response
    {
        return $this->render('register');
    }

    public function store()
    {
        //
    }
}
