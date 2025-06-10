<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CitizenRequest;
use App\Models\User;

class TestCompleteDocumentWorkflow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:complete-workflow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test complet du workflow de documents avec les nouvelles améliorations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST COMPLET DU WORKFLOW DE DOCUMENTS ===');
        $this->newLine();

        // Créer quelques demandes de test
        $user = User::where('role', 'citizen')->first();
        
        if (!$user) {
            $this->error('Aucun utilisateur citoyen trouvé');
            return;
        }

        $this->info('👤 Utilisateur de test: ' . $user->nom . ' ' . $user->prenoms);
        $this->newLine();

        // Test 1: Demande via formulaire interactif (extrait de naissance)
        $this->info('🧪 Test 1: Formulaire interactif - Extrait de naissance');
        $request1 = CitizenRequest::create([
            'user_id' => $user->id,
            'type' => 'extrait-acte',
            'description' => 'Demande via formulaire interactif - Extrait de naissance',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_required' => true,
            'additional_data' => json_encode([
                'form_type' => 'extrait-naissance',
                'generated_via' => 'interactive_form',
                'form_data' => [
                    'name' => $user->nom . ' ' . $user->prenoms,
                    'date_of_birth' => '1990-01-01',
                    'place_of_birth' => 'Abidjan'
                ]
            ])
        ]);

        $this->line("  ✅ Demande créée: {$request1->reference_number}");
        $this->line("  📄 Titre: {$request1->getDocumentTitle()}");
        $this->line("  📂 Catégorie: {$request1->getDocumentCategory()}");
        $this->newLine();

        // Test 2: Demande via formulaire interactif (attestation de domicile)
        $this->info('🧪 Test 2: Formulaire interactif - Attestation de domicile');
        $request2 = CitizenRequest::create([
            'user_id' => $user->id,
            'type' => 'attestation',
            'description' => 'Demande via formulaire interactif - Attestation de domicile',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_required' => true,
            'additional_data' => json_encode([
                'form_type' => 'attestation-domicile',
                'generated_via' => 'interactive_form',
                'form_data' => [
                    'name' => $user->nom . ' ' . $user->prenoms,
                    'address' => '123 Rue de la Paix, Abidjan',
                    'residence_duration' => '5 ans'
                ]
            ])
        ]);

        $this->line("  ✅ Demande créée: {$request2->reference_number}");
        $this->line("  📄 Titre: {$request2->getDocumentTitle()}");
        $this->line("  📂 Catégorie: {$request2->getDocumentCategory()}");
        $this->newLine();

        // Test 3: Demande classique (si il y a un document dans la DB)
        $document = \App\Models\Document::first();
        if ($document) {
            $this->info('🧪 Test 3: Demande classique avec document');
            $request3 = CitizenRequest::create([
                'user_id' => $user->id,
                'document_id' => $document->id,
                'type' => 'document-classique',
                'description' => 'Demande classique avec document associé',
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_required' => true,
            ]);

            $this->line("  ✅ Demande créée: {$request3->reference_number}");
            $this->line("  📄 Titre: {$request3->getDocumentTitle()}");
            $this->line("  📂 Catégorie: {$request3->getDocumentCategory()}");
            $this->newLine();
        }

        // Afficher les URLs pour tester dans le navigateur
        $this->info('🌐 URLs pour tester dans le navigateur:');
        $this->line("  Agent - Documents: http://localhost:8000/agent/documents");
        $this->line("  Agent - Demandes: http://localhost:8000/agent/requests");
        $this->line("  Agent - Traitement demande 1: http://localhost:8000/agent/requests/{$request1->id}/process");
        $this->line("  Agent - Traitement demande 2: http://localhost:8000/agent/requests/{$request2->id}/process");
        if (isset($request3)) {
            $this->line("  Agent - Traitement demande 3: http://localhost:8000/agent/requests/{$request3->id}/process");
        }
        $this->newLine();

        $this->info('✨ Test terminé ! Vérifiez les URLs ci-dessus pour confirmer que:');
        $this->line('  1. Le titre du document s\'affiche correctement');
        $this->line('  2. La catégorie est bien affichée');
        $this->line('  3. Les badges "Formulaire interactif" apparaissent');
        $this->line('  4. Les pièces jointes fonctionnent');

        // Proposer de nettoyer
        if ($this->confirm('Voulez-vous supprimer les demandes de test créées ?', true)) {
            $request1->delete();
            $request2->delete();
            if (isset($request3)) {
                $request3->delete();
            }
            $this->info('🗑️ Demandes de test supprimées.');
        }

        return 0;
    }
}
