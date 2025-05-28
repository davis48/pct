<?php

use App\Models\CitizenRequest;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\Hash;

// Créer un utilisateur citoyen de test s'il n'existe pas
$citizen = User::firstOrCreate(
    ['email' => 'citoyen@test.com'],
    [
        'nom' => 'Test',
        'prenoms' => 'Citoyen',
        'password' => Hash::make('password'),
        'role' => 'citizen',
        'telephone' => '1234567890',
        'date_naissance' => '1990-01-01',
        'lieu_naissance' => 'Test',
        'profession' => 'Test',
        'adresse' => 'Test',
    ]
);

echo "Citoyen créé/trouvé: {$citizen->prenoms} {$citizen->nom}\n";

// Créer un document de test s'il n'existe pas
$document = Document::firstOrCreate(
    ['title' => 'Certificat de Naissance'],
    [
        'category' => 'État Civil',
        'required_documents' => ['Extrait de naissance', 'Pièce d\'identité'],
        'processing_time' => '3-5 jours ouvrables',
        'fees' => 5000,
        'description' => 'Certificat officiel de naissance',
        'is_active' => true,
    ]
);

echo "Document créé/trouvé: {$document->title}\n";

// Créer quelques demandes de test
for ($i = 1; $i <= 5; $i++) {
    $request = CitizenRequest::create([
        'user_id' => $citizen->id,
        'document_id' => $document->id,
        'type' => 'certificate',
        'description' => "Demande de test numéro {$i}",
        'status' => rand(0, 1) ? 'pending' : 'in_progress',
        'attachments' => ['test_document_' . $i . '.pdf'],
        'is_read' => false,
    ]);
    
    echo "Demande créée: {$request->reference_number}\n";
}

echo "Données de test créées avec succès!\n";
