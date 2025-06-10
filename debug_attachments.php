<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

echo "=== Debug des Attachments ===\n\n";

// Récupérer tous les attachments
$attachments = Attachment::limit(5)->get();

echo "Nombre d'attachments trouvés: " . $attachments->count() . "\n\n";

foreach ($attachments as $attachment) {
    echo "--- Attachment ID: {$attachment->id} ---\n";
    echo "Nom du fichier: {$attachment->file_name}\n";
    echo "Chemin dans DB: {$attachment->file_path}\n";
    echo "Taille: " . number_format($attachment->file_size / 1024, 2) . " KB\n";
    
    // Vérifier différents chemins possibles
    $pathsToCheck = [
        $attachment->file_path,
        'public/' . $attachment->file_path,
        'attachments/' . basename($attachment->file_path),
        'public/attachments/' . basename($attachment->file_path)
    ];
    
    echo "Vérification des chemins Storage:\n";
    foreach ($pathsToCheck as $path) {
        $exists = Storage::exists($path);
        echo "  - {$path}: " . ($exists ? "✓ EXISTE" : "✗ N'existe pas") . "\n";
    }
    
    // Vérifier les chemins physiques
    $physicalPaths = [
        storage_path('app/' . $attachment->file_path),
        storage_path('app/public/' . $attachment->file_path),
        storage_path('app/public/attachments/' . basename($attachment->file_path)),
        public_path('storage/' . $attachment->file_path),
        public_path('storage/attachments/' . basename($attachment->file_path))
    ];
    
    echo "Vérification des chemins physiques:\n";
    foreach ($physicalPaths as $path) {
        $exists = file_exists($path);
        echo "  - {$path}: " . ($exists ? "✓ EXISTE" : "✗ N'existe pas") . "\n";
    }
    
    echo "\n";
}

echo "=== Fin du debug ===\n";
