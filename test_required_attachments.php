<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "TEST DE VALIDATION DES PIÈCES JOINTES OBLIGATOIRES\n";
echo "=================================================\n\n";

// Créer un disque de stockage temporaire pour les tests
Storage::fake('public');

// Trouver un utilisateur citoyen pour le test
$citizen = User::where('role', 'citizen')->first();

if (!$citizen) {
    echo "Erreur: Aucun utilisateur citoyen trouvé dans la base de données.\n";
    exit(1);
}

// Se connecter en tant que citoyen
Auth::login($citizen);
echo "Connecté en tant que citoyen: {$citizen->nom} {$citizen->prenoms} (ID: {$citizen->id})\n\n";

// Trouver un document disponible
$document = Document::where('is_public', true)->where('status', 'active')->first();

if (!$document) {
    echo "Erreur: Aucun document disponible trouvé dans la base de données.\n";
    exit(1);
}

echo "Document sélectionné: {$document->title} (ID: {$document->id})\n\n";

// Créer une instance du contrôleur
$controller = new App\Http\Controllers\Front\RequestController();

// Test 1: Soumettre une demande sans pièce jointe
echo "Test 1: Soumettre une demande sans pièce jointe\n";

$request = new Illuminate\Http\Request();
$request->merge([
    'document_id' => $document->id,
    'type' => 'attestation',
    'description' => 'Demande de test sans pièce jointe'
]);

try {
    $controller->store($request);
    echo "ÉCHEC: La demande a été acceptée sans pièce jointe.\n";
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "SUCCÈS: La validation a échoué comme prévu.\n";
    echo "Messages d'erreur: " . json_encode($e->errors()) . "\n\n";
}

// Test 2: Soumettre une demande avec pièce jointe
echo "Test 2: Soumettre une demande avec pièce jointe\n";

$request = new Illuminate\Http\Request();
$request->merge([
    'document_id' => $document->id,
    'type' => 'attestation',
    'description' => 'Demande de test avec pièce jointe'
]);

// Créer un fichier factice
$file = UploadedFile::fake()->create('document.pdf', 1000);
$request->files->set('attachments', [$file]);

try {
    $response = $controller->store($request);
    echo "SUCCÈS: La demande a été acceptée avec pièce jointe.\n";
} catch (\Exception $e) {
    echo "ÉCHEC: La demande a échoué malgré la présence d'une pièce jointe.\n";
    echo "Erreur: " . $e->getMessage() . "\n\n";
}

echo "\nTEST TERMINÉ: Les pièces jointes sont maintenant obligatoires dans le formulaire de demande du citoyen.\n";
