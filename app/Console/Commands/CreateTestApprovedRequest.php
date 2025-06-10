<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CitizenRequest;
use App\Models\Document;
use App\Models\User;

class CreateTestApprovedRequest extends Command
{
    protected $signature = 'test:create-approved-request';
    protected $description = 'Créer une demande approuvée pour tester le téléchargement PDF';

    public function handle()
    {
        // Trouver ou créer un utilisateur de test
        $user = User::where('email', 'test@example.com')->first();
        
        if (!$user) {
            $user = User::create([
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
            ]);
            $this->info("Utilisateur de test créé : {$user->email}");
        }

        // Trouver un document
        $document = Document::first();
        
        if (!$document) {
            $this->error('Aucun document trouvé. Veuillez d\'abord créer des documents.');
            return;
        }

        // Créer une demande approuvée
        $request = CitizenRequest::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'type' => $document->name,
            'description' => 'Demande de test pour le téléchargement PDF',
            'reason' => 'Test de génération de document',
            'status' => 'approved',
            'reference_number' => 'TEST-' . date('Ymd-His'),
            'payment_status' => 'paid',
            'place_of_birth' => 'Abidjan',
            'profession' => 'Enseignant',
            'cin_number' => 'CI1234567890',
            'nationality' => 'Ivoirienne',
        ]);

        $this->info("Demande approuvée créée avec succès !");
        $this->info("Référence : {$request->reference_number}");
        $this->info("Utilisateur : {$user->email} (mot de passe: password123)");
        $this->info("Document : {$document->name}");
        $this->info("Vous pouvez maintenant tester le téléchargement dans l'interface.");
    }
}
