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
            }
            
            if (!Schema::hasColumn('citizen_requests', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('processed_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen_requests', function (Blueprint $table) {
            if (Schema::hasColumn('citizen_requests', 'processed_by')) {
                $table->dropForeign(['processed_by']);
                $table->dropColumn('processed_by');
            }
            
            if (Schema::hasColumn('citizen_requests', 'processed_at')) {
                $table->dropColumn('processed_at');
            }
        });
    }
};
