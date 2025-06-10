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
            if (!Schema::hasColumn('citizen_requests', 'processed_by')) {
                $table->unsignedBigInteger('processed_by')->nullable()->after('admin_comments');
                $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
                $table->timestamp('processed_at')->nullable()->after('processed_by');
            }
            
            if (!Schema::hasColumn('citizen_requests', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable()->after('processed_by');
                $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen_requests', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
        });
    }
};
