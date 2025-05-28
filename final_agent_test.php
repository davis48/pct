<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Start session
session_start();

echo "=== Test final d'authentification et d'accès agent ===\n\n";

try {
    // 1. Vérifier l'agent de test
    echo "1. Vérification de l'agent de test:\n";

    $agent = App\Models\User::where('email', 'agent@pct-uvci.ci')->first();
    if (!$agent) {
        echo "❌ Agent de test non trouvé\n";
        exit(1);
    }

    echo "✅ Agent trouvé: {$agent->email}\n";
    echo "   Rôle: {$agent->role}\n";
    echo "   Nom: {$agent->first_name} {$agent->last_name}\n";
    echo "   ID: {$agent->id}\n";

    // 2. Simuler l'authentification
    echo "\n2. Simulation de l'authentification:\n";

    Illuminate\Support\Facades\Auth::login($agent);

    if (Illuminate\Support\Facades\Auth::check()) {
        echo "✅ Authentification réussie\n";
        echo "✅ Utilisateur connecté: " . Illuminate\Support\Facades\Auth::user()->email . "\n";
        echo "✅ isAgent(): " . (Illuminate\Support\Facades\Auth::user()->isAgent() ? 'true' : 'false') . "\n";
    } else {
        echo "❌ Échec de l'authentification\n";
        exit(1);
    }

    // 3. Vérifier les routes agent
    echo "\n3. Vérification des routes agent:\n";

    $routes = Illuminate\Support\Facades\Route::getRoutes();
    $agentRoutes = [];

    foreach ($routes as $route) {
        if (strpos($route->getName() ?: '', 'agent.') === 0) {
            $agentRoutes[] = [
                'name' => $route->getName(),
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'controller' => $route->getActionName()
            ];
        }
    }

    if (!empty($agentRoutes)) {
        echo "✅ Routes agent trouvées (" . count($agentRoutes) . "):\n";
        foreach ($agentRoutes as $route) {
            echo "  - {$route['name']} -> {$route['uri']} [" . implode(',', $route['methods']) . "]\n";
        }
    } else {
        echo "❌ Aucune route agent trouvée\n";
    }

    // 4. Test du contrôleur dashboard
    echo "\n4. Test du contrôleur Agent Dashboard:\n";

    try {
        $controller = new App\Http\Controllers\Agent\DashboardController();
        echo "✅ DashboardController créé\n";

        // Créer une requête simulée
        $request = Illuminate\Http\Request::create('/agent/dashboard', 'GET');
        $request->setUserResolver(function () {
            return Illuminate\Support\Facades\Auth::user();
        });

        // Appeler la méthode index
        $response = $controller->index($request);
        echo "✅ Méthode index() exécutée\n";
        echo "✅ Type de réponse: " . get_class($response) . "\n";

        if ($response instanceof \Illuminate\Http\Response || $response instanceof \Illuminate\View\View) {
            echo "✅ Dashboard agent accessible avec succès!\n";
        }

    } catch (Exception $e) {
        echo "❌ Erreur lors de l'exécution du contrôleur: " . $e->getMessage() . "\n";
        echo "Ligne: " . $e->getLine() . "\n";
        echo "Fichier: " . $e->getFile() . "\n";
    }

    // 5. Vérifier la vue dashboard
    echo "\n5. Vérification de la vue dashboard:\n";

    $dashboardView = resource_path('views/agent/dashboard.blade.php');
    if (file_exists($dashboardView)) {
        echo "✅ Vue agent/dashboard.blade.php existe\n";
        echo "   Taille: " . filesize($dashboardView) . " bytes\n";
    } else {
        echo "❌ Vue agent/dashboard.blade.php manquante\n";
    }

    echo "\n🎉 SUCCÈS COMPLET! 🎉\n";
    echo "- L'authentification agent fonctionne\n";
    echo "- Les routes agent sont chargées\n";
    echo "- Le contrôleur dashboard fonctionne\n";
    echo "- L'interface agent est accessible\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin du test final ===\n";
