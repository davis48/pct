<?php

// Initialiser les services Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\CitizenRequest;

// Récupérer toutes les demandes avec des pièces jointes
$requests = CitizenRequest::whereNotNull('attachments')->get();

echo "Vérification des pièces jointes de {$requests->count()} demandes\n\n";

foreach ($requests as $request) {
    echo "Demande #{$request->reference_number} (ID: {$request->id}):\n";
    
    if (!is_array($request->attachments)) {
        echo "  - Format des pièces jointes invalide: " . gettype($request->attachments) . "\n";
        continue;
    }
    
    echo "  - " . count($request->attachments) . " pièce(s) jointe(s) trouvée(s)\n";
    
    foreach ($request->attachments as $index => $attachment) {
        $filename = is_string($attachment) ? $attachment : ($attachment['name'] ?? 'inconnu');
        echo "    - Pièce jointe #{$index}: {$filename}\n";
        
        // Vérifier dans le stockage
        $possiblePaths = [
            "attachments/{$filename}",
            "public/attachments/{$filename}",
            "citizen_attachments/{$filename}",
            "public/citizen_attachments/{$filename}",
            $filename
        ];
        
        $found = false;
        foreach ($possiblePaths as $path) {
            if (Storage::exists($path)) {
                echo "      ✓ Trouvé à {$path} (" . Storage::size($path) . " octets)\n";
                $found = true;
                break;
            }
        }
        
        // Vérifier dans le dossier public
        if (!$found) {
            $publicPaths = [
                public_path("storage/attachments/{$filename}"),
                public_path("attachments/{$filename}"),
                public_path("uploads/{$filename}"),
                public_path($filename)
            ];
            
            foreach ($publicPaths as $path) {
                if (file_exists($path)) {
                    echo "      ✓ Trouvé dans le dossier public à {$path} (" . filesize($path) . " octets)\n";
                    $found = true;
                    break;
                }
            }
        }
        
        if (!$found) {
            echo "      ✗ Fichier non trouvé sur le serveur\n";
            
            // Essayer de trouver où les fichiers sont stockés
            if ($index === 0) {
                $baseDirs = [
                    storage_path('app'),
                    storage_path('app/public'),
                    public_path('storage'),
                    public_path()
                ];
                
                echo "      Recherche récursive de \"{$filename}\" dans les dossiers de stockage...\n";
                
                foreach ($baseDirs as $baseDir) {
                    if (!is_dir($baseDir)) continue;
                    
                    $iterator = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST
                    );
                    
                    foreach ($iterator as $file) {
                        if ($file->isFile() && $file->getFilename() === $filename) {
                            echo "      ! Fichier trouvé à {$file->getPathname()}\n";
                        }
                    }
                }
            }
        }
    }
    
    echo "\n";
}

echo "Test terminé.\n";
