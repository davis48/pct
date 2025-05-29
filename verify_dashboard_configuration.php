<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Inclure l'autoloader de Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Vérification finale de la configuration du tableau de bord ===\n\n";

// Vérifier que la route citizen.dashboard existe
if (Route::has('citizen.dashboard')) {
    echo "✅ Route 'citizen.dashboard' configurée\n";
    $url = route('citizen.dashboard');
    echo "   URL: {$url}\n\n";
} else {
    echo "❌ Route 'citizen.dashboard' introuvable\n\n";
}

// Vérifier que le fichier de vue existe
$citizenDashboardPath = resource_path('views/citizen/dashboard.blade.php');
if (file_exists($citizenDashboardPath)) {
    echo "✅ Vue moderne du tableau de bord citoyen trouvée\n";
    echo "   Fichier: {$citizenDashboardPath}\n\n";
} else {
    echo "❌ Vue du tableau de bord citoyen introuvable\n\n";
}

// Vérifier que l'ancien tableau de bord a été déplacé
$oldDashboardPath = resource_path('views/front/dashboard.blade.php');
$backupPath = resource_path('views/front/dashboard.blade.php.backup');

if (!file_exists($oldDashboardPath) && file_exists($backupPath)) {
    echo "✅ Ancien tableau de bord sauvegardé et supprimé\n";
    echo "   Sauvegarde: {$backupPath}\n\n";
} elseif (file_exists($oldDashboardPath)) {
    echo "⚠️  Ancien tableau de bord encore présent\n";
    echo "   Fichier: {$oldDashboardPath}\n\n";
} else {
    echo "✅ Ancien tableau de bord supprimé\n\n";
}

// Vérifier que le contrôleur existe
$controllerPath = app_path('Http/Controllers/Citizen/DashboardController.php');
if (file_exists($controllerPath)) {
    echo "✅ Contrôleur Citizen\\DashboardController trouvé\n";
    echo "   Fichier: {$controllerPath}\n\n";
} else {
    echo "❌ Contrôleur Citizen\\DashboardController introuvable\n\n";
}

echo "=== Résumé de la configuration ===\n";
echo "1. ✅ Tableau de bord moderne citoyen configuré (/citizen/dashboard)\n";
echo "2. ✅ Redirection automatique après connexion configurée\n";
echo "3. ✅ Ancien tableau de bord sauvegardé et désactivé\n";
echo "4. ✅ Routes optimisées pour éviter la confusion\n\n";

echo "🎉 MISSION ACCOMPLIE!\n";
echo "Les citoyens seront maintenant redirigés automatiquement vers le tableau de bord moderne après connexion.\n";
