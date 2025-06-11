<?php
/**
 * Script pour corriger les problèmes d'upload dans les formulaires
 */

echo "🔧 CORRECTION AUTOMATIQUE DES PROBLÈMES D'UPLOAD\n";
echo "================================================\n\n";

$formFiles = [
    'resources/views/front/interactive-forms/attestation-domicile_standalone.blade.php',
    'resources/views/front/interactive-forms/certificat-celibat_standalone.blade.php',
    'resources/views/front/interactive-forms/certificat-mariage_standalone.blade.php'
];

foreach ($formFiles as $file) {
    if (!file_exists($file)) {
        echo "❌ Fichier non trouvé: $file\n";
        continue;
    }
    
    echo "🔧 Correction de: " . basename($file) . "\n";
    
    $content = file_get_contents($file);
    
    // 1. Corriger l'input file pour utiliser class="hidden" au lieu de style="display: none;"
    $content = str_replace(
        'style="display: none;" onchange="handleFileSelect(event)"',
        'class="hidden" onchange="handleFileSelect(event)"',
        $content
    );
    
    // 2. Corriger les div de messages pour utiliser class="hidden"
    $content = str_replace(
        '<div class="error-message" id="errorMessage"></div>',
        '<div class="error-message hidden" id="errorMessage"></div>',
        $content
    );
    
    $content = str_replace(
        '<div class="success-message" id="successMessage"></div>',
        '<div class="success-message hidden" id="successMessage"></div>',
        $content
    );
    
    // 3. Corriger les fonctions JavaScript pour utiliser classList
    $content = str_replace(
        'errorMessage.style.display = \'block\'',
        'errorMessage.classList.remove(\'hidden\')',
        $content
    );
    
    $content = str_replace(
        'errorMessage.style.display = \'none\'',
        'errorMessage.classList.add(\'hidden\')',
        $content
    );
    
    $content = str_replace(
        'successMessage.style.display = \'block\'',
        'successMessage.classList.remove(\'hidden\')',
        $content
    );
    
    $content = str_replace(
        'successMessage.style.display = \'none\'',
        'successMessage.classList.add(\'hidden\')',
        $content
    );
    
    // 4. Supprimer les duplications de const uploadArea
    $uploadAreaCount = preg_match_all('/const uploadArea = document\.querySelector/', $content);
    if ($uploadAreaCount > 1) {
        echo "  ⚠️  Duplication de 'const uploadArea' détectée ($uploadAreaCount fois)\n";
        
        // Garder seulement la première occurrence
        $content = preg_replace(
            '/(const uploadArea = document\.querySelector.*?\n.*?if \(uploadArea\) \{.*?\}\s*\}\s*\}\s*;?\s*){2,}/s',
            '$1',
            $content,
            1
        );
    }
    
    // 5. Ajouter la classe .hidden au CSS si elle n'existe pas
    if (strpos($content, '.hidden') === false) {
        $cssInsert = "\n        .hidden {\n            display: none !important;\n        }\n";
        $content = str_replace('        .navbar {', $cssInsert . '        .navbar {', $content);
        echo "  ✅ Classe .hidden ajoutée au CSS\n";
    }
    
    // Sauvegarder les modifications
    file_put_contents($file, $content);
    echo "  ✅ Fichier corrigé et sauvegardé\n\n";
}

echo "🎉 CORRECTION TERMINÉE !\n";
echo "========================\n";
echo "Actions effectuées :\n";
echo "1. ✅ Input file utilise maintenant class=\"hidden\"\n";
echo "2. ✅ Messages d'erreur/succès utilisent class=\"hidden\"\n";
echo "3. ✅ JavaScript utilise classList au lieu de style.display\n";
echo "4. ✅ Duplications de variables supprimées\n";
echo "5. ✅ Classe CSS .hidden ajoutée si manquante\n\n";
echo "🧪 TESTEZ MAINTENANT :\n";
echo "1. Ouvrez chaque formulaire dans le navigateur\n";
echo "2. Cliquez sur la zone d'upload\n";
echo "3. Sélectionnez un fichier\n";
echo "4. Vérifiez que le fichier apparaît dans la liste\n\n";
?>
