<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Supprimer tous les anciens documents qui ne correspondent pas aux services de la mairie
        DB::table('documents')->delete();
        
        // Ajouter les nouveaux documents correspondant aux services de la mairie
        $documents = [
            [
                'name' => 'Extrait d\'acte de naissance',
                'type' => 'État civil',
                'description' => 'Demande d\'extrait d\'acte de naissance officiel.',
                'requirements' => json_encode([
                    'Pièce d\'identité du demandeur',
                    'Justificatif de lien de parenté (si différent du titulaire)',
                    'Frais administratifs'
                ]),
                'is_public' => true,
                'status' => 'active',
                'file_path' => 'documents/extrait-naissance.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certificat de mariage',
                'type' => 'État civil',
                'description' => 'Demande de certificat de mariage officiel.',
                'requirements' => json_encode([
                    'Pièces d\'identité des époux',
                    'Témoins (2 minimum)',
                    'Dossier de publication des bans',
                    'Certificat médical prénuptial'
                ]),
                'is_public' => true,
                'status' => 'active',
                'file_path' => 'documents/certificat-mariage.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Déclaration de naissance',
                'type' => 'État civil',
                'description' => 'Déclaration officielle de naissance à l\'état civil.',
                'requirements' => json_encode([
                    'Certificat d\'accouchement de la maternité',
                    'Pièces d\'identité des parents',
                    'Livret de famille (si existant)',
                    'Déclaration dans les 5 jours suivant la naissance'
                ]),
                'is_public' => true,
                'status' => 'active',
                'file_path' => 'documents/declaration-naissance.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certificat de célibat',
                'type' => 'État civil',
                'description' => 'Certificat attestant du statut de célibataire.',
                'requirements' => json_encode([
                    'Pièce d\'identité valide',
                    'Extrait d\'acte de naissance récent (moins de 3 mois)',
                    'Déclaration sur l\'honneur',
                    'Frais administratifs'
                ]),
                'is_public' => true,
                'status' => 'active',
                'file_path' => 'documents/certificat-celibat.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certificat de décès',
                'type' => 'État civil',
                'description' => 'Demande de certificat de décès officiel.',
                'requirements' => json_encode([
                    'Certificat médical de décès',
                    'Pièce d\'identité du déclarant',
                    'Justificatif de lien avec le défunt',
                    'Livret de famille (si existant)'
                ]),
                'is_public' => true,
                'status' => 'active',
                'file_path' => 'documents/certificat-deces.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($documents as $document) {
            DB::table('documents')->insert($document);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les anciens documents si nécessaire
        DB::table('documents')->delete();
    }
};
