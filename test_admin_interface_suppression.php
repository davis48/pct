<?php

// Test de suppression de l'ancienne interface admin
echo "=== TEST DE SUPPRESSION DE L'ANCIENNE INTERFACE ADMIN ===\n";
echo "Date du test: " . date('Y-m-d H:i:s') . "\n\n";

// Liste des fichiers qui ne devraient plus être accessibles via les routes
$old_routes = [
    'admin.users.index',
    'admin.users.create',
    'admin.users.edit',
    'admin.users.show',
    'admin.agents.index',
    'admin.agents.create',
    'admin.agents.edit',
    'admin.documents.index',
    'admin.documents.create',
    'admin.documents.edit',
    'admin.requests.show',
    'admin.statistics.index',
    'admin.statistics.document',
    'admin.statistics.agent'
];

// Fonction pour vérifier si une route existe
function route_exists($name) {
    try {
        route($name);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

// Vérification des anciennes routes
echo "== VÉRIFICATION DES ANCIENNES ROUTES ==\n";
$all_removed = true;

foreach ($old_routes as $route) {
    if (route_exists($route)) {
        echo "❌ La route '{$route}' existe encore et devrait être supprimée\n";
        $all_removed = false;
    } else {
        echo "✅ La route '{$route}' a été correctement supprimée\n";
    }
}

// Vérification des nouvelles routes
echo "\n== VÉRIFICATION DES NOUVELLES ROUTES ==\n";
$new_routes = [
    'admin.dashboard',
    'admin.statistics',
    'admin.system-info',
    'admin.maintenance',
    'admin.logs',
    'admin.performance',
    'admin.api.users',
    'admin.api.documents',
    'admin.api.requests',
    'admin.api.agents'
];

$all_new_exist = true;
foreach ($new_routes as $route) {
    if (route_exists($route)) {
        echo "✅ La route '{$route}' existe correctement\n";
    } else {
        echo "❌ La route '{$route}' n'existe pas mais devrait être disponible\n";
        $all_new_exist = false;
    }
}

// Conclusion
echo "\n== CONCLUSION ==\n";
if ($all_removed && $all_new_exist) {
    echo "✅ MIGRATION RÉUSSIE: L'ancienne interface admin a été complètement supprimée\n";
    echo "✅ Toutes les routes pointent maintenant vers la nouvelle interface\n";
} else {
    if (!$all_removed) {
        echo "❌ ERREUR: Certaines anciennes routes existent encore\n";
    }
    if (!$all_new_exist) {
        echo "❌ ERREUR: Certaines nouvelles routes sont manquantes\n";
    }
    echo "⚠️ La migration n'est pas complète\n";
}

echo "\n=== FIN DU TEST ===\n";
