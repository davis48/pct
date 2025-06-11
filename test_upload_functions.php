<?php
/**
 * Script de test rapide pour vérifier les uploads
 */

$formTemplates = [
    'attestation-domicile_standalone',
    'certificat-celibat_standalone', 
    'certificat-mariage_standalone',
    'certificat-deces_standalone',
    'extrait-naissance_standalone',
    'legalisation_standalone'
];

echo "🔍 VÉRIFICATION DES FONCTIONS UPLOAD DANS LES FORMULAIRES\n";
echo "=========================================================\n\n";

foreach ($formTemplates as $template) {
    $filePath = "resources/views/front/interactive-forms/{$template}.blade.php";
    
    echo "📋 Formulaire: $template\n";
    
    if (!file_exists($filePath)) {
        echo "❌ Fichier non trouvé: $filePath\n\n";
        continue;
    }
    
    $content = file_get_contents($filePath);
    
    // Vérifications
    $checks = [
        'handleFileSelect' => [
            'pattern' => 'window\.handleFileSelect\s*=\s*function',
            'description' => 'Fonction handleFileSelect'
        ],
        'removeDocumentFile' => [
            'pattern' => 'window\.removeDocumentFile\s*=\s*function',
            'description' => 'Fonction removeDocumentFile'
        ],
        'updateDocumentFileList' => [
            'pattern' => 'function updateDocumentFileList\(',
            'description' => 'Fonction updateDocumentFileList'
        ],
        'updateDocumentFileCounter' => [
            'pattern' => 'function updateDocumentFileCounter\(',
            'description' => 'Fonction updateDocumentFileCounter'
        ],
        'updateDocumentFileInput' => [
            'pattern' => 'function updateDocumentFileInput\(',
            'description' => 'Fonction updateDocumentFileInput'
        ],
        'inputFile' => [
            'pattern' => 'type="file".*?onchange="handleFileSelect\(event\)"',
            'description' => 'Input file avec onchange'
        ]
    ];
    
    foreach ($checks as $key => $check) {
        if (preg_match('/' . $check['pattern'] . '/s', $content)) {
            echo "  ✅ " . $check['description'] . "\n";
        } else {
            echo "  ❌ " . $check['description'] . " - MANQUANT\n";
        }
    }
    
    // Vérification des duplications
    $duplicates = [
        'handleFileSelect' => preg_match_all('/window\.handleFileSelect\s*=\s*function/', $content),
        'removeDocumentFile' => preg_match_all('/window\.removeDocumentFile\s*=\s*function/', $content),
        'updateDocumentFileList' => preg_match_all('/function updateDocumentFileList\(/', $content),
    ];
    
    foreach ($duplicates as $func => $count) {
        if ($count > 1) {
            echo "  ⚠️  DUPLICATION détectée: $func ($count fois)\n";
        }
    }
    
    echo "\n";
}

echo "💡 CONSEILS:\n";
echo "============\n";
echo "1. Chaque formulaire doit avoir toutes les fonctions listées\n";
echo "2. Aucune fonction ne doit être dupliquée\n";
echo "3. L'input file doit avoir onchange=\"handleFileSelect(event)\"\n";
echo "4. Testez chaque formulaire individuellement dans le navigateur\n";
echo "5. Vérifiez la console JavaScript pour les erreurs\n";
?>
