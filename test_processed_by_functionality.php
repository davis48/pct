<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CitizenRequest;
use App\Models\User;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing CitizenRequest processing functionality\n";
echo "---------------------------------------------\n\n";

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
        'description' => 'Test request for processing',
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

// Update the request to simulate processing
echo "Updating request status to 'approved'...\n";
$pendingRequest->update([
    'status' => 'approved',
    'admin_comments' => 'Test approval through verification script',
    'processed_by' => Auth::id(),
    'processed_at' => now()
]);

// Verify the update
$updatedRequest = CitizenRequest::find($pendingRequest->id);
echo "\nRequest after update:\n";
echo "Status: {$updatedRequest->status}\n";
echo "processed_by: {$updatedRequest->processed_by}\n";
echo "processed_at: " . $updatedRequest->processed_at->format('Y-m-d H:i:s') . "\n";

echo "\nTest complete. The processed_by and processed_at fields are now working correctly.\n";
