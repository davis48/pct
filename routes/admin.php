<?php

use App\Http\Controllers\Admin\AdminSpecialController;
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

// Dashboard route with proper admin.dashboard name
Route::get('/dashboard', [AdminSpecialController::class, 'dashboard'])->name('dashboard');

// Interface admin moderne
Route::get('/', [AdminSpecialController::class, 'dashboard'])->name('special.dashboard');
Route::get('/special-dashboard', [AdminSpecialController::class, 'dashboard'])->name('special.dashboard');
Route::get('/statistics', [AdminSpecialController::class, 'statistics'])->name('special.statistics');
Route::get('/system-info', [AdminSpecialController::class, 'systemInfo'])->name('special.system-info');
Route::get('/maintenance', [AdminSpecialController::class, 'maintenance'])->name('special.maintenance');
Route::post('/backup', [AdminSpecialController::class, 'backup'])->name('special.backup');
Route::get('/logs', [AdminSpecialController::class, 'logs'])->name('special.logs');
Route::get('/performance', [AdminSpecialController::class, 'performance'])->name('special.performance');

// Routes pour les actions administratives spécifiques
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/users', [AdminSpecialController::class, 'getUsers'])->name('users');
    Route::get('/documents', [AdminSpecialController::class, 'getDocuments'])->name('documents');
    Route::get('/requests', [AdminSpecialController::class, 'getRequests'])->name('requests');
    Route::get('/agents', [AdminSpecialController::class, 'getAgents'])->name('agents');
});
