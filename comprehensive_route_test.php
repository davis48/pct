<?php

require_once __DIR__ . '/vendor/autoload.php';

echo "=== Diagnostic complet RouteServiceProvider ===\n\n";

try {
    // Bootstrap Laravel avec plus de détails
    $app = require_once __DIR__ . '/bootstrap/app.php';

    echo "1. Application Laravel bootstrappée avec succès\n";

    // Vérifier le provider
    $provider = new App\Providers\RouteServiceProvider($app);
    echo "2. RouteServiceProvider instancié\n";

    // Vérifier si toutes les classes sont disponibles
    echo "\n3. Vérification des imports/classes:\n";

    $imports = [
        'Illuminate\Cache\RateLimiting\Limit' => class_exists('Illuminate\Cache\RateLimiting\Limit'),
        'Illuminate\Foundation\Support\Providers\RouteServiceProvider' => class_exists('Illuminate\Foundation\Support\Providers\RouteServiceProvider'),
        'Illuminate\Http\Request' => class_exists('Illuminate\Http\Request'),
        'Illuminate\Support\Facades\RateLimiter' => class_exists('Illuminate\Support\Facades\RateLimiter'),
        'Illuminate\Support\Facades\Route' => class_exists('Illuminate\Support\Facades\Route'),
        'Exception' => class_exists('Exception')
    ];

    foreach ($imports as $class => $exists) {
        echo "  $class: " . ($exists ? "✅" : "❌") . "\n";
    }

    // Vérifier les fichiers de routes
    echo "\n4. Vérification des fichiers de routes:\n";

    $routeFiles = [
        'api.php' => base_path('routes/api.php'),
        'web.php' => base_path('routes/web.php'),
        'admin.php' => base_path('routes/admin.php'),
        'agent.php' => base_path('routes/agent.php')
    ];

    foreach ($routeFiles as $name => $path) {
        $exists = file_exists($path);
        $readable = $exists && is_readable($path);
        echo "  $name: " . ($exists ? "✅ Existe" : "❌ Manquant");
        if ($exists) {
            echo " | " . ($readable ? "✅ Lisible" : "❌ Non lisible");
            echo " | Taille: " . filesize($path) . " bytes";
        }
        echo "\n";
    }

    // Test de chargement individuel de chaque section
    echo "\n5. Test de chargement des sections du RouteServiceProvider:\n";

    // Configurer les facades
    $app->instance('app', $app);
    Illuminate\Support\Facades\Facade::setFacadeApplication($app);

    $router = $app['router'];
    echo "  Router initialisé: ✅\n";

    // Test API routes
    echo "  5.1. Test routes API:\n";
    try {
        $countBefore = count($router->getRoutes());
        $router->middleware('api')
               ->prefix('api')
               ->group(base_path('routes/api.php'));
        $countAfter = count($router->getRoutes());
        echo "    ✅ API routes chargées: " . ($countAfter - $countBefore) . " routes ajoutées\n";
    } catch (Exception $e) {
        echo "    ❌ Erreur API: " . $e->getMessage() . "\n";
    }

    // Test Web routes
    echo "  5.2. Test routes Web:\n";
    try {
        $countBefore = count($router->getRoutes());
        $router->middleware('web')
               ->group(base_path('routes/web.php'));
        $countAfter = count($router->getRoutes());
        echo "    ✅ Web routes chargées: " . ($countAfter - $countBefore) . " routes ajoutées\n";
    } catch (Exception $e) {
        echo "    ❌ Erreur Web: " . $e->getMessage() . "\n";
    }

    // Test Admin routes
    echo "  5.3. Test routes Admin:\n";
    try {
        $countBefore = count($router->getRoutes());
        $router->middleware(['web', 'auth', 'role:admin'])
               ->prefix('admin')
               ->name('admin.')
               ->group(base_path('routes/admin.php'));
        $countAfter = count($router->getRoutes());
        echo "    ✅ Admin routes chargées: " . ($countAfter - $countBefore) . " routes ajoutées\n";
    } catch (Exception $e) {
        echo "    ❌ Erreur Admin: " . $e->getMessage() . "\n";
        echo "    Détails: " . $e->getTraceAsString() . "\n";
    }

    // Test Agent routes - LE TEST CRITIQUE
    echo "  5.4. Test routes Agent:\n";
    try {
        $countBefore = count($router->getRoutes());
        echo "    Routes avant chargement agent: $countBefore\n";

        $router->middleware(['web', 'auth', 'role:agent,admin'])
               ->prefix('agent')
               ->name('agent.')
               ->group(base_path('routes/agent.php'));

        $countAfter = count($router->getRoutes());
        echo "    Routes après chargement agent: $countAfter\n";
        echo "    ✅ Agent routes chargées: " . ($countAfter - $countBefore) . " routes ajoutées\n";

        // Lister les routes agent spécifiquement
        $agentRoutes = [];
        foreach ($router->getRoutes() as $route) {
            if (strpos($route->uri(), 'agent/') === 0) {
                $agentRoutes[] = $route->uri() . " [" . implode(',', $route->methods()) . "]";
            }
        }

        if (!empty($agentRoutes)) {
            echo "    Routes agent trouvées:\n";
            foreach ($agentRoutes as $routeInfo) {
                echo "      - $routeInfo\n";
            }
        } else {
            echo "    ❌ Aucune route agent trouvée malgré le chargement\n";
        }

    } catch (Exception $e) {
        echo "    ❌ Erreur Agent: " . $e->getMessage() . "\n";
        echo "    Ligne: " . $e->getLine() . "\n";
        echo "    Fichier: " . $e->getFile() . "\n";
        echo "    Trace: " . $e->getTraceAsString() . "\n";
    }

    // Test du contenu du fichier agent.php directement
    echo "\n6. Analyse du contenu de routes/agent.php:\n";
    $agentContent = file_get_contents(base_path('routes/agent.php'));
    echo "  Contenu (premiers 200 caractères):\n";
    echo "  " . substr($agentContent, 0, 200) . "...\n";

    // Vérifier s'il y a des erreurs de syntaxe
    $tempFile = tempnam(sys_get_temp_dir(), 'agent_routes_check');
    file_put_contents($tempFile, $agentContent);

    $output = [];
    $returnVar = 0;
    exec("php -l \"$tempFile\" 2>&1", $output, $returnVar);

    if ($returnVar === 0) {
        echo "  ✅ Syntaxe PHP valide\n";
    } else {
        echo "  ❌ Erreur de syntaxe:\n";
        foreach ($output as $line) {
            echo "    $line\n";
        }
    }

    unlink($tempFile);

} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin du diagnostic ===\n";
