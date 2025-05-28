<?php
// Final Documents Controller Verification
require_once 'vendor/autoload.php';

echo "🔍 Final verification of DocumentsController fixes...\n\n";

// 1. Check that DocumentsController has the correct imports
echo "1. Checking DocumentsController imports...\n";
$controllerContent = file_get_contents('app/Http/Controllers/Agent/DocumentsController.php');

$hasUserImport = strpos($controllerContent, 'use App\Models\User;') !== false;
$hasAuthImport = strpos($controllerContent, 'use Illuminate\Support\Facades\Auth;') !== false;

echo "   - User model import: " . ($hasUserImport ? "✅ Found" : "❌ Missing") . "\n";
echo "   - Auth facade import: " . ($hasAuthImport ? "✅ Found" : "❌ Missing") . "\n";

// 2. Check stats array format
echo "\n2. Checking stats array format...\n";
$hasStandardStats = strpos($controllerContent, "'pendingRequests'") !== false;
$hasOldStats = strpos($controllerContent, "'pending'") !== false && strpos($controllerContent, "'processing'") !== false;

echo "   - Standard stats format (pendingRequests): " . ($hasStandardStats ? "✅ Found" : "❌ Missing") . "\n";
echo "   - Old stats format (pending): " . ($hasOldStats ? "❌ Still present" : "✅ Removed") . "\n";

// 3. Check Auth::id() usage
echo "\n3. Checking Auth::id() usage...\n";
$authUsageCount = substr_count($controllerContent, 'Auth::id()');
$oldAuthUsageCount = substr_count($controllerContent, 'auth()->id()');

echo "   - Auth::id() usages: $authUsageCount " . ($authUsageCount > 0 ? "✅" : "❌") . "\n";
echo "   - auth()->id() usages: $oldAuthUsageCount " . ($oldAuthUsageCount === 0 ? "✅" : "❌") . "\n";

// 4. Check template fixes
echo "\n4. Checking template attachment fixes...\n";
$indexTemplate = file_get_contents('resources/views/agent/documents/index.blade.php');
$showTemplate = file_get_contents('resources/views/agent/documents/show.blade.php');

$indexBadUsage = substr_count($indexTemplate, '->attachments->count()');
$showBadUsage = substr_count($showTemplate, '->attachments->count()');
$indexGoodUsage = substr_count($indexTemplate, 'count($request->attachments)');
$showGoodUsage = substr_count($showTemplate, 'count($documentRequest->attachments)');

echo "   - Index template bad usage: $indexBadUsage " . ($indexBadUsage === 0 ? "✅" : "❌") . "\n";
echo "   - Index template good usage: $indexGoodUsage " . ($indexGoodUsage > 0 ? "✅" : "❌") . "\n";
echo "   - Show template bad usage: $showBadUsage " . ($showBadUsage === 0 ? "✅" : "❌") . "\n";
echo "   - Show template good usage: $showGoodUsage " . ($showGoodUsage > 0 ? "✅" : "❌") . "\n";

// 5. Summary
echo "\n📊 SUMMARY:\n";
$allGood = $hasUserImport && $hasAuthImport && $hasStandardStats && !$hasOldStats &&
           $authUsageCount > 0 && $oldAuthUsageCount === 0 &&
           $indexBadUsage === 0 && $showBadUsage === 0 &&
           $indexGoodUsage > 0 && $showGoodUsage > 0;

if ($allGood) {
    echo "✅ ALL FIXES VERIFIED! DocumentsController is now consistent with other agent controllers.\n";
    echo "🎉 The undefined array key errors should be resolved.\n";
} else {
    echo "❌ Some issues remain. Please review the items marked with ❌ above.\n";
}

echo "\n🔧 Next steps:\n";
echo "   - Test the agent interface in browser at: http://localhost:8000/agent/login\n";
echo "   - Navigate to Documents section to verify no errors\n";
echo "   - Check browser console and Laravel logs for any remaining issues\n";

echo "\n✅ Verification complete!\n";
?>
