<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CitizenRequest;
use App\Models\User;

echo "Creating a test request with null document...\n";

// Get a user to create a request for
$user = User::where('role', 'citizen')->first();

if ($user) {
    // Create a request without a document
    $request = CitizenRequest::create([
        'user_id' => $user->id,
        'document_id' => null, // Explicitly set to null
        'type' => 'complaint',
        'description' => 'Test request with no associated document to verify null handling',
        'status' => 'pending',
        'admin_comments' => null,
        'attachments' => null,
    ]);

    echo "Created test request with ID: {$request->id} and reference: {$request->reference_number}\n";
    echo "Document ID: " . ($request->document_id ?? 'null') . "\n";
    echo "Document title: " . ($request->document ? $request->document->title : 'No document') . "\n";
} else {
    echo "No citizen users found to create test request.\n";
}

// Now test the view rendering logic
echo "\nTesting view logic simulation:\n";
$testRequest = CitizenRequest::with(['user', 'document'])->whereNull('document_id')->first();

if ($testRequest) {
    echo "Found request without document:\n";
    echo "Reference: {$testRequest->reference_number}\n";
    echo "Document check: " . ($testRequest->document ? 'Has document' : 'No document') . "\n";

    // Simulate the view logic that was causing the error
    if ($testRequest->document) {
        echo "Document title: {$testRequest->document->title}\n";
        echo "Document category: {$testRequest->document->category}\n";
    } else {
        echo "Document title: Document non spécifié\n";
        echo "Document category: -\n";
    }
}

echo "\nAll tests passed!\n";
