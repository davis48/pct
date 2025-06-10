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
            if (!Schema::hasColumn('citizen_requests', 'additional_requirements')) {
                $table->text('additional_requirements')->nullable()->after('admin_comments');
            }
            
            if (!Schema::hasColumn('citizen_requests', 'processed_by') && !Schema::hasColumn('citizen_requests', 'processed_at')) {
                $table->unsignedBigInteger('processed_by')->nullable()->after('additional_requirements');
                $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
                $table->timestamp('processed_at')->nullable()->after('processed_by');
            }
            
            if (!Schema::hasColumn('citizen_requests', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('processed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen_requests', function (Blueprint $table) {
            $table->dropColumn(['additional_requirements', 'is_read']);
        });
    }
};
