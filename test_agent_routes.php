<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Start session
session_start();

echo "=== Test des routes Agent ===\n\n";

try {
    // 1. Vérifier si les routes agent sont bien définies
    echo "1. Vérification des routes définies:\n";

    // Créer une requête pour charger les routes
    $request = Request::create('/', 'GET');
    $kernel->handle($request);

    $routes = Route::getRoutes();
    $agentRoutes = [];

    foreach ($routes as $route) {
        $uri = $route->uri();
        if (strpos($uri, 'agent/') === 0) {
            $agentRoutes[] = [
                'uri' => $uri,
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->gatherMiddleware()
            ];
        }
    }

    if (empty($agentRoutes)) {
        echo "❌ Aucune route agent trouvée!\n";
    } else {
        echo "✅ Routes agent trouvées:\n";
        foreach ($agentRoutes as $route) {
            echo "  - {$route['uri']} -> {$route['action']}\n";
            echo "    Nom: {$route['name']}\n";
            echo "    Middleware: " . implode(', ', $route['middleware']) . "\n\n";
        }
    }

    // 2. Tester l'authentification avec un agent
    echo "2. Test d'authentification agent:\n";

    $agent = User::where('email', 'agent@pct-uvci.ci')->first();
    if (!$agent) {
        echo "❌ Agent de test non trouvé\n";
        exit(1);
    }

    echo "✅ Agent trouvé: {$agent->email} (role: {$agent->role})\n";

    // Simuler l'authentification
    Auth::login($agent);

    if (Auth::check()) {
        echo "✅ Authentification réussie\n";
        echo "✅ Utilisateur connecté: " . Auth::user()->email . "\n";
        echo "✅ Rôle: " . Auth::user()->role . "\n";

        // Vérifier la méthode isAgent
        if (method_exists(Auth::user(), 'isAgent')) {
            echo "✅ isAgent(): " . (Auth::user()->isAgent() ? 'true' : 'false') . "\n";
        } else {
            echo "⚠️  Méthode isAgent() non trouvée\n";
        }
    } else {
        echo "❌ Échec de l'authentification\n";
        exit(1);
    }

    // 3. Tester l'accès aux routes agent
    echo "\n3. Test d'accès aux routes agent:\n";

    foreach ($agentRoutes as $route) {
        if ($route['name'] === 'agent.dashboard') {
            echo "Test de la route: {$route['uri']}\n";

            try {
                // Créer une requête pour cette route
                $testRequest = Request::create("/{$route['uri']}", 'GET');
                $testRequest->setUserResolver(function () {
                    return Auth::user();
                });

                // Tenter de résoudre la route
                $matchedRoute = Route::getRoutes()->match($testRequest);
                echo "✅ Route trouvée: " . $matchedRoute->getName() . "\n";

                // Vérifier les middleware
                $middleware = $matchedRoute->gatherMiddleware();
                echo "Middleware à appliquer: " . implode(', ', $middleware) . "\n";

            } catch (Exception $e) {
                echo "❌ Erreur lors du test de la route: " . $e->getMessage() . "\n";
            }
        }
    }

    // 4. Vérifier le contrôleur dashboard
    echo "\n4. Vérification du contrôleur Agent Dashboard:\n";

    if (class_exists('App\Http\Controllers\Agent\DashboardController')) {
        echo "✅ DashboardController existe\n";

        $controller = new App\Http\Controllers\Agent\DashboardController();
        if (method_exists($controller, 'index')) {
            echo "✅ Méthode index() existe\n";
        } else {
            echo "❌ Méthode index() manquante\n";
        }
    } else {
        echo "❌ DashboardController non trouvé\n";
    }

    // 5. Vérifier la vue dashboard
    echo "\n5. Vérification des vues:\n";

    $dashboardView = resource_path('views/agent/dashboard.blade.php');
    if (file_exists($dashboardView)) {
        echo "✅ Vue agent/dashboard.blade.php existe\n";
    } else {
        echo "❌ Vue agent/dashboard.blade.php manquante\n";
        echo "Chemin vérifié: $dashboardView\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin du test ===\n";
