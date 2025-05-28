<?php
// Test SQLite date function fix for StatisticsController
require_once 'vendor/autoload.php';

echo "🔍 Testing SQLite date function fix...\n\n";

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Test the SQLite date calculation directly
    $result = \Illuminate\Support\Facades\DB::select(
        "SELECT AVG((julianday(updated_at) - julianday(created_at)) * 24) as avg_hours
         FROM citizen_requests
         WHERE status = 'approved'
         LIMIT 1"
    );

    echo "✅ SQLite date function test successful!\n";
    echo "📊 Average processing time query result: " . ($result[0]->avg_hours ?? 'NULL') . " hours\n";

    // Test the StatisticsController method
    echo "\n🎛️ Testing StatisticsController...\n";

    // Mock authentication
    $user = \App\Models\User::first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        echo "✅ User authenticated for testing\n";

        try {
            $controller = new \App\Http\Controllers\Agent\StatisticsController();
            $reflection = new ReflectionClass($controller);
            $method = $reflection->getMethod('getAvgProcessingTime');
            $method->setAccessible(true);

            $avgTime = $method->invoke($controller);
            echo "✅ getAvgProcessingTime() method works: {$avgTime} hours\n";

        } catch (Exception $e) {
            echo "❌ StatisticsController method error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "⚠️ No users found for authentication test\n";
    }

} catch (Exception $e) {
    echo "❌ SQLite query error: " . $e->getMessage() . "\n";
    echo "📝 The TIMESTAMPDIFF function is not supported in SQLite.\n";
    echo "🔧 Fixed with: julianday(updated_at) - julianday(created_at)\n";
}

echo "\n🎯 Next: Test /agent/statistics in browser\n";
?>
