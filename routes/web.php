<?php

use App\Controllers\HomeController;
use Jatmy\Framework\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
];
