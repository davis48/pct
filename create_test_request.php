<?php

use App\Models\User;
use App\Models\Document;
use App\Models\CitizenRequest;

// Créer une demande de test avec statut approved
$user = User::where('role', 'citizen')->first();
$document = Document::first();

if ($user && $document) {
    $request = CitizenRequest::create([
        'user_id' => $user->id,
        'document_id' => $document->id,
        'type' => 'Demande de test PDF',
        'description' => 'Test de génération de document PDF',
        'reason' => 'Test fonctionnel',
        'status' => 'approved',
        'reference_number' => 'TEST-PDF-' . uniqid(),
        'urgency' => 'normal',
        'place_of_birth' => 'Abidjan',
        'profession' => 'Développeur',
        'cin_number' => 'CI0123456789',
        'nationality' => 'Ivoirienne'
    ]);
    
    echo "Demande de test créée avec ID: " . $request->id . "\n";
    echo "Référence: " . $request->reference_number . "\n";
    echo "URL de téléchargement: /documents/" . $request->id . "/download\n";
} else {
    echo "Erreur: Utilisateur ou document introuvable\n";
}
