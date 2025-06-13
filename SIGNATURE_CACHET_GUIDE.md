# Signature et Cachet Officiel pour les Documents

## Vue d'ensemble

Le système a été modifié pour inclure automatiquement la signature et le cachet du maire sur tous les documents PDF générés pour les citoyens. Cette fonctionnalité garantit l'authenticité et la validité officielle des documents délivrés.

## Configuration

### 1. Variables d'environnement

Ajoutez ces variables dans votre fichier `.env` :

```env
# Nom du maire
MAYOR_NAME="M. Jean KOUASSI"

# Nom de la municipalité
MUNICIPALITY_NAME="Mairie de Cocody"
```

### 2. Images officielles

Placez les images suivantes dans le répertoire `public/images/official/` :

- **signature_maire.png** : Signature du maire (200x100px recommandé)
- **cachet_officiel.png** : Cachet officiel de la mairie (150x150px recommandé)

**Spécifications recommandées :**
- Format : PNG avec fond transparent
- Résolution : 300 DPI minimum
- Couleur : Bleu officiel (#2c5aa0) ou noir

## Fonctionnalités implémentées

### 1. Service DocumentPdfGeneratorService

Le service a été modifié pour :
- Détecter automatiquement la présence des images de signature et cachet
- Inclure les données officielles dans tous les documents générés
- Gérer l'affichage conditionnel (affiche les images si disponibles, sinon affiche un texte)

### 2. Templates mis à jour

Tous les templates de documents ont été modifiés :
- **extrait-naissance.blade.php** ✓
- **certificat-mariage.blade.php** ✓
- **certificat-celibat.blade.php** ✓
- **attestation-domicile.blade.php** ✓
- **certificat-deces.blade.php** (à mettre à jour)
- **declaration-naissance.blade.php** (à mettre à jour)
- **legalisation.blade.php** (à mettre à jour)

### 3. Affichage conditionnel

Le système fonctionne en mode "graceful degradation" :
- Si les images sont présentes → affichage de la signature et du cachet
- Si les images sont absentes → affichage du texte "Cachet et signature"

## Installation et mise en place

### 1. Exécuter le script de configuration

#### Sur Windows (PowerShell)
```powershell
.\setup-official-images.ps1
```

#### Sur Linux/Mac
```bash
chmod +x convert-images.sh
./convert-images.sh
```

### 2. Ajouter les vraies images

1. Demandez au maire de fournir :
   - Sa signature scannée en haute résolution
   - Le cachet officiel de la mairie

2. Traitez les images :
   - Utilisez un logiciel comme GIMP ou Photoshop
   - Supprimez le fond pour le rendre transparent
   - Redimensionnez selon les spécifications
   - Enregistrez au format PNG

3. Placez les fichiers dans `public/images/official/` :
   - `signature_maire.png`
   - `cachet_officiel.png`

### 3. Tester le système

1. Lancez le serveur : `php artisan serve`
2. Créez une demande de citoyen
3. Générez un document PDF
4. Vérifiez la présence de la signature et du cachet

## Sécurité et bonnes pratiques

### 1. Protection des images

- Les images de signatures sont sensibles
- Considérez l'ajout de restrictions d'accès au répertoire `public/images/official/`
- Sauvegardez les images originales en lieu sûr

### 2. Contrôle de version

- N'incluez PAS les vraies signatures dans Git
- Ajoutez `public/images/official/*.png` au `.gitignore`
- Documentez le processus de déploiement pour les images

### 3. Qualité des images

- Utilisez toujours des images haute résolution
- Vérifiez l'apparence sur différents types de documents
- Testez l'impression physique des documents

## Problèmes courants et solutions

### 1. Images non affichées

**Problème :** Les images n'apparaissent pas dans le PDF
**Solutions :**
- Vérifiez que les fichiers existent dans le bon répertoire
- Assurez-vous que les permissions de fichier sont correctes
- Vérifiez que DomPDF peut accéder aux images

### 2. Qualité d'image dégradée

**Problème :** Les signatures apparaissent floues ou pixelisées
**Solutions :**
- Utilisez des images haute résolution (300 DPI minimum)
- Vérifiez le format PNG avec fond transparent
- Redimensionnez les images aux bonnes dimensions

### 3. Mise en page déformée

**Problème :** La signature/cachet déplace le contenu
**Solutions :**
- Ajustez les dimensions des images
- Modifiez les styles CSS dans les templates
- Testez sur différents types de documents

## Maintenance

### 1. Changement de maire

1. Remplacez `signature_maire.png` par la nouvelle signature
2. Mettez à jour `MAYOR_NAME` dans `.env`
3. Testez tous les types de documents

### 2. Mise à jour du cachet

1. Remplacez `cachet_officiel.png`
2. Vérifiez la cohérence sur tous les documents
3. Mettez à jour les exemples SVG si nécessaire

### 3. Ajout de nouveaux templates

Pour chaque nouveau template :
1. Ajoutez l'appel à `$this->getOfficialDocumentData()` dans le service
2. Incluez la section signature dans le template Blade
3. Testez l'affichage et l'impression

## Support technique

En cas de problème, vérifiez :
1. Les logs Laravel (`storage/logs/laravel.log`)
2. Les permissions des fichiers images
3. La configuration DomPDF
4. Les variables d'environnement
