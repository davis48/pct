<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Vérifier les agents existants
$agents = User::where('role', 'agent')->get(['nom', 'prenoms', 'email', 'role']);

echo "Nombre d'agents trouvés: " . $agents->count() . "\n";

foreach ($agents as $agent) {
    echo "- " . $agent->nom . " " . $agent->prenoms . " (" . $agent->email . ") - " . $agent->role . "\n";
}

// Si pas d'agent, essayer d'en créer un nouveau
if ($agents->count() === 0) {
    echo "\nCréation d'un nouvel agent...\n";

    // Vérifier si l'email existe déjà
    $existingUser = User::where('email', 'agent@pct-uvci.ci')->first();
    if ($existingUser) {
        echo "Un utilisateur avec cet email existe déjà : " . $existingUser->role . "\n";
        if ($existingUser->role !== 'agent') {
            $existingUser->role = 'agent';
            $existingUser->save();
            echo "Rôle mis à jour vers 'agent'\n";
        }
    } else {
        $agent = User::create([
            'nom' => 'Test',
            'prenoms' => 'Agent',
            'email' => 'agent@pct-uvci.ci',
            'password' => bcrypt('password123'),
            'role' => 'agent',
            'date_naissance' => '1990-01-01',
            'genre' => 'M',
            'phone' => '+225 01 02 03 04 05',
            'address' => 'Abidjan, Côte d\'Ivoire',
            'email_verified_at' => now(),
        ]);
        echo "Nouvel agent créé: " . $agent->email . "\n";
    }
}
