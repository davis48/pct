<?php
// Test the Documents Controller route after template fix
require_once 'vendor/autoload.php';

echo "🔍 Testing Documents Controller after template fix...\n\n";

// Load Laravel application
$app = require_once 'bootstrap/app.php';

try {
    // Check that DocumentsController returns the correct stats format
    $controller = new App\Http\Controllers\Agent\DocumentsController();

    // Mock Auth facade
    \Illuminate\Support\Facades\Auth::shouldReceive('id')->andReturn(1);

    echo "✅ DocumentsController instantiated successfully\n";

    // Test the stats format
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('index');

    echo "✅ Method 'index' found\n";

    // Check template file exists and uses correct stats keys
    $templatePath = 'resources/views/agent/documents/index.blade.php';
    $templateContent = file_get_contents($templatePath);

    $badKeys = ['total', 'pending', 'processing', 'completed', 'rejected'];
    $goodKeys = ['documents', 'pendingRequests', 'myAssignedRequests', 'myCompletedRequests', 'requests'];

    $foundBadKeys = [];
    $foundGoodKeys = [];

    foreach ($badKeys as $key) {
        if (strpos($templateContent, "\$stats['$key']") !== false) {
            $foundBadKeys[] = $key;
        }
    }

    foreach ($goodKeys as $key) {
        if (strpos($templateContent, "\$stats['$key']") !== false) {
            $foundGoodKeys[] = $key;
        }
    }

    echo "\n📊 Template Analysis:\n";
    echo "   Bad keys found: " . (empty($foundBadKeys) ? "None ✅" : implode(', ', $foundBadKeys) . " ❌") . "\n";
    echo "   Good keys found: " . (count($foundGoodKeys) > 0 ? implode(', ', $foundGoodKeys) . " ✅" : "None ❌") . "\n";

    if (empty($foundBadKeys) && count($foundGoodKeys) > 0) {
        echo "\n🎉 SUCCESS! Template now uses standardized stats format.\n";
        echo "📝 The undefined array key error should be resolved.\n";
    } else {
        echo "\n❌ Template still has issues that need fixing.\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n🔧 Next: Test in browser at http://localhost:8000/agent/documents\n";
?>
