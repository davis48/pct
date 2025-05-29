<?php

use Illuminate\Support\Facades\Route;

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de la route /dashboard ===\n\n";

// Vérifier que la route existe et ne pointe plus vers HomeController
$route = Route::getRoutes()->getByName('dashboard');

if ($route) {
    echo "✅ Route 'dashboard' trouvée\n";
    echo "URI: " . $route->uri() . "\n";
    
    // Vérifier si c'est une closure et non un contrôleur
    $action = $route->getAction();
    if (isset($action['uses']) && is_callable($action['uses'])) {
        echo "✅ Route utilise une closure (redirection intelligente)\n";
    } elseif (isset($action['uses']) && is_string($action['uses'])) {
        echo "❌ Route pointe encore vers: " . $action['uses'] . "\n";
    } else {
        echo "✅ Route configurée avec closure\n";
    }
} else {
    echo "❌ Route 'dashboard' introuvable\n";
}

echo "\n=== Test terminé ===\n";
echo "Vous pouvez maintenant tester l'accès à http://127.0.0.1:8000/dashboard\n";
