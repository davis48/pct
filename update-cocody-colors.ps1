# Script PowerShell pour uniformiser les couleurs des interfaces standalone
# Utilise la palette de couleurs officielle de la Mairie de Cocody

$standaloneFiles = @(
    "resources\views\citizen\request-detail_standalone.blade.php",
    "resources\views\front\choose-role-standalone.blade.php",
    "resources\views\front\interactive-forms\attestation-domicile_standalone.blade.php",
    "resources\views\front\interactive-forms\certificat-celibat_standalone.blade.php",
    "resources\views\front\interactive-forms\certificat-deces_standalone.blade.php",
    "resources\views\front\interactive-forms\certificat-mariage_standalone.blade.php",
    "resources\views\front\interactive-forms\extrait-naissance_standalone.blade.php",
    "resources\views\front\interactive-forms\index_standalone.blade.php",
    "resources\views\front\interactive-forms\legalisation_standalone.blade.php",
    "resources\views\front\payments\process_standalone.blade.php",
    "resources\views\front\payments\show_standalone.blade.php",
    "resources\views\front\requests\create_standalone.blade.php",
    "resources\views\front\requests\index_standalone.blade.php",
    "resources\views\front\requests\show_standalone.blade.php"
)

# Dictionnaire des remplacements de couleurs
$colorReplacements = @{
    # Backgrounds violets/pourpres vers bleu Cocody
    "linear-gradient\(135deg, #f8fafc 0%, #e0e7ff 100%\)" = "linear-gradient(135deg, #f8fafc 0%, #e1f5fe 100%)"
    "linear-gradient\(135deg, #667eea 0%, #764ba2 100%\)" = "linear-gradient(135deg, #f8fafc 0%, #e1f5fe 100%)"
    
    # Boutons primaires
    "linear-gradient\(135deg, #3b82f6, #1d4ed8\)" = "linear-gradient(135deg, #1976d2, #1565c0)"
    "linear-gradient\(135deg, #2563eb, #1e40af\)" = "linear-gradient(135deg, #1565c0, #0d47a1)"
    "rgba\(59, 130, 246, 0\.4\)" = "rgba(25, 118, 210, 0.4)"
    "rgba\(59, 130, 246, 0\.1\)" = "rgba(25, 118, 210, 0.1)"
    
    # Couleurs de texte
    "color: #3b82f6" = "color: #1976d2"
    "border-color: #3b82f6" = "border-color: #1976d2"
    "color: #1e40af" = "color: #0d47a1"
    
    # Couleurs violettes/pourpres
    "linear-gradient\(90deg, #3b82f6, #6366f1, #8b5cf6\)" = "linear-gradient(90deg, #1976d2, #43a047)"
    "linear-gradient\(135deg, #eff6ff 0%, #f0f9ff 100%\)" = "linear-gradient(135deg, #e3f2fd 0%, #e8f5e8 100%)"
    "border: 1px solid #dbeafe" = "border: 1px solid #bbdefb"
    "background: #3b82f6" = "background: #1976d2"
    
    # Shadows violettes
    "rgba\(124, 58, 237, 0\.1\)" = "rgba(25, 118, 210, 0.1)"
    
    # Boutons secondaires vers vert Cocody
    "linear-gradient\(135deg, #6b7280 0%, #4b5563 100%\)" = "linear-gradient(135deg, #43a047 0%, #388e3c 100%)"
}

Write-Host "🎨 Début de l'uniformisation des couleurs Mairie de Cocody..." -ForegroundColor Green

foreach ($file in $standaloneFiles) {
    $fullPath = Join-Path $PWD $file
    
    if (Test-Path $fullPath) {
        Write-Host "📝 Traitement de : $file" -ForegroundColor Yellow
        
        $content = Get-Content $fullPath -Raw
        $originalContent = $content
        
        foreach ($oldColor in $colorReplacements.Keys) {
            $newColor = $colorReplacements[$oldColor]
            $content = $content -replace $oldColor, $newColor
        }
        
        if ($content -ne $originalContent) {
            Set-Content $fullPath $content -NoNewline
            Write-Host "✅ Couleurs mises à jour dans : $file" -ForegroundColor Green
        } else {
            Write-Host "ℹ️  Aucun changement nécessaire dans : $file" -ForegroundColor Cyan
        }
    } else {
        Write-Host "❌ Fichier non trouvé : $file" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "🎨 Uniformisation terminée !" -ForegroundColor Green
Write-Host "Les couleurs suivantes ont été appliquées :" -ForegroundColor White
Write-Host "  • Bleu principal Cocody : #1976d2" -ForegroundColor Blue
Write-Host "  • Vert secondaire Cocody : #43a047" -ForegroundColor Green
Write-Host "  • Background : dégradé bleu clair" -ForegroundColor Cyan
Write-Host ""
Write-Host "🌐 Palette inspirée du site officiel : https://mairiecocody.com" -ForegroundColor Magenta
