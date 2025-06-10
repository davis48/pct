<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création de documents de test
        $documents = [
            [
                'name' => 'Formulaire de demande de carte nationale d\'identité',
                'type' => 'Identité',
                'description' => 'Formulaire officiel pour demander une carte nationale d\'identité.',
                'requirements' => json_encode([
                    'Pièce d\'identité valide',
                    'Photo d\'identité récente',
                    'Justificatif de domicile'
                ]),
            ],
            [
                'name' => 'Formulaire de demande de passeport',
                'type' => 'Identité',
                'description' => 'Formulaire officiel pour demander un passeport.',
                'requirements' => json_encode([
                    'Pièce d\'identité valide',
                    'Photo d\'identité récente',
                    'Justificatif de domicile',
                    'Ancien passeport (si renouvellement)'
                ]),
            ],
            [
                'name' => 'Formulaire de déclaration de naissance',
                'type' => 'État civil',
                'description' => 'Formulaire pour déclarer une naissance à l\'état civil.',
                'requirements' => json_encode([
                    'Certificat de naissance de l\'hôpital',
                    'Pièces d\'identité des parents',
                    'Livret de famille (si existant)'
                ]),
            ],
            [
                'name' => 'Demande de certificat de résidence',
                'type' => 'Résidence',
                'description' => 'Formulaire pour obtenir un certificat de résidence.',
                'requirements' => json_encode([
                    'Pièce d\'identité valide',
                    'Justificatif de domicile',
                    'Photo d\'identité récente'
                ]),
            ],
            [
                'name' => 'Guide des procédures administratives',
                'type' => 'Guide',
                'description' => 'Guide complet des procédures administratives pour les citoyens.',
                'requirements' => json_encode([]),
            ],
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
