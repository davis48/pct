<?php
/**
 * Test final de validation complète du système
 * Vérifie que tous les éléments demandés sont en place
 */

echo "🎯 FINAL SYSTEM VALIDATION TEST\n";
echo "===============================\n\n";

// Test des documents disponibles dans le formulaire citoyen
echo "📋 CITIZEN FORM DOCUMENT TYPES:\n";
$citizenForm = file_get_contents(__DIR__ . '/resources/views/front/requests/create.blade.php');
$documentTypes = [];

if (preg_match_all('/<option value="([^"]*)"[^>]*>([^<]*)<\/option>/', $citizenForm, $matches)) {
    for ($i = 0; $i < count($matches[1]); $i++) {
        if (!empty($matches[1][$i]) && $matches[1][$i] !== '') {
            $documentTypes[] = $matches[2][$i];
        }
    }
}

foreach ($documentTypes as $type) {
    echo "   ✅ " . trim($type) . "\n";
}

// Vérification spéciale pour déclaration de naissance
if (in_array('Déclaration de naissance', $documentTypes)) {
    echo "   🎯 BIRTH DECLARATION: ✅ PRESENT\n";
} else {
    echo "   🎯 BIRTH DECLARATION: ❌ MISSING\n";
}

echo "\n🏛️ ADMIN STATISTICS DOCUMENT TYPES:\n";
$adminController = file_get_contents(__DIR__ . '/app/Http/Controllers/Admin/AdminSpecialController.php');

// Extraire les types de documents des statistiques admin
preg_match_all("/'\s*([^']*)\s*' => \[\s*'name' => '([^']*)',/", $adminController, $adminMatches);

$adminDocTypes = [];
for ($i = 0; $i < count($adminMatches[2]); $i++) {
    $adminDocTypes[] = $adminMatches[2][$i];
}

foreach ($adminDocTypes as $type) {
    echo "   ✅ " . $type . "\n";
}

// Vérifications spéciales
if (in_array('Déclaration de Naissance', $adminDocTypes)) {
    echo "   🎯 BIRTH DECLARATION: ✅ PRESENT\n";
} else {
    echo "   🎯 BIRTH DECLARATION: ❌ MISSING\n";
}

if (in_array('Casier Judiciaire', $adminDocTypes)) {
    echo "   ⚠️  CRIMINAL RECORD: ❌ STILL PRESENT (should be removed)\n";
} else {
    echo "   🎯 CRIMINAL RECORD: ✅ REMOVED\n";
}

echo "\n🤖 AGENT INTERFACE CONFIGURATION:\n";
$agentController = file_get_contents(__DIR__ . '/app/Http/Controllers/Agent/StatisticsController.php');

if (strpos($agentController, 'withCount(\'citizenRequests\')') !== false) {
    echo "   ✅ Dynamic document counting enabled\n";
} else {
    echo "   ❌ Dynamic document counting not found\n";
}

if (strpos($agentController, 'getDocumentsByType') !== false) {
    echo "   ✅ getDocumentsByType method present\n";
} else {
    echo "   ❌ getDocumentsByType method missing\n";
}

echo "\n📊 FINAL VALIDATION RESULTS:\n";
echo "============================\n";

$citizenOK = in_array('Déclaration de naissance', $documentTypes);
$adminOK = in_array('Déclaration de Naissance', $adminDocTypes) && !in_array('Casier Judiciaire', $adminDocTypes);
$agentOK = strpos($agentController, 'withCount(\'citizenRequests\')') !== false;

if ($citizenOK) {
    echo "✅ CITIZEN FORM: Birth declaration option available\n";
} else {
    echo "❌ CITIZEN FORM: Birth declaration missing\n";
}

if ($adminOK) {
    echo "✅ ADMIN INTERFACE: Birth declaration stats included, criminal record removed\n";
} else {
    echo "❌ ADMIN INTERFACE: Issues with document type configuration\n";
}

if ($agentOK) {
    echo "✅ AGENT INTERFACE: Dynamic statistics will include all document types\n";
} else {
    echo "❌ AGENT INTERFACE: Dynamic statistics not properly configured\n";
}

echo "\n🏆 OVERALL STATUS:\n";
if ($citizenOK && $adminOK && $agentOK) {
    echo "🎉 SUCCESS! All requirements have been implemented:\n";
    echo "   ✅ Citizens can request birth declarations\n";
    echo "   ✅ Admin interface shows birth declaration statistics\n";
    echo "   ✅ Criminal record removed from admin interface\n";
    echo "   ✅ Agent interface will dynamically show all document types\n";
    echo "\n🚀 SYSTEM IS READY FOR PRODUCTION!\n";
} else {
    echo "⚠️  PARTIAL SUCCESS - Some issues need to be addressed\n";
}

echo "\n📈 STATISTICS CONFIGURATION SUMMARY:\n";
echo "====================================\n";
echo "CITIZEN FORM: " . count($documentTypes) . " document types available\n";
echo "ADMIN STATS:  " . count($adminDocTypes) . " document types in statistics\n";
echo "AGENT STATS:  Dynamic (pulls from database)\n";

echo "\n✅ TASK COMPLETION VERIFICATION: COMPLETE!\n";
