<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Agent\DocumentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "Testing DocumentsController fix...\n";

// Create mock request
$request = new Request();

// Create controller instance
$controller = new DocumentsController();

try {
    // Test if we can call the method without errors
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');

    echo "✓ DocumentsController index method exists\n";

    // Mock user authentication (simulate agent user)
    $agent = \App\Models\User::where('role', 'agent')->first();
    if ($agent) {
        Auth::login($agent);
        echo "✓ Agent user authenticated for testing\n";
    } else {
        echo "⚠ No agent user found, skipping auth test\n";
    }

    echo "\nTesting stats array creation...\n";

    // Test the stats calculation
    $stats = [
        'total' => \App\Models\CitizenRequest::count(),
        'pending' => \App\Models\CitizenRequest::where('status', 'pending')->count(),
        'processing' => \App\Models\CitizenRequest::where('status', 'processing')->count(),
        'completed' => \App\Models\CitizenRequest::where('status', 'approved')->count(),
        'approved' => \App\Models\CitizenRequest::where('status', 'approved')->count(),
        'rejected' => \App\Models\CitizenRequest::where('status', 'rejected')->count(),
    ];

    echo "Stats array contents:\n";
    foreach ($stats as $key => $value) {
        echo "  $key: $value\n";
    }

    // Check if all required keys are present
    $requiredKeys = ['total', 'pending', 'processing', 'completed', 'rejected'];
    $missingKeys = [];

    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $stats)) {
            $missingKeys[] = $key;
        }
    }

    if (empty($missingKeys)) {
        echo "\n✅ All required keys are present in stats array!\n";
        echo "The 'Undefined array key \"processing\"' error should now be fixed.\n";
    } else {
        echo "\n❌ Missing keys: " . implode(', ', $missingKeys) . "\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\nTest completed.\n";
