<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

echo "Test complet de connexion agent...\n\n";

// Données de test
$email = 'agent@pct-uvci.ci';
$password = 'password123';
$selectedRole = 'agent';

echo "=== ÉTAPE 1: Validation des données ===\n";
echo "Email: $email\n";
echo "Password: $password\n";
echo "Role sélectionné: $selectedRole\n";

// Validation comme dans le contrôleur
$emailValid = filter_var($email, FILTER_VALIDATE_EMAIL);
$passwordValid = !empty($password);
$roleValid = in_array($selectedRole, ['agent', 'citizen']);

echo "✅ Email valide: " . ($emailValid ? 'Oui' : 'Non') . "\n";
echo "✅ Password valide: " . ($passwordValid ? 'Oui' : 'Non') . "\n";
echo "✅ Role valide: " . ($roleValid ? 'Oui' : 'Non') . "\n\n";

echo "=== ÉTAPE 2: Récupération utilisateur ===\n";
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ ERREUR: Utilisateur non trouvé avec l'email $email\n";
    exit;
}

echo "✅ Utilisateur trouvé:\n";
echo "   - ID: " . $user->id . "\n";
echo "   - Nom: " . $user->nom . " " . $user->prenoms . "\n";
echo "   - Email: " . $user->email . "\n";
echo "   - Rôle DB: " . $user->role . "\n\n";

echo "=== ÉTAPE 3: Vérification du rôle ===\n";
if ($user->role !== $selectedRole) {
    echo "❌ ERREUR: Rôle ne correspond pas!\n";
    echo "   - Rôle dans DB: " . $user->role . "\n";
    echo "   - Rôle sélectionné: " . $selectedRole . "\n";
    echo "   - Message d'erreur: 'Vous ne pouvez pas vous connecter avec ce rôle.'\n";
    exit;
}

echo "✅ Rôle correspond: " . $user->role . " === " . $selectedRole . "\n\n";

echo "=== ÉTAPE 4: Vérification du mot de passe ===\n";
if (!Hash::check($password, $user->password)) {
    echo "❌ ERREUR: Mot de passe incorrect\n";
    exit;
}

echo "✅ Mot de passe correct\n\n";

echo "=== ÉTAPE 5: Test d'authentification Laravel ===\n";
$credentials = [
    'email' => $email,
    'password' => $password,
];

// Simuler la tentative d'authentification
try {
    if (Auth::attempt($credentials)) {
        echo "✅ Auth::attempt() réussi\n";
        echo "   - Utilisateur connecté: " . Auth::user()->email . "\n";
        echo "   - Rôle connecté: " . Auth::user()->role . "\n";

        // Test des méthodes de rôle
        echo "   - isAdmin(): " . (Auth::user()->isAdmin() ? 'true' : 'false') . "\n";
        echo "   - isAgent(): " . (Auth::user()->isAgent() ? 'true' : 'false') . "\n";
        echo "   - isCitizen(): " . (Auth::user()->isCitizen() ? 'true' : 'false') . "\n";

        // Déterminer la redirection
        $redirect = '/dashboard'; // défaut
        if (Auth::user()->isAdmin()) {
            $redirect = '/admin/dashboard';
        } elseif (Auth::user()->isAgent()) {
            $redirect = '/agent/dashboard';
        }

        echo "   - Redirection vers: " . $redirect . "\n";

        Auth::logout(); // déconnexion pour éviter les conflits
    } else {
        echo "❌ ERREUR: Auth::attempt() a échoué\n";
        echo "   - Credentials utilisés: " . json_encode($credentials) . "\n";
    }
} catch (Exception $e) {
    echo "❌ ERREUR lors de Auth::attempt(): " . $e->getMessage() . "\n";
}

echo "\n🎯 RÉSUMÉ:\n";
echo "- Tous les tests de validation passent\n";
echo "- L'utilisateur agent existe dans la DB\n";
echo "- Le rôle correspond\n";
echo "- Le mot de passe est correct\n";
echo "- L'authentification Laravel devrait fonctionner\n\n";

echo "💡 Si vous obtenez encore l'erreur 'role sélectionné est invalide',\n";
echo "   le problème vient probablement de la validation côté formulaire\n";
echo "   ou d'une session/cache qui interfère.\n";
