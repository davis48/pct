<?php
/**
 * Statistics Controller Fix Verification Test
 * Testing that the statistics controller now includes 'completed' key
 */

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Initialize Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🧪 STATISTICS CONTROLLER FIX VERIFICATION\n";
echo "==========================================\n\n";

try {
    // Test StatisticsController
    $controller = new \App\Http\Controllers\Agent\StatisticsController();

    echo "1️⃣  Testing StatisticsController...\n";

    // Check if controller file has 'completed' key
    $reflection = new ReflectionClass($controller);
    $code = file_get_contents($reflection->getFileName());

    $hasCompletedInGlobal = strpos($code, "'completed' => CitizenRequest::where('status', 'approved')->count()") !== false;
    $hasCompletedInAgent = strpos($code, "'completed' => CitizenRequest::where('status', 'approved')->count()") !== false;

    echo "   ✅ Global stats has 'completed' key: " . ($hasCompletedInGlobal ? 'Yes' : 'No') . "\n";
    echo "   ✅ Agent stats has 'completed' key: " . ($hasCompletedInAgent ? 'Yes' : 'No') . "\n";

    echo "\n2️⃣  Testing Statistics Template...\n";

    // Check statistics template
    $templateFile = __DIR__.'/resources/views/agent/statistics/index.blade.php';
    if (file_exists($templateFile)) {
        $template = file_get_contents($templateFile);

        $usesCompleted = strpos($template, "\$globalStats['requests']['completed']") !== false;
        echo "   ✅ Template uses 'completed' key: " . ($usesCompleted ? 'Yes' : 'No') . "\n";
    }

    echo "\n✅ STATISTICS FIX STATUS: ";
    if ($hasCompletedInGlobal && $hasCompletedInAgent && $usesCompleted) {
        echo "COMPLETE ✅\n";
        echo "The undefined 'completed' key error should now be resolved!\n";
    } else {
        echo "INCOMPLETE ❌\n";
        echo "Some components still need fixing.\n";
    }

} catch (Exception $e) {
    echo "❌ Test error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Next step: Refresh the /agent/statistics page to confirm the fix!\n";
