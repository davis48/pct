<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CitizenRequest;

echo "=== Testing Attachments Template Fix ===\n\n";

// Test that attachments are properly handled as arrays
echo "1. Testing CitizenRequest attachments as arrays:\n";
try {
    $requests = CitizenRequest::with(['document', 'user'])->take(5)->get();

    echo "   ✓ Successfully loaded " . $requests->count() . " CitizenRequest records\n";

    foreach ($requests as $request) {
        echo "   ✓ Request {$request->id}:\n";
        echo "     - Attachments type: " . gettype($request->attachments) . "\n";
        echo "     - Attachments value: " . ($request->attachments ? json_encode($request->attachments) : 'null') . "\n";

        // Test the template logic
        if ($request->attachments && count($request->attachments) > 0) {
            echo "     - Template count: " . count($request->attachments) . " attachments\n";
        } else {
            echo "     - Template count: 0 attachments (null or empty)\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error testing attachments: " . $e->getMessage() . "\n";
}

echo "2. Testing template logic simulation:\n";
try {
    // Simulate what the template will do
    $request = CitizenRequest::first();
    if ($request) {
        echo "   Testing template conditions for request {$request->id}:\n";

        // Original problematic code: $request->attachments->count()
        // Fixed code: $request->attachments && count($request->attachments) > 0

        $attachments = $request->attachments;
        echo "   - Attachments raw: " . ($attachments ? json_encode($attachments) : 'null') . "\n";
        echo "   - Is attachments truthy: " . ($attachments ? 'true' : 'false') . "\n";

        if ($attachments && count($attachments) > 0) {
            echo "   ✓ Template condition passed: count = " . count($attachments) . "\n";
        } else {
            echo "   ✓ Template condition failed: no attachments to display\n";
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error in template simulation: " . $e->getMessage() . "\n";
}

echo "\n3. Creating test request with attachments:\n";
try {
    // Create a test request with some attachments to verify the fix
    $user = \App\Models\User::where('role', 'citizen')->first();
    $document = \App\Models\Document::first();

    if ($user && $document) {
        $testRequest = CitizenRequest::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'type' => 'test',
            'description' => 'Test request for attachment verification',
            'attachments' => ['test_file1.pdf', 'test_file2.jpg'],
            'status' => 'pending'
        ]);

        echo "   ✓ Created test request {$testRequest->id} with attachments\n";
        echo "   ✓ Attachments count: " . count($testRequest->attachments) . "\n";
        echo "   ✓ Template logic result: " . (count($testRequest->attachments) > 0 ? 'Show attachments' : 'Hide attachments') . "\n";

        // Clean up
        $testRequest->delete();
        echo "   ✓ Test request cleaned up\n";
    } else {
        echo "   ! Could not create test request - missing user or document\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error creating test request: " . $e->getMessage() . "\n";
}

echo "\n=== Template Fix Test Complete ===\n";
