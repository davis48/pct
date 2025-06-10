<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Créer des données de test pour les documents PDF
     */
    public function up()
    {
        // Vérifier si l'utilisateur de test existe
        $testUser = DB::table('users')->where('email', 'test@example.com')->first();
        
        if (!$testUser) {
            // Créer un utilisateur de test
            $userId = DB::table('users')->insertGetId([
                'nom' => 'KOUASSI',
                'prenoms' => 'Jean-Baptiste',
                'email' => 'test@example.com',
                'password' => bcrypt('password123'),
                'role' => 'citizen',
                'date_naissance' => '1990-05-15',
                'address' => '123 Rue de la Paix, Abidjan',
                'genre' => 'M',
                'phone' => '+22501020304',
                'profession' => 'Enseignant',
                'place_of_birth' => 'Abidjan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $testUser->id;
        }

        // Trouver un document existant ou en créer un
        $document = DB::table('documents')->first();
        
        if (!$document) {
            $documentId = DB::table('documents')->insertGetId([
                'name' => 'Extrait d\'acte de naissance',
                'title' => 'Extrait d\'acte de naissance',
                'description' => 'Document officiel d\'état civil',
                'price' => 5000,
                'processing_time' => '3 jours',
                'required_documents' => json_encode(['Copie CNI', 'Photo d\'identité']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $documentId = $document->id;
        }        // Créer une demande approuvée de test
        DB::table('citizen_requests')->insert([
            'user_id' => $userId,
            'document_id' => $documentId,
            'type' => 'Extrait d\'acte de naissance',
            'description' => 'Demande de test pour téléchargement PDF',
            'reason' => 'Test de génération de document',
            'status' => 'approved',
            'reference_number' => 'TEST-' . date('Ymd-His'),
            'payment_status' => 'paid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Supprimer les données de test
     */
    public function down()
    {
        DB::table('citizen_requests')->where('reference_number', 'like', 'TEST-%')->delete();
        DB::table('users')->where('email', 'test@example.com')->delete();
    }
};
