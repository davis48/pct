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
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'is_public')) {
                $table->boolean('is_public')->default(false);
            }
            
            if (!Schema::hasColumn('documents', 'status')) {
                $table->string('status')->default('active');
            }
            
            if (!Schema::hasColumn('documents', 'file_path')) {
                $table->string('file_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn('documents', 'is_public')) {
                $table->dropColumn('is_public');
            }
            
            if (Schema::hasColumn('documents', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('documents', 'file_path')) {
                $table->dropColumn('file_path');
            }
        });
    }
};
