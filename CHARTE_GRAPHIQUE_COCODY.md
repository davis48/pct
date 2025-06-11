# 🎨 Charte Graphique - Mairie de Cocody

## Palette de Couleurs Officielle

Cette charte graphique a été créée en s'inspirant du site officiel de la Mairie de Cocody : [mairiecocody.com](https://mairiecocody.com)

### 🔵 Couleurs Principales (Bleu Cocody)

- **Bleu Principal** : `#1976d2` 
- **Bleu Foncé** : `#1565c0`
- **Bleu Très Foncé** : `#0d47a1`
- **Bleu Clair** : `#42a5f5`
- **Bleu Très Clair** : `#bbdefb`

### 🟢 Couleurs Secondaires (Vert Cocody)

- **Vert Principal** : `#43a047`
- **Vert Foncé** : `#388e3c`
- **Vert Très Foncé** : `#2e7d32`
- **Vert Clair** : `#66bb6a`
- **Vert Très Clair** : `#c8e6c9`

### ⚪ Couleurs Neutres

- **Gris 50** : `#f8fafc`
- **Gris 100** : `#f1f5f9`
- **Gris 200** : `#e2e8f0`
- **Gris 300** : `#cbd5e1`
- **Gris 400** : `#94a3b8`
- **Gris 500** : `#64748b`
- **Gris 600** : `#475569`
- **Gris 700** : `#334155`
- **Gris 800** : `#1e293b`
- **Gris 900** : `#0f172a`

## 📐 Gradients Officiels

### Background Principal
```css
background: linear-gradient(135deg, #f8fafc 0%, #e1f5fe 100%);
```

### Bouton Principal
```css
background: linear-gradient(135deg, #1976d2, #1565c0);
```

### Bouton Secondaire
```css
background: linear-gradient(135deg, #43a047, #388e3c);
```

### Carte d'Information
```css
background: linear-gradient(135deg, #e3f2fd 0%, #e8f5e8 100%);
```

### Barre de Progression
```css
background: linear-gradient(90deg, #1976d2, #43a047);
```

## 🎯 Usage Recommandé

### Interfaces Citoyens (Standalone)
- **Arrière-plan** : Gradient bleu clair
- **Boutons principaux** : Bleu Cocody
- **Boutons secondaires** : Vert Cocody
- **Liens** : Bleu principal
- **Cartes importantes** : Gradient bleu-vert

### Interface Administrateur
- **Couleurs plus sobres** pour usage professionnel
- **Bleu foncé** pour les éléments de navigation
- **Vert** pour les actions de validation

## 🚀 Classes CSS Utilitaires

Le fichier `public/css/cocody-theme.css` contient des classes prêtes à utiliser :

- `.btn-cocody-primary` - Bouton principal
- `.btn-cocody-secondary` - Bouton secondaire  
- `.link-cocody-primary` - Lien stylisé
- `.card-cocody` - Carte standard
- `.card-cocody-info` - Carte d'information
- `.body-cocody` - Style de body
- `.navbar-cocody` - Barre de navigation

## 📝 Fichiers Modifiés

Les interfaces suivantes utilisent maintenant la palette officielle :

✅ **Pages de Connexion/Inscription**
- `login_standalone.blade.php`
- `register_standalone.blade.php`
- `forgot-password.blade.php`
- `choose-role-standalone.blade.php`

✅ **Formulaires Interactifs**
- `attestation-domicile_standalone.blade.php`
- `certificat-celibat_standalone.blade.php`
- `certificat-deces_standalone.blade.php`
- `certificat-mariage_standalone.blade.php`
- `extrait-naissance_standalone.blade.php`
- `legalisation_standalone.blade.php`
- `index_standalone.blade.php`

✅ **Gestion des Demandes**
- `requests/create_standalone.blade.php`
- `requests/index_standalone.blade.php`
- `requests/show_standalone.blade.php`
- `request-detail_standalone.blade.php`

✅ **Paiements**
- `payments/process_standalone.blade.php`
- `payments/show_standalone.blade.php`

## 🔧 Maintenance

Pour appliquer la charte à de nouvelles pages, utilisez :
```powershell
.\update-cocody-colors.ps1
```

## 📐 Inspiration Design

Cette charte respecte l'identité visuelle de la Mairie de Cocody en :
- **Conservant le professionnalisme** requis pour une administration
- **Utilisant les couleurs officielles** du site web
- **Évitant les couleurs trop flashy** (violet/rose)
- **Privilégiant la lisibilité** et l'accessibilité

---

*Dernière mise à jour : 10 juin 2025*
*Basé sur le site officiel : https://mairiecocody.com*
