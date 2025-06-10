<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Recherche des demandes avec des statuts incorrects...\n";

// Trouver les demandes qui ont un payment_status 'paid' mais status 'pending' au lieu de 'en_attente'
$incorrectRequests = DB::table('citizen_requests')
    ->where('payment_status', 'paid')
    ->where('status', 'pending')
    ->get();

echo "Trouvé " . $incorrectRequests->count() . " demandes avec des statuts incorrects.\n";

if ($incorrectRequests->count() > 0) {
    echo "Correction des statuts...\n";
    
    $corrected = DB::table('citizen_requests')
        ->where('payment_status', 'paid')
        ->where('status', 'pending')
        ->update(['status' => 'en_attente']);
    
    echo "✅ {$corrected} demandes corrigées avec le statut 'en_attente'.\n";
} else {
    echo "✅ Aucune correction nécessaire.\n";
}

// Afficher un résumé des demandes payées
$paidRequests = DB::table('citizen_requests')
    ->where('payment_status', 'paid')
    ->get(['id', 'user_id', 'type', 'status', 'created_at']);

echo "\n📊 Résumé des demandes payées :\n";
foreach ($paidRequests as $request) {
    echo "- ID: {$request->id}, Type: {$request->type}, Status: {$request->status}, Créée: {$request->created_at}\n";
}

echo "\nTerminé !\n";
