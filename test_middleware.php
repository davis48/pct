<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test if the role middleware is registered
$middlewareAliases = app(\App\Http\Kernel::class)->getMiddlewareAliases ?? [];

echo "Middleware aliases:\n";
foreach ($middlewareAliases as $alias => $class) {
    echo "$alias => $class\n";
}

// Test if the CheckRole class exists
if (class_exists(\App\Http\Middleware\CheckRole::class)) {
    echo "\nCheckRole middleware class exists.\n";
} else {
    echo "\nCheckRole middleware class does NOT exist.\n";
}

// Test middleware resolution
try {
    $middleware = app('App\Http\Middleware\CheckRole');
    echo "Middleware can be resolved from container.\n";
} catch (Exception $e) {
    echo "Error resolving middleware: " . $e->getMessage() . "\n";
}
