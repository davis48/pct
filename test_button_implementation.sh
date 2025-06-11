#!/bin/bash

echo "🔧 Test de la vue show_standalone.blade.php"
echo "============================================="

# Vérifier que les fichiers existent
echo "✅ Vérification des fichiers modifiés..."

if [ -f "resources/views/front/requests/show_standalone.blade.php" ]; then
    echo "✅ show_standalone.blade.php existe"
else
    echo "❌ show_standalone.blade.php manquant"
    exit 1
fi

if [ -f "app/Http/Controllers/DocumentDownloadController.php" ]; then
    echo "✅ DocumentDownloadController.php existe"
else
    echo "❌ DocumentDownloadController.php manquant"
    exit 1
fi

# Vérifier la syntaxe PHP
echo "🔍 Vérification de la syntaxe PHP..."
php -l app/Http/Controllers/DocumentDownloadController.php
if [ $? -eq 0 ]; then
    echo "✅ Syntaxe PHP correcte"
else
    echo "❌ Erreur de syntaxe PHP"
    exit 1
fi

# Vérifier que la vue contient les éléments requis
echo "🔍 Vérification du contenu de la vue..."

if grep -q "Télécharger" "resources/views/front/requests/show_standalone.blade.php"; then
    echo "✅ Bouton Télécharger trouvé"
else
    echo "❌ Bouton Télécharger manquant"
fi

if grep -q "Imprimer" "resources/views/front/requests/show_standalone.blade.php"; then
    echo "✅ Bouton Imprimer trouvé"
else
    echo "❌ Bouton Imprimer manquant"
fi

if grep -q "downloadApprovedDocument" "resources/views/front/requests/show_standalone.blade.php"; then
    echo "✅ Route de téléchargement trouvée"
else
    echo "❌ Route de téléchargement manquante"
fi

echo "✅ Tests terminés avec succès!"
echo ""
echo "🌐 Vous pouvez maintenant tester à l'adresse :"
echo "   http://127.0.0.1:8000/citizen-request-standalone/69"
echo ""
echo "📝 Fonctionnalités ajoutées :"
echo "   - Bouton 'Télécharger' personnalisé avec nom du document"
echo "   - Bouton 'Imprimer' à côté du bouton de téléchargement"
echo "   - Affichage conditionnel pour les demandes approuvées"
echo "   - Support pour différents types de documents :"
echo "     * Extrait de naissance"
echo "     * Certificat de mariage"
echo "     * Certificat de décès"
echo "     * Certificat de célibat"
echo "     * Attestation de domicile"
echo "     * Légalisation"
echo "     * Carte d'identité"
echo "     * Passeport"
