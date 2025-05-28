<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Test 1: Check if routes are properly loaded
echo "=== TESTING AGENT ROUTES ===\n";
$router = app('router');
$routes = $router->getRoutes();

$agentRoutes = [];
foreach ($routes as $route) {
    if (strpos($route->getName() ?? '', 'agent.') === 0) {
        $agentRoutes[] = [
            'name' => $route->getName(),
            'uri' => $route->uri(),
            'middleware' => $route->middleware()
        ];
    }
}

echo "Found " . count($agentRoutes) . " agent routes:\n";
foreach ($agentRoutes as $route) {
    echo "- {$route['name']}: {$route['uri']} [" . implode(', ', $route['middleware']) . "]\n";
}

// Test 2: Check middleware registration
echo "\n=== TESTING MIDDLEWARE REGISTRATION ===\n";
$kernel = app(\App\Http\Kernel::class);
$aliases = $kernel->getMiddlewareAliases();

echo "Middleware aliases:\n";
foreach (['auth', 'role', 'admin'] as $alias) {
    if (isset($aliases[$alias])) {
        echo "- $alias => {$aliases[$alias]}\n";
    } else {
        echo "- $alias => NOT FOUND\n";
    }
}

// Test 3: Test middleware class resolution
echo "\n=== TESTING MIDDLEWARE RESOLUTION ===\n";
try {
    $checkRoleMiddleware = app(\App\Http\Middleware\CheckRole::class);
    echo "✓ CheckRole middleware can be resolved\n";
} catch (Exception $e) {
    echo "✗ CheckRole middleware resolution failed: " . $e->getMessage() . "\n";
}

// Test 4: Check agent users
echo "\n=== TESTING AGENT USERS ===\n";
$agents = \App\Models\User::where('role', 'agent')->get(['email', 'role']);
echo "Found " . $agents->count() . " agent users:\n";
foreach ($agents as $agent) {
    echo "- {$agent->email} (role: {$agent->role})\n";
}

// Test 5: Test route middleware configuration
echo "\n=== TESTING ROUTE MIDDLEWARE CONFIG ===\n";
$agentDashboardRoute = null;
foreach ($routes as $route) {
    if ($route->getName() === 'agent.dashboard') {
        $agentDashboardRoute = $route;
        break;
    }
}

if ($agentDashboardRoute) {
    echo "Agent dashboard route found:\n";
    echo "- URI: {$agentDashboardRoute->uri()}\n";
    echo "- Middleware: " . implode(', ', $agentDashboardRoute->middleware()) . "\n";
    echo "- Action: {$agentDashboardRoute->getActionName()}\n";
} else {
    echo "✗ Agent dashboard route not found\n";
}

echo "\n=== TEST COMPLETE ===\n";
