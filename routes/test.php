<?php

use Illuminate\Support\Facades\Route;
use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/test-middleware', function () {
    return 'Middleware test successful!';
})->middleware(['auth', 'role:agent']);

// Test route pour téléchargement de document
Route::get('/test-document-download', function () {
    // Créer un utilisateur de test s'il n'existe pas
    $user = User::firstOrCreate([
        'email' => 'test@cocody.ci'
    ], [
        'name' => 'Utilisateur Test',
        'password' => bcrypt('password'),
        'role' => 'citizen',
        'status' => 'active'
    ]);
    
    // Connecter l'utilisateur de test
    Auth::login($user);
    
    // Créer une demande de test avec des données d'extrait de naissance
    $testData = [
        'form_type' => 'extrait-naissance',
        'form_data' => [
            'name' => 'KOUAME',
            'first_names' => 'Jean Pierre',
            'gender' => 'Masculin',
            'date_of_birth' => '1990-05-15',
            'place_of_birth' => 'Abidjan',
            'nationality' => 'Ivoirienne',
            'birth_time' => '14:30',
            'father_name' => 'KOUAME',
            'prenoms_pere' => 'Paul',
            'age_pere' => 55,
            'profession_pere' => 'Commerçant',
            'domicile_pere' => 'Cocody, Abidjan',
            'mother_name' => 'TRAORE',
            'prenoms_mere' => 'Marie',
            'age_mere' => 50,
            'profession_mere' => 'Enseignante',
            'domicile_mere' => 'Cocody, Abidjan',
            'centre_etat_civil' => 'Mairie de Cocody',
            'registry_number' => 'REG001/2024',
            'registration_date' => '1990-05-16',
            'declarant_name' => 'KOUAME Paul'
        ]
    ];
    
    $citizenRequest = CitizenRequest::create([
        'user_id' => $user->id,
        'type' => 'extrait-acte',
        'description' => 'Test de génération d\'extrait de naissance',
        'status' => 'completed',
        'payment_status' => 'paid',
        'additional_data' => json_encode($testData),
        'reference_number' => 'TEST-' . now()->format('YmdHis')
    ]);
    
    // Rediriger vers la route de téléchargement
    return redirect()->route('interactive-forms.download', [
        'formType' => 'extrait-naissance',
        'requestId' => $citizenRequest->id
    ]);
})->name('test.document.download');

// Test route pour le template d'extrait de naissance
Route::get('/test-template-extrait', function () {
    $templateData = [
        'form_data' => [
            'name' => 'KOUAME',
            'first_names' => 'Jean Pierre',
            'gender' => 'Masculin',
            'date_of_birth' => '1990-05-15',
            'place_of_birth' => 'Abidjan',
            'nationality' => 'Ivoirienne',
            'birth_time' => '14:30',
            'father_name' => 'KOUAME',
            'prenoms_pere' => 'Paul',
            'age_pere' => 55,
            'profession_pere' => 'Commerçant',
            'domicile_pere' => 'Cocody, Abidjan',
            'mother_name' => 'TRAORE',
            'prenoms_mere' => 'Marie',
            'age_mere' => 50,
            'profession_mere' => 'Enseignante',
            'domicile_mere' => 'Cocody, Abidjan',
            'centre_etat_civil' => 'Mairie de Cocody',
            'registry_number' => 'REG001/2024',
            'registration_date' => '1990-05-16',
            'declarant_name' => 'KOUAME Paul'
        ],
        'reference_number' => 'TEST-' . now()->format('YmdHis'),
        'date_generation' => now(),
        'municipality' => 'Commune de Cocody',
        'mayor_name' => 'M. le Maire de Cocody'
    ];
    
    try {
        // Générer le PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.templates.extrait-naissance', $templateData);
        
        return $pdf->stream('test-extrait-naissance.pdf');
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de la génération du PDF',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('test.template.extrait');
