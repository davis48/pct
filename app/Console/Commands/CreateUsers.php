<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUsers extends Command
{
    protected $signature = 'users:create-all';
    protected $description = 'Créer tous les utilisateurs nécessaires (admin, agent, citoyen)';

    public function handle()
    {
        $this->info('Création des utilisateurs...');

        // Vérifier si les utilisateurs existent déjà
        $adminExists = User::where('email', 'admin@example.com')->exists();
        $agentExists = User::where('email', 'agent@example.com')->exists();
        $citizenExists = User::where('email', 'citoyen@example.com')->exists();

        // Créer l'administrateur
        if (!$adminExists) {
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
            $this->info('Administrateur créé');
        } else {
            $this->info('Administrateur existe déjà');
        }

        // Créer l'agent
        if (!$agentExists) {
            User::create([
                'nom' => 'Agent',
                'prenoms' => 'Support',
                'date_naissance' => '1985-03-15',
                'genre' => 'M',
                'email' => 'agent@example.com',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'phone' => '+225 0123456787',
                'address' => 'Bouaké, Côte d\'Ivoire',
            ]);
            $this->info('Agent créé');
        } else {
            $this->info('Agent existe déjà');
        }

        // Créer le citoyen
        if (!$citizenExists) {
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
            $this->info('Citoyen créé');
        } else {
            $this->info('Citoyen existe déjà');
        }

        $this->info('Utilisateurs créés avec succès !');
        $this->info('Vous pouvez vous connecter avec les identifiants suivants :');
        $this->info('Admin: admin@example.com / password');
        $this->info('Agent: agent@example.com / password');
        $this->info('Citoyen: citoyen@example.com / password');
    }
}
