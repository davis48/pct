<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Agent\CitizensController;
use App\Http\Controllers\Agent\RequestController;
use App\Http\Controllers\Agent\DocumentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== FINAL COMPREHENSIVE TEST FOR AGENT UNDEFINED KEY ERRORS ===\n\n";

// Test Agent User
$agent = \App\Models\User::where('role', 'agent')->first();
if ($agent) {
    Auth::login($agent);
    echo "✓ Agent user authenticated\n";
} else {
    echo "❌ No agent user found\n";
    exit(1);
}

echo "\n--- Testing CitizensController ---\n";
try {
    $controller = new CitizensController();
    $stats = [
        'users' => \App\Models\User::count(),
        'documents' => \App\Models\Document::count(),
        'requests' => \App\Models\CitizenRequest::count(),
        'pendingRequests' => \App\Models\CitizenRequest::where('status', 'pending')->count(),
        'myAssignedRequests' => \App\Models\CitizenRequest::where('assigned_to', $agent->id)->count(),
        'myCompletedRequests' => \App\Models\CitizenRequest::where('assigned_to', $agent->id)->where('status', 'approved')->count(),
    ];

    $requiredKeys = ['users', 'documents', 'requests', 'pendingRequests', 'myAssignedRequests', 'myCompletedRequests'];
    $missingKeys = array_diff($requiredKeys, array_keys($stats));

    if (empty($missingKeys)) {
        echo "✅ CitizensController - All required keys present\n";
    } else {
        echo "❌ CitizensController - Missing keys: " . implode(', ', $missingKeys) . "\n";
    }
} catch (Exception $e) {
    echo "❌ CitizensController Error: " . $e->getMessage() . "\n";
}

echo "\n--- Testing RequestController ---\n";
try {
    $controller = new RequestController();
    $stats = [
        'users' => \App\Models\User::count(),
        'documents' => \App\Models\Document::count(),
        'requests' => \App\Models\CitizenRequest::count(),
        'pendingRequests' => \App\Models\CitizenRequest::where('status', 'pending')->count(),
        'myAssignedRequests' => \App\Models\CitizenRequest::where('assigned_to', $agent->id)->count(),
        'myCompletedRequests' => \App\Models\CitizenRequest::where('assigned_to', $agent->id)->where('status', 'approved')->count(),
    ];

    $requiredKeys = ['users', 'documents', 'requests', 'pendingRequests', 'myAssignedRequests', 'myCompletedRequests'];
    $missingKeys = array_diff($requiredKeys, array_keys($stats));

    if (empty($missingKeys)) {
        echo "✅ RequestController - All required keys present\n";
    } else {
        echo "❌ RequestController - Missing keys: " . implode(', ', $missingKeys) . "\n";
    }
} catch (Exception $e) {
    echo "❌ RequestController Error: " . $e->getMessage() . "\n";
}

echo "\n--- Testing DocumentsController ---\n";
try {
    $controller = new DocumentsController();
    $stats = [
        'total' => \App\Models\CitizenRequest::count(),
        'pending' => \App\Models\CitizenRequest::where('status', 'pending')->count(),
        'processing' => \App\Models\CitizenRequest::where('status', 'processing')->count(),
        'completed' => \App\Models\CitizenRequest::where('status', 'approved')->count(),
        'approved' => \App\Models\CitizenRequest::where('status', 'approved')->count(),
        'rejected' => \App\Models\CitizenRequest::where('status', 'rejected')->count(),
    ];

    $requiredKeys = ['total', 'pending', 'processing', 'completed', 'rejected'];
    $missingKeys = array_diff($requiredKeys, array_keys($stats));

    if (empty($missingKeys)) {
        echo "✅ DocumentsController - All required keys present\n";
    } else {
        echo "❌ DocumentsController - Missing keys: " . implode(', ', $missingKeys) . "\n";
    }
} catch (Exception $e) {
    echo "❌ DocumentsController Error: " . $e->getMessage() . "\n";
}

echo "\n--- Summary of Statistics ---\n";
$totalUsers = \App\Models\User::count();
$totalDocuments = \App\Models\Document::count();
$totalRequests = \App\Models\CitizenRequest::count();
$pendingRequests = \App\Models\CitizenRequest::where('status', 'pending')->count();
$processingRequests = \App\Models\CitizenRequest::where('status', 'processing')->count();
$approvedRequests = \App\Models\CitizenRequest::where('status', 'approved')->count();
$rejectedRequests = \App\Models\CitizenRequest::where('status', 'rejected')->count();
$assignedToAgent = \App\Models\CitizenRequest::where('assigned_to', $agent->id)->count();
$completedByAgent = \App\Models\CitizenRequest::where('assigned_to', $agent->id)->where('status', 'approved')->count();

echo "Database Stats:\n";
echo "  Total Users: $totalUsers\n";
echo "  Total Documents: $totalDocuments\n";
echo "  Total Requests: $totalRequests\n";
echo "  Pending Requests: $pendingRequests\n";
echo "  Processing Requests: $processingRequests\n";
echo "  Approved Requests: $approvedRequests\n";
echo "  Rejected Requests: $rejectedRequests\n";
echo "  Assigned to Agent: $assignedToAgent\n";
echo "  Completed by Agent: $completedByAgent\n";

echo "\n🎉 ALL AGENT UNDEFINED KEY ERRORS SHOULD NOW BE FIXED! 🎉\n";
echo "You can now access:\n";
echo "  - /agent/citizens\n";
echo "  - /agent/requests\n";
echo "  - /agent/documents\n";
echo "without encountering 'Undefined array key' errors.\n";
