<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
          body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e1f5fe 100%);
            color: #1f2937;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #1f2937;
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        .navbar-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
        }
        
        .navbar-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-link {
            text-decoration: none;
            color: #6b7280;
            font-weight: 500;
            transition: color 0.3s ease;
        }
          .nav-link:hover {
            color: #1976d2;
        }
        
        .main-content {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        .form-container {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: #d1fae5;
            border: 1px solid #34d399;
            color: #064e3b;
        }
        
        .alert-error {
            background: #fee2e2;
            border: 1px solid #f87171;
            color: #7f1d1d;
        }
        
        .alert-info {
            background: #dbeafe;
            border: 1px solid #60a5fa;
            color: #1e3a8a;
        }
        
        .input-group {
            margin-bottom: 1.5rem;
        }
        
        .input-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .input-required {
            color: #ef4444;
        }
        
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            background: white;
        }
          .input-field:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }
        
        .input-field.error {
            border-color: #ef4444;
        }
        
        .error-text {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            flex: 1;
            justify-content: center;
        }
          .btn-primary {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1565c0, #0d47a1);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.4);
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-1px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
          .login-link a {
            color: #1976d2;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .pending-info {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .pending-info h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .container {
                max-width: 100%;
                padding: 0 1rem;
            }
            
            .form-container {
                padding: 1.5rem;
                border-radius: 12px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .page-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="navbar-icon">
                    <i class="fas fa-university"></i>
                </div>
                PCT UVCI
            </a>
            
            <div class="navbar-nav">
                <a href="{{ route('home') }}" class="nav-link">Accueil</a>
                <a href="{{ route('login') }}" class="nav-link">Connexion</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-user-plus"></i>
                    Créer un compte
                </h1>
                <p class="page-subtitle">
                    Inscrivez-vous pour accéder aux services en ligne de PCT UVCI
                </p>
            </div>

            <!-- Affichage des informations sur la demande en attente -->
            @if(session('pending_form_submission'))
                <div class="pending-info">
                    <h4><i class="fas fa-clock"></i> Demande en attente</h4>
                    <p>Vous avez une demande de {{ session('pending_form_submission.form_type') }} en attente. Après inscription, votre demande sera automatiquement traitée.</p>
                </div>
            @endif

            <!-- Messages d'alerte -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                </div>
            @endif

            <!-- Affichage des erreurs de validation -->
            @if($errors->any())
                <div class="alert alert-error">
                    <h4><i class="fas fa-exclamation-triangle"></i> Erreurs de validation</h4>
                    <ul style="margin: 0.5rem 0 0 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-container">
                <form action="{{ route('register.standalone') }}" method="POST">
                    @csrf
                      <div class="input-group">
                        <label class="input-label" for="nom">
                            Nom de famille <span class="input-required">*</span>
                        </label>
                        <input type="text" name="nom" id="nom" 
                               class="input-field @error('nom') error @enderror" 
                               value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="prenoms">
                            Prénoms <span class="input-required">*</span>
                        </label>
                        <input type="text" name="prenoms" id="prenoms" 
                               class="input-field @error('prenoms') error @enderror" 
                               value="{{ old('prenoms') }}" required>
                        @error('prenoms')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="email">
                            Adresse e-mail <span class="input-required">*</span>
                        </label>
                        <input type="email" name="email" id="email" 
                               class="input-field @error('email') error @enderror" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="phone">
                            Numéro de téléphone <span class="input-required">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" 
                               class="input-field @error('phone') error @enderror" 
                               value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="date_naissance">
                            Date de naissance
                        </label>
                        <input type="date" name="date_naissance" id="date_naissance" 
                               class="input-field @error('date_naissance') error @enderror" 
                               value="{{ old('date_naissance') }}">
                        @error('date_naissance')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="place_of_birth">
                            Lieu de naissance
                        </label>
                        <input type="text" name="place_of_birth" id="place_of_birth" 
                               class="input-field @error('place_of_birth') error @enderror" 
                               value="{{ old('place_of_birth') }}" 
                               placeholder="Ex: Abidjan, Côte d'Ivoire">
                        @error('place_of_birth')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="genre">
                            Sexe
                        </label>
                        <select name="genre" id="genre" class="input-field @error('genre') error @enderror">
                            <option value="">Sélectionner</option>
                            <option value="M" {{ old('genre') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('genre') == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('genre')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="nationality">
                            Nationalité
                        </label>
                        <input type="text" name="nationality" id="nationality" 
                               class="input-field @error('nationality') error @enderror" 
                               value="{{ old('nationality', 'Ivoirienne') }}">
                        @error('nationality')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="address">
                            Adresse
                        </label>
                        <textarea name="address" id="address" rows="3"
                               class="input-field @error('address') error @enderror" 
                               placeholder="Adresse complète">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="profession">
                            Profession
                        </label>
                        <input type="text" name="profession" id="profession" 
                               class="input-field @error('profession') error @enderror" 
                               value="{{ old('profession') }}" 
                               placeholder="Ex: Étudiant, Commerçant, Fonctionnaire">
                        @error('profession')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password">
                            Mot de passe <span class="input-required">*</span>
                        </label>
                        <input type="password" name="password" id="password" 
                               class="input-field @error('password') error @enderror" required>
                        @error('password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password_confirmation">
                            Confirmer le mot de passe <span class="input-required">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="input-field" required>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('interactive-forms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Retour
                        </a>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i>
                            Créer mon compte
                        </button>
                    </div>
                </form>

                <div class="login-link">
                    <p>Vous avez déjà un compte ? <a href="{{ route('login.standalone') }}">Se connecter</a></p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
