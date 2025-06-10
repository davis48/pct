<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAgentUser extends Command
{
    protected $signature = 'user:create-agent';
    protected $description = 'Créer un utilisateur avec le rôle agent';

    public function handle()
    {
        $agent = User::create([
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

        $this->info('Agent utilisateur créé avec succès:');
        $this->info('Email: agent@example.com');
        $this->info('Mot de passe: password');
    }
}
