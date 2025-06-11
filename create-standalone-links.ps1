# Script PowerShell pour créer des liens symboliques des vues standalone vers les noms normaux
# Cela permet de maintenir la compatibilité avec les routes existantes

$mappings = @{
    "resources\views\front\login_standalone.blade.php" = "resources\views\front\login.blade.php"
    "resources\views\front\register_standalone.blade.php" = "resources\views\front\register.blade.php"
    "resources\views\front\choose-role-standalone.blade.php" = "resources\views\front\choose-role.blade.php"
    "resources\views\front\interactive-forms\attestation-domicile_standalone.blade.php" = "resources\views\front\interactive-forms\attestation-domicile.blade.php"
    "resources\views\front\interactive-forms\certificat-celibat_standalone.blade.php" = "resources\views\front\interactive-forms\certificat-celibat.blade.php"
    "resources\views\front\interactive-forms\certificat-deces_standalone.blade.php" = "resources\views\front\interactive-forms\certificat-deces.blade.php"
    "resources\views\front\interactive-forms\certificat-mariage_standalone.blade.php" = "resources\views\front\interactive-forms\certificat-mariage.blade.php"
    "resources\views\front\interactive-forms\extrait-naissance_standalone.blade.php" = "resources\views\front\interactive-forms\extrait-naissance.blade.php"
    "resources\views\front\interactive-forms\legalisation_standalone.blade.php" = "resources\views\front\interactive-forms\legalisation.blade.php"
    "resources\views\front\interactive-forms\index_standalone.blade.php" = "resources\views\front\interactive-forms\index.blade.php"
    "resources\views\front\requests\create_standalone.blade.php" = "resources\views\front\requests\create.blade.php"
    "resources\views\front\requests\index_standalone.blade.php" = "resources\views\front\requests\index.blade.php"
    "resources\views\front\requests\show_standalone.blade.php" = "resources\views\front\requests\show.blade.php"
    "resources\views\front\payments\process_standalone.blade.php" = "resources\views\front\payments\process.blade.php"
    "resources\views\front\payments\show_standalone.blade.php" = "resources\views\front\payments\show.blade.php"
    "resources\views\citizen\request-detail_standalone.blade.php" = "resources\views\citizen\request-detail.blade.php"
}

Write-Host "🔗 Création des liens symboliques pour les vues standalone..." -ForegroundColor Green

foreach ($source in $mappings.Keys) {
    $target = $mappings[$source]
    $fullSource = Join-Path $PWD $source
    $fullTarget = Join-Path $PWD $target
    
    if (Test-Path $fullSource) {
        try {
            # Créer un lien symbolique
            New-Item -ItemType SymbolicLink -Path $fullTarget -Target $fullSource -Force
            Write-Host "✅ Lien créé : $target -> $source" -ForegroundColor Green
        } catch {
            # Si les liens symboliques ne fonctionnent pas, copier le fichier
            Copy-Item $fullSource $fullTarget -Force
            Write-Host "📋 Copie créée : $target <- $source" -ForegroundColor Yellow
        }
    } else {
        Write-Host "❌ Source non trouvée : $source" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "🎯 Migration terminée !" -ForegroundColor Green
Write-Host "Les interfaces standalone sont maintenant les interfaces principales." -ForegroundColor White
Write-Host "Les routes existantes continueront de fonctionner." -ForegroundColor Cyan
