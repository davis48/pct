<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== Test de chargement du RouteServiceProvider ===\n\n";

try {
    // Créer une instance du RouteServiceProvider
    $routeProvider = new App\Providers\RouteServiceProvider($app);

    echo "✅ RouteServiceProvider créé avec succès\n";

    // Vérifier si la méthode boot existe
    if (method_exists($routeProvider, 'boot')) {
        echo "✅ Méthode boot() existe\n";

        // Essayer d'appeler boot
        try {
            $routeProvider->boot();
            echo "✅ Méthode boot() exécutée avec succès\n";
        } catch (Exception $e) {
            echo "❌ Erreur lors de l'exécution de boot(): " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
    } else {
        echo "❌ Méthode boot() manquante\n";
    }

    // Vérifier manuellement le fichier agent.php
    echo "\n2. Test de chargement direct du fichier agent.php:\n";

    $agentFile = base_path('routes/agent.php');
    if (file_exists($agentFile)) {
        echo "✅ Fichier agent.php existe\n";

        // Vérifier la syntaxe PHP
        $output = [];
        $return_var = 0;
        exec("php -l \"$agentFile\"", $output, $return_var);

        if ($return_var === 0) {
            echo "✅ Syntaxe PHP valide\n";
        } else {
            echo "❌ Erreur de syntaxe PHP:\n";
            foreach ($output as $line) {
                echo "  $line\n";
            }
        }

        // Essayer de l'inclure directement
        try {
            // Configurer les facades avant l'inclusion
            $app->instance('app', $app);
            Illuminate\Support\Facades\Facade::setFacadeApplication($app);

            // Tester avec un routeur
            $router = $app['router'];

            echo "✅ Router configuré\n";

            // Compter les routes avant
            $routesBeforeCount = count($router->getRoutes());
            echo "Routes avant inclusion: $routesBeforeCount\n";

            // Inclure le fichier agent.php dans le contexte du router
            $router->group([
                'middleware' => ['web', 'auth', 'role:agent,admin'],
                'prefix' => 'agent',
                'namespace' => 'App\Http\Controllers\Agent'
            ], $agentFile);

            $routesAfterCount = count($router->getRoutes());
            echo "Routes après inclusion: $routesAfterCount\n";
            echo "Nouvelles routes ajoutées: " . ($routesAfterCount - $routesBeforeCount) . "\n";

            // Lister les nouvelles routes
            $routes = $router->getRoutes();
            $agentRoutes = [];

            foreach ($routes as $route) {
                $uri = $route->uri();
                if (strpos($uri, 'agent/') === 0) {
                    $agentRoutes[] = [
                        'uri' => $uri,
                        'methods' => implode('|', $route->methods()),
                        'action' => $route->getActionName()
                    ];
                }
            }

            if (!empty($agentRoutes)) {
                echo "✅ Routes agent trouvées:\n";
                foreach ($agentRoutes as $route) {
                    echo "  {$route['methods']} {$route['uri']} -> {$route['action']}\n";
                }
            } else {
                echo "❌ Aucune route agent trouvée\n";
            }

        } catch (Exception $e) {
            echo "❌ Erreur lors de l'inclusion: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
    } else {
        echo "❌ Fichier agent.php manquant\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin du test ===\n";
