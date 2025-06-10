<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CitizenRequest;
use App\Models\Attachment;

class VerifyAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:attachments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie l\'état des pièces jointes après migration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== VÉRIFICATION DES PIÈCES JOINTES MIGRÉES ===');
        $this->newLine();

        // Vérifier les demandes avec des pièces jointes dans la nouvelle table
        $requestsWithNewAttachments = CitizenRequest::with('citizenAttachments')->whereHas('citizenAttachments')->get();
        $this->info("Demandes avec pièces jointes dans la nouvelle table: " . $requestsWithNewAttachments->count());

        foreach ($requestsWithNewAttachments as $request) {
            $this->line("- Demande {$request->id} ({$request->reference_number}): {$request->citizenAttachments->count()} fichier(s)");
            foreach ($request->citizenAttachments as $attachment) {
                $sizeKB = $attachment->file_size ? number_format($attachment->file_size / 1024, 1) : '0';
                $this->line("  * {$attachment->file_name} ({$attachment->file_type}, {$sizeKB} KB)");
            }
        }

        $this->newLine();

        // Vérifier les demandes qui ont encore l'ancien format
        $requestsWithOldAttachments = CitizenRequest::whereNotNull('attachments')
            ->whereJsonLength('attachments', '>', 0)
            ->whereDoesntHave('citizenAttachments')
            ->get();

        $this->info("Demandes avec ancien format JSON restantes: " . $requestsWithOldAttachments->count());

        if ($requestsWithOldAttachments->count() > 0) {
            foreach ($requestsWithOldAttachments as $request) {
                $attachmentCount = is_array($request->attachments) ? count($request->attachments) : 0;
                $this->line("- Demande {$request->id} ({$request->reference_number}): {$attachmentCount} fichier(s) en format legacy");
            }
        }

        $this->newLine();
        $this->info('=== TOTAL ===');
        $this->info("Total pièces jointes dans la table 'attachments': " . Attachment::where('type', 'citizen')->count());
        $this->info('Vérification terminée.');

        return 0;
    }
}
