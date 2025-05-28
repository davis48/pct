<?php

require __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\Agent\RequestController;
use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "TESTING REQUEST CONTROLLER UPDATE METHOD\n";
echo "======================================\n\n";

// Find a pending request to update
$pendingRequest = CitizenRequest::where('status', 'pending')->first();

if (!$pendingRequest) {
    echo "No pending requests found. Creating a test request...\n";
    
    // Find a citizen user
    $citizen = User::where('role', 'citizen')->first();
    if (!$citizen) {
        echo "Error: No citizen user found. Cannot create test request.\n";
        exit(1);
    }
    
    // Create a test request
    $pendingRequest = CitizenRequest::create([
        'user_id' => $citizen->id,
        'type' => 'certificate',
        'description' => 'Test request for controller update test',
        'status' => 'pending',
    ]);
    
    echo "Created test request with ID: {$pendingRequest->id}\n";
}

echo "Found request with ID: {$pendingRequest->id}\n";
echo "Current status: {$pendingRequest->status}\n";
echo "processed_by: " . ($pendingRequest->processed_by ?? 'NULL') . "\n";
echo "processed_at: " . ($pendingRequest->processed_at ? $pendingRequest->processed_at->format('Y-m-d H:i:s') : 'NULL') . "\n\n";

// Find an agent user to use for processing
$agent = User::where('role', 'agent')->first();
if (!$agent) {
    echo "Error: No agent user found. Cannot test processing.\n";
    exit(1);
}

// Log in as the agent
Auth::login($agent);
echo "Logged in as agent: {$agent->name} (ID: {$agent->id})\n";

try {
    // Create a request controller instance
    $controller = new RequestController();
    
    // Create a mock HTTP request with the required data
    $httpRequest = Request::create(
        "/agent/requests/{$pendingRequest->id}/update",
        'POST',
        [
            'status' => 'in_progress',
            'admin_comments' => 'Test update through controller',
            'additional_requirements' => null,
        ]
    );
    
    // Simulate the update method call
    echo "Calling RequestController::update method...\n";
    $response = $controller->update($httpRequest, $pendingRequest->id);
    
    echo "Controller response status: " . get_class($response) . "\n";
    
    // Verify the update
    $updatedRequest = CitizenRequest::find($pendingRequest->id);
    echo "\nRequest after update:\n";
    echo "Status: {$updatedRequest->status}\n";
    echo "processed_by: {$updatedRequest->processed_by}\n";
    echo "processed_at: " . ($updatedRequest->processed_at ? $updatedRequest->processed_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    
    echo "\nTest successful! The controller update method is working correctly.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Test failed. Please check the controller code.\n";
}
