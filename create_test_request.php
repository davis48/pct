<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: __DIR__)
    ->withRouting(
        web: __DIR__.'/routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CRÉATION D'UNE DEMANDE TEST POUR LE PAIEMENT ===\n\n";

try {
    // Trouvons un utilisateur citoyen
    $user = \App\Models\User::where('role', 'citizen')->first();
    
    if (!$user) {
        echo "❌ Aucun utilisateur citoyen trouvé\n";
        exit(1);
    }
    
    echo "✅ Utilisateur trouvé: {$user->name} (ID: {$user->id})\n";
    
    // Trouvons un document
    $document = \App\Models\Document::first();
    
    if (!$document) {
        echo "❌ Aucun document trouvé\n";
        exit(1);
    }
    
    echo "✅ Document trouvé: {$document->name} (ID: {$document->id})\n";
    
    // Créer une demande en brouillon nécessitant un paiement
    $request = \App\Models\CitizenRequest::create([
        'user_id' => $user->id,
        'document_id' => $document->id,
        'type' => 'acte-naissance',
        'description' => 'Demande d\'acte de naissance pour test de paiement via interface web',
        'status' => 'draft',
        'payment_status' => 'unpaid',
        'payment_required' => true,
        'reference_number' => 'REQ-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
    ]);
    
    echo "✅ Demande créée avec succès!\n";
    echo "- ID: {$request->id}\n";
    echo "- Référence: {$request->reference_number}\n";
    echo "- Status: {$request->status}\n";
    echo "- Payment Status: {$request->payment_status}\n";
    echo "- Type: {$request->type}\n";
    
    echo "\n🌐 ÉTAPES POUR TESTER VIA L'INTERFACE WEB:\n";
    echo "1. Allez sur: http://127.0.0.1:8000/login\n";
    echo "2. Connectez-vous avec: {$user->email}\n";
    echo "3. Allez sur le dashboard citoyen\n";
    echo "4. Vous devriez voir la demande en statut 'À Payer'\n";
    echo "5. Cliquez pour effectuer le paiement\n";
    echo "6. Testez le processus de paiement mobile money\n";
    echo "7. Vérifiez que la notification est créée\n";
    echo "8. Vérifiez que le statut change après paiement\n";
    
    // Afficher l'email et un mot de passe par défaut si disponible
    echo "\n📧 INFORMATIONS DE CONNEXION:\n";
    echo "Email: {$user->email}\n";
    echo "Mot de passe: probablement 'password' (essayez ce mot de passe standard)\n";
    
    // Compter les demandes actuelles de ce user
    $allRequests = \App\Models\CitizenRequest::where('user_id', $user->id)->get();
    $draftCount = $allRequests->where('status', 'draft')->count();
    $paidCount = $allRequests->where('payment_status', 'paid')->count();
    
    echo "\n📊 STATISTIQUES ACTUELLES POUR CET UTILISATEUR:\n";
    echo "- Total demandes: {$allRequests->count()}\n";
    echo "- Demandes en brouillon (À Payer): {$draftCount}\n";
    echo "- Demandes payées: {$paidCount}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== DEMANDE CRÉÉE POUR TEST ===\n";
