<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un administrateur par défaut
        User::firstOrCreate(
            ['email' => 'admin@pct-uvci.ci'],
            [
                'nom' => 'Admin',
                'prenoms' => 'Système',
                'email' => 'admin@pct-uvci.ci',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'telephone' => '0123456789',
                'date_naissance' => '1980-01-01',
                'lieu_naissance' => 'Abidjan',
                'genre' => 'M',
                'nationality' => 'Ivoirienne',
                'address' => 'Cocody, Abidjan',
                'profession' => 'Administrateur Système',
                'email_verified_at' => now(),
            ]
        );

        // Créer un agent par défaut
        User::firstOrCreate(
            ['email' => 'agent@pct-uvci.ci'],
            [
                'nom' => 'Agent',
                'prenoms' => 'Test',
                'email' => 'agent@pct-uvci.ci',
                'password' => Hash::make('agent123'),
                'role' => 'agent',
                'telephone' => '0123456788',
                'date_naissance' => '1985-05-15',
                'lieu_naissance' => 'Abidjan',
                'genre' => 'F',
                'nationality' => 'Ivoirienne',
                'address' => 'Plateau, Abidjan',
                'profession' => 'Agent Municipal',
                'email_verified_at' => now(),
            ]
        );

        // Créer un citoyen par défaut
        User::firstOrCreate(
            ['email' => 'citoyen@pct-uvci.ci'],
            [
                'nom' => 'Kouassi',
                'prenoms' => 'Jean Baptiste',
                'email' => 'citoyen@pct-uvci.ci',
                'password' => Hash::make('citoyen123'),
                'role' => 'citizen',
                'telephone' => '0123456787',
                'date_naissance' => '1990-03-20',
                'lieu_naissance' => 'Cocody',
                'genre' => 'M',
                'nationality' => 'Ivoirienne',
                'address' => 'Yopougon, Abidjan',
                'profession' => 'Développeur',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Comptes par défaut créés avec succès :');
        $this->command->info('Admin: admin@pct-uvci.ci / admin123');
        $this->command->info('Agent: agent@pct-uvci.ci / agent123');
        $this->command->info('Citoyen: citoyen@pct-uvci.ci / citoyen123');
    }
}
