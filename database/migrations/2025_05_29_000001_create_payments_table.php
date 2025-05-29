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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_request_id')->constrained('citizen_requests')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('reference')->unique();
            $table->string('phone_number')->nullable();
            $table->string('provider')->nullable();
            $table->string('status');
            $table->string('transaction_id')->nullable();
            $table->string('payment_method');
            $table->text('notes')->nullable();
            $table->json('callback_data')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            // Index
            $table->index('status');
            $table->index('reference');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
