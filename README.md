# PCT UVCI - Plateforme de Demande de Documents Officiels

## 📋 Description

PCT UVCI est une plateforme web complète développée avec Laravel qui permet aux citoyens de faire des demandes de documents officiels en ligne. Le système facilite la gestion administrative en proposant une interface moderne pour les citoyens, les agents municipaux et les administrateurs.

### 🎯 Objectifs principaux

- Digitaliser les procédures administratives
- Réduire les délais de traitement des demandes
- Améliorer l'expérience utilisateur
- Centraliser la gestion des documents officiels

## ✨ Fonctionnalités

### 👤 Pour les Citoyens

- **Création de compte 
- **Formulaires interactifs** pour différents types de documents :
  - Extrait d'acte de naissance
  - Certificat de mariage
  - Certificat de célibat
  - Certificat de décès
  - Légalisation de documents
  - Attestations diverses
- **Système de paiement intégré** (Mobile Money : Orange Money, MTN Money, Moov Money, Wave)
- **Suivi en temps réel** des demandes avec notifications
- **Téléchargement sécurisé** des documents approuvés
- **Tableau de bord personnalisé** avec statistiques
- **Centre de notifications** avec préférences personnalisables

### 👨‍💼 Pour les Agents Municipaux

- **Interface de gestion** des demandes citoyennes
- **Système d'assignation** des demandes
- **Workflow de traitement** (En attente → En cours → Approuvé/Rejeté)
- **Gestion des documents** avec prévisualisation
- **Système de notifications** pour les nouvelles assignations
- **Rapports et exports** (CSV, Excel, PDF)
- **Suivi des performances** et statistiques

### 🔧 Pour les Administrateurs

- **Dashboard administratif** complet
- **Gestion des utilisateurs** (citoyens et agents)
- **Configuration des types de documents**
- **Gestion des tarifs** et frais
- **Monitoring du système** et logs
- **Statistiques globales** et rapports

## 🛠 Technologies Utilisées

### Backend

- **Laravel 11.31** - Framework PHP
- **PHP 8.2+** - Langage de programmation
- **SQLite** - Base de données (par défaut)
- **Composer** - Gestionnaire de dépendances PHP

### Frontend

- **Bootstrap 5.2.3** - Framework CSS
- **Tailwind CSS 3.4.13** - Framework CSS utilitaire
- **JavaScript/Ajax** - Interactivité
- **Sass** - Préprocesseur CSS
- **Vite** - Build tool et serveur de développement

### Outils de développement

- **Laravel Tinker** - REPL pour Laravel
- **Laravel Pail** - Visualisation des logs
- **DomPDF** - Génération de documents PDF
- **PHPUnit** - Tests unitaires

## 📋 Prérequis

Avant d'installer le projet, assurez-vous d'avoir :

- **PHP 8.2 ou supérieur**
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js 16+** et **npm** (pour les assets frontend)
- **Git** (pour cloner le repository)
- **Serveur web** (Apache/Nginx) ou utiliser le serveur intégré de Laravel

### Extensions PHP requises

- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD (pour la manipulation d'images)

## 🚀 Installation

### 1. Cloner le repository

```powershell
git clone https://github.com/votre-username/pct_uvci.git
cd pct_uvci
```

### 2. Installer les dépendances PHP

```powershell
composer install
```

### 3. Installer les dépendances JavaScript

```powershell
npm install
```

### 4. Configuration de l'environnement

```powershell
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 5. Configuration de la base de données

Le projet est configuré par défaut pour utiliser SQLite. La base de données sera créée automatiquement.

Si vous préférez utiliser MySQL, modifiez le fichier `.env` :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pct_uvci
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

### 6. Exécuter les migrations

```powershell
php artisan migrate
```

### 7. Créer les données de base (optionnel)

```powershell
php artisan db:seed
```

### 8. Compiler les assets

```powershell
# Pour le développement
npm run dev

# Pour la production
npm run build
```

### 9. Créer les liens symboliques pour le stockage

```powershell
php artisan storage:link
```

### 10. Démarrer le serveur de développement

```powershell
php artisan serve
```

L'application sera accessible à l'adresse : `http://localhost:8000`

## ⚙️ Configuration

### Variables d'environnement importantes

```env
# Configuration de base
APP_NAME="PCT UVCI"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=sqlite

# Configuration email (pour les notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_app_password
MAIL_ENCRYPTION=tls

# Configuration des paiements (à adapter selon vos APIs)
ORANGE_MONEY_API_KEY=your_api_key
MTN_MONEY_API_KEY=your_api_key
MOOV_MONEY_API_KEY=your_api_key
```

### Permissions des dossiers

Assurez-vous que les dossiers suivants sont accessibles en écriture :

```powershell
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 👥 Comptes par défaut

Après l'installation, vous pouvez créer des comptes de test :

### Administrateur

- Accès : `/admin/login`
- Vous devrez créer ce compte manuellement via la commande :

```powershell
php artisan make:admin
```

### Agent

- Accès : `/agent/login`
- Inscription via l'interface d'administration

### Citoyen

- Accès : `/connexion`
- Inscription libre via `/inscription`

## 🔄 Utilisation

### Workflow des demandes

1. **Inscription du citoyen** via l'interface web
2. **Création d'une demande** via les formulaires interactifs
3. **Paiement sécurisé** via Mobile Money
4. **Assignation automatique** à un agent
5. **Traitement par l'agent** (vérification des documents)
6. **Approbation/Rejet** avec notifications
7. **Téléchargement du document** par le citoyen

### Types de documents disponibles

- **Extrait d'acte de naissance** (500 FCFA)
- **Certificat de mariage** (1000 FCFA)
- **Certificat de célibat** (750 FCFA)
- **Certificat de décès** (500 FCFA)
- **Légalisation de documents** (300 FCFA)
- **Attestations diverses** (prix variables)

## 🧪 Tests

### Exécuter les tests

```powershell
# Tests unitaires
./vendor/bin/phpunit

# Tests avec couverture
./vendor/bin/phpunit --coverage-html coverage
```

### Tests manuels

Des pages de test sont disponibles en mode développement :

- `/test-notifications.html` - Test du système de notifications
- `/test-payment-button.php` - Test des boutons de paiement
- `/test-pdf-generation.html` - Test de génération PDF

## 📦 Structure du projet

```text
pct_uvci-master/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Contrôleurs administrateur
│   │   ├── Agent/          # Contrôleurs agents
│   │   ├── Citizen/        # Contrôleurs citoyens
│   │   └── Front/          # Contrôleurs frontend
│   ├── Models/             # Modèles Eloquent
│   ├── Services/           # Services métier
│   └── ...
├── database/
│   ├── migrations/         # Migrations de base de données
│   └── seeders/           # Données de test
├── resources/
│   ├── views/             # Templates Blade
│   ├── css/               # Styles CSS
│   └── js/                # Scripts JavaScript
├── routes/
│   ├── web.php            # Routes web principales
│   ├── admin.php          # Routes administration
│   └── agent.php          # Routes agents
└── public/                # Assets publics
```

## 🚀 Déploiement en production

### 1. Configuration du serveur

- Installer PHP 8.2+ avec les extensions requises
- Configurer Apache/Nginx
- Installer Composer

### 2. Optimisations

```powershell
# Cache des configurations
php artisan config:cache

# Cache des routes
php artisan route:cache

# Cache des vues
php artisan view:cache

# Optimisation Composer
composer install --optimize-autoloader --no-dev
```

### 3. Variables d'environnement production

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your_generated_key
```

### 4. Configuration du serveur web

Pointez le document root vers le dossier `public/`

### 5. HTTPS et sécurité

- Configurez un certificat SSL
- Activez les en-têtes de sécurité
- Configurez les sauvegardes automatiques

## 🛡️ Sécurité

- **Authentification sécurisée** avec Laravel Sanctum
- **Protection CSRF** sur tous les formulaires
- **Validation côté serveur** de toutes les données
- **Chiffrement des mots de passe** avec bcrypt
- **Protection contre les injections SQL** via Eloquent ORM
- **Limitations de taux** (rate limiting) sur les API
- **Stockage sécurisé** des fichiers uploadés

## 📞 Support et maintenance

### Logs et debugging

- Logs disponibles dans `storage/logs/`
- Mode debug activable via `APP_DEBUG=true`
- Monitoring des erreurs avec Laravel Telescope (optionnel)

### Sauvegarde

```powershell
# Sauvegarde de la base de données SQLite
cp database/database.sqlite database/backup_$(date +%Y%m%d_%H%M%S).sqlite

# Sauvegarde des fichiers uploadés
tar -czf storage_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/app/
```

### Mise à jour

```powershell
# Récupérer les dernières modifications
git pull origin main

# Mettre à jour les dépendances
composer update
npm update

# Exécuter les nouvelles migrations
php artisan migrate

# Reconstruire les caches
php artisan config:cache
php artisan route:cache
```

## 🤝 Contribution

1. Forkez le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Pushez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📧 Contact

Pour toute question ou support :

- Email : <support@pct-uvci.ci>
- Documentation : [Wiki du projet](https://github.com/votre-username/pct_uvci/wiki)
- Issues : [GitHub Issues](https://github.com/votre-username/pct_uvci/issues)

---

**PCT UVCI** - Simplifiant l'accès aux services municipaux pour tous les citoyens.
