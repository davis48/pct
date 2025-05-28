<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if the processed_by column exists
$hasProcessedBy = Schema::hasColumn('citizen_requests', 'processed_by');
$hasProcessedAt = Schema::hasColumn('citizen_requests', 'processed_at');

echo "Checking citizen_requests table structure:\n";
echo "- processed_by column exists: " . ($hasProcessedBy ? 'Yes' : 'No') . "\n";
echo "- processed_at column exists: " . ($hasProcessedAt ? 'Yes' : 'No') . "\n";

// List all columns in the citizen_requests table
echo "\nList of all columns in the citizen_requests table:\n";
$columns = Schema::getColumnListing('citizen_requests');
print_r($columns);

// Count the number of requests with processed_by set
$processedCount = DB::table('citizen_requests')->whereNotNull('processed_by')->count();
echo "\nNumber of requests with processed_by value: $processedCount\n";

echo "\nTest complete. Migration successful if processed_by and processed_at columns exist.\n";
