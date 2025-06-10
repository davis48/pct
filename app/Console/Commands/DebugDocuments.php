<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;

class DebugDocuments extends Command
{
    protected $signature = 'debug:documents';
    protected $description = 'Debug des documents pour la vue create';

    public function handle()
    {
        $this->info('=== DEBUG DOCUMENTS ===');
        
        // Test de la requête exacte du contrôleur
        $documents = Document::where('is_public', true)
                           ->where('status', 'active')
                           ->get();

        $this->info('Documents trouvés: ' . $documents->count());
        
        foreach ($documents as $document) {
            $this->line("ID: {$document->id}");
            $this->line("Name: {$document->name}");
            $this->line("Type: {$document->type}");
            $this->line("Is Public: " . ($document->is_public ? 'Oui' : 'Non'));
            $this->line("Status: {$document->status}");
            $this->line("---");
        }
        
        // Test du HTML qui serait généré
        $this->info('HTML généré pour le select:');
        $html = '<select>';
        $html .= '<option value="" selected disabled>Sélectionnez un document</option>';
        foreach ($documents as $document) {
            $html .= '<option value="' . $document->id . '">' . $document->name . '</option>';
        }
        $html .= '</select>';
        
        $this->line($html);
    }
}
