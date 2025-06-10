<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use Illuminate\Support\Facades\DB;

class UpdateDocuments extends Command
{
    protected $signature = 'documents:update';
    protected $description = 'Mettre à jour les documents existants avec les nouvelles colonnes';

    public function handle()
    {
        $this->info('Mise à jour des documents...');

        // Mettre à jour tous les documents existants
        DB::table('documents')->update([
            'is_public' => true,
            'status' => 'active',
            'file_path' => 'documents/default.pdf'
        ]);

        $this->info('Documents mis à jour avec succès !');
    }
}
