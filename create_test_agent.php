<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Créer un agent de test
$user = new User();
$user->nom = 'Agent';
$user->prenoms = 'Test';
$user->email = 'agent@pct-uvci.ci';
$user->password = Hash::make('password123');
$user->role = 'agent';
$user->date_naissance = '1990-01-01';
$user->genre = 'M';
$user->phone = '+225 01 02 03 04 05';
$user->address = '123 Rue Test, Abidjan';
$user->email_verified_at = now();
$user->save();

echo "Agent créé avec succès:\n";
echo "Email: " . $user->email . "\n";
echo "Mot de passe: password123\n";
echo "Rôle: " . $user->role . "\n";
