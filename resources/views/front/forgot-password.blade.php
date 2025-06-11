<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié | PCT UVCI</title>
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
            min-height: 100vh;
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
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #1f2937;
        }
        
        .logo img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .main-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            padding: 2rem 1rem;
        }
        
        .forgot-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
        }
        
        .forgot-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #1976d2, #43a047);
        }
        
        .forgot-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .forgot-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }
        
        .forgot-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .forgot-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .info-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #e8f5e8 100%);
            border: 1px solid #bbdefb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .info-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #1976d2;
            border-radius: 2px 0 0 2px;
        }
        
        .info-icon {
            color: #1976d2;
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }
        
        .info-text {
            color: #0d47a1;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .contact-info {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        
        .contact-item:last-child {
            margin-bottom: 0;
        }
        
        .contact-icon {
            width: 32px;
            height: 32px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
        }
        
        .contact-label {
            font-weight: 600;
            color: #374151;
            min-width: 80px;
        }
        
        .contact-value {
            color: #1f2937;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #43a047 0%, #388e3c 100%);
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
            justify-content: center;
        }
        
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 16px -4px rgba(0, 0, 0, 0.1);
            color: white;
            text-decoration: none;
        }
        
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape-1 {
            top: 10%;
            left: 10%;
            width: 100px;
            height: 100px;
            background: #3b82f6;
            border-radius: 50%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            top: 70%;
            right: 10%;
            width: 80px;
            height: 80px;
            background: #6366f1;
            border-radius: 20px;
            animation-delay: 2s;
        }
        
        .shape-3 {
            bottom: 20%;
            left: 20%;
            width: 60px;
            height: 60px;
            background: #8b5cf6;
            border-radius: 50%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        @media (max-width: 768px) {
            .forgot-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .navbar-content {
                padding: 0 1rem;
            }
            
            .forgot-title {
                font-size: 1.5rem;
            }
            
            .forgot-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo PCT UVCI" onerror="this.style.display='none'">
                <span class="logo-text">PCT UVCI</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <div class="forgot-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h1 class="forgot-title">Mot de passe oublié ?</h1>
                <p class="forgot-subtitle">Pas de problème, nous allons vous aider</p>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <p class="info-text">
                    Pour réinitialiser votre mot de passe, veuillez contacter l'administrateur du système aux coordonnées ci-dessous :
                </p>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <span class="contact-label">Email :</span>
                        <span class="contact-value">admin@pct-uvci.ci</span>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <span class="contact-label">Téléphone :</span>
                        <span class="contact-value">+225 XX XX XX XX</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('login') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour à la connexion
            </a>
        </div>
    </div>
</body>
</html>
