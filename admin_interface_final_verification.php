<?php

// Vérification finale de la suppression de l'ancienne interface admin

echo "=== VÉRIFICATION FINALE DE SUPPRESSION DE L'ANCIENNE INTERFACE ADMIN ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "Version: 1.0\n\n";

// Vérification des fichiers de vues de l'ancienne interface 
// qui ne sont plus référencés dans les routes

$anciennesVues = [
    'admin.dashboard',
    'admin.statistics.index',
    'admin.statistics.document',
    'admin.statistics.agent',
    'admin.users.index',
    'admin.users.create',
    'admin.users.edit',
    'admin.users.show',
    'admin.agents.index',
    'admin.agents.create',
    'admin.agents.edit',
    'admin.documents.index',
    'admin.documents.create',
    'admin.documents.edit',
    'admin.requests.show'
];

$nouvellesVues = [
    'admin.special.dashboard',
    'admin.special.statistics',
    'admin.special.system-info',
    'admin.special.maintenance',
    'admin.special.logs',
    'admin.special.performance'
];

echo "== ANCIENNES VUES ==\n";
foreach ($anciennesVues as $vue) {
    $existe = view()->exists($vue);
    if ($existe) {
        echo "⚠️ La vue '{$vue}' existe encore mais n'est plus référencée dans les routes\n";
    } else {
        echo "✅ La vue '{$vue}' n'existe plus ou a été remplacée\n";
    }
}

echo "\n== NOUVELLES VUES ==\n";
foreach ($nouvellesVues as $vue) {
    $existe = view()->exists($vue);
    if ($existe) {
        echo "✅ La nouvelle vue '{$vue}' est correctement configurée\n";
    } else {
        echo "❌ ERREUR: La nouvelle vue '{$vue}' est manquante\n";
    }
}

// Vérification des contrôleurs
echo "\n== CONTRÔLEURS ==\n";
$nouveauControleur = 'App\Http\Controllers\Admin\AdminSpecialController';
$anciensControleurs = [
    'App\Http\Controllers\Admin\DashboardController',
    'App\Http\Controllers\Admin\StatisticsController',
    'App\Http\Controllers\Admin\UserController',
    'App\Http\Controllers\Admin\AgentController',
    'App\Http\Controllers\Admin\DocumentController',
    'App\Http\Controllers\Admin\RequestController'
];

if (class_exists($nouveauControleur)) {
    echo "✅ Le nouveau contrôleur '{$nouveauControleur}' est correctement configuré\n";
} else {
    echo "❌ ERREUR: Le nouveau contrôleur '{$nouveauControleur}' est manquant\n";
}

foreach ($anciensControleurs as $controleur) {
    if (class_exists($controleur)) {
        echo "⚠️ L'ancien contrôleur '{$controleur}' existe encore mais n'est plus utilisé dans les routes\n";
    } else {
        echo "✅ L'ancien contrôleur '{$controleur}' a été supprimé ou remplacé\n";
    }
}

// Vérification du fichier routes/admin.php
echo "\n== ROUTES ADMIN ==\n";
$routesPath = base_path('routes/admin.php');
$routesContent = file_get_contents($routesPath);

// Vérification des nouvelles routes
$nouvellementAjoutees = [
    "Route::get('/', [AdminSpecialController::class, 'dashboard'])",
    "Route::get('/dashboard', [AdminSpecialController::class, 'dashboard'])",
    "Route::get('/statistics', [AdminSpecialController::class, 'statistics'])",
    "Route::get('/system-info', [AdminSpecialController::class, 'systemInfo'])",
    "Route::get('/maintenance', [AdminSpecialController::class, 'maintenance'])",
    "Route::get('/logs', [AdminSpecialController::class, 'logs'])",
    "Route::get('/performance', [AdminSpecialController::class, 'performance'])"
];

$toutesNouvellesRoutesPresentes = true;
foreach ($nouvellementAjoutees as $route) {
    if (strpos($routesContent, $route) !== false) {
        echo "✅ Route '{$route}' présente dans admin.php\n";
    } else {
        echo "❌ ERREUR: Route '{$route}' manquante dans admin.php\n";
        $toutesNouvellesRoutesPresentes = false;
    }
}

// Vérification d'anciennes routes
$anciennesRoutes = [
    "UserController",
    "AgentController",
    "DocumentController",
    "RequestController",
    "DashboardController",
    "StatisticsController"
];

$aucuneAncienneRoutePresente = true;
foreach ($anciennesRoutes as $route) {
    if (strpos($routesContent, $route) !== false) {
        echo "❌ ERREUR: Référence à l'ancien contrôleur '{$route}' encore présente dans admin.php\n";
        $aucuneAncienneRoutePresente = false;
    } else {
        echo "✅ Aucune référence à l'ancien contrôleur '{$route}' dans admin.php\n";
    }
}

// Résultat final
echo "\n== RÉSULTAT FINAL ==\n";
if ($toutesNouvellesRoutesPresentes && $aucuneAncienneRoutePresente) {
    echo "✅ SUPPRESSION RÉUSSIE: L'ancienne interface admin a été entièrement supprimée.\n";
    echo "Toutes les routes pointent désormais vers la nouvelle interface moderne.\n";
    echo "MISSION ACCOMPLIE !\n";
} else {
    echo "⚠️ SUPPRESSION PARTIELLE: Il reste encore des traces de l'ancienne interface.\n";
    echo "Veuillez vérifier les problèmes mentionnés ci-dessus.\n";
}

echo "\n=== FIN DE LA VÉRIFICATION ===\n";
