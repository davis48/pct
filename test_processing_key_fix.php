<?php
/**
 * Statistics "Processing" Key Fix Verification
 * Testing that the statistics controller now includes 'processing' key
 */

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Initialize Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🧪 STATISTICS 'PROCESSING' KEY FIX VERIFICATION\n";
echo "===============================================\n\n";

try {
    // Test StatisticsController
    $controller = new \App\Http\Controllers\Agent\StatisticsController();

    echo "1️⃣  Testing StatisticsController...\n";

    // Check if controller file has 'processing' key
    $reflection = new ReflectionClass($controller);
    $code = file_get_contents($reflection->getFileName());

    $hasProcessingInGlobal = strpos($code, "'processing' => CitizenRequest::where('status', 'processing')->count()") !== false;
    $hasCompletedKey = strpos($code, "'completed' => CitizenRequest::where('status', 'approved')->count()") !== false;

    echo "   ✅ Global stats has 'processing' key: " . ($hasProcessingInGlobal ? 'Yes' : 'No') . "\n";
    echo "   ✅ Global stats has 'completed' key: " . ($hasCompletedKey ? 'Yes' : 'No') . "\n";

    echo "\n2️⃣  Testing Statistics Template...\n";

    // Check statistics template
    $templateFile = __DIR__.'/resources/views/agent/statistics/index.blade.php';
    if (file_exists($templateFile)) {
        $template = file_get_contents($templateFile);

        $usesProcessing = strpos($template, "\$globalStats['requests']['processing']") !== false;
        $usesCompleted = strpos($template, "\$globalStats['requests']['completed']") !== false;

        echo "   ✅ Template uses 'processing' key: " . ($usesProcessing ? 'Yes' : 'No') . "\n";
        echo "   ✅ Template uses 'completed' key: " . ($usesCompleted ? 'Yes' : 'No') . "\n";
    }

    echo "\n✅ STATISTICS FIX STATUS: ";
    if ($hasProcessingInGlobal && $hasCompletedKey && $usesProcessing && $usesCompleted) {
        echo "COMPLETE ✅\n";
        echo "Both 'processing' and 'completed' key errors should now be resolved!\n";
    } else {
        echo "INCOMPLETE ❌\n";
        echo "Some components still need fixing.\n";
    }

} catch (Exception $e) {
    echo "❌ Test error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Next step: Refresh the /agent/statistics page to confirm the fix!\n";
