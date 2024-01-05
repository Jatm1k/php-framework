<?php

use App\Controllers\HomeController;
use App\Controllers\PostController;
use Jatmy\Framework\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/posts/{id}', [PostController::class, 'show']),
];
