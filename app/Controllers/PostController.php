<?php

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use Jatmy\Framework\Http\Response;
use Jatmy\Framework\Controller\AbstractController;
use Jatmy\Framework\Http\Request;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $service,
    ) {
        
    }
    public function show(int $id): Response
    {
        $post = $this->service->findOrFail($id);

        return $this->render('posts/show', ['post' => $post]);
    }

    public function create(): Response
    {
        return $this->render('posts/create');
    }

    public function store()
    {
        $post = Post::create(
            $this->request->input('title'),
            $this->request->input('body')
        );
        $postId = $this->service->save($post);
        dd($postId);
    }
}
