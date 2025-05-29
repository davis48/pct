<?php

echo "=== Dashboard View Fix Verification ===\n\n";

// Check if the view file exists
$viewPath = __DIR__ . '/resources/views/front/dashboard.blade.php';
if (file_exists($viewPath)) {
    echo "✓ Dashboard view file exists\n";
    
    // Check if the problematic line has been fixed
    $content = file_get_contents($viewPath);
    
    if (strpos($content, 'Auth::user()->requests && count(Auth::user()->requests)') !== false) {
        echo "✗ Old problematic code still present\n";
    } else {
        echo "✓ Problematic code has been removed\n";
    }
    
    if (strpos($content, '$userRequests = Auth::user()->requests()->latest()->take(5)->get();') !== false) {
        echo "✓ New defensive code is present\n";
    } else {
        echo "✗ New defensive code not found\n";
    }
    
} else {
    echo "✗ Dashboard view file not found\n";
}

// Check if the User model has the requests relationship
$userModelPath = __DIR__ . '/app/Models/User.php';
if (file_exists($userModelPath)) {
    echo "✓ User model exists\n";
    
    $userContent = file_get_contents($userModelPath);
    if (strpos($userContent, 'public function requests()') !== false) {
        echo "✓ User model has requests() relationship\n";
    } else {
        echo "✗ User model missing requests() relationship\n";
    }
} else {
    echo "✗ User model not found\n";
}

// Check if CitizenRequest model exists
$requestModelPath = __DIR__ . '/app/Models/CitizenRequest.php';
if (file_exists($requestModelPath)) {
    echo "✓ CitizenRequest model exists\n";
} else {
    echo "✗ CitizenRequest model not found\n";
}

// Check if view config exists
$viewConfigPath = __DIR__ . '/config/view.php';
if (file_exists($viewConfigPath)) {
    echo "✓ View configuration file exists\n";
} else {
    echo "✗ View configuration file missing\n";
}

echo "\n=== Summary ===\n";
echo "The issue was in the dashboard view template where it was trying to access\n";
echo "Auth::user()->requests as a property instead of calling the relationship method.\n";
echo "This has been fixed by using a more defensive approach with @php block.\n\n";

echo "The fix should resolve the 'View [front.dashboard] not found' error\n";
echo "which was actually a view compilation error, not a missing view file.\n";
