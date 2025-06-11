// Script de débogage pour les champs d'upload dans les formulaires

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 DÉBOGAGE CHAMPS UPLOAD');
    console.log('========================');
    
    // Vérifier les éléments requis
    const uploadArea = document.querySelector('.upload-area');
    const fileInput = document.getElementById('documents');
    const fileList = document.getElementById('fileList');
    const fileCounter = document.getElementById('fileCounter');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    
    console.log('Éléments trouvés :');
    console.log('- Upload area:', uploadArea ? '✅' : '❌');
    console.log('- File input:', fileInput ? '✅' : '❌');
    console.log('- File list:', fileList ? '✅' : '❌');
    console.log('- File counter:', fileCounter ? '✅' : '❌');
    console.log('- Error message:', errorMessage ? '✅' : '❌');
    console.log('- Success message:', successMessage ? '✅' : '❌');
    
    // Vérifier les event handlers
    if (uploadArea) {
        console.log('- Upload area onclick:', uploadArea.onclick ? '✅' : '❌');
        console.log('- Upload area click event:', uploadArea.addEventListener ? '✅' : '❌');
    }
    
    if (fileInput) {
        console.log('- File input onchange:', fileInput.onchange ? '✅' : '❌');
        console.log('- handleFileSelect function:', window.handleFileSelect ? '✅' : '❌');
    }
    
    // Test manuel du clic
    if (uploadArea) {
        uploadArea.addEventListener('click', function() {
            console.log('🖱️ Upload area cliquée');
            if (fileInput) {
                console.log('📁 Tentative d\'ouverture du sélecteur de fichiers');
                fileInput.click();
            } else {
                console.log('❌ Aucun input file trouvé');
            }
        });
    }
    
    // Test manuel du changement de fichier
    if (fileInput) {
        fileInput.addEventListener('change', function(event) {
            console.log('📎 Fichiers sélectionnés:', event.target.files.length);
            if (window.handleFileSelect) {
                console.log('🔧 Appel de handleFileSelect');
                window.handleFileSelect(event);
            } else {
                console.log('❌ handleFileSelect non trouvée');
            }
        });
    }
});

// Fonction de test manuelle
window.testUpload = function() {
    const fileInput = document.getElementById('documents');
    if (fileInput) {
        fileInput.click();
        console.log('📁 Test manuel - sélecteur ouvert');
    } else {
        console.log('❌ Test manuel - aucun input trouvé');
    }
};
