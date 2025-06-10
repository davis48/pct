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
        Schema::table('citizen_requests', function (Blueprint $table) {
            // Nouveaux champs pour améliorer le processus de demande
            $table->text('reason')->nullable()->after('description'); // Motif de la demande
            $table->enum('urgency', ['normal', 'urgent', 'very_urgent'])->default('normal')->after('reason');
            $table->enum('contact_preference', ['email', 'phone', 'both'])->default('email')->after('urgency');
            $table->text('additional_data')->nullable()->after('attachments'); // Données JSON supplémentaires
            
            // Index pour améliorer les performances
            $table->index('urgency');
            $table->index(['status', 'urgency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen_requests', function (Blueprint $table) {
            $table->dropIndex(['citizen_requests_urgency_index']);
            $table->dropIndex(['citizen_requests_status_urgency_index']);
            $table->dropColumn([
                'reason',
                'urgency', 
                'contact_preference',
                'additional_data'
            ]);
        });
    }
};
