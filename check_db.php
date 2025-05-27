<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CitizenRequest;

echo "Checking citizen requests...\n";

$request = CitizenRequest::first();
if ($request) {
    echo "Found request:\n";
    print_r($request->toArray());
} else {
    echo "No citizen requests found in database\n";
}

echo "\nChecking table columns...\n";
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('citizen_requests');
echo "Columns in citizen_requests table:\n";
print_r($columns);
