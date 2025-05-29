<?php

// Vérification de la correction des routes admin

echo "=== VÉRIFICATION DE LA CORRECTION DES ROUTES ADMIN ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Fonction pour vérifier si une route existe
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

// Vérification des routes avec le préfixe "special"
$routes_to_check = [
    'admin.special.dashboard',
    'admin.special.statistics',
    'admin.special.system-info',
    'admin.special.maintenance',
    'admin.special.logs',
    'admin.special.performance',
    'admin.special.backup'
];

echo "== VÉRIFICATION DES ROUTES ADMIN SPECIAL ==\n";
$success = true;
foreach ($routes_to_check as $route) {
    if (!route_exists($route)) {
        $success = false;
    }
}

if ($success) {
    echo "\n✅ CORRECTION RÉUSSIE: Toutes les routes sont maintenant correctement définies avec le préfixe 'special'.\n";
} else {
    echo "\n❌ ERREUR: Certaines routes ne sont pas encore correctement définies.\n";
    echo "Veuillez vérifier les noms de routes dans le fichier routes/admin.php.\n";
}

echo "\n=== FIN DE LA VÉRIFICATION ===\n";
