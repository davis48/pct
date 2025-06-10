<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CitizenRequest;
use App\Models\User;

class TestDocumentTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:document-title';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test les nouvelles méthodes de titre de document';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST DES MÉTHODES DE TITRE DE DOCUMENT ===');
        $this->newLine();

        // Créer une demande de test pour formulaire interactif
        $user = User::where('role', 'citizen')->first();
        
        if (!$user) {
            $this->error('Aucun utilisateur citoyen trouvé');
            return;
        }

        $testRequest = CitizenRequest::create([
            'user_id' => $user->id,
            'type' => 'extrait-acte',
            'description' => 'Test document title',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_required' => true,
            'additional_data' => json_encode([
                'form_type' => 'extrait-naissance',
                'generated_via' => 'interactive_form'
            ])
        ]);

        $this->info("Demande de test créée: {$testRequest->id} ({$testRequest->reference_number})");
        $this->info("Titre du document: {$testRequest->getDocumentTitle()}");
        $this->info("Catégorie: {$testRequest->getDocumentCategory()}");

        $this->newLine();

        // Tester avec les demandes existantes
        $this->info('=== DEMANDES EXISTANTES ===');
        
        $existingRequests = CitizenRequest::with(['document', 'user'])->limit(5)->get();
        
        foreach ($existingRequests as $request) {
            $this->line("Demande {$request->id} ({$request->reference_number}):");
            $this->line("  - Titre: {$request->getDocumentTitle()}");
            $this->line("  - Catégorie: {$request->getDocumentCategory()}");
            $this->line("  - Type: {$request->type}");
            if ($request->document) {
                $this->line("  - Document original: {$request->document->title}");
            }
            $this->newLine();
        }

        // Nettoyer la demande de test
        $testRequest->delete();
        $this->info('Demande de test supprimée.');

        return 0;
    }
}
