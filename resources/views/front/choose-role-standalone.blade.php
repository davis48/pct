<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir un rôle | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/navbar-blue-standalone.css') }}">
    
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
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
          .navbar-icon {
            width: 40px;
            height: 40px;
            background: #2563eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .navbar-nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        
        .nav-link {
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: #1976d2;
        }
        
        .main-content {
            min-height: calc(100vh - 80px);
            padding: 4rem 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-title {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1976d2, #1565c0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }
        
        .page-subtitle {
            color: #6b7280;
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .role-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
        }
        
        .role-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #1976d2;
        }
        
        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .role-card:hover::before {
            transform: scaleX(1);
        }
        
        .role-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            position: relative;
            transition: transform 0.3s ease;
        }
        
        .role-card:hover .role-icon {
            transform: scale(1.1);
        }
        
        .role-icon.agent {
            background: linear-gradient(135deg, #10b981, #047857);
        }
        
        .role-icon.citizen {
            background: linear-gradient(135deg, #1976d2, #1565c0);
        }
        
        .role-icon::after {
            content: '';
            position: absolute;
            top: -4px;
            right: -4px;
            width: 24px;
            height: 24px;
            background: #f59e0b;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; }
        }
        
        .role-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        
        .role-description {
            color: #6b7280;
            font-size: 1.125rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .role-button {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .role-button.agent {
            background: linear-gradient(135deg, #10b981, #047857);
        }
        
        .role-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }
        
        .role-button.agent:hover {
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }
        
        @media (max-width: 768px) {
            .role-grid {
                grid-template-columns: 1fr;
                max-width: 500px;
            }
            
            .role-card {
                padding: 2rem;
            }
            
            .page-title {
                font-size: 2.5rem;
            }
            
            .role-title {
                font-size: 1.75rem;
            }
            
            .container {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="navbar-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                PCT UVCI
            </a>
            
            <div class="navbar-nav">
                <a href="{{ route('home') }}" class="nav-link">Accueil</a>
                <a href="{{ route('register.standalone') }}" class="nav-link">Inscription</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Choisissez votre type de compte</h1>
                <p class="page-subtitle">
                    Sélectionnez votre profil pour accéder à votre espace personnalisé
                </p>
            </div>
            
            <div class="role-grid">
                <!-- Espace Agent -->
                <a href="{{ route('login.standalone', ['role' => 'agent']) }}" class="role-card">
                    <div class="role-icon agent">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="role-title">Je suis un Agent</h3>
                    <p class="role-description">
                        Accédez à votre espace agent pour gérer les demandes citoyennes et traiter les dossiers administratifs
                    </p>
                    <div class="role-button agent">
                        <i class="fas fa-sign-in-alt"></i>
                        Connexion Agent
                    </div>
                </a>

                <!-- Espace Citoyen -->
                <a href="{{ route('login.standalone', ['role' => 'citizen']) }}" class="role-card">
                    <div class="role-icon citizen">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="role-title">Je suis un Citoyen</h3>
                    <p class="role-description">
                        Accédez à votre espace citoyen pour créer vos demandes administratives et suivre leur progression
                    </p>
                    <div class="role-button">
                        <i class="fas fa-sign-in-alt"></i>
                        Connexion Citoyen
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>


