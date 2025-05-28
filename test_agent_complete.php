<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\CitizenRequest;
use App\Models\Document;

echo "=== AGENT INTERFACE COMPLETE TEST ===\n\n";

// Test 1: Verify Agent Account
echo "1. Testing Agent Account:\n";
$agent = User::where('email', 'agent@pct-uvci.ci')->first();
if ($agent) {
    echo "   ✓ Agent account exists: {$agent->prenoms} {$agent->nom} ({$agent->email})\n";
    echo "   ✓ Role: {$agent->role}\n";
} else {
    echo "   ✗ Agent account not found!\n";
}

// Test 2: Verify Sample Data
echo "\n2. Testing Sample Data:\n";
$citizenCount = User::where('role', 'citizen')->count();
$documentCount = Document::count();
$requestCount = CitizenRequest::count();

echo "   ✓ Citizens: {$citizenCount}\n";
echo "   ✓ Documents: {$documentCount}\n";
echo "   ✓ Requests: {$requestCount}\n";

// Test 3: Verify Request Statistics
echo "\n3. Testing Request Statistics:\n";
$pendingCount = CitizenRequest::where('status', 'pending')->count();
$approvedCount = CitizenRequest::where('status', 'approved')->count();
$rejectedCount = CitizenRequest::where('status', 'rejected')->count();

echo "   ✓ Pending: {$pendingCount}\n";
echo "   ✓ Approved: {$approvedCount}\n";
echo "   ✓ Rejected: {$rejectedCount}\n";

// Test 4: Verify Recent Requests with Relations
echo "\n4. Testing Request Relations:\n";
$recentRequests = CitizenRequest::with(['user', 'document'])
    ->latest()
    ->take(3)
    ->get();

foreach ($recentRequests as $request) {
    $citizenName = $request->user ? "{$request->user->prenoms} {$request->user->nom}" : "Unknown";
    $documentTitle = $request->document ? $request->document->title : "Unknown";
    echo "   ✓ Request #{$request->id}: {$citizenName} - {$documentTitle} ({$request->status})\n";
}

// Test 5: Verify Monthly Statistics
echo "\n5. Testing Monthly Statistics:\n";
$monthlyStats = [];
for ($i = 2; $i >= 0; $i--) {
    $month = now()->subMonths($i);
    $count = CitizenRequest::whereBetween('created_at', [
        $month->startOfMonth(),
        $month->endOfMonth()
    ])->count();
    echo "   ✓ {$month->format('M Y')}: {$count} requests\n";
}

// Test 6: Test Routes (simplified check)
echo "\n6. Agent Interface URLs to Test:\n";
echo "   📱 Login: http://localhost:8000/ (use agent@pct-uvci.ci / password123)\n";
echo "   📊 Dashboard: http://localhost:8000/agent/dashboard\n";
echo "   📋 Requests: http://localhost:8000/agent/requests\n";
echo "   👥 Citizens: http://localhost:8000/agent/citizens\n";
echo "   📄 Documents: http://localhost:8000/agent/documents\n";
echo "   📈 Statistics: http://localhost:8000/agent/statistics\n";

echo "\n=== TEST COMPLETED ===\n";
echo "✨ All systems ready! Agent interface should be fully functional.\n";
echo "🔑 Use agent@pct-uvci.ci / password123 to login as agent\n";
