<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CitizenRequest;

echo "Populating reference numbers for existing citizen requests...\n";

$requests = CitizenRequest::whereNull('reference_number')->get();

echo "Found " . $requests->count() . " requests without reference numbers.\n";

foreach ($requests as $request) {
    do {
        $referenceNumber = 'REQ-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
    } while (CitizenRequest::where('reference_number', $referenceNumber)->exists());

    $request->update(['reference_number' => $referenceNumber]);
    echo "Updated request ID {$request->id} with reference number: {$referenceNumber}\n";
}

echo "All existing requests now have reference numbers!\n";
