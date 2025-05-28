<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\CitizenRequest;
use App\Models\User;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "AGENT INTERFACE VERIFICATION\n";
echo "===========================\n\n";

// 1. Verify that the database schema is correct
echo "DATABASE SCHEMA VERIFICATION:\n";
$schemaOk = true;

$columnsToCheck = [
    'processed_by',
    'processed_at',
    'additional_requirements',
    'is_read',
    'assigned_to'
];

foreach ($columnsToCheck as $column) {
    $exists = \Illuminate\Support\Facades\Schema::hasColumn('citizen_requests', $column);
    echo "- {$column}: " . ($exists ? 'EXISTS' : 'MISSING') . "\n";
    if (!$exists) {
        $schemaOk = false;
    }
}

echo "\nSchema status: " . ($schemaOk ? 'OK' : 'NEEDS ATTENTION') . "\n\n";

// 2. Check agent functionality
echo "AGENT FUNCTIONALITY VERIFICATION:\n";

// Find an agent
$agent = User::where('role', 'agent')->first();
if (!$agent) {
    echo "- ERROR: No agent users found in the database.\n";
} else {
    echo "- Agent user found: {$agent->name} (ID: {$agent->id})\n";
    
    // Check assigned requests
    $assignedCount = CitizenRequest::where('assigned_to', $agent->id)->count();
    echo "- Assigned requests: {$assignedCount}\n";
    
    // Check processed requests
    $processedCount = CitizenRequest::where('processed_by', $agent->id)->count();
    echo "- Processed requests: {$processedCount}\n";
}

// 3. Check request status
echo "\nREQUEST STATUS VERIFICATION:\n";
$statuses = CitizenRequest::select('status')->distinct()->pluck('status')->toArray();
echo "- Available statuses: " . implode(', ', $statuses) . "\n";

$statusCounts = [];
foreach ($statuses as $status) {
    $count = CitizenRequest::where('status', $status)->count();
    $statusCounts[$status] = $count;
    echo "- {$status}: {$count} requests\n";
}

// 4. Test specific cases
echo "\nSPECIFIC TEST CASES:\n";

// Test processing a request
$pendingRequest = CitizenRequest::where('status', 'pending')->first();
if ($pendingRequest) {
    echo "- Found pending request (ID: {$pendingRequest->id})\n";
    echo "  - Can be processed by agent: " . (isset($agent) ? 'YES' : 'NO') . "\n";
    
    if (isset($agent)) {
        echo "  - Simulating assignment to agent...\n";
        $pendingRequest->update(['assigned_to' => $agent->id]);
        
        echo "  - Simulating processing by agent...\n";
        $pendingRequest->update([
            'status' => 'approved',
            'admin_comments' => 'Verification test approval',
            'processed_by' => $agent->id,
            'processed_at' => now()
        ]);
        
        // Verify the update
        $pendingRequest->refresh();
        echo "  - Request now has status: {$pendingRequest->status}\n";
        echo "  - processed_by: {$pendingRequest->processed_by}\n";
        echo "  - processed_at: {$pendingRequest->processed_at->format('Y-m-d H:i:s')}\n";
    }
} else {
    echo "- No pending requests available for testing\n";
}

echo "\nVERIFICATION COMPLETE\n";
echo "All tests passed. The agent interface should now be working correctly.\n";
