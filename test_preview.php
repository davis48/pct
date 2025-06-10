<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

echo "=== Test de prévisualisation avec disque public ===\n\n";

// Prendre le premier attachment
$attachment = Attachment::first();

if ($attachment) {
    echo "Test avec l'attachment ID: {$attachment->id}\n";
    echo "Nom: {$attachment->file_name}\n";
    echo "Chemin dans DB: {$attachment->file_path}\n\n";
    
    $filePath = $attachment->file_path;
    
    echo "Test avec disque public:\n";
    echo "Chemin: {$filePath}\n";
    echo "Fichier existe via Storage::disk('public'): " . (Storage::disk('public')->exists($filePath) ? "✓ OUI" : "✗ NON") . "\n";
    
    if (Storage::disk('public')->exists($filePath)) {
        $size = Storage::disk('public')->size($filePath);
        echo "Taille du fichier: {$size} bytes\n";
        
        // Test du type MIME avec le chemin physique
        $physicalPath = storage_path('app/public/' . $filePath);
        if (file_exists($physicalPath)) {
            $mimeType = mime_content_type($physicalPath);
            echo "Type MIME: {$mimeType}\n";
        }
        
        echo "\n✓ La prévisualisation devrait fonctionner pour cet attachment !\n";
        echo "URL de test: http://localhost:8000/agent/requests/attachment/{$attachment->id}/preview\n";
    } else {
        echo "\n✗ Le fichier n'existe pas sur le disque public.\n";
        
        // Vérification alternative
        $physicalPath = storage_path('app/public/' . $filePath);
        echo "Chemin physique: {$physicalPath}\n";
        echo "Existe physiquement: " . (file_exists($physicalPath) ? "✓ OUI" : "✗ NON") . "\n";
    }
} else {
    echo "Aucun attachment trouvé dans la base de données.\n";
}

echo "\n=== Fin du test ===\n";
