<?php

namespace App\Controllers;

use Jatmy\Framework\Http\Response;
use Jatmy\Framework\Controller\AbstractController;

class PostController extends AbstractController
{
    public function show(int $id): Response
    {
        return $this->render('posts/show', ['id' => $id]);
    }

    public function create(): Response
    {
        return $this->render('posts/create');
    }
}
