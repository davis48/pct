<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\RequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

// Test route to check if admin routes are loading
Route::get('/test', function() {
    return 'Admin route test works!';
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Gestion des utilisateurs
Route::resource('users', UserController::class);

// Gestion des documents
Route::resource('documents', DocumentController::class);

// Gestion des demandes
Route::resource('requests', RequestController::class);
