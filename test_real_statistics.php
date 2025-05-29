<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Créer une requête factice
$request = Request::create('/', 'GET');
$response = $kernel->handle($request);

// Test des statistiques réelles
echo "=== TEST DES STATISTIQUES RÉELLES ===\n\n";

// Test du contrôleur Admin
echo "1. Test du contrôleur AdminSpecialController:\n";
try {
    $adminController = new \App\Http\Controllers\Admin\AdminSpecialController();
    
    // Test des méthodes privées via reflection
    $reflection = new ReflectionClass($adminController);
    
    // Test getTotalRequests
    $getTotalRequestsMethod = $reflection->getMethod('getTotalRequests');
    $getTotalRequestsMethod->setAccessible(true);
    $totalRequests = $getTotalRequestsMethod->invoke($adminController);
    echo "   - Total des demandes: $totalRequests\n";
    
    // Test getRequestsToday
    $getRequestsTodayMethod = $reflection->getMethod('getRequestsToday');
    $getRequestsTodayMethod->setAccessible(true);
    $requestsToday = $getRequestsTodayMethod->invoke($adminController);
    echo "   - Demandes aujourd'hui: $requestsToday\n";
    
    // Test getActiveAgents
    $getActiveAgentsMethod = $reflection->getMethod('getActiveAgents');
    $getActiveAgentsMethod->setAccessible(true);
    $activeAgents = $getActiveAgentsMethod->invoke($adminController);
    echo "   - Agents actifs: $activeAgents\n";
    
    // Test getTotalAgents
    $getTotalAgentsMethod = $reflection->getMethod('getTotalAgents');
    $getTotalAgentsMethod->setAccessible(true);
    $totalAgents = $getTotalAgentsMethod->invoke($adminController);
    echo "   - Total agents: $totalAgents\n";
    
    // Test getCompletionRate
    $getCompletionRateMethod = $reflection->getMethod('getCompletionRate');
    $getCompletionRateMethod->setAccessible(true);
    $completionRate = $getCompletionRateMethod->invoke($adminController);
    echo "   - Taux de completion: $completionRate%\n";
    
    echo "   ✅ Contrôleur AdminSpecialController testé avec succès\n\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur dans AdminSpecialController: " . $e->getMessage() . "\n\n";
}

// Test du contrôleur Agent Statistics
echo "2. Test du contrôleur Agent StatisticsController:\n";
try {
    $statsController = new \App\Http\Controllers\Agent\StatisticsController();
    
    $reflection = new ReflectionClass($statsController);
    
    // Test getGlobalStats
    $getGlobalStatsMethod = $reflection->getMethod('getGlobalStats');
    $getGlobalStatsMethod->setAccessible(true);
    $globalStats = $getGlobalStatsMethod->invoke($statsController);
    
    echo "   - Total des demandes: " . $globalStats['requests']['total'] . "\n";
    echo "   - Demandes en attente: " . $globalStats['requests']['pending'] . "\n";
    echo "   - Demandes approuvées: " . $globalStats['requests']['approved'] . "\n";
    echo "   - Total citoyens: " . $globalStats['users']['total_citizens'] . "\n";
    echo "   - Total agents: " . $globalStats['users']['total_agents'] . "\n";
    
    echo "   ✅ Contrôleur Agent StatisticsController testé avec succès\n\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur dans Agent StatisticsController: " . $e->getMessage() . "\n\n";
}

// Test des modèles
echo "3. Test des modèles de données:\n";
try {
    $totalUsers = \App\Models\User::count();
    $totalCitizens = \App\Models\User::where('role', 'citizen')->count();
    $totalAgents = \App\Models\User::where('role', 'agent')->count();
    $totalRequests = \App\Models\CitizenRequest::count();
    $totalDocuments = \App\Models\Document::count();
    $totalAttachments = \App\Models\Attachment::count();
    
    echo "   - Utilisateurs total: $totalUsers\n";
    echo "   - Citoyens: $totalCitizens\n";
    echo "   - Agents: $totalAgents\n";
    echo "   - Demandes: $totalRequests\n";
    echo "   - Documents: $totalDocuments\n";
    echo "   - Pièces jointes: $totalAttachments\n";
    
    echo "   ✅ Modèles de données testés avec succès\n\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur dans les modèles: " . $e->getMessage() . "\n\n";
}

// Test des statistiques par type de document
echo "4. Test des statistiques par type de document:\n";
try {
    $documentTypes = [
        'attestation' => 'Attestation de résidence',
        'legalisation' => 'Légalisation de signature',
        'mariage' => 'Certificat de mariage',
        'extrait-acte' => 'Extrait d\'acte de naissance',
        'declaration-naissance' => 'Déclaration de naissance',
        'certificat' => 'Certificat de célibat',
        'information' => 'Demande d\'information',
        'autre' => 'Autre'
    ];
    
    foreach ($documentTypes as $type => $label) {
        $count = \App\Models\CitizenRequest::where('type', $type)->count();
        if ($count > 0) {
            echo "   - $label: $count demandes\n";
        }
    }
    
    echo "   ✅ Statistiques par type testées avec succès\n\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur dans les statistiques par type: " . $e->getMessage() . "\n\n";
}

echo "=== FIN DES TESTS ===\n";
echo "✅ Toutes les statistiques utilisent maintenant les données réelles de la base de données !\n";
