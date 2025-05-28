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

echo "🎯 === ABSOLUTE FINAL STATISTICS VERIFICATION === 🎯\n\n";

try {
    $controller = new StatisticsController();

    // Test 1: Global Stats Structure
    echo "1️⃣ Testing getGlobalStats() structure...\n";
    $globalStats = $controller->getGlobalStats();

    $requiredGlobalSections = [
        'requests' => ['total', 'pending', 'processing', 'approved', 'completed', 'rejected', 'this_month', 'last_month'],
        'users' => ['total_citizens', 'total_agents', 'new_citizens_this_month'],
        'documents' => ['types_count', 'most_requested', 'by_type'],
        'processing' => ['average_time'],
        'agents' => ['top_performers'],
        'recent_activity' => []
    ];

    $allSectionsPass = true;
    foreach ($requiredGlobalSections as $section => $requiredKeys) {
        if (isset($globalStats[$section])) {
            if ($section === 'recent_activity') {
                echo "   ✅ $section: Present\n";
            } else {
                $missing = array_diff($requiredKeys, array_keys($globalStats[$section]));
                if (empty($missing)) {
                    echo "   ✅ $section: All keys present\n";
                } else {
                    echo "   ❌ $section: Missing " . implode(', ', $missing) . "\n";
                    $allSectionsPass = false;
                }
            }
        } else {
            echo "   ❌ $section: Section missing\n";
            $allSectionsPass = false;
        }
    }

    // Test 2: Top Performers Structure
    echo "\n2️⃣ Testing top performers structure...\n";
    if (isset($globalStats['agents']['top_performers']) && !empty($globalStats['agents']['top_performers'])) {
        $performer = $globalStats['agents']['top_performers'][0];
        $requiredPerformerKeys = ['name', 'processed', 'success_rate'];
        $missing = array_diff($requiredPerformerKeys, array_keys($performer));

        if (empty($missing)) {
            echo "   ✅ Top performers: All keys present (name, processed, success_rate)\n";
        } else {
            echo "   ❌ Top performers: Missing " . implode(', ', $missing) . "\n";
            $allSectionsPass = false;
        }
    } else {
        echo "   ❌ Top performers: No data found\n";
        $allSectionsPass = false;
    }

    // Test 3: Recent Activity Structure
    echo "\n3️⃣ Testing recent activity structure...\n";
    if (isset($globalStats['recent_activity']) && !empty($globalStats['recent_activity'])) {
        $activity = $globalStats['recent_activity'][0];
        $requiredActivityKeys = ['status', 'document_name', 'user_name', 'time'];
        $missing = array_diff($requiredActivityKeys, array_keys($activity));

        if (empty($missing)) {
            echo "   ✅ Recent activity: All template keys present (status, document_name, user_name, time)\n";
            echo "      Sample data:\n";
            echo "      - Status: {$activity['status']}\n";
            echo "      - Document: {$activity['document_name']}\n";
            echo "      - User: {$activity['user_name']}\n";
            echo "      - Time: {$activity['time']}\n";
        } else {
            echo "   ❌ Recent activity: Missing " . implode(', ', $missing) . "\n";
            $allSectionsPass = false;
        }
    } else {
        echo "   ❌ Recent activity: No data found\n";
        $allSectionsPass = false;
    }

    // Test 4: Agent Stats Structure
    echo "\n4️⃣ Testing getAgentStats() structure...\n";
    $agentStats = $controller->getAgentStats(1);
    $requiredAgentKeys = ['total_assigned', 'processing', 'approved', 'completed', 'rejected', 'this_month', 'avg_processing_time', 'performance_rating', 'success_rate'];
    $missing = array_diff($requiredAgentKeys, array_keys($agentStats));

    if (empty($missing)) {
        echo "   ✅ Agent stats: All keys present including success_rate\n";
    } else {
        echo "   ❌ Agent stats: Missing " . implode(', ', $missing) . "\n";
        $allSectionsPass = false;
    }

    // Final Result
    echo "\n" . str_repeat("=", 60) . "\n";
    if ($allSectionsPass) {
        echo "🎉 ALL STATISTICS TESTS PASSED! 🎉\n";
        echo "✅ Global stats structure: COMPLETE\n";
        echo "✅ Top performers with success_rate: WORKING\n";
        echo "✅ Recent activity with status: WORKING\n";
        echo "✅ Agent stats with success_rate: WORKING\n";
        echo "\n🚀 STATISTICS PAGE SHOULD BE FULLY FUNCTIONAL! 🚀\n";
    } else {
        echo "❌ SOME TESTS FAILED - Review above output\n";
    }
    echo str_repeat("=", 60) . "\n";

} catch (Exception $e) {
    echo "❌ CRITICAL ERROR: " . $e->getMessage() . "\n";
}

echo "\n🎯 READY TO TEST: Visit http://127.0.0.1:8000/agent/statistics\n";
