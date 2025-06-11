<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation Paiement | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .phone-simulation {
            background: linear-gradient(135deg, #f8fafc 0%, #e1f5fe 100%);
            border-radius: 25px;
            padding: 20px;
            color: white;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transform: perspective(1000px) rotateY(-5deg);
            transition: all 0.3s ease;
        }
        
        .phone-simulation:hover {
            transform: perspective(1000px) rotateY(0deg);
        }
        
        .phone-content {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin: 15px 0;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .notification-item {
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 10px;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.5s ease;
            border-left: 4px solid rgba(255,255,255,0.3);
        }
        
        .notification-item.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 16px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, #10b981, #3b82f6, #6366f1);
            z-index: 1;
        }
        
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }
        
        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .step-item.completed .step-circle {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .step-item.active .step-circle {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
            animation: pulse 2s infinite;
        }
        
        .step-item.pending .step-circle {
            background: #f3f4f6;
            color: #9ca3af;
        }

        .navbar {
            background: linear-gradient(135deg, #f8fafc 0%, #e1f5fe 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Barre de navigation -->
    <nav class="navbar">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('home') }}" class="navbar-brand">
                    PCT UVCI
                </a>
                
                <div class="flex space-x-6">
                    <a href="{{ route('home') }}" class="nav-link">Accueil</a>
                    @auth
                        <span class="text-white">{{ Auth::user()->nom ?? Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link bg-transparent border-0 p-0" style="background: none; border: none; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login.standalone') }}" class="nav-link">Connexion</a>
                        <a href="{{ route('register.standalone') }}" class="nav-link">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- Indicateur d'étapes amélioré -->
            <div class="step-indicator">
                <div class="step-item completed">
                    <div class="step-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="text-sm font-medium text-green-600 mt-2">Demande soumise</span>
                </div>
                <div class="step-item completed">
                    <div class="step-circle">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span class="text-sm font-medium text-green-600 mt-2">Paiement initié</span>
                </div>
                <div class="step-item active">
                    <div class="step-circle">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <span class="text-sm font-medium text-blue-600 mt-2">Validation en cours</span>
                </div>
                <div class="step-item pending">
                    <div class="step-circle">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-500 mt-2">Confirmation</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Informations de paiement -->
                <div class="bg-white rounded-xl shadow-lg p-8 fade-in-up">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-mobile-alt text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Validation de paiement</h3>
                        <p class="text-gray-600">Veuillez valider le paiement sur votre téléphone</p>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h4 class="font-semibold text-blue-900 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informations de paiement
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Référence :</span>
                                    <span class="font-medium text-blue-900">{{ $citizenRequest->reference_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Montant :</span>
                                    <span class="font-medium text-blue-900">{{ number_format(5000, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Méthode :</span>
                                    <span class="font-medium text-blue-900 capitalize">{{ $paymentMethod ?? 'Mobile Money' }}</span>
                                </div>
                            </div>
                        </div>                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <h4 class="font-semibold text-yellow-900 mb-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                Instructions pour la démonstration
                            </h4>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-yellow-800">
                                <li>Cette démonstration simule un paiement mobile money</li>
                                <li>Cliquez sur "Procéder au paiement" pour valider automatiquement</li>
                                <li>Votre demande passera automatiquement en cours de traitement</li>
                                <li>Vous serez redirigé vers la page de détails de votre demande</li>
                            </ol>
                        </div><!-- Actions -->
                        <div class="flex space-x-4">
                            <form action="{{ route('payments.standalone.validate-payment', $citizenRequest) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Procéder au paiement
                                </button>
                            </form>
                            
                            <button onclick="window.location.reload()" class="bg-gray-600 hover:bg-gray-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                <i class="fas fa-redo mr-2"></i>
                                Actualiser
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Problème avec le paiement ?</p>
                            <a href="{{ route('payments.standalone.show', $citizenRequest) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Retour aux options de paiement
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Simulation téléphone -->
                <div class="fade-in-up" style="animation-delay: 0.2s;">
                    <div class="phone-simulation">
                        <div class="text-center mb-4">
                            <h4 class="text-lg font-semibold">Votre téléphone</h4>
                            <p class="text-sm opacity-90">Simulation de la notification</p>
                        </div>
                        
                        <div class="phone-content">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium">Mobile Money</span>
                                <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded">Maintenant</span>
                            </div>
                            <div class="notification-item show">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-credit-card text-lg mt-1"></i>
                                    <div class="flex-1">
                                        <p class="font-medium text-sm">Demande de paiement</p>
                                        <p class="text-xs opacity-90 mt-1">
                                            PCT UVCI - {{ number_format(5000, 0, ',', ' ') }} FCFA
                                        </p>
                                        <p class="text-xs opacity-90">
                                            Ref: {{ $citizenRequest->reference_number }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <div class="inline-flex items-center space-x-2 bg-white bg-opacity-20 px-4 py-2 rounded-full">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-sm">En attente de votre confirmation</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="mt-8 bg-white rounded-xl shadow-lg p-6 fade-in-up" style="animation-delay: 0.4s;">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-question-circle text-blue-600 mr-2"></i>
                    Questions fréquentes
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-medium text-gray-900 mb-2">Combien de temps pour la validation ?</h5>
                        <p class="text-sm text-gray-600">La validation prend généralement entre 30 secondes et 2 minutes selon votre opérateur.</p>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-900 mb-2">Que faire si je n'ai pas reçu la notification ?</h5>
                        <p class="text-sm text-gray-600">Vérifiez votre solde et redémarrez votre téléphone. Vous pouvez aussi cliquer sur "Vérifier le statut".</p>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-900 mb-2">Le paiement est-il sécurisé ?</h5>
                        <p class="text-sm text-gray-600">Oui, tous les paiements passent par les plateformes sécurisées des opérateurs mobiles.</p>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-900 mb-2">Puis-je annuler le paiement ?</h5>
                        <p class="text-sm text-gray-600">Vous pouvez refuser la demande sur votre téléphone. Le paiement ne sera pas débité.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p>&copy; 2025 PCT UVCI. Tous droits réservés.</p>
                <p class="text-gray-400 text-sm mt-2">Paiement sécurisé et traitement rapide de vos demandes</p>
            </div>
        </div>
    </footer>

    <script>
        // Auto-refresh toutes les 10 secondes pour vérifier le statut du paiement
        setTimeout(function() {
            window.location.reload();
        }, 10000);

        // Animation des notifications
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification-item');
            notifications.forEach((notification, index) => {
                setTimeout(() => {
                    notification.classList.add('show');
                }, index * 500);
            });
        });
    </script>
</body>
</html>
