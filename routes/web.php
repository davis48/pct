<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\DocumentController;
use App\Http\Controllers\Front\RequestController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\InteractiveFormController;
use App\Http\Controllers\Front\PaymentController;

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

// Routes standalone pour l'inscription et la connexion (utilisées par les formulaires interactifs)
Route::get('/choose-role-standalone', [HomeController::class, 'chooseRoleStandalone'])->name('choose.role.standalone');
Route::get('/inscription-standalone', [HomeController::class, 'registerStandalone'])->name('register.standalone');
Route::post('/inscription-standalone', [HomeController::class, 'processRegisterStandalone']);
Route::get('/connexion-standalone', [HomeController::class, 'loginStandalone'])->name('login.standalone');
Route::post('/connexion-standalone', [HomeController::class, 'processLoginStandalone']);

// Routes des formulaires interactifs (publiques)
Route::prefix('formulaires-interactifs')->name('interactive-forms.')->group(function () {
    Route::get('/', [InteractiveFormController::class, 'index'])->name('index');
    Route::get('/{formType}', [InteractiveFormController::class, 'show'])->name('show');
    Route::post('/{formType}/generate', [InteractiveFormController::class, 'generate'])->name('generate');
    Route::get('/{formType}/{requestId}/download', [InteractiveFormController::class, 'download'])->name('download');
});

// Routes des formulaires standalone
Route::prefix('interactive-forms')->name('interactive-forms.standalone.')->group(function () {
    Route::get('/extrait-naissance-standalone', [InteractiveFormController::class, 'extraitNaissanceStandalone'])->name('extrait-naissance');
    Route::get('/attestation-domicile-standalone', [InteractiveFormController::class, 'attestationDomicileStandalone'])->name('attestation-domicile');
    Route::get('/certificat-mariage-standalone', [InteractiveFormController::class, 'certificatMariageStandalone'])->name('certificat-mariage');
    Route::get('/certificat-celibat-standalone', [InteractiveFormController::class, 'certificatCelibatStandalone'])->name('certificat-celibat');
    Route::get('/certificat-deces-standalone', [InteractiveFormController::class, 'certificatDecesStandalone'])->name('certificat-deces');
    Route::get('/legalisation-standalone', [InteractiveFormController::class, 'legalisationStandalone'])->name('legalisation');
    Route::post('/{formType}/generate', [InteractiveFormController::class, 'generate'])->name('generate');
    Route::post('/process-pending', [InteractiveFormController::class, 'processPendingSubmission'])->name('process-pending');
});

// Routes des paiements standalone
Route::prefix('payments-standalone')->name('payments.standalone.')->middleware('auth')->group(function () {
    Route::get('/{citizenRequest}', [PaymentController::class, 'showStandalone'])->name('show');
    Route::post('/{citizenRequest}/process', [PaymentController::class, 'processStandalone'])->name('process');
    Route::get('/{citizenRequest}/process', [PaymentController::class, 'showProcessStandalone'])->name('show-process');
    Route::post('/{citizenRequest}/check-status', [PaymentController::class, 'checkStatusStandalone'])->name('check-status');
    Route::post('/{citizenRequest}/validate-payment', [PaymentController::class, 'validatePaymentStandalone'])->name('validate-payment');
});

// Route de test pour accéder directement au formulaire d'extrait de naissance
Route::get('/test-extrait-naissance', function() {
    return view('front.interactive-forms.extrait-naissance', ['userData' => []]);
})->name('test.extrait.naissance');

// Route pour télécharger les templates vides (pour les formulaires traditionnels)
Route::get('/documents/{type}/template', function($type) {
    // Ici vous pouvez gérer le téléchargement des templates PDF vides
    return response()->download(public_path("templates/{$type}.pdf"));
})->name('documents.download-template');

// Route de test pour les formulaires interactifs
Route::get('/test-formulaires', function() {
    return view('front.interactive-forms.index', [
        'availableForms' => [
            'certificat-mariage' => [
                'title' => 'Certificat de Mariage',
                'description' => 'Formulaire pour demander un certificat de mariage',
                'icon' => 'fas fa-heart',
                'estimated_time' => '5-10 minutes'
            ],
            'certificat-celibat' => [
                'title' => 'Certificat de Célibat',
                'description' => 'Formulaire pour demander un certificat de célibat',
                'icon' => 'fas fa-user',
                'estimated_time' => '3-5 minutes'
            ],
            'extrait-naissance' => [
                'title' => 'Extrait de Naissance',
                'description' => 'Formulaire pour demander un extrait de naissance',
                'icon' => 'fas fa-baby',
                'estimated_time' => '3-5 minutes'
            ],
            'attestation-domicile' => [
                'title' => 'Attestation de Domicile',
                'description' => 'Formulaire pour demander une attestation de domicile',
                'icon' => 'fas fa-home',
                'estimated_time' => '3-5 minutes'
            ]
        ]
    ]);
})->name('test.forms');

// Route de test directe pour l'extrait de naissance
Route::get('/test-extrait-naissance', function() {
    return view('front.interactive-forms.extrait-naissance', [
        'userData' => []
    ]);
})->name('test.birth.certificate');

// Route de démonstration pour le formulaire amélioré
Route::get('/demo-formulaire', function() {
    return view('front.requests.demo');
})->name('demo.form');

// Route pour voir l'ancien formulaire (comparaison)
Route::get('/ancien-formulaire', function() {
    return view('front.requests.old-version');
})->name('old.form');

// Route de comparaison ancien vs nouveau formulaire
Route::get('/comparaison-formulaires', function() {
    return view('front.requests.comparison');
})->name('forms.comparison');

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

// Routes de test pour la génération PDF
Route::get('/test-pdf/{type}', [\App\Http\Controllers\TestPdfController::class, 'generateTest'])
     ->name('test.pdf.generate');

// Routes d'authentification personnalisées
Route::post('/connexion', [HomeController::class, 'authenticate'])->name('login.post');
Route::post('/inscription', function() {
    return redirect()->route('register.standalone');
})->name('register.post');
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
    // Gestion du profil - Version standalone
    Route::get('/profile/edit', function() {
        return response()->file(public_path('profile-edit-standalone.html'));
    })->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Gestion des documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    
    // Gestion des formulaires téléchargeables
    Route::get('/formulaires', [\App\Http\Controllers\Front\FormulaireController::class, 'index'])->name('formulaires.index');
    Route::get('/formulaires/{type}', [\App\Http\Controllers\Front\FormulaireController::class, 'show'])->name('formulaires.show');
    Route::get('/formulaires/{type}/download', [\App\Http\Controllers\Front\FormulaireController::class, 'download'])->name('formulaires.download');

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
        Route::get('/notifications/ajax', [\App\Http\Controllers\Citizen\DashboardController::class, 'getNotificationsAjax'])->name('notifications.ajax');
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

// Routes standalone pour les détails de demande
Route::prefix('citizen-request-standalone')->name('citizen.request.standalone.')->middleware('auth')->group(function () {
    Route::get('/{id}', [\App\Http\Controllers\Citizen\DashboardController::class, 'showRequestStandalone'])->name('show');
});

// Les routes admin sont maintenant entièrement définies dans routes/admin.php
// Voir RouteServiceProvider pour plus de détails

// Routes d'authentification Laravel personnalisées
// Auth::routes(); // Désactivées car nous utilisons nos propres routes

// Routes pour télécharger les documents PDF
Route::middleware(['auth'])->group(function () {
    Route::get('/documents/{request}/download', [\App\Http\Controllers\Front\DocumentDownloadController::class, 'downloadApprovedDocument'])
         ->name('documents.download');
    Route::get('/documents/{request}/preview', [\App\Http\Controllers\Front\DocumentDownloadController::class, 'previewDocument'])
         ->name('documents.preview');
});

// Route de test pour créer des notifications (à supprimer en production)
Route::get('/test-create-notifications', function() {
    if (!Auth::check()) {
        return redirect()->route('login')->with('info', 'Veuillez vous connecter pour tester les notifications.');
    }
    
    $user = Auth::user();
    
    // Créer quelques notifications de test
    $notifications = [
        [
            'title' => 'Demande approuvée',
            'message' => 'Votre demande d\'extrait de naissance a été approuvée et est prête au téléchargement.',
            'type' => 'success'
        ],
        [
            'title' => 'Paiement reçu',
            'message' => 'Votre paiement de 5000 FCFA a été confirmé pour la demande #REF-2024-001.',
            'type' => 'payment'
        ],
        [
            'title' => 'Document en cours de traitement',
            'message' => 'Votre demande de certificat de mariage est en cours de traitement par nos services.',
            'type' => 'info'
        ],
        [
            'title' => 'Information importante',
            'message' => 'Pensez à vérifier vos documents avant de les soumettre pour éviter les retards.',
            'type' => 'warning'
        ]
    ];
    
    foreach ($notifications as $notif) {
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => $notif['title'],
            'message' => $notif['message'],
            'type' => $notif['type'],
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    return redirect()->route('citizen.dashboard')->with('success', 'Notifications de test créées avec succès !');
})->name('test.notifications');

// Route de test pour l'API notifications
Route::get('/test-notifications-api', function() {
    if (!Auth::check()) {
        return response()->json(['error' => 'Non authentifié'], 401);
    }
    
    $user = Auth::user();
    
    $notifications = \App\Models\Notification::where('user_id', $user->id)
        ->where('is_read', false)
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

    return response()->json([
        'notifications' => $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => \Illuminate\Support\Str::limit($notification->message, 80),
                'type' => $notification->type,
                'time_ago' => $notification->created_at->diffForHumans(),
                'icon' => 'fas fa-bell text-gray-500',
            ];
        }),
        'count' => $notifications->count(),
        'debug_info' => [
            'user_id' => $user->id,
            'total_notifications' => \App\Models\Notification::where('user_id', $user->id)->count(),
            'unread_notifications' => \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count()
        ]
    ]);
})->name('test.notifications.api');
