<?php

// Ce script doit être exécuté dans le contexte de Laravel via: php artisan tinker --execute="require 'admin_interface_migration_test.php';"

// Vérification de la migration complète vers la nouvelle interface admin

// Informations de base
echo "=== Test de migration vers la nouvelle interface admin ===\n";
echo "Date de test: " . date('Y-m-d H:i:s') . "\n";
echo "Version: 2.0\n\n";

// Vérification du contrôleur
echo "== Vérification du contrôleur ==\n";
$controller_path = app_path('Http/Controllers/Admin/AdminSpecialController.php');
if (file_exists($controller_path)) {
    echo "✓ AdminSpecialController existe à {$controller_path}\n";
    
    // Vérification des méthodes
    $controller = new \ReflectionClass('App\Http\Controllers\Admin\AdminSpecialController');
    $methods = [
        'dashboard', 'statistics', 'systemInfo', 'maintenance', 
        'logs', 'performance', 'getUsers', 'getDocuments', 
        'getRequests', 'getAgents', 'backup'
    ];
    
    $methods_success = true;
    foreach ($methods as $method) {
        if ($controller->hasMethod($method)) {
            echo "✓ Méthode '{$method}' existe\n";
        } else {
            echo "✗ Méthode '{$method}' MANQUANTE\n";
            $methods_success = false;
        }
    }
} else {
    echo "✗ AdminSpecialController INTROUVABLE\n";
    $methods_success = false;
}

// Vérification des vues
echo "\n== Vérification des vues ==\n";
$views = [
    'admin.special.layout',
    'admin.special.dashboard',
    'admin.special.statistics',
    'admin.special.system-info',
    'admin.special.maintenance',
    'admin.special.logs',
    'admin.special.performance'
];

$views_success = true;
foreach ($views as $view) {
    if (view()->exists($view)) {
        echo "✓ Vue '{$view}' existe\n";
    } else {
        echo "✗ Vue '{$view}' INTROUVABLE\n";
        $views_success = false;
    }
}

// Vérification du fichier de routes
echo "\n== Vérification du fichier de routes admin.php ==\n";
$routes_path = base_path('routes/admin.php');
if (file_exists($routes_path)) {
    echo "✓ Le fichier routes/admin.php existe\n";
    $routes_content = file_get_contents($routes_path);
    
    // Vérification du contenu du fichier de routes
    $old_interface_mentions = [
        'Redirection de l\'ancienne interface',
        'UserController',
        'AgentController',
        'DocumentController',
        'RequestController',
        'DashboardController',
        'StatisticsController'
    ];
    
    $has_old_interface = false;
    foreach ($old_interface_mentions as $mention) {
        if (strpos($routes_content, $mention) !== false) {
            echo "✗ Le fichier contient encore des références à l'ancienne interface: '{$mention}'\n";
            $has_old_interface = true;
        }
    }
    
    if (!$has_old_interface) {
        echo "✓ Le fichier de routes ne contient plus de références à l'ancienne interface\n";
    }
} else {
    echo "✗ Le fichier routes/admin.php est INTROUVABLE\n";
}

// Résultat final
echo "\n== RÉSULTAT FINAL ==\n";
if ($methods_success && $views_success && !$has_old_interface) {
    echo "✅ MIGRATION RÉUSSIE: Toutes les vérifications ont été passées avec succès.\n";
    echo "La nouvelle interface admin est correctement configurée et prête à être utilisée.\n";
} else {
    echo "❌ MIGRATION INCOMPLÈTE: Certaines vérifications ont échoué.\n";
    echo "Veuillez corriger les problèmes ci-dessus avant de considérer la migration comme terminée.\n";
}

echo "\n=== Fin du test ===\n";
