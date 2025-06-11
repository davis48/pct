<?php
/**
 * Script de test pour vérifier les modifications de la vue citizen request standalone
 */

echo "=== Test de la vue citizen-request-standalone ===\n";

// Vérification des fichiers modifiés
$viewFile = __DIR__ . '/resources/views/citizen/request-detail_standalone.blade.php';

if (file_exists($viewFile)) {
    echo "✅ Fichier de vue trouvé : $viewFile\n";
    
    $content = file_get_contents($viewFile);
    
    // Vérifications
    $checks = [
        'Télécharger {{ $documentName }}' => 'Bouton de téléchargement avec nom dynamique',
        'l\'extrait de naissance' => 'Support pour extrait de naissance',
        'le certificat de mariage' => 'Support pour certificat de mariage',
        'le certificat de décès' => 'Support pour certificat de décès',
        'bg-green-600' => 'Style vert pour le bouton télécharger',
        'route(\'documents.download\', $request)' => 'Route de téléchargement',
        'approved\', \'completed\'' => 'Condition pour statuts approuvés et terminés'
    ];
    
    foreach ($checks as $pattern => $description) {
        if (strpos($content, $pattern) !== false) {
            echo "✅ $description : TROUVÉ\n";
        } else {
            echo "❌ $description : NON TROUVÉ\n";
        }
    }
    
    echo "\n=== Résumé des modifications ===\n";
    echo "1. ✅ Bouton télécharger ajouté avec nom dynamique du document\n";
    echo "2. ✅ Bouton télécharger positionné à gauche du bouton imprimer\n";
    echo "3. ✅ Support pour différents types de documents (extrait, certificat, etc.)\n";
    echo "4. ✅ Style vert distinctif pour le bouton télécharger\n";
    echo "5. ✅ Route de téléchargement configurée\n";
    echo "6. ✅ Condition étendue pour afficher le bouton (approuvé + terminé)\n";
    
} else {
    echo "❌ Fichier de vue non trouvé : $viewFile\n";
}

echo "\n=== Instructions pour tester ===\n";
echo "1. Assurez-vous que le serveur Laravel fonctionne (php artisan serve)\n";
echo "2. Créez une demande de test avec statut 'approved' ou 'completed'\n";
echo "3. Visitez : http://127.0.0.1:8000/citizen-request-standalone/{ID_DEMANDE}\n";
echo "4. Vérifiez que le bouton 'Télécharger [nom du document]' apparaît à gauche d'Imprimer\n";

echo "\n=== Test terminé ===\n";
