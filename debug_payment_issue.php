<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUG PROBLÈME DE PAIEMENT ===\n\n";

// 1. Vérifier la structure de la base de données
echo "1. Vérification de la structure de la base de données:\n";

try {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('citizen_requests');
    echo "   Colonnes de citizen_requests: " . implode(', ', $columns) . "\n";
    
    if (in_array('payment_status', $columns)) {
        echo "   ✅ payment_status existe\n";
    } else {
        echo "   ❌ payment_status n'existe pas\n";
    }
    
    if (in_array('payment_required', $columns)) {
        echo "   ✅ payment_required existe\n";
    } else {
        echo "   ❌ payment_required n'existe pas\n";
    }
    
    $paymentsColumns = \Illuminate\Support\Facades\Schema::getColumnListing('payments');
    echo "   Colonnes de payments: " . implode(', ', $paymentsColumns) . "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

// 2. Vérifier les données de test
echo "\n2. Vérification des données de test:\n";

try {
    $requests = \App\Models\CitizenRequest::take(3)->get();
    echo "   Nombre de demandes: " . $requests->count() . "\n";
    
    foreach ($requests as $request) {
        echo "   - Demande {$request->id}: status={$request->status}, payment_status={$request->payment_status}\n";
        $paymentsCount = $request->payments()->count();
        echo "     Paiements: {$paymentsCount}\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

// 3. Tester la création d'un paiement
echo "\n3. Test de création d'un paiement:\n";

try {
    $request = \App\Models\CitizenRequest::first();
    if ($request) {
        echo "   Demande trouvée: {$request->id}\n";
        
        // Créer un paiement test
        $payment = new \App\Models\Payment([
            'citizen_request_id' => $request->id,
            'amount' => 5000,
            'reference' => \App\Models\Payment::generateReference(),
            'status' => \App\Models\Payment::STATUS_PENDING,
            'payment_method' => \App\Models\Payment::METHOD_MOBILE_MONEY,
            'phone_number' => '0701020304',
            'provider' => \App\Models\Payment::PROVIDER_MTN,
            'notes' => 'Test de paiement',
        ]);
        
        $payment->save();
        echo "   ✅ Paiement créé avec succès (ID: {$payment->id})\n";
        
        // Tester la simulation de paiement
        $paymentService = new \App\Services\PaymentService();
        $response = $paymentService->simulateMobileMoneyPayment($payment, [
            'phone_number' => $payment->phone_number,
            'provider' => $payment->provider,
        ]);
        
        echo "   Réponse de simulation: " . json_encode($response, JSON_PRETTY_PRINT) . "\n";
        
        // Vérifier l'état après simulation
        $payment->refresh();
        $request->refresh();
        
        echo "   État du paiement après simulation: {$payment->status}\n";
        echo "   État de la demande après simulation: status={$request->status}, payment_status={$request->payment_status}\n";
        
        // Vérifier les notifications
        $notifications = \App\Models\Notification::where('user_id', $request->user_id)->get();
        echo "   Notifications créées: " . $notifications->count() . "\n";
        
        if ($notifications->count() > 0) {
            $lastNotification = $notifications->last();
            echo "   Dernière notification: {$lastNotification->title}\n";
        }
        
    } else {
        echo "   ❌ Aucune demande trouvée\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DU DEBUG ===\n";
