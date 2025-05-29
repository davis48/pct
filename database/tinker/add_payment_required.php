<?php

namespace Database\Tinker;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Vérifier si la colonne existe déjà
if (!Schema::hasColumn('citizen_requests', 'payment_required')) {
    // Ajouter la colonne payment_required
    Schema::table('citizen_requests', function ($table) {
        $table->boolean('payment_required')->default(true)->after('payment_status');
    });
    echo "Colonne payment_required ajoutée avec succès.\n";
} else {
    echo "La colonne payment_required existe déjà.\n";
}

echo "Terminé.\n";
