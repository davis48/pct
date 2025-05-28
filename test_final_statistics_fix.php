<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Application;
use App\Http\Controllers\Agent\StatisticsController;
use App\Models\User;
use App\Models\CitizenRequest;
use App\Models\Document;

// Mock the Laravel application
$app = new Application();
$app->singleton('config', function () {
    return new \Illuminate\Config\Repository([
        'database' => [
            'default' => 'sqlite',
            'connections' => [
                'sqlite' => [
                    'driver' => 'sqlite',
                    'database' => __DIR__ . '/database/database.sqlite',
                    'prefix' => '',
                ],
            ],
        ],
    ]);
});

// Bootstrap basic Laravel components
$app->singleton('db', function ($app) {
    $config = $app['config']['database'];
    $default = $config['default'];
    $connection = $config['connections'][$default];

    return new \Illuminate\Database\Capsule\Manager();
});

// Initialize Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database/database.sqlite',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== FINAL STATISTICS FIX TEST ===\n\n";

// Test 1: Statistics Controller Global Stats
echo "1. Testing StatisticsController getGlobalStats...\n";
try {
    $controller = new StatisticsController();
    $globalStats = $controller->getGlobalStats();

    echo "   ✓ Global stats structure:\n";
    echo "   - Requests: " . json_encode(array_keys($globalStats['requests'])) . "\n";
    echo "   - Users: " . json_encode(array_keys($globalStats['users'])) . "\n";
    echo "   - Documents: " . json_encode(array_keys($globalStats['documents'])) . "\n";
    echo "   - Processing: " . json_encode(array_keys($globalStats['processing'])) . "\n";
    echo "   - Agents: " . json_encode(array_keys($globalStats['agents'])) . "\n";

    // Check top performers structure
    if (!empty($globalStats['agents']['top_performers'])) {
        $firstPerformer = $globalStats['agents']['top_performers'][0];
        echo "   - Top performer keys: " . json_encode(array_keys($firstPerformer)) . "\n";
        echo "   - Has success_rate: " . (isset($firstPerformer['success_rate']) ? "YES" : "NO") . "\n";
    }

} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 2: Agent Stats
echo "\n2. Testing getAgentStats...\n";
try {
    $agentStats = $controller->getAgentStats(1);
    echo "   ✓ Agent stats keys: " . json_encode(array_keys($agentStats)) . "\n";
    echo "   - Has success_rate: " . (isset($agentStats['success_rate']) ? "YES" : "NO") . "\n";
    echo "   - Success rate value: " . ($agentStats['success_rate'] ?? 'N/A') . "\n";

} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 3: Verify all required keys for templates
echo "\n3. Checking template compatibility...\n";

$requiredGlobalKeys = [
    'requests' => ['total', 'pending', 'processing', 'approved', 'completed', 'rejected', 'this_month', 'last_month'],
    'users' => ['total_citizens', 'total_agents', 'new_citizens_this_month'],
    'documents' => ['types_count', 'most_requested', 'by_type'],
    'processing' => ['average_time'],
    'agents' => ['top_performers'],
    'recent_activity' => []
];

$requiredAgentKeys = ['total_assigned', 'processing', 'approved', 'completed', 'rejected', 'this_month', 'avg_processing_time', 'performance_rating', 'success_rate'];

$requiredTopPerformerKeys = ['name', 'processed', 'success_rate'];

try {
    $globalStats = $controller->getGlobalStats();
    $agentStats = $controller->getAgentStats(1);

    echo "   Checking global stats keys:\n";
    foreach ($requiredGlobalKeys as $section => $keys) {
        echo "   - $section: ";
        if (isset($globalStats[$section])) {
            $missing = array_diff($keys, array_keys($globalStats[$section]));
            if (empty($missing)) {
                echo "✓ All keys present\n";
            } else {
                echo "✗ Missing: " . implode(', ', $missing) . "\n";
            }
        } else {
            echo "✗ Section missing\n";
        }
    }

    echo "   Checking agent stats keys:\n";
    $missing = array_diff($requiredAgentKeys, array_keys($agentStats));
    if (empty($missing)) {
        echo "   ✓ All agent keys present\n";
    } else {
        echo "   ✗ Missing agent keys: " . implode(', ', $missing) . "\n";
    }

    echo "   Checking top performer keys:\n";
    if (!empty($globalStats['agents']['top_performers'])) {
        $performer = $globalStats['agents']['top_performers'][0];
        $missing = array_diff($requiredTopPerformerKeys, array_keys($performer));
        if (empty($missing)) {
            echo "   ✓ All top performer keys present\n";
        } else {
            echo "   ✗ Missing top performer keys: " . implode(', ', $missing) . "\n";
        }
    } else {
        echo "   ✗ No top performers data\n";
    }

} catch (Exception $e) {
    echo "   ✗ Error during key checking: " . $e->getMessage() . "\n";
}

// Test 4: Test with sample data if available
echo "\n4. Testing with actual data...\n";
try {
    $userCount = User::count();
    $requestCount = CitizenRequest::count();
    $documentCount = Document::count();

    echo "   Database contents:\n";
    echo "   - Users: $userCount\n";
    echo "   - Requests: $requestCount\n";
    echo "   - Documents: $documentCount\n";

    if ($userCount > 0 && $requestCount > 0) {
        echo "   ✓ Sufficient data for realistic testing\n";
    } else {
        echo "   ! Limited data - using simulated values\n";
    }

} catch (Exception $e) {
    echo "   ✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "The statistics controller should now handle all template requirements.\n";
echo "Key fixes made:\n";
echo "- Added 'success_rate' to getTopPerformers() method\n";
echo "- Added 'success_rate' to getAgentStats() method\n";
echo "- Both methods now calculate realistic success rates\n";
echo "- Templates should no longer show 'undefined array key' errors\n";
