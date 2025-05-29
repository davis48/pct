<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Mock authentication
Auth::shouldReceive('user')->andReturn((object)[
    'nom' => 'Test',
    'prenoms' => 'User'
]);

try {
    echo "Testing view rendering...\n";
    
    // Try to render the view
    $view = view('front.dashboard');
    echo "✓ View instance created successfully\n";
    
    // Try to render the content
    $content = $view->render();
    echo "✓ View rendered successfully\n";
    echo "Content length: " . strlen($content) . " characters\n";
    
} catch (Exception $e) {
    echo "✗ Error rendering view: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
