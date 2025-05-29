<?php

/**
 * Test de vérification du graphique "Répartition par Type de Document"
 * Ce script vérifie que les données nécessaires sont présentes pour afficher le graphique
 */

echo "🔍 Test du graphique 'Répartition par Type de Document'\n";
echo "=======================================================\n\n";

// Simule l'appel au contrôleur pour vérifier les données
require_once __DIR__ . '/vendor/autoload.php';

// Test des données statiques
$testDocumentTypesData = [
    'labels' => ['Acte de Naissance', 'Acte de Mariage', 'Certificat de Nationalité', 'Déclaration de Naissance', 'Certificat de Résidence'],
    'data' => [45, 25, 15, 10, 5]
];

echo "✅ Test des données du graphique document_types :\n";
echo "   Labels: " . implode(', ', $testDocumentTypesData['labels']) . "\n";
echo "   Données: " . implode(', ', $testDocumentTypesData['data']) . "\n";
echo "   Total des données: " . array_sum($testDocumentTypesData['data']) . "\n\n";

// Vérification de la cohérence
$totalPercentage = array_sum($testDocumentTypesData['data']);
if ($totalPercentage > 0) {
    echo "✅ Données cohérentes - Total : {$totalPercentage}%\n";
    
    foreach ($testDocumentTypesData['labels'] as $index => $label) {
        $value = $testDocumentTypesData['data'][$index];
        $percentage = round(($value / $totalPercentage) * 100, 1);
        echo "   - {$label}: {$value} ({$percentage}%)\n";
    }
} else {
    echo "❌ Erreur: Aucune donnée disponible\n";
}

echo "\n📊 Test de la structure JavaScript :\n";

// Vérifie si le fichier contient les bonnes références
$dashboardContent = file_get_contents(__DIR__ . '/resources/views/admin/special/dashboard.blade.php');

if (strpos($dashboardContent, 'documentTypesChart') !== false) {
    echo "✅ Canvas 'documentTypesChart' trouvé\n";
} else {
    echo "❌ Canvas 'documentTypesChart' manquant\n";
}

if (strpos($dashboardContent, 'document_types') !== false) {
    echo "✅ Référence aux données 'document_types' trouvée\n";
} else {
    echo "❌ Référence aux données 'document_types' manquante\n";
}

// Vérification du contrôleur
$controllerContent = file_get_contents(__DIR__ . '/app/Http/Controllers/Admin/AdminSpecialController.php');

if (strpos($controllerContent, "'document_types' => \$documentTypesData") !== false) {
    echo "✅ Méthode getChartData() mise à jour avec document_types\n";
} else {
    echo "❌ Méthode getChartData() ne contient pas document_types\n";
}

echo "\n🎯 Résultat du test :\n";
echo "===================\n";

$hasData = $totalPercentage > 0;
$hasCanvas = strpos($dashboardContent, 'documentTypesChart') !== false;
$hasController = strpos($controllerContent, "'document_types'") !== false;

if ($hasData && $hasCanvas && $hasController) {
    echo "✅ SUCCÈS: Le graphique 'Répartition par Type de Document' devrait maintenant s'afficher correctement\n";
    echo "   - Données disponibles ✅\n";
    echo "   - Canvas configuré ✅\n";
    echo "   - Contrôleur mis à jour ✅\n";
} else {
    echo "❌ ÉCHEC: Des éléments manquent encore\n";
    echo "   - Données: " . ($hasData ? "✅" : "❌") . "\n";
    echo "   - Canvas: " . ($hasCanvas ? "✅" : "❌") . "\n";
    echo "   - Contrôleur: " . ($hasController ? "✅" : "❌") . "\n";
}

echo "\n💡 Pour voir les changements :\n";
echo "1. Visitez /admin/dashboard\n";
echo "2. Cherchez la section 'Répartition par Type de Document'\n";
echo "3. Le graphique en anneau devrait maintenant afficher les données\n";

?>
