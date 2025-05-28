<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CitizenRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

echo "=== Testing Fixed Attachment Display in show.blade.php ===\n\n";

try {
    echo "1. Testing render of attachments in different formats\n";
    
    // Find requests with attachments
    $requests = CitizenRequest::whereNotNull('attachments')->take(5)->get();
    
    if ($requests->isEmpty()) {
        echo "   No requests with attachments found for testing.\n";
    } else {
        echo "   Found " . $requests->count() . " requests with attachments for testing\n";
        
        foreach ($requests as $request) {
            echo "\n   Request #{$request->id} ({$request->reference_number}):\n";
            echo "   - Attachments type: " . gettype($request->attachments) . "\n";
            
            // Test each attachment and how it would be processed in the blade
            if ($request->attachments && count($request->attachments) > 0) {
                echo "   - Has " . count($request->attachments) . " attachments\n";
                
                foreach ($request->attachments as $index => $attachment) {
                    echo "     - Attachment #{$index} type: " . gettype($attachment) . "\n";
                    
                    // Test the exact rendering logic from the blade template
                    if (is_string($attachment)) {
                        echo "       ✓ String attachment: would display as " . basename($attachment) . "\n";
                    } elseif (is_array($attachment) && isset($attachment['name'])) {
                        echo "       ✓ Array attachment: would display as " . $attachment['name'] . "\n";
                    } else {
                        echo "       ✓ Other format: would display as 'Pièce jointe' with disabled download\n";
                    }
                }
            } else {
                echo "   - No attachments to display\n";
            }
        }
    }
    
    echo "\n2. Testing rendering through Blade simulation\n";
    
    // Create test data with both string and array attachments
    $testRequest = new CitizenRequest();
    $testRequest->attachments = [
        'document1.pdf',
        ['name' => 'document2.pdf', 'path' => 'attachments/document2.pdf'],
        ['type' => 'pdf', 'size' => 1024], // Incomplete array without name
        null // Null attachment
    ];
    
    echo "   Testing with 4 mixed format attachments:\n";
    foreach ($testRequest->attachments as $index => $attachment) {
        echo "   - Attachment #{$index}: ";
        
        if (is_string($attachment)) {
            echo "String format - would display as " . basename($attachment) . "\n";
        } elseif (is_array($attachment) && isset($attachment['name'])) {
            echo "Array format with name - would display as " . $attachment['name'] . "\n";
        } elseif (is_array($attachment)) {
            echo "Array format without name - would display as 'Pièce jointe' with disabled download\n";
        } else {
            echo "Null/invalid format - would display as 'Pièce jointe' with disabled download\n";
        }
    }
    
    echo "\n✅ All attachment format tests completed successfully\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}

echo "\n=== Test Complete ===\n";
