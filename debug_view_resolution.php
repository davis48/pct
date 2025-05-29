<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\View\Factory;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test view resolution
    $viewFactory = app('view');
    
    echo "Testing view resolution...\n";
    
    // Test if the view exists
    if ($viewFactory->exists('front.dashboard')) {
        echo "✓ View 'front.dashboard' exists\n";
    } else {
        echo "✗ View 'front.dashboard' does not exist\n";
    }
    
    // Test view paths
    echo "\nView paths:\n";
    $finder = $viewFactory->getFinder();
    foreach ($finder->getPaths() as $path) {
        echo "- " . $path . "\n";
    }
    
    // Test if specific files exist
    $dashboardPath = resource_path('views/front/dashboard.blade.php');
    echo "\nChecking file existence:\n";
    echo "Dashboard file: " . $dashboardPath . " - " . (file_exists($dashboardPath) ? "EXISTS" : "NOT FOUND") . "\n";
    
    // Try to find the view
    try {
        $viewPath = $finder->find('front.dashboard');
        echo "Found view at: " . $viewPath . "\n";
    } catch (Exception $e) {
        echo "Error finding view: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
