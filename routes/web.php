<?php

use App\Controllers\HomeController;
use App\Controllers\InvokeController;
use App\Controllers\PostController;
use Jatmy\Framework\Http\Response;
use Jatmy\Framework\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/invoke', InvokeController::class),
    Route::get('/posts/{id}', [PostController::class, 'show']),
    Route::get('/hi/{name}', function (string $name) {
        return new Response('Hi ' . $name);
    }),
];
