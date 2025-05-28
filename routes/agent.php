<?php

use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\RequestController;
use App\Http\Controllers\Agent\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Agent Routes
|--------------------------------------------------------------------------
|
| Here is where you can register agent routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "agent" middleware group. Make something great!
|
*/

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartDataApi'])->name('dashboard.chart-data');
Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats'])->name('dashboard.stats');

// Actions rapides du dashboard
Route::post('/assign-next', [DashboardController::class, 'assignNext'])->name('assign-next');

// Notifications
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/count', [NotificationController::class, 'getUnreadCount'])->name('count');
    Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
});

// Gestion des demandes - routes simplifiées et fonctionnelles
Route::prefix('requests')->name('requests.')->group(function () {
    Route::get('/', [RequestController::class, 'index'])->name('index');
    Route::get('/pending', [RequestController::class, 'pending'])->name('pending');
    Route::get('/in-progress', [RequestController::class, 'inProgress'])->name('in-progress');
    Route::get('/my-assignments', [RequestController::class, 'myAssignments'])->name('my-assignments');
    Route::get('/my-completed', [RequestController::class, 'myCompleted'])->name('my-completed');
    Route::get('/reminders', [RequestController::class, 'reminders'])->name('reminders');
    
    // Routes pour traiter les demandes
    Route::get('/{id}', [RequestController::class, 'show'])->name('show');
    Route::get('/{id}/process', [RequestController::class, 'process'])->name('process');
    Route::get('/{id}/edit', [RequestController::class, 'edit'])->name('edit');
    Route::post('/{id}/update', [RequestController::class, 'update'])->name('update');
    Route::post('/{id}/complete', [RequestController::class, 'complete'])->name('complete');
    Route::post('/{id}/reject', [RequestController::class, 'reject'])->name('reject');
    Route::post('/{id}/assign', [RequestController::class, 'assign'])->name('assign');
    
    // Gestion des pièces jointes
    Route::get('/attachment/{id}/download', [RequestController::class, 'downloadAttachment'])->name('attachment.download');
    Route::get('/citizen-attachment/{requestId}/{fileIndex}/download', [RequestController::class, 'downloadCitizenAttachment'])->name('citizen-attachment.download');
    Route::get('/citizen-attachment/{requestId}/debug', [RequestController::class, 'debugCitizenAttachments'])->name('citizen-attachment.debug');
    Route::delete('/attachment/{id}', [RequestController::class, 'deleteAttachment'])->name('attachment.delete');
});

// Routes pour les citoyens
use App\Http\Controllers\Agent\CitizensController;

Route::prefix('citizens')->name('citizens.')->group(function () {
    Route::get('/', [CitizensController::class, 'index'])->name('index');
    Route::get('/{citizen}', [CitizensController::class, 'show'])->name('show');
});

// Gestion des documents
use App\Http\Controllers\Agent\DocumentsController;

Route::prefix('documents')->group(function () {
    Route::get('/', [DocumentsController::class, 'index'])->name('documents.index');
    Route::get('/{request}', [DocumentsController::class, 'show'])->name('documents.show');
    Route::get('/attachment/{attachment}/download', [DocumentsController::class, 'downloadAttachment'])->name('documents.attachment.download');
    Route::get('/attachment/{attachment}/preview', [DocumentsController::class, 'previewAttachment'])->name('documents.attachment.preview');
    Route::get('/statistics/data', [DocumentsController::class, 'statistics'])->name('documents.statistics');
    Route::get('/export/{format?}', [DocumentsController::class, 'export'])->name('documents.export');
    Route::get('/metrics/realtime', [DocumentsController::class, 'metrics'])->name('documents.metrics');
});
use App\Http\Controllers\Agent\StatisticsController;

Route::prefix('statistics')->group(function () {
    Route::get('/', [StatisticsController::class, 'index'])->name('statistics');
    Route::get('/chart-data', [StatisticsController::class, 'getChartData'])->name('statistics.chart-data');
    Route::post('/reports/generate', [StatisticsController::class, 'generateReport'])->name('statistics.generate-report');
});
