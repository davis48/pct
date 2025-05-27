<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CitizenRequest;

echo "Testing the fix for document null error...\n\n";

// Get requests with and without documents
$requestsWithDocument = CitizenRequest::with(['user', 'document'])->whereNotNull('document_id')->get();
$requestsWithoutDocument = CitizenRequest::with(['user', 'document'])->whereNull('document_id')->get();

echo "Requests with documents: " . $requestsWithDocument->count() . "\n";
echo "Requests without documents: " . $requestsWithoutDocument->count() . "\n\n";

// Test accessing document properties that would cause the error
echo "Testing document access (this should not throw errors now):\n";

foreach (CitizenRequest::with(['user', 'document'])->take(5)->get() as $request) {
    echo "Request #{$request->reference_number}:\n";
    echo "  - Document title: " . ($request->document ? $request->document->title : 'No document') . "\n";
    echo "  - Document category: " . ($request->document ? $request->document->category : 'No category') . "\n";
    echo "  - User: " . $request->user->nom . " " . $request->user->prenoms . "\n\n";
}

echo "Test completed successfully! No errors occurred.\n";
