<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CitizenRequest;

echo "=== Testing DocumentsController Attachments Fix ===\n\n";

// Test that the CitizenRequest model has the attachments relationship
echo "1. Testing CitizenRequest model attachments relationship:\n";
try {
    $request = CitizenRequest::with(['document', 'user', 'attachments'])->first();
    if ($request) {
        echo "   ✓ Successfully loaded CitizenRequest with attachments relationship\n";
        echo "   ✓ Request ID: {$request->id}\n";

        // Test accessing attachments
        $attachmentsCount = $request->attachments->count();
        echo "   ✓ Attachments count: {$attachmentsCount}\n";
        echo "   ✓ Attachments is: " . gettype($request->attachments) . "\n";
    } else {
        echo "   ! No CitizenRequest records found in database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error loading attachments: " . $e->getMessage() . "\n";
}

echo "\n2. Testing DocumentsController index query simulation:\n";
try {
    // Simulate the exact query from DocumentsController::index()
    $query = CitizenRequest::with(['document', 'user', 'attachments']);
    $requests = $query->latest()->take(5)->get();

    echo "   ✓ Successfully executed DocumentsController index query\n";
    echo "   ✓ Retrieved " . $requests->count() . " requests\n";

    foreach ($requests as $request) {
        echo "   ✓ Request {$request->id}: attachments count = " . $request->attachments->count() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error in DocumentsController index query: " . $e->getMessage() . "\n";
}

echo "\n3. Testing DocumentsController show query simulation:\n";
try {
    $request = CitizenRequest::first();
    if ($request) {
        $request->load(['user', 'document', 'attachments']);
        echo "   ✓ Successfully loaded request with all relationships\n";
        echo "   ✓ Request {$request->id}: attachments count = " . $request->attachments->count() . "\n";
    } else {
        echo "   ! No CitizenRequest records found for show test\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error in DocumentsController show query: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
