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
            $table->string('place_of_birth')->nullable()->after('date_naissance');
            $table->string('profession')->nullable()->after('place_of_birth');
            $table->string('cin_number')->nullable()->after('profession');
            $table->string('nationality')->default('Ivoirienne')->after('cin_number');
            $table->text('complete_address')->nullable()->after('address');
            $table->string('father_name')->nullable()->after('complete_address');
            $table->string('father_profession')->nullable()->after('father_name');
            $table->text('father_address')->nullable()->after('father_profession');
            $table->string('mother_name')->nullable()->after('father_address');
            $table->string('mother_profession')->nullable()->after('mother_name');
            $table->text('mother_address')->nullable()->after('mother_profession');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'place_of_birth',
                'profession',
                'cin_number',
                'nationality',
                'complete_address',
                'father_name',
                'father_profession',
                'father_address',
                'mother_name',
                'mother_profession',
                'mother_address'
            ]);
        });
    }
};
