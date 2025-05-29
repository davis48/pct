<?php

// Script pour afficher toutes les routes enregistrées dans l'application
// Ce script doit être exécuté via Artisan:
// php artisan tinker --execute="require 'list_all_routes.php';"

echo "=== LISTE DES ROUTES ENREGISTRÉES ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Récupération de toutes les routes
$routes = Route::getRoutes();
$adminRoutes = [];

// Filtrage des routes admin
foreach ($routes as $route) {
    $name = $route->getName();
    if ($name && strpos($name, 'admin.') === 0) {
        $adminRoutes[$name] = [
            'uri' => $route->uri(),
            'methods' => implode('|', $route->methods()),
            'action' => $route->getActionName()
        ];
    }
}

// Tri des routes par nom
ksort($adminRoutes);

// Affichage des routes admin
echo "== ROUTES ADMIN ==\n";
echo str_pad("NOM", 30) . " | " . str_pad("URI", 30) . " | " . str_pad("MÉTHODES", 10) . " | ACTION\n";
echo str_repeat("-", 100) . "\n";

foreach ($adminRoutes as $name => $info) {
    echo str_pad($name, 30) . " | " . 
         str_pad($info['uri'], 30) . " | " . 
         str_pad($info['methods'], 10) . " | " . 
         $info['action'] . "\n";
}

echo "\n=== FIN DE LA LISTE ===\n";
