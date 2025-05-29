<?php

// Test de vérification des routes admin après correction

// Ce script doit être exécuté via Artisan:
// php artisan tinker --execute="require 'admin_routes_fix_test.php';"

echo "=== TEST DE VÉRIFICATION DES ROUTES ADMIN ===\n";
echo "Date du test: " . date('Y-m-d H:i:s') . "\n\n";

// Fonction pour vérifier l'existence d'une route
function route_exists($name) {
    try {
        $url = route($name);
        echo "✅ Route '{$name}' existe et pointe vers: {$url}\n";
        return true;
    } catch (\Exception $e) {
        echo "❌ Route '{$name}' INTROUVABLE: " . $e->getMessage() . "\n";
        return false;
    }
}

// Vérification des anciennes routes (qui ne devraient plus exister)
$old_routes = [
    'admin.dashboard',
    'admin.statistics',
    'admin.system-info',
    'admin.maintenance',
    'admin.logs',
    'admin.performance',
    'admin.backup'
];

echo "== VÉRIFICATION DES ANCIENNES ROUTES ==\n";
$all_old_routes_removed = true;
foreach ($old_routes as $route) {
    if (route_exists($route)) {
        $all_old_routes_removed = false;
    }
}

// Vérification des nouvelles routes avec le préfixe "special"
$new_routes = [
    'admin.special.dashboard',
    'admin.special.statistics',
    'admin.special.system-info',
    'admin.special.maintenance',
    'admin.special.logs',
    'admin.special.performance',
    'admin.special.backup'
];

echo "\n== VÉRIFICATION DES NOUVELLES ROUTES ==\n";
$all_new_routes_exist = true;
foreach ($new_routes as $route) {
    if (!route_exists($route)) {
        $all_new_routes_exist = false;
    }
}

// Vérification des routes API
$api_routes = [
    'admin.api.users',
    'admin.api.documents',
    'admin.api.requests',
    'admin.api.agents'
];

echo "\n== VÉRIFICATION DES ROUTES API ==\n";
$all_api_routes_exist = true;
foreach ($api_routes as $route) {
    if (!route_exists($route)) {
        $all_api_routes_exist = false;
    }
}

// Résultat final
echo "\n== RÉSULTAT FINAL ==\n";
if ($all_old_routes_removed && $all_new_routes_exist && $all_api_routes_exist) {
    echo "✅ CORRECTION RÉUSSIE: Toutes les routes ont été correctement renommées\n";
    echo "✅ Les routes de l'ancienne interface ont été supprimées\n";
    echo "✅ Les nouvelles routes avec le préfixe 'special' sont accessibles\n";
    echo "✅ Les routes API sont accessibles\n";
} else {
    if (!$all_old_routes_removed) {
        echo "⚠️ ATTENTION: Certaines anciennes routes existent encore\n";
    }
    if (!$all_new_routes_exist) {
        echo "❌ ERREUR: Certaines nouvelles routes ne sont pas accessibles\n";
    }
    if (!$all_api_routes_exist) {
        echo "❌ ERREUR: Certaines routes API ne sont pas accessibles\n";
    }
}

echo "\n=== FIN DU TEST ===\n";
