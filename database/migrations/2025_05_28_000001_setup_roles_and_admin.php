<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Créer l'administrateur unique si non existant
        if (!DB::table('users')->where('role', 'admin')->exists()) {
            DB::table('users')->insert([
                'nom' => 'Admin',
                'prenoms' => 'System',
                'email' => 'admin@pct-uvci.ci',
                'password' => Hash::make('admin123456'), // À changer après la première connexion
                'role' => 'admin',
                'date_naissance' => '1990-01-01',
                'genre' => 'M',
                'phone' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // S'assurer que tous les utilisateurs existants sans rôle sont des citoyens
        DB::table('users')
            ->whereNull('role')
            ->update(['role' => 'citizen']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer l'administrateur
        DB::table('users')
            ->where('email', 'admin@pct-uvci.ci')
            ->delete();
    }
};
