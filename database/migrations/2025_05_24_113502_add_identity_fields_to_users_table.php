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
            $table->string('nic')->nullable()->unique()->after('id');
            $table->date('date_naissance')->nullable()->after('name');
            $table->enum('genre', ['M', 'F', 'Autre'])->nullable()->after('date_naissance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_nic_unique');
            $table->dropColumn(['nic', 'date_naissance', 'genre']);
        });
    }
};
