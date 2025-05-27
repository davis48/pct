<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CitizenRequest;

echo "Testing attachments handling...\n";

// Check requests with different attachment scenarios
$requests = CitizenRequest::with(['user', 'document'])->take(5)->get();

foreach ($requests as $request) {
    echo "Request #{$request->reference_number}:\n";
    echo "  - Attachments type: " . gettype($request->attachments) . "\n";
    echo "  - Attachments value: " . ($request->attachments ? json_encode($request->attachments) : 'null') . "\n";
    echo "  - Document: " . ($request->document ? $request->document->title : 'No document') . "\n\n";
}

echo "Test completed successfully!\n";
