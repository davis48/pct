<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CitizenRequest;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MigrateAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:attachments {--dry-run : Voir les changements sans les appliquer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migre les pièces jointes de l\'ancien format JSON vers la nouvelle table attachments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('=== MODE SIMULATION (Dry Run) ===');
        }

        // Récupérer toutes les demandes qui ont des attachments en JSON mais pas dans la table attachments
        $requests = CitizenRequest::whereNotNull('attachments')
            ->whereJsonLength('attachments', '>', 0)
            ->whereDoesntHave('citizenAttachments')
            ->get();

        if ($requests->isEmpty()) {
            $this->info('Aucune demande à migrer trouvée.');
            return;
        }

        $this->info("Trouvé {$requests->count()} demande(s) à migrer.");

        $bar = $this->output->createProgressBar($requests->count());
        $bar->start();

        $migrated = 0;
        $failed = 0;

        foreach ($requests as $request) {
            try {
                $attachments = $request->attachments;
                
                if (!is_array($attachments)) {
                    $this->newLine();
                    $this->warn("Demande {$request->id}: attachments n'est pas un tableau");
                    $failed++;
                    $bar->advance();
                    continue;
                }

                $attachmentsMigrated = 0;

                foreach ($attachments as $attachmentData) {
                    if (!$isDryRun) {
                        // Déterminer le nom et le chemin du fichier
                        $fileName = '';
                        $filePath = '';
                        $fileSize = 0;
                        $fileType = '';

                        if (is_string($attachmentData)) {
                            // Format simple : juste le nom du fichier
                            $fileName = $attachmentData;
                            $filePath = 'attachments/' . $fileName;
                        } elseif (is_array($attachmentData)) {
                            // Format complexe avec métadonnées
                            $fileName = $attachmentData['name'] ?? $attachmentData['stored_name'] ?? 'fichier.pdf';
                            $filePath = $attachmentData['path'] ?? 'attachments/' . ($attachmentData['stored_name'] ?? $fileName);
                            $fileSize = $attachmentData['size'] ?? 0;
                            $fileType = $attachmentData['type'] ?? 'application/pdf';
                            
                            // Nettoyer le chemin si nécessaire
                            if (str_starts_with($filePath, 'public/')) {
                                $filePath = substr($filePath, 7); // Retirer 'public/'
                            }
                        }

                        // Créer l'enregistrement dans la table attachments
                        Attachment::create([
                            'citizen_request_id' => $request->id,
                            'file_path' => $filePath,
                            'file_name' => $fileName,
                            'file_type' => $fileType ?: 'application/octet-stream',
                            'file_size' => $fileSize,
                            'uploaded_by' => $request->user_id,
                            'type' => 'citizen',
                            'created_at' => $request->created_at,
                            'updated_at' => $request->updated_at,
                        ]);
                    }
                    
                    $attachmentsMigrated++;
                }

                if (!$isDryRun) {
                    // Optionnel : vider le champ attachments JSON après migration
                    // $request->update(['attachments' => null]);
                }

                $migrated++;
                
                if ($isDryRun) {
                    $this->newLine();
                    $this->line("Demande {$request->id}: {$attachmentsMigrated} fichier(s) seraient migrés");
                }

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Erreur pour la demande {$request->id}: " . $e->getMessage());
                Log::error("Erreur migration attachments pour demande {$request->id}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($isDryRun) {
            $this->info("=== RÉSULTATS DE LA SIMULATION ===");
            $this->info("{$migrated} demande(s) peuvent être migrées");
            $this->info("{$failed} demande(s) ont des erreurs");
            $this->newLine();
            $this->info("Pour effectuer la migration réelle, lancez : php artisan migrate:attachments");
        } else {
            $this->info("=== MIGRATION TERMINÉE ===");
            $this->info("{$migrated} demande(s) migrées avec succès");
            if ($failed > 0) {
                $this->warn("{$failed} demande(s) ont échoué");
            }
        }

        return 0;
    }
}
