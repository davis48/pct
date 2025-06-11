#!/bin/bash

# Script de test pour vérifier l'upload sur tous les formulaires

echo "🔍 Test des champs d'upload sur tous les formulaires"
echo "=================================================="

FORMULAIRES=(
    "attestation-domicile"
    "certificat-celibat"
    "certificat-mariage"
    "certificat-deces"
    "extrait-naissance"
    "legalisation"
)

for FORM in "${FORMULAIRES[@]}"; do
    echo ""
    echo "📋 Vérification de : $FORM"
    echo "----------------------------"
    
    FILE="resources/views/front/interactive-forms/${FORM}_standalone.blade.php"
    
    if [ -f "$FILE" ]; then
        echo "✅ Fichier trouvé"
        
        # Vérifier la présence des éléments requis
        if grep -q 'id="documents"' "$FILE"; then
            echo "✅ Input file présent"
        else
            echo "❌ Input file manquant"
        fi
        
        if grep -q 'handleFileSelect' "$FILE"; then
            echo "✅ handleFileSelect présent"
        else
            echo "❌ handleFileSelect manquant"
        fi
        
        if grep -q 'class="upload-area"' "$FILE"; then
            echo "✅ Upload area présente"
        else
            echo "❌ Upload area manquante"
        fi
        
        if grep -q 'onclick="document.getElementById' "$FILE"; then
            echo "✅ Event onclick présent"
        else
            echo "❌ Event onclick manquant"
        fi
        
        if grep -q 'function showError' "$FILE"; then
            echo "✅ Function showError présente"
        else
            echo "❌ Function showError manquante"
        fi
        
        if grep -q 'function processFiles' "$FILE"; then
            echo "✅ Function processFiles présente"
        else
            echo "❌ Function processFiles manquante"
        fi
        
    else
        echo "❌ Fichier non trouvé"
    fi
done

echo ""
echo "💡 RECOMMANDATIONS :"
echo "==================="
echo "1. Si des éléments sont manquants, copiez-les depuis extrait-naissance"
echo "2. Vérifiez que les IDs ne sont pas dupliqués"
echo "3. Testez chaque formulaire individuellement"
echo "4. Utilisez la console du navigateur pour identifier les erreurs JS"
