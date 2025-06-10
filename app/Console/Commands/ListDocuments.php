<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;

class ListDocuments extends Command
{
    protected $signature = 'documents:list';
    protected $description = 'Lister tous les documents disponibles';

    public function handle()
    {
        $documents = Document::where('is_public', true)
                           ->where('status', 'active')
                           ->get();

        $this->info('Documents disponibles pour la sélection:');
        $this->info('Total: ' . $documents->count());
        
        foreach ($documents as $document) {
            $this->line("ID: {$document->id} | Nom: {$document->name} | Type: {$document->type}");
        }
        
        if ($documents->count() === 0) {
            $this->warn('Aucun document public et actif trouvé!');
        }
    }
}
