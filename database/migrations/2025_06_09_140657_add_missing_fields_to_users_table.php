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
            // Ajouter les champs manquants pour la génération des documents PDF seulement s'ils n'existent pas
            if (!Schema::hasColumn('users', 'place_of_birth')) {
                $table->string('place_of_birth')->nullable()->after('date_naissance');
            }
            if (!Schema::hasColumn('users', 'profession')) {
                $table->string('profession')->nullable()->after('genre');
            }
            if (!Schema::hasColumn('users', 'cin_number')) {
                $table->string('cin_number')->nullable()->unique()->after('profession');
            }
            if (!Schema::hasColumn('users', 'nationality')) {
                $table->string('nationality')->default('Ivoirienne')->after('cin_number');
            }
            
            // Ajouter des champs pour la mère et le père (pour actes de naissance)
            if (!Schema::hasColumn('users', 'father_name')) {
                $table->string('father_name')->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'father_profession')) {
                $table->string('father_profession')->nullable()->after('father_name');
            }
            if (!Schema::hasColumn('users', 'father_address')) {
                $table->text('father_address')->nullable()->after('father_profession');
            }
            
            if (!Schema::hasColumn('users', 'mother_name')) {
                $table->string('mother_name')->nullable()->after('father_address');
            }
            if (!Schema::hasColumn('users', 'mother_profession')) {
                $table->string('mother_profession')->nullable()->after('mother_name');
            }
            if (!Schema::hasColumn('users', 'mother_address')) {
                $table->text('mother_address')->nullable()->after('mother_profession');
            }
            
            // Champs additionnels
            if (!Schema::hasColumn('users', 'marital_status')) {
                $table->string('marital_status')->default('Célibataire')->after('mother_address');
            }
            if (!Schema::hasColumn('users', 'emergency_contact')) {
                $table->text('emergency_contact')->nullable()->after('marital_status');
            }
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
                'father_name',
                'father_profession',
                'father_address',
                'mother_name',
                'mother_profession',
                'mother_address',
                'marital_status',
                'emergency_contact'
            ]);
        });
    }
};
