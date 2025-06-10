<?php

// Script de vérification des pièces jointes migrées
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\CitizenRequest;
use App\Models\Attachment;

echo "=== VÉRIFICATION DES PIÈCES JOINTES MIGRÉES ===\n\n";

// Vérifier les demandes avec des pièces jointes dans la nouvelle table
$requestsWithNewAttachments = CitizenRequest::with('citizenAttachments')->whereHas('citizenAttachments')->get();
echo "Demandes avec pièces jointes dans la nouvelle table: " . $requestsWithNewAttachments->count() . "\n";

foreach ($requestsWithNewAttachments as $request) {
    echo "- Demande {$request->id} ({$request->reference_number}): {$request->citizenAttachments->count()} fichier(s)\n";
    foreach ($request->citizenAttachments as $attachment) {
        echo "  * {$attachment->file_name} ({$attachment->file_type}, " . number_format($attachment->file_size / 1024, 1) . " KB)\n";
    }
}

echo "\n";

// Vérifier les demandes qui ont encore l'ancien format
$requestsWithOldAttachments = CitizenRequest::whereNotNull('attachments')
    ->whereJsonLength('attachments', '>', 0)
    ->whereDoesntHave('citizenAttachments')
    ->get();

echo "Demandes avec ancien format JSON restantes: " . $requestsWithOldAttachments->count() . "\n";

if ($requestsWithOldAttachments->count() > 0) {
    foreach ($requestsWithOldAttachments as $request) {
        $attachmentCount = is_array($request->attachments) ? count($request->attachments) : 0;
        echo "- Demande {$request->id} ({$request->reference_number}): {$attachmentCount} fichier(s) en format legacy\n";
    }
}

echo "\n=== TOTAL ===\n";
echo "Total pièces jointes dans la table 'attachments': " . Attachment::where('type', 'citizen')->count() . "\n";
echo "Vérification terminée.\n";
