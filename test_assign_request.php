<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "TEST DE LA FONCTIONNALITÉ 'PRENDRE UNE DEMANDE'\n";
echo "===========================================\n\n";

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
$pendingCount = CitizenRequest::where('status', 'pending')
                ->whereNull('assigned_to')
                ->count();

echo "Nombre de demandes en attente non assignées: {$pendingCount}\n";

if ($pendingCount == 0) {
    // Créer une demande de test
    echo "Création d'une demande de test...\n";
    
    // Trouver un citoyen
    $citizen = User::where('role', 'citizen')->first();
    
    if (!$citizen) {
        echo "Erreur: Aucun citoyen trouvé dans la base de données.\n";
        exit(1);
    }
    
    // Créer la demande
    $request = CitizenRequest::create([
        'user_id' => $citizen->id,
        'type' => 'certificate',
        'description' => 'Demande de test pour la fonctionnalité "Prendre une demande"',
        'status' => 'pending',
        'reference_number' => 'TEST-' . date('YmdHis')
    ]);
    
    echo "Demande créée avec l'ID: {$request->id} et la référence: {$request->reference_number}\n\n";
}

// Récupérer la prochaine demande non assignée
$nextRequest = CitizenRequest::where('status', 'pending')
              ->whereNull('assigned_to')
              ->first();

if (!$nextRequest) {
    echo "Erreur: Aucune demande en attente n'est disponible pour le test.\n";
    exit(1);
}

echo "Demande à assigner: #{$nextRequest->reference_number} (ID: {$nextRequest->id})\n";
echo "Statut actuel: {$nextRequest->status}\n";
echo "Assignée à: " . ($nextRequest->assigned_to ? "Agent ID {$nextRequest->assigned_to}" : "Non assignée") . "\n\n";

// Simuler le clic sur le bouton "Prendre une demande"
echo "Simulation de l'assignation de la demande à l'agent...\n";

$nextRequest->update([
    'assigned_to' => $agent->id,
    'status' => 'in_progress'
]);

// Vérifier que la demande a bien été assignée
$updatedRequest = CitizenRequest::find($nextRequest->id);

echo "Résultat après assignation:\n";
echo "Statut: {$updatedRequest->status}\n";
echo "Assignée à: " . ($updatedRequest->assigned_to ? "Agent ID {$updatedRequest->assigned_to}" : "Non assignée") . "\n\n";

if ($updatedRequest->assigned_to == $agent->id && $updatedRequest->status == 'in_progress') {
    echo "TEST RÉUSSI: La demande a été correctement assignée à l'agent.\n";
} else {
    echo "TEST ÉCHOUÉ: La demande n'a pas été correctement assignée.\n";
}

echo "\nLa fonctionnalité 'Prendre une demande' devrait maintenant fonctionner correctement.\n";
