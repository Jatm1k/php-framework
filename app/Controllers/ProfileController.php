<?php

namespace App\Controllers;

use Jatmy\Framework\Controller\AbstractController;
use Jatmy\Framework\Http\Response;

class ProfileController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('profile');
    }
}
