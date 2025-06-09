<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\DocumentController;
use App\Http\Controllers\Front\RequestController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\DocumentDownloadController;

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

// Route de test pour les messages de paiement
Route::get('/test-payment-message', function() {
    // Simuler un message de session
    session()->flash('success', 'Test de message de succès depuis la route!');
    return view('front.payments.test-status');
})->name('test.payment.message');

// Route de test pour l'échec de paiement
Route::get('/test-payment-failure/{payment}', function(\App\Models\Payment $payment) {
    // Simuler un message d'échec de session
    session()->flash('payment_failed', 'Le paiement a échoué. Solde insuffisant ou problème de réseau. Veuillez réessayer.');
    return view('front.payments.result', [
        'payment' => $payment,
        'request' => $payment->citizenRequest,
    ]);
})->name('test.payment.failure');

// Route de test pour le téléchargement de documents
Route::get('/test-document-download', function() {
    $approvedRequests = \App\Models\CitizenRequest::where('status', 'approved')
        ->with(['user', 'document'])
        ->orderBy('updated_at', 'desc')
        ->get();
    
    return view('test-document-download', compact('approvedRequests'));
})->name('test.document.download');

// Route de test pour le succès de paiement
Route::get('/test-payment-success/{payment}', function(\App\Models\Payment $payment) {
    // Simuler un message de succès de session
    session()->flash('payment_success', 'Votre paiement de ' . number_format($payment->amount, 0, ',', ' ') . ' FCFA a été effectué avec succès ! Votre demande est maintenant en cours de traitement.');
    return view('front.payments.result', [
        'payment' => $payment,
        'request' => $payment->citizenRequest,
    ]);
})->name('test.payment.success');

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
    // Redirection automatique vers le bon tableau de bord selon le rôle
    Route::get('/dashboard', function() {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->isAgent()) {
            return redirect()->route('agent.dashboard');
        } else {
            return redirect()->route('citizen.dashboard');
        }
    })->name('dashboard');
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
        'pendingRequests' => \App\Models\CitizenRequest::where('status', 'en_attente')->count(),
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
    Route::post('/requests/{request}/confirm', [RequestController::class, 'confirm'])->name('requests.confirm');
    Route::delete('/requests/{request}', [RequestController::class, 'destroy'])->name('requests.destroy');
    
    // Téléchargement de documents approuvés
    Route::get('/documents/{request}/download', [\App\Http\Controllers\DocumentDownloadController::class, 'downloadApprovedDocument'])->name('documents.download');
    Route::get('/documents/{request}/preview', [\App\Http\Controllers\DocumentDownloadController::class, 'previewDocument'])->name('documents.preview');
    
    // Gestion des paiements
    Route::get('/payments/{citizenRequest}', [\App\Http\Controllers\Front\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{citizenRequest}/initialize', [\App\Http\Controllers\Front\PaymentController::class, 'initialize'])->name('payments.initialize');
    Route::get('/payments/{payment}/process', [\App\Http\Controllers\Front\PaymentController::class, 'process'])->name('payments.process');
    Route::post('/payments/{payment}/simulate', [\App\Http\Controllers\Front\PaymentController::class, 'simulateMobileMoneyPayment'])->name('payments.simulate');
    Route::get('/payments/{payment}/status', [\App\Http\Controllers\Front\PaymentController::class, 'status'])->name('payments.status');
    Route::get('/payments/{payment}/result', [\App\Http\Controllers\Front\PaymentController::class, 'result'])->name('payments.result');
    Route::get('/payments/{payment}/retry', [\App\Http\Controllers\Front\PaymentController::class, 'retry'])->name('payments.retry');
    Route::get('/payments/{payment}/cancel', [\App\Http\Controllers\Front\PaymentController::class, 'cancel'])->name('payments.cancel');
    
    // Espace citoyen
    Route::prefix('citizen')->name('citizen.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Citizen\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/requests/updates', [\App\Http\Controllers\Citizen\DashboardController::class, 'getRequestUpdates'])->name('requests.updates');
        Route::get('/notifications', [\App\Http\Controllers\Citizen\DashboardController::class, 'getNotifications'])->name('notifications');
        Route::get('/stats', [\App\Http\Controllers\Citizen\DashboardController::class, 'getStats'])->name('stats');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Citizen\DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Citizen\DashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
        
        // Préférences de notification
        Route::get('/notification-preferences', [\App\Http\Controllers\Citizen\DashboardController::class, 'notificationPreferences'])->name('notification-preferences');
        Route::post('/notification-preferences', [\App\Http\Controllers\Citizen\DashboardController::class, 'updateNotificationPreferences'])->name('notification-preferences.update');
        Route::post('/test-notification', [\App\Http\Controllers\Citizen\DashboardController::class, 'sendTestNotification'])->name('test-notification');
        
        // Centre de notifications
        Route::get('/notifications-center', [\App\Http\Controllers\Citizen\DashboardController::class, 'notificationCenter'])->name('notifications.center');
        Route::delete('/notifications/{id}', [\App\Http\Controllers\Citizen\DashboardController::class, 'deleteNotification'])->name('notifications.delete');
        Route::delete('/notifications/read/all', [\App\Http\Controllers\Citizen\DashboardController::class, 'deleteReadNotifications'])->name('notifications.delete-read');
        Route::delete('/notifications/all', [\App\Http\Controllers\Citizen\DashboardController::class, 'deleteAllNotifications'])->name('notifications.delete-all');
        Route::get('/notifications/filter', [\App\Http\Controllers\Citizen\DashboardController::class, 'filterNotifications'])->name('notifications.filter');
        
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
