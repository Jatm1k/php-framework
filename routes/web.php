<?php

use Jatmy\Framework\Routing\Route;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Controllers\InvokeController;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/invoke', InvokeController::class),
    Route::get('/posts/create', [PostController::class, 'create']),
    Route::get('/posts/{id}', [PostController::class, 'show']),
    Route::post('/posts', [PostController::class, 'store']),
];
