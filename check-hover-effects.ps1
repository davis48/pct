# Script de vérification des effets hover dans les pages standalone
# Vérifie que tous les fichiers standalone incluent le CSS d'effets hover

Write-Host "=== Vérification des effets hover dans les pages standalone ===" -ForegroundColor Green

$standaloneFiles = @(
    "resources\views\front\login_standalone.blade.php",
    "resources\views\front\register_standalone.blade.php", 
    "resources\views\citizen\request-detail_standalone.blade.php",
    "resources\views\front\interactive-forms\certificat-mariage_standalone.blade.php",
    "resources\views\front\interactive-forms\certificat-deces_standalone.blade.php",
    "resources\views\front\requests\show_standalone.blade.php",
    "resources\views\front\interactive-forms\certificat-celibat_standalone.blade.php",
    "resources\views\front\profile\edit_standalone.blade.php",
    "resources\views\front\interactive-forms\attestation-domicile_standalone.blade.php",
    "resources\views\front\requests\index_standalone.blade.php",
    "resources\views\front\interactive-forms\extrait-naissance_standalone.blade.php",
    "resources\views\front\interactive-forms\index_standalone.blade.php",
    "resources\views\front\payments\show_standalone.blade.php",
    "resources\views\front\requests\create_standalone.blade.php",
    "resources\views\front\payments\process_standalone.blade.php",
    "resources\views\front\interactive-forms\legalisation_standalone.blade.php"
)

Write-Host "`nFichiers avec CSS d'effets hover :" -ForegroundColor Cyan
$withHover = @()

Write-Host "`nFichiers sans CSS d'effets hover :" -ForegroundColor Yellow
$withoutHover = @()

foreach ($file in $standaloneFiles) {
    $fullPath = "f:\pct_uvci-master\$file"
    if (Test-Path $fullPath) {
        $content = Get-Content $fullPath -Raw
        if ($content -match "standalone-hover-effects\.css") {
            Write-Host "✅ $file" -ForegroundColor Green
            $withHover += $file
        } else {
            Write-Host "❌ $file" -ForegroundColor Red
            $withoutHover += $file
        }
    } else {
        Write-Host "⚠️  $file (fichier introuvable)" -ForegroundColor Magenta
    }
}

Write-Host "`n=== Résumé ===" -ForegroundColor Green
Write-Host "Fichiers avec effets hover: $($withHover.Count)" -ForegroundColor Green
Write-Host "Fichiers sans effets hover: $($withoutHover.Count)" -ForegroundColor Yellow

if ($withoutHover.Count -eq 0) {
    Write-Host "`n🎉 Tous les fichiers standalone incluent le CSS d'effets hover !" -ForegroundColor Green
} else {
    Write-Host "`n⚠️  Il reste $($withoutHover.Count) fichier(s) sans effets hover." -ForegroundColor Yellow
}

Write-Host "`n=== CSS créés ===" -ForegroundColor Blue
$cssFiles = @(
    "public\css\standalone-hover-effects.css",
    "public\css\navbar-accessibility-fix.css", 
    "public\css\navbar-blue-standalone.css"
)

foreach ($css in $cssFiles) {
    $cssPath = "f:\pct_uvci-master\$css"
    if (Test-Path $cssPath) {
        Write-Host "✅ $css" -ForegroundColor Green
    } else {
        Write-Host "❌ $css" -ForegroundColor Red
    }
}

Write-Host "`nVérification terminée !" -ForegroundColor Green
