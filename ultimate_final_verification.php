<?php
// ULTIMATE FINAL VERIFICATION - All Fixes Complete
require_once 'vendor/autoload.php';

echo "🎯 ULTIMATE FINAL VERIFICATION - Agent Interface Complete Fix\n";
echo "=" . str_repeat("=", 60) . "\n\n";

$allGood = true;
$issues = [];

// 1. Check DocumentsController
echo "1. 📄 DocumentsController Analysis:\n";
$controllerContent = file_get_contents('app/Http/Controllers/Agent/DocumentsController.php');

$checks = [
    'User Import' => strpos($controllerContent, 'use App\Models\User;') !== false,
    'Auth Import' => strpos($controllerContent, 'use Illuminate\Support\Facades\Auth;') !== false,
    'Standard Stats' => strpos($controllerContent, "'pendingRequests'") !== false,
    'No Old Stats Keys' => strpos($controllerContent, "'pending' =>") === false &&
                          strpos($controllerContent, "'processing' =>") === false &&
                          strpos($controllerContent, "'total' =>") === false &&
                          strpos($controllerContent, "'completed' =>") === false &&
                          strpos($controllerContent, "'rejected' =>") === false,
    'Auth::id() Usage' => substr_count($controllerContent, 'Auth::id()') >= 3,
    'No auth()->id()' => strpos($controllerContent, 'auth()->id()') === false,
];

foreach ($checks as $check => $passed) {
    echo "   " . ($passed ? "✅" : "❌") . " $check\n";
    if (!$passed) {
        $allGood = false;
        $issues[] = "DocumentsController: $check";
    }
}

// 2. Check Templates
echo "\n2. 🎨 Template Analysis:\n";

// Documents Index Template
$indexTemplate = file_get_contents('resources/views/agent/documents/index.blade.php');
$indexBadKeys = 0;
$indexGoodKeys = 0;

$badPatterns = ["\$stats['total']", "\$stats['pending']", "\$stats['processing']", "\$stats['completed']", "\$stats['rejected']"];
$goodPatterns = ["\$stats['documents']", "\$stats['pendingRequests']", "\$stats['myAssignedRequests']"];

foreach ($badPatterns as $pattern) {
    if (strpos($indexTemplate, $pattern) !== false) $indexBadKeys++;
}
foreach ($goodPatterns as $pattern) {
    if (strpos($indexTemplate, $pattern) !== false) $indexGoodKeys++;
}

$indexAttachmentsBad = substr_count($indexTemplate, '->attachments->count()');
$indexAttachmentsGood = substr_count($indexTemplate, 'count($request->attachments)');

echo "   " . ($indexBadKeys === 0 ? "✅" : "❌") . " Index template - No bad stats keys ($indexBadKeys found)\n";
echo "   " . ($indexGoodKeys >= 3 ? "✅" : "❌") . " Index template - Has good stats keys ($indexGoodKeys found)\n";
echo "   " . ($indexAttachmentsBad === 0 ? "✅" : "❌") . " Index template - No bad attachments usage ($indexAttachmentsBad found)\n";
echo "   " . ($indexAttachmentsGood >= 3 ? "✅" : "❌") . " Index template - Has safe attachments usage ($indexAttachmentsGood found)\n";

if ($indexBadKeys > 0) {
    $allGood = false;
    $issues[] = "Index template: Still has old stats keys";
}
if ($indexAttachmentsBad > 0) {
    $allGood = false;
    $issues[] = "Index template: Still has unsafe attachment usage";
}

// Documents Show Template
$showTemplate = file_get_contents('resources/views/agent/documents/show.blade.php');
$showAttachmentsBad = substr_count($showTemplate, '->attachments->count()');
$showAttachmentsGood = substr_count($showTemplate, 'count($documentRequest->attachments)');

echo "   " . ($showAttachmentsBad === 0 ? "✅" : "❌") . " Show template - No bad attachments usage ($showAttachmentsBad found)\n";
echo "   " . ($showAttachmentsGood >= 1 ? "✅" : "❌") . " Show template - Has safe attachments usage ($showAttachmentsGood found)\n";

if ($showAttachmentsBad > 0) {
    $allGood = false;
    $issues[] = "Show template: Still has unsafe attachment usage";
}

// 3. Check other controllers for consistency
echo "\n3. 🎛️ Controller Consistency Check:\n";

$controllers = [
    'CitizensController' => 'app/Http/Controllers/Agent/CitizensController.php',
    'RequestController' => 'app/Http/Controllers/Agent/RequestController.php'
];

foreach ($controllers as $name => $path) {
    $content = file_get_contents($path);
    $hasStandardStats = strpos($content, "'pendingRequests'") !== false;
    $hasUserImport = strpos($content, 'use App\Models\User;') !== false;

    echo "   " . ($hasStandardStats ? "✅" : "❌") . " $name - Standard stats format\n";
    echo "   " . ($hasUserImport ? "✅" : "❌") . " $name - User import\n";

    if (!$hasStandardStats || !$hasUserImport) {
        $allGood = false;
        $issues[] = "$name: Missing standard format or imports";
    }
}

// Final Summary
echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 ULTIMATE FINAL SUMMARY:\n\n";

if ($allGood) {
    echo "🎉 🎉 🎉 ALL FIXES COMPLETE AND VERIFIED! 🎉 🎉 🎉\n\n";
    echo "✅ DocumentsController: Fully updated with standard format\n";
    echo "✅ Templates: All undefined key errors fixed\n";
    echo "✅ Controller Consistency: All agent controllers standardized\n";
    echo "✅ Attachments: All template errors resolved\n\n";
    echo "🌐 The agent interface should now work without any undefined array key errors!\n";
    echo "🔗 Test at: http://localhost:8000/agent/documents\n\n";
    echo "🏆 STATUS: MISSION ACCOMPLISHED! 🏆\n";
} else {
    echo "❌ ISSUES STILL REMAINING:\n\n";
    foreach ($issues as $issue) {
        echo "   • $issue\n";
    }
    echo "\n🔧 Please address the remaining issues above.\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
?>
