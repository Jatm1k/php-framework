<?php

use Jatmy\Framework\Routing\Route;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Controllers\LoginController;
use App\Controllers\InvokeController;
use App\Controllers\ProfileController;
use App\Controllers\RegisterController;
use Jatmy\Framework\Http\Middleware\Authenticate;
use Jatmy\Framework\Http\Middleware\Guest;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/invoke', InvokeController::class),
    Route::get('/posts/create', [PostController::class, 'create']),
    Route::get('/posts/{id}', [PostController::class, 'show']),
    Route::post('/posts', [PostController::class, 'store']),
    Route::get('/register', [RegisterController::class, 'index'], [Guest::class]),
    Route::post('/register', [RegisterController::class, 'store']),
    Route::get('/login', [LoginController::class, 'index'], [Guest::class]),
    Route::post('/login', [LoginController::class, 'store']),
    Route::post('/logout', [LoginController::class, 'destroy']),
    Route::get('/profile', [ProfileController::class, 'index'], [Authenticate::class]),
];
