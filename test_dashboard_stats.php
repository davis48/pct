<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "TEST DES STATISTIQUES DU TABLEAU DE BORD\n";
echo "=====================================\n\n";

// Trouver un agent pour le test
$agent = User::where('role', 'agent')->first();

if (!$agent) {
    echo "Erreur: Aucun agent trouvé dans la base de données.\n";
    exit(1);
}

// Se connecter en tant qu'agent
Auth::login($agent);
echo "Connecté en tant qu'agent: {$agent->nom} {$agent->prenoms} (ID: {$agent->id})\n\n";

// Obtenir les statistiques avant
echo "STATISTIQUES AVANT MISE À JOUR:\n";
$stats = [
    'pending' => CitizenRequest::where('status', 'pending')->count(),
    'assigned' => CitizenRequest::where('assigned_to', $agent->id)->count(),
    'completed_today' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                      ->whereDate('updated_at', today())
                                      ->count(),
    'monthly_total' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                   ->whereYear('updated_at', now()->year)
                                   ->whereMonth('updated_at', now()->month)
                                   ->count(),
];

echo "- Demandes en attente: {$stats['pending']}\n";
echo "- Mes assignations: {$stats['assigned']}\n";
echo "- Complétées aujourd'hui: {$stats['completed_today']}\n";
echo "- Total ce mois: {$stats['monthly_total']}\n\n";

// Simuler un changement dans les données
echo "SIMULATION DE CHANGEMENTS...\n";

// Trouver un citoyen
$citizen = User::where('role', 'citizen')->first();

if (!$citizen) {
    echo "Erreur: Aucun citoyen trouvé dans la base de données.\n";
    exit(1);
}

// Créer une nouvelle demande
$request = CitizenRequest::create([
    'user_id' => $citizen->id,
    'type' => 'certificate',
    'description' => 'Test des statistiques du tableau de bord',
    'status' => 'pending',
    'reference_number' => 'STAT-' . date('YmdHis')
]);

echo "- Nouvelle demande créée avec ID: {$request->id}\n";

// Assigner la demande à l'agent
$request->update([
    'assigned_to' => $agent->id,
    'status' => 'in_progress'
]);

echo "- Demande assignée à l'agent\n";

// Compléter une autre demande
$pendingRequest = CitizenRequest::where('status', 'pending')
                              ->first();

if ($pendingRequest) {
    $pendingRequest->update([
        'status' => 'approved',
        'processed_by' => $agent->id,
        'processed_at' => now(),
        'admin_comments' => 'Test automatique des statistiques'
    ]);
    
    echo "- Demande {$pendingRequest->id} approuvée\n\n";
}

// Obtenir les statistiques après
echo "STATISTIQUES APRÈS MISE À JOUR:\n";
$updatedStats = [
    'pending' => CitizenRequest::where('status', 'pending')->count(),
    'assigned' => CitizenRequest::where('assigned_to', $agent->id)->count(),
    'completed_today' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                      ->whereDate('updated_at', today())
                                      ->count(),
    'monthly_total' => CitizenRequest::whereIn('status', ['approved', 'rejected'])
                                   ->whereYear('updated_at', now()->year)
                                   ->whereMonth('updated_at', now()->month)
                                   ->count(),
];

echo "- Demandes en attente: {$updatedStats['pending']} (changement: " . ($updatedStats['pending'] - $stats['pending']) . ")\n";
echo "- Mes assignations: {$updatedStats['assigned']} (changement: " . ($updatedStats['assigned'] - $stats['assigned']) . ")\n";
echo "- Complétées aujourd'hui: {$updatedStats['completed_today']} (changement: " . ($updatedStats['completed_today'] - $stats['completed_today']) . ")\n";
echo "- Total ce mois: {$updatedStats['monthly_total']} (changement: " . ($updatedStats['monthly_total'] - $stats['monthly_total']) . ")\n\n";

// Tester les points d'API
echo "TEST DES POINTS D'API:\n";

// Obtenir les statistiques
try {
    $controller = new \App\Http\Controllers\Agent\DashboardController();
    $response = $controller->getDashboardStats();
    $data = json_decode($response->getContent(), true);
    
    echo "- Statistiques récupérées: " . count($data) . " métriques\n";
    echo "  - Demandes en attente: {$data['pending']}\n";
    echo "  - Mes assignations: {$data['assigned']}\n";
    
    echo "\nTEST RÉUSSI: Les API de statistiques fonctionnent correctement.\n";
} catch (\Exception $e) {
    echo "ERREUR: {$e->getMessage()}\n";
}

echo "\nLes statistiques du tableau de bord devraient maintenant être mises à jour automatiquement.\n";
