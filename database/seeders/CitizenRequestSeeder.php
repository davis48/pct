<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CitizenRequest;
use App\Models\User;
use App\Models\Document;

class CitizenRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les IDs des utilisateurs citoyens
        $userIds = User::where('role', 'citizen')->pluck('id')->toArray();

        // Récupérer les IDs des documents
        $documentIds = Document::pluck('id')->toArray();

        // Types de demandes
        $requestTypes = ['certificate', 'authorization', 'complaint', 'information'];

        // Statuts des demandes
        $statuses = ['pending', 'approved', 'rejected'];

        // Créer des demandes aléatoires
        for ($i = 0; $i < 20; $i++) {
            CitizenRequest::create([
                'user_id' => $userIds[array_rand($userIds)],
                'document_id' => $documentIds[array_rand($documentIds)],
                'type' => $requestTypes[array_rand($requestTypes)],
                'description' => 'Demande de test numéro ' . ($i + 1) . '. ' . fake()->paragraph(),
                'status' => $statuses[array_rand($statuses)],
                'admin_comments' => rand(0, 1) ? fake()->sentence() : null,
                'attachments' => null,
            ]);
        }
    }
}
