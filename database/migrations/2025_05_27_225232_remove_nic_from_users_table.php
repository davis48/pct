<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer l'index unique d'abord
            $table->dropUnique(['nic']);
            // Supprimer la colonne NIC
            $table->dropColumn('nic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Recréer la colonne NIC si rollback nécessaire
            $table->string('nic')->nullable()->unique()->after('id');
        });
    }
};
