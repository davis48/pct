<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Vérifier si la colonne existe déjà
if (!Schema::hasColumn('citizen_requests', 'payment_required')) {
    // Ajouter la colonne payment_required
    DB::statement('ALTER TABLE citizen_requests ADD COLUMN payment_required BOOLEAN DEFAULT 1');
    echo "Colonne payment_required ajoutée avec succès.\n";
} else {
    echo "La colonne payment_required existe déjà.\n";
}

echo "Terminé.\n";
