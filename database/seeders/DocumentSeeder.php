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
                'title' => 'Formulaire de demande de carte nationale d\'identité',
                'description' => 'Formulaire officiel pour demander une carte nationale d\'identité.',
                'category' => 'Identité',
                'file_path' => 'documents/cni-form.pdf',
                'is_public' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Formulaire de demande de passeport',
                'description' => 'Formulaire officiel pour demander un passeport.',
                'category' => 'Identité',
                'file_path' => 'documents/passport-form.pdf',
                'is_public' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Formulaire de déclaration de naissance',
                'description' => 'Formulaire pour déclarer une naissance à l\'état civil.',
                'category' => 'État civil',
                'file_path' => 'documents/birth-form.pdf',
                'is_public' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Demande de certificat de résidence',
                'description' => 'Formulaire pour obtenir un certificat de résidence.',
                'category' => 'Résidence',
                'file_path' => 'documents/residence-form.pdf',
                'is_public' => true,
                'status' => 'active',
            ],
            [
                'title' => 'Guide des procédures administratives',
                'description' => 'Guide complet des procédures administratives pour les citoyens.',
                'category' => 'Guide',
                'file_path' => 'documents/procedures-guide.pdf',
                'is_public' => true,
                'status' => 'active',
            ],
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}
