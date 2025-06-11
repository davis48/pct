<?php
/**
 * Script de test pour vérifier que les données des formulaires standalone
 * sont bien sauvegardées dans le champ additional_data
 */

// Vérification des routes de soumission de formulaire
$routesToCheck = [
    'attestation-domicile',
    'certificat-celibat',
    'certificat-mariage',
    'certificat-deces',
    'extrait-naissance',
    'legalisation'
];

echo "🔍 VÉRIFICATION DES CONTROLEURS POUR SAUVEGARDE DES DONNÉES\n";
echo "===========================================================\n\n";

foreach ($routesToCheck as $route) {
    echo "📋 Vérification du formulaire : $route\n";
    
    // Chercher le contrôleur correspondant
    $controllerFile = "app/Http/Controllers/FormController.php"; // ou autre selon l'architecture
    
    if (file_exists($controllerFile)) {
        $content = file_get_contents($controllerFile);
        
        // Vérifier si additional_data est utilisé
        if (strpos($content, 'additional_data') !== false) {
            echo "✅ Le contrôleur utilise additional_data\n";
        } else {
            echo "❌ Le contrôleur N'UTILISE PAS additional_data\n";
            echo "   → CORRECTION NÉCESSAIRE\n";
        }
    } else {
        echo "⚠️  Contrôleur non trouvé\n";
    }
    
    echo "\n";
}

echo "\n💡 ACTIONS RECOMMANDÉES :\n";
echo "========================\n";
echo "1. Vérifier que chaque contrôleur de formulaire sauvegarde dans additional_data\n";
echo "2. Exemple de code nécessaire :\n";
echo "   \$request = CitizenRequest::create([\n";
echo "       'user_id' => auth()->id(),\n";
echo "       'type' => 'attestation',\n";
echo "       'additional_data' => \$request->all(), // ← IMPORTANT\n";
echo "       // autres champs...\n";
echo "   ]);\n";
echo "\n3. Tester chaque formulaire individuellement\n";
echo "4. Générer un document et vérifier que toutes les données apparaissent\n";
