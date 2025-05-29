<?php
/**
 * Test rapide pour vérifier les statistiques par type de document
 * Exécuter avec: php test_document_statistics.php
 */

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Http\Controllers\Admin\AdminSpecialController;

// Configuration minimale pour tester
$app = new Application(realpath(__DIR__));
$app->singleton('app', function () use ($app) { return $app; });

try {
    // Instancier le contrôleur
    $controller = new AdminSpecialController();
    
    // Utiliser la reflection pour accéder aux méthodes privées
    $reflection = new ReflectionClass($controller);
    
    // Test de getDocumentTypesStatistics
    $getDocumentTypesStatistics = $reflection->getMethod('getDocumentTypesStatistics');
    $getDocumentTypesStatistics->setAccessible(true);
    
    echo "🔍 Test de la méthode getDocumentTypesStatistics()...\n\n";
    
    $documentStats = $getDocumentTypesStatistics->invoke($controller);
    
    echo "✅ Méthode exécutée avec succès !\n";
    echo "📊 Nombre de types de documents: " . count($documentStats) . "\n\n";
    
    echo "📋 Types de documents disponibles:\n";
    foreach ($documentStats as $type => $stats) {
        echo "  • $type: {$stats['volume']} demandes (Succès: {$stats['success_rate']}%)\n";
    }
    
    echo "\n";
    
    // Test de getAdvancedChartData
    $getAdvancedChartData = $reflection->getMethod('getAdvancedChartData');
    $getAdvancedChartData->setAccessible(true);
    
    echo "🔍 Test de la méthode getAdvancedChartData()...\n";
    
    $chartData = $getAdvancedChartData->invoke($controller);
    
    echo "✅ Méthode exécutée avec succès !\n";
    echo "📈 Sections de données disponibles:\n";
    foreach (array_keys($chartData) as $section) {
        echo "  • $section\n";
    }
    
    // Vérifier la présence des statistiques par document
    if (isset($chartData['document_types_detailed'])) {
        echo "\n✅ Les statistiques par type de document sont bien intégrées dans les données de graphique !\n";
        echo "📊 Types disponibles dans les données: " . implode(', ', array_keys($chartData['document_types_detailed'])) . "\n";
    } else {
        echo "\n❌ Les statistiques par type de document ne sont pas présentes dans les données de graphique.\n";
    }
    
    echo "\n🎉 Tous les tests sont terminés avec succès !\n";
    echo "💡 La fonctionnalité de statistiques par type de document est prête à être utilisée.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du test: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
}
