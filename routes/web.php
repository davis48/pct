<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\DocumentController;
use App\Http\Controllers\Front\RequestController;
use App\Http\Controllers\Front\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes (Front-end / Citoyen)
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/choose-role', [HomeController::class, 'chooseRole'])->name('choose.role');
Route::get('/connexion', [HomeController::class, 'login'])->name('login');
Route::get('/inscription', [HomeController::class, 'register'])->name('register');

// Routes d'authentification personnalisées
Route::post('/connexion', [HomeController::class, 'authenticate'])->name('login.post');
Route::post('/inscription', [HomeController::class, 'store'])->name('register.post');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');

// Routes d'authentification admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Route de réinitialisation mot de passe simple
Route::get('/mot-de-passe-oublie', function() {
    return view('front.forgot-password');
})->name('password.request');

// Routes protégées pour les utilisateurs connectés
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

// Admin test route directly in web.php
Route::get('/admin-test', function() {
    return redirect('/admin/dashboard');
})->name('admin.test');

// Another test route that directly returns the admin dashboard view
Route::get('/admin-view-test', function() {
    // Récupérer les statistiques pour le dashboard
    $stats = [
        'users' => \App\Models\User::count(),
        'documents' => \App\Models\Document::count(),
        'requests' => \App\Models\CitizenRequest::count(),
        'pendingRequests' => \App\Models\CitizenRequest::where('status', 'pending')->count(),
    ];

    // Récupérer les dernières demandes
    $latestRequests = \App\Models\CitizenRequest::with(['user', 'document'])
                    ->latest()
                    ->take(5)
                    ->get();

    return view('admin.dashboard', compact('stats', 'latestRequests'));
})->name('admin.view.test');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Gestion du profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Gestion des documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');

    // Gestion des demandes
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{request}', [RequestController::class, 'show'])->name('requests.show');
    
    // Espace citoyen
    Route::prefix('citizen')->name('citizen.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Citizen\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests/updates', [\App\Http\Controllers\Citizen\DashboardController::class, 'getRequestUpdates'])->name('requests.updates');
        Route::get('/notifications', [\App\Http\Controllers\Citizen\DashboardController::class, 'getNotifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Citizen\DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Citizen\DashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
        Route::get('/request/{id}', [\App\Http\Controllers\Citizen\DashboardController::class, 'showRequest'])->name('request.show');
        Route::get('/request/{id}/updates', function($id) {
            $request = \App\Models\CitizenRequest::findOrFail($id);
            return response()->json(['status' => $request->status]);
        })->name('request.updates');
    });
});

// Les routes admin sont maintenant entièrement définies dans routes/admin.php
// Voir RouteServiceProvider pour plus de détails

// Routes d'authentification Laravel personnalisées
// Auth::routes(); // Désactivées car nous utilisons nos propres routes
