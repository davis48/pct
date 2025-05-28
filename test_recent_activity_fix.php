<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Http\Controllers\Agent\StatisticsController;

// Initialize Eloquent
$capsule = new Capsule();
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database/database.sqlite',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🔧 TESTING STATISTICS RECENT ACTIVITY FIX\n\n";

try {
    $controller = new StatisticsController();
    $globalStats = $controller->getGlobalStats();

    if (isset($globalStats['recent_activity'])) {
        $recentActivity = $globalStats['recent_activity'];

        echo "📊 Recent activity structure:\n";
        if (!empty($recentActivity)) {
            $firstActivity = $recentActivity[0];
            $activityKeys = array_keys($firstActivity);
            echo "   Keys found: " . implode(', ', $activityKeys) . "\n";

            // Check for required template keys
            $requiredKeys = ['status', 'document_name', 'user_name', 'time'];
            $missingKeys = array_diff($requiredKeys, $activityKeys);

            if (empty($missingKeys)) {
                echo "   ✅ All required template keys present!\n";
                echo "   - status: {$firstActivity['status']}\n";
                echo "   - document_name: {$firstActivity['document_name']}\n";
                echo "   - user_name: {$firstActivity['user_name']}\n";
                echo "   - time: {$firstActivity['time']}\n";
            } else {
                echo "   ❌ Missing keys: " . implode(', ', $missingKeys) . "\n";
            }
        } else {
            echo "   ⚠️ No recent activity data found\n";
        }
    } else {
        echo "   ❌ recent_activity key missing from global stats\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎯 EXPECTED RESULT: Statistics page should now work without status errors!\n";
