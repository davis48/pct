<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupDatabase extends Command
{
    protected $signature = 'cleanup:database';
    protected $description = 'Clean all citizen requests from the database';

    public function handle()
    {
        $this->info('=== NETTOYAGE COMPLET DE LA BASE DE DONNÉES ===');
        
        // Compter les demandes actuelles
        $requestCount = DB::table('citizen_requests')->count();
        $this->info("Demandes actuelles: {$requestCount}");
        
        if ($requestCount > 0) {
            // Supprimer toutes les demandes
            $deleted = DB::table('citizen_requests')->delete();
            $this->info("Demandes supprimées: {$deleted}");
            
            // Réinitialiser l'AUTO_INCREMENT
            DB::statement('ALTER TABLE citizen_requests AUTO_INCREMENT = 1');
            $this->info('Compteur AUTO_INCREMENT réinitialisé');
        }
        
        // Vérification finale
        $finalCount = DB::table('citizen_requests')->count();
        $this->info("Demandes restantes: {$finalCount}");
        
        $this->info('=== NETTOYAGE TERMINÉ ===');
        
        return 0;
    }
}
