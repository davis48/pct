<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Test d'authentification...\n\n";

// Test avec agent@pct-uvci.ci
$email = 'agent@pct-uvci.ci';
$password = 'password123';
$role = 'agent';

echo "Tentative de connexion avec:\n";
echo "Email: $email\n";
echo "Password: $password\n";
echo "Role sélectionné: $role\n\n";

// Récupérer l'utilisateur
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ Erreur: Utilisateur non trouvé\n";
    exit;
}

echo "✅ Utilisateur trouvé:\n";
echo "- Nom: " . $user->nom . " " . $user->prenoms . "\n";
echo "- Email: " . $user->email . "\n";
echo "- Rôle: " . $user->role . "\n\n";

// Vérifier le mot de passe
if (Hash::check($password, $user->password)) {
    echo "✅ Mot de passe correct\n";
} else {
    echo "❌ Mot de passe incorrect\n";
    exit;
}

// Vérifier le rôle
if ($user->role === $role) {
    echo "✅ Rôle correspond: " . $user->role . " === " . $role . "\n";
} else {
    echo "❌ Rôle ne correspond pas: " . $user->role . " !== " . $role . "\n";
    echo "C'est ici que se situe le problème!\n";
    exit;
}

echo "\n🎉 Tous les tests passent! La connexion devrait fonctionner.\n";
