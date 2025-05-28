<?php

require_once __DIR__ . '/vendor/autoload.php';

echo "=== Debug RouteServiceProvider ===\n\n";

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';

    echo "1. Vérification des fichiers de routes:\n";
    $agentFile = base_path('routes/agent.php');
    echo "Fichier agent.php: " . ($file_exists = file_exists($agentFile) ? "✅ Existe" : "❌ Manquant") . "\n";
    echo "Chemin: $agentFile\n";

    if ($file_exists) {
        echo "Contenu du fichier:\n";
        echo "```php\n" . file_get_contents($agentFile) . "\n```\n\n";
    }

    // Vérification des middlewares
    echo "2. Vérification des middlewares:\n";

    // Vérifier si les alias de middleware existent
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    echo "Kernel: " . get_class($kernel) . "\n";

    // Test de chargement des routes manuellement
    echo "\n3. Test de chargement manuel des routes agent:\n";

    try {
        // Simuler le processus de chargement des routes
        $router = $app['router'];

        echo "Router chargé: " . get_class($router) . "\n";

        // Essayer de charger les routes agent manuellement
        $router->middleware(['web', 'auth', 'role:agent,admin'])
               ->prefix('agent')
               ->name('agent.')
               ->group(base_path('routes/agent.php'));

        echo "✅ Routes agent chargées manuellement\n";

        // Lister les routes après chargement manuel
        $routes = $router->getRoutes();
        $agentRoutes = [];

        foreach ($routes as $route) {
            $uri = $route->uri();
            if (strpos($uri, 'agent/') === 0 || strpos($route->getName() ?: '', 'agent.') === 0) {
                $agentRoutes[] = [
                    'uri' => $uri,
                    'name' => $route->getName(),
                    'action' => $route->getActionName()
                ];
            }
        }

        if (!empty($agentRoutes)) {
            echo "✅ Routes agent trouvées après chargement manuel:\n";
            foreach ($agentRoutes as $route) {
                echo "  - {$route['uri']} [{$route['name']}] -> {$route['action']}\n";
            }
        } else {
            echo "❌ Aucune route agent trouvée même après chargement manuel\n";
        }

    } catch (Exception $e) {
        echo "❌ Erreur lors du chargement manuel: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }

    // Test des middlewares
    echo "\n4. Test des middlewares:\n";

    try {
        // Vérifier si le middleware 'role' existe
        $middlewareAliases = $app['config']->get('app.aliases', []);

        // Accéder aux alias de middleware depuis le Kernel
        $reflection = new ReflectionClass($kernel);
        $middlewareAliasesProperty = $reflection->getProperty('middlewareAliases');
        $middlewareAliasesProperty->setAccessible(true);
        $aliases = $middlewareAliasesProperty->getValue($kernel);

        echo "Alias de middleware disponibles:\n";
        foreach ($aliases as $alias => $class) {
            echo "  - $alias => $class\n";
        }

        if (isset($aliases['role'])) {
            echo "✅ Middleware 'role' trouvé: " . $aliases['role'] . "\n";

            // Vérifier si la classe existe
            if (class_exists($aliases['role'])) {
                echo "✅ Classe de middleware existe\n";
            } else {
                echo "❌ Classe de middleware manquante\n";
            }
        } else {
            echo "❌ Middleware 'role' non trouvé\n";
        }

    } catch (Exception $e) {
        echo "❌ Erreur lors de la vérification des middlewares: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin du debug ===\n";
