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
        $statuses = ['pending', 'in_progress', 'approved', 'rejected'];

        // Récupérer les IDs des agents
        $agentIds = User::where('role', 'agent')->pluck('id')->toArray();

        // Créer des demandes aléatoires
        for ($i = 0; $i < 20; $i++) {
            $status = $statuses[array_rand($statuses)];
            $assigned_to = null;
            $processed_by = null;
            $processed_at = null;

            // Si la demande est en cours ou terminée, assigner un agent
            if (in_array($status, ['in_progress', 'approved', 'rejected']) && !empty($agentIds)) {
                $assigned_to = $agentIds[array_rand($agentIds)];
                
                if (in_array($status, ['approved', 'rejected'])) {
                    $processed_by = $assigned_to;
                    $processed_at = fake()->dateTimeBetween('-1 month', 'now');
                }
            }

            CitizenRequest::create([
                'user_id' => $userIds[array_rand($userIds)],
                'document_id' => $documentIds[array_rand($documentIds)],
                'type' => $requestTypes[array_rand($requestTypes)],
                'description' => 'Demande de test numéro ' . ($i + 1) . '. ' . fake()->paragraph(),
                'status' => $status,
                'admin_comments' => in_array($status, ['approved', 'rejected']) ? fake()->sentence() : null,
                'attachments' => null,
                'assigned_to' => $assigned_to,
                'processed_by' => $processed_by,
                'processed_at' => $processed_at,
            ]);
        }
    }
}
