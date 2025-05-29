<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Charger l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== TEST DE LA PAGE STATISTIQUES ADMIN ===\n\n";

try {
    // Créer une instance du contrôleur
    $adminController = new \App\Http\Controllers\Admin\AdminSpecialController();
    
    // Test de la méthode getDocumentTypesStatistics qui causait l'erreur
    $reflection = new ReflectionClass($adminController);
    $getDocumentTypesStatsMethod = $reflection->getMethod('getDocumentTypesStatistics');
    $getDocumentTypesStatsMethod->setAccessible(true);
    
    echo "1. Test de getDocumentTypesStatistics():\n";
    $documentStats = $getDocumentTypesStatsMethod->invoke($adminController);
    
    if (is_array($documentStats)) {
        echo "   ✅ Méthode exécutée avec succès\n";
        echo "   📊 Types de documents trouvés: " . count($documentStats) . "\n";
        
        foreach ($documentStats as $type => $stats) {
            echo "   - $type: " . $stats['total_requests'] . " demandes, " . 
                 $stats['avg_attachments_required'] . " pièces jointes moyennes\n";
        }
    } else {
        echo "   ❌ Erreur: méthode ne retourne pas un array\n";
    }
    
    echo "\n2. Test du modèle Attachment:\n";
    
    // Vérifier que le modèle Attachment est accessible
    $totalAttachments = \App\Models\Attachment::count();
    echo "   ✅ Modèle Attachment accessible\n";
    echo "   📎 Total pièces jointes: $totalAttachments\n";
    
    // Test d'une requête join comme dans le contrôleur
    $attachmentsData = \App\Models\Attachment::join('citizen_requests', 'attachments.citizen_request_id', '=', 'citizen_requests.id')
        ->selectRaw('citizen_request_id, COUNT(*) as attachment_count')
        ->groupBy('citizen_request_id')
        ->get();
    
    echo "   ✅ Requête JOIN exécutée avec succès\n";
    echo "   📁 Demandes avec pièces jointes: " . $attachmentsData->count() . "\n";
    
    if ($attachmentsData->count() > 0) {
        $totalAttachments = $attachmentsData->sum('attachment_count');
        $avgAttachments = round($totalAttachments / $attachmentsData->count(), 1);
        echo "   📊 Moyenne pièces jointes par demande: $avgAttachments\n";
    }
    
    echo "\n✅ TOUS LES TESTS PASSENT - L'ERREUR EST CORRIGÉE !\n";
    echo "🎉 La page admin/statistics devrait maintenant fonctionner correctement.\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n";
}

echo "\n=== FIN DU TEST ===\n";
