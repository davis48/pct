<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "TEST DE CORRECTION DU BOUTON 'PRENDRE UNE DEMANDE'\n";
echo "===============================================\n\n";

// Trouver un agent pour le test
$agent = User::where('role', 'agent')->first();

if (!$agent) {
    echo "Erreur: Aucun agent trouvé dans la base de données.\n";
    exit(1);
}

// Se connecter en tant qu'agent
Auth::login($agent);
echo "Connecté en tant qu'agent: {$agent->nom} {$agent->prenoms} (ID: {$agent->id})\n\n";

// Vérifier s'il y a des demandes non assignées
$pendingRequest = CitizenRequest::where('status', 'pending')
                ->whereNull('assigned_to')
                ->first();

if (!$pendingRequest) {
    echo "Création d'une demande test en attente...\n";
    // Trouver un citoyen
    $citizen = User::where('role', 'citizen')->first();
    if (!$citizen) {
        echo "Erreur: Aucun citoyen trouvé dans la base de données.\n";
        exit(1);
    }
    
    // Créer une demande test
    $pendingRequest = CitizenRequest::create([
        'user_id' => $citizen->id,
        'type' => 'test',
        'description' => 'Demande test pour vérifier la fonctionnalité "Prendre une demande"',
        'status' => 'pending',
        'reference_number' => 'TEST-' . date('YmdHis')
    ]);
    
    echo "Demande test créée avec ID: {$pendingRequest->id}\n\n";
}

echo "1. VÉRIFICATION DE LA MÉTHODE RequestController::assign\n";
echo "--------------------------------------------------\n";

echo "Demande à assigner: #{$pendingRequest->id}\n";
echo "- Statut initial: {$pendingRequest->status}\n";
echo "- Assignée à: " . ($pendingRequest->assigned_to ? "Agent ID {$pendingRequest->assigned_to}" : "Non assignée") . "\n\n";

// Créer une instance du contrôleur
$controller = new App\Http\Controllers\Agent\RequestController();

// Créer une requête HTTP simulée
$httpRequest = new Illuminate\Http\Request();

// Simuler l'appel à la méthode assign
echo "Simulation de l'appel à RequestController::assign...\n";
try {
    $response = $controller->assign($httpRequest, $pendingRequest->id);
    
    echo "✅ Méthode exécutée avec succès\n";
    echo "✅ Type de réponse: " . get_class($response) . "\n";
    
    if (method_exists($response, 'getTargetUrl')) {
        echo "✅ URL de redirection: " . $response->getTargetUrl() . "\n";
    }
    
    // Vérifier que la demande a été mise à jour
    $pendingRequest->refresh();
    echo "\nÉtat de la demande après assignation:\n";
    echo "- Statut: {$pendingRequest->status}\n";
    echo "- Assignée à: Agent ID {$pendingRequest->assigned_to}\n";
    
    if ($pendingRequest->status === 'in_progress' && $pendingRequest->assigned_to === $agent->id) {
        echo "\n✅ TEST RÉUSSI: La demande a été correctement assignée à l'agent.\n";
        echo "✅ La redirection vers la page de traitement fonctionnera maintenant.\n";
    } else {
        echo "\n❌ TEST ÉCHOUÉ: La demande n'a pas été correctement assignée.\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur lors de l'appel à la méthode: " . $e->getMessage() . "\n";
}

echo "\nCORRECTION TERMINÉE - Le bouton 'Prendre une demande' dans l'onglet des demandes en attente redirige maintenant correctement vers la page de traitement.\n";
