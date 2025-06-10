<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un administrateur
        User::create([
            'nom' => 'Admin',
            'prenoms' => 'System',
            'date_naissance' => '1980-01-01',
            'genre' => 'M',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+225 0123456789',
            'address' => 'Abidjan, Côte d\'Ivoire',
        ]);

        // Création de quelques utilisateurs standard
        User::create([
            'nom' => 'Citoyen',
            'prenoms' => 'Test',
            'date_naissance' => '1990-05-15',
            'genre' => 'F',
            'email' => 'citoyen@example.com',
            'password' => Hash::make('password'),
            'role' => 'citizen',
            'phone' => '+225 0123456788',
            'address' => 'Yamoussoukro, Côte d\'Ivoire',
        ]);

        // Création d'utilisateurs factices supplémentaires
        User::factory(10)->create();
    }
}
