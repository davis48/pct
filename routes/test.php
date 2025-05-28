<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-middleware', function () {
    return 'Middleware test successful!';
})->middleware(['auth', 'role:agent']);
