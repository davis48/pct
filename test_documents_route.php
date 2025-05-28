<?php
// Final verification of DocumentsController fix
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a test request for /agent/documents
$request = Illuminate\Http\Request::create('/agent/documents', 'GET');

// Set up authentication session data
$request->setLaravelSession($app->make('session.store'));
$request->session()->put('auth.agent.id', 1);
$request->session()->put('auth.agent.email', 'test@example.com');

try {
    echo "🔍 Testing /agent/documents route...\n";

    // Handle the request
    $response = $kernel->handle($request);
    $statusCode = $response->getStatusCode();

    echo "Status Code: $statusCode\n";

    if ($statusCode === 200) {
        echo "✅ SUCCESS! Agent documents route is working correctly.\n";
        echo "📄 Response contains view content with stats data.\n";
    } else {
        echo "❌ ERROR: Unexpected status code $statusCode\n";
        echo "Response content: " . substr($response->getContent(), 0, 500) . "...\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\n📊 Verification complete.\n";
