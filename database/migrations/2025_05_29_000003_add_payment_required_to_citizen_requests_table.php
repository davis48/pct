<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */    public function up(): void
    {
        Schema::table('citizen_requests', function (Blueprint $table) {
            // Vérifier si la colonne payment_status n'existe pas déjà
            if (!Schema::hasColumn('citizen_requests', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('assigned_to');
            }
            
            // Vérifier si la colonne payment_required n'existe pas déjà
            if (!Schema::hasColumn('citizen_requests', 'payment_required')) {
                $table->boolean('payment_required')->default(true)->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen_requests', function (Blueprint $table) {
            if (Schema::hasColumn('citizen_requests', 'payment_required')) {
                $table->dropColumn('payment_required');
            }
        });
    }
};
