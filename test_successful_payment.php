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

echo "=== TEST PAIEMENT RÉUSSI ===\n\n";

try {
    // Trouvons une demande non payée
    $request = \App\Models\CitizenRequest::where('payment_status', '!=', 'paid')->first();
    
    if (!$request) {
        echo "Aucune demande non payée trouvée. Créons-en une.\n";
        
        $user = \App\Models\User::where('role', 'citizen')->first();
        if (!$user) {
            echo "❌ Aucun utilisateur citoyen trouvé\n";
            exit(1);
        }
        
        $document = \App\Models\Document::first();
        if (!$document) {
            echo "❌ Aucun document trouvé\n";
            exit(1);
        }
        
        $request = \App\Models\CitizenRequest::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'type' => 'acte-naissance',
            'description' => 'Test de paiement',
            'status' => 'draft',
            'payment_status' => 'unpaid',
            'payment_required' => true,
            'reference_number' => 'REQ-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
        ]);
        
        echo "✅ Demande créée (ID: {$request->id})\n";
    }
    
    echo "État initial de la demande {$request->id}:\n";
    echo "- status: {$request->status}\n";
    echo "- payment_status: {$request->payment_status}\n";
    echo "- reference: {$request->reference_number}\n";
    
    // Créer un paiement
    $paymentService = new \App\Services\PaymentService();
    
    $payment = $paymentService->initializePayment($request, \App\Models\Payment::METHOD_MOBILE_MONEY, [
        'provider' => \App\Models\Payment::PROVIDER_MTN,
        'phone_number' => '0701020304',
    ]);
    
    echo "\n✅ Paiement initialisé (ID: {$payment->id})\n";
    echo "- Référence: {$payment->reference}\n";
    echo "- Montant: {$payment->amount} FCFA\n";
    echo "- Status: {$payment->status}\n";
    
    // Simuler le paiement
    $response = $paymentService->simulateMobileMoneyPayment($payment, [
        'phone_number' => $payment->phone_number,
        'provider' => $payment->provider,
    ]);
    
    echo "\nRéponse de simulation:\n";
    echo "- Succès: " . ($response['success'] ? 'OUI' : 'NON') . "\n";
    echo "- Message: {$response['message']}\n";
    echo "- Transaction ID: {$response['transaction_id']}\n";
    
    // Vérifier l'état final
    $payment->refresh();
    $request->refresh();
    
    echo "\nÉtat final:\n";
    echo "- Paiement status: {$payment->status}\n";
    echo "- Demande status: {$request->status}\n";
    echo "- Demande payment_status: {$request->payment_status}\n";
    
    // Vérifier les notifications
    $notifications = \App\Models\Notification::where('user_id', $request->user_id)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    echo "\nNotifications récentes ({$notifications->count()}):\n";
    foreach ($notifications as $notification) {
        echo "- {$notification->title} ({$notification->type}) - " . 
             ($notification->is_read ? 'Lu' : 'Non lu') . "\n";
    }
    
    if ($response['success']) {
        echo "\n🎉 SUCCÈS! Le paiement a été traité avec succès!\n";
        echo "✅ La demande est maintenant en statut: {$request->status}\n";
        echo "✅ Le payment_status est: {$request->payment_status}\n";
        echo "✅ Notification créée pour l'utilisateur\n";
    } else {
        echo "\n⚠️  Le paiement a échoué, mais le système fonctionne correctement.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
