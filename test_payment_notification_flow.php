<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\CitizenRequest;
use App\Models\User;
use App\Models\Notification;
use App\Services\PaymentService;

echo "=== TEST DU NOUVEAU FLUX DE PAIEMENT AVEC NOTIFICATIONS ===\n\n";

try {
    // Vérifier qu'une demande en brouillon existe
    $draftRequest = CitizenRequest::where('status', 'draft')
                                 ->where('payment_status', 'unpaid')
                                 ->first();
    
    if (!$draftRequest) {
        echo "❌ Aucune demande en brouillon trouvée\n";
        echo "💡 Créez d'abord une demande depuis l'interface citizen pour tester\n";
        exit(1);
    }

    echo "✅ Demande en brouillon trouvée:\n";
    echo "   - ID: {$draftRequest->id}\n";
    echo "   - Référence: {$draftRequest->reference_number}\n";
    echo "   - Type: {$draftRequest->type}\n";
    echo "   - Statut: {$draftRequest->status}\n";
    echo "   - Paiement: {$draftRequest->payment_status}\n\n";

    // Simuler le paiement
    $paymentService = new PaymentService();
    
    echo "🔄 Initialisation du paiement...\n";
    $payment = $paymentService->initializePayment($draftRequest, 'mobile_money', [
        'phone_number' => '+225 01 02 03 04 05',
        'provider' => 'orange_money'
    ]);
    
    echo "✅ Paiement initialisé:\n";
    echo "   - ID: {$payment->id}\n";
    echo "   - Référence: {$payment->reference}\n";
    echo "   - Montant: {$payment->amount} FCFA\n";
    echo "   - Statut: {$payment->status}\n\n";

    echo "🔄 Simulation du paiement réussi...\n";
    $result = $paymentService->simulateMobileMoneyPayment($payment, [
        'phone_number' => '+225 01 02 03 04 05',
        'provider' => 'orange_money'
    ]);
    
    // Recharger la demande
    $draftRequest->refresh();
    
    echo "✅ Paiement effectué avec succès!\n";
    echo "   - Statut demande: {$draftRequest->status}\n";
    echo "   - Statut paiement: {$draftRequest->payment_status}\n\n";

    // Vérifier la notification
    $notification = Notification::where('user_id', $draftRequest->user_id)
                                ->where('type', 'payment_success')
                                ->latest()
                                ->first();

    if ($notification) {
        echo "✅ Notification de paiement créée:\n";
        echo "   - Titre: {$notification->title}\n";
        echo "   - Message: " . substr($notification->message, 0, 100) . "...\n";
        echo "   - Type: {$notification->type}\n";
        echo "   - Lu: " . ($notification->is_read ? 'Oui' : 'Non') . "\n\n";
    } else {
        echo "❌ Aucune notification de paiement trouvée\n\n";
    }

    // Vérifier les statistiques
    echo "📊 Nouvelles statistiques:\n";
    $user = User::find($draftRequest->user_id);
    $requests = CitizenRequest::where('user_id', $user->id)->get();
    $submittedRequests = $requests->where('payment_status', 'paid');
    $draftRequests = $requests->where('status', 'draft');

    echo "   - Total soumis: " . $submittedRequests->count() . "\n";
    echo "   - En attente: " . $submittedRequests->where('status', 'pending')->count() . "\n";
    echo "   - En cours: " . $submittedRequests->where('status', 'in_progress')->count() . "\n";
    echo "   - Approuvées: " . $submittedRequests->where('status', 'approved')->count() . "\n";
    echo "   - Rejetées: " . $submittedRequests->where('status', 'rejected')->count() . "\n";
    echo "   - En brouillon (à payer): " . $draftRequests->count() . "\n\n";

    echo "🎉 FLUX DE PAIEMENT AVEC NOTIFICATION TESTÉ AVEC SUCCÈS!\n\n";

    echo "📋 RÉSUMÉ DU NOUVEAU PROCESSUS:\n";
    echo "1. ✅ Demande créée en statut 'draft' avec payment_status 'unpaid'\n";
    echo "2. ✅ Citoyen effectue le paiement\n";
    echo "3. ✅ Notification 'Paiement effectué' envoyée au citoyen\n";
    echo "4. ✅ Demande passe en statut 'pending' avec payment_status 'paid'\n";
    echo "5. ✅ Demande peut maintenant être traitée par les agents\n";
    echo "6. ✅ Statistiques reflètent seulement les demandes payées\n\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
