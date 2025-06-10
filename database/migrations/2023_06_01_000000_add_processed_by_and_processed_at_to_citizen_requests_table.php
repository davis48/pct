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
        if (!Schema::hasTable('citizen_requests')) {
            if (!Schema::hasTable('documents')) {
                Schema::create('documents', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('type');
                    $table->text('description')->nullable();
                    $table->json('requirements')->nullable();
                    $table->timestamps();
                });
            }
            
            Schema::create('citizen_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('document_id')->nullable()->constrained()->onDelete('set null');
                $table->string('type'); // certificate, authorization, complaint, etc.
                $table->text('description');
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->text('admin_comments')->nullable();
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->json('attachments')->nullable(); // Store file paths as JSON
                $table->timestamps();
                
                $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            });
        } else {
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
