<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement | PCT UVCI</title>
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
        .payment-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .payment-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .provider-logo {
            height: 50px;
            object-fit: contain;
        }
        
        .step-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            position: relative;
            z-index: 1;
        }
        
        .step.active .step-icon {
            background-color: #0d6efd;
            color: white;
        }
        
        .step.completed .step-icon {
            background-color: #198754;
            color: white;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            
            <!-- Indicateur d'étapes -->
            <div class="flex justify-between items-center mb-8">
                <div class="flex flex-col items-center text-center flex-1 relative">
                    <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center relative z-10">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <div class="text-sm mt-2 font-medium text-green-600">Demande</div>
                    <div class="absolute top-5 left-1/2 w-full h-0.5 bg-green-500 hidden lg:block" style="transform: translateX(50%);"></div>
                </div>
                <div class="flex flex-col items-center text-center flex-1 relative">
                    <div class="w-10 h-10 bg-primary-600 text-white rounded-full flex items-center justify-center relative z-10">
                        <i class="fas fa-credit-card text-sm"></i>
                    </div>
                    <div class="text-sm mt-2 font-medium text-primary-600">Paiement</div>
                    <div class="absolute top-5 left-1/2 w-full h-0.5 bg-gray-300 hidden lg:block" style="transform: translateX(50%);"></div>
                </div>
                <div class="flex flex-col items-center text-center flex-1 relative">
                    <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center relative z-10">
                        <i class="fas fa-file-alt text-sm"></i>
                    </div>
                    <div class="text-sm mt-2 font-medium text-gray-600">Traitement</div>
                    <div class="absolute top-5 left-1/2 w-full h-0.5 bg-gray-300 hidden lg:block" style="transform: translateX(50%);"></div>
                </div>
                <div class="flex flex-col items-center text-center flex-1">
                    <div class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-sm"></i>
                    </div>
                    <div class="text-sm mt-2 font-medium text-gray-600">Réception</div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white p-6">
                    <h4 class="text-2xl font-bold">Paiement de votre demande</h4>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Formulaire de paiement -->
                        <div class="lg:col-span-2">
                            <h5 class="text-xl font-semibold mb-6 text-gray-900">Choisissez votre méthode de paiement</h5>
                            
                            @if (session('error'))
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-center text-red-800">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ session('error') }}
                                    </div>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-center text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            <!-- Options de paiement -->
                            <div class="space-y-4">
                                <!-- Orange Money -->
                                <div class="payment-option border border-gray-200 rounded-lg p-6 hover:border-orange-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f8/Orange_logo.svg/1200px-Orange_logo.svg.png" 
                                                 alt="Orange Money" class="provider-logo">
                                            <div>
                                                <h6 class="font-semibold text-gray-900">Orange Money</h6>
                                                <p class="text-sm text-gray-600">Paiement sécurisé via Orange Money</p>
                                            </div>
                                        </div>
                                        <form action="{{ route('payments.standalone.process', $citizenRequest) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="payment_method" value="orange_money">
                                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors">
                                                Payer {{ number_format(5000, 0, ',', ' ') }} FCFA
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- MTN Mobile Money -->
                                <div class="payment-option border border-gray-200 rounded-lg p-6 hover:border-yellow-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/MTN_Logo.svg/1200px-MTN_Logo.svg.png" 
                                                 alt="MTN Mobile Money" class="provider-logo">
                                            <div>
                                                <h6 class="font-semibold text-gray-900">MTN Mobile Money</h6>
                                                <p class="text-sm text-gray-600">Paiement sécurisé via MTN Mobile Money</p>
                                            </div>
                                        </div>
                                        <form action="{{ route('payments.standalone.process', $citizenRequest) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="payment_method" value="mtn_money">
                                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg transition-colors">
                                                Payer {{ number_format(5000, 0, ',', ' ') }} FCFA
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Moov Money -->
                                <div class="payment-option border border-gray-200 rounded-lg p-6 hover:border-blue-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Moov_logo.svg/1200px-Moov_logo.svg.png" 
                                                 alt="Moov Money" class="provider-logo">
                                            <div>
                                                <h6 class="font-semibold text-gray-900">Moov Money</h6>
                                                <p class="text-sm text-gray-600">Paiement sécurisé via Moov Money</p>
                                            </div>
                                        </div>
                                        <form action="{{ route('payments.standalone.process', $citizenRequest) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="payment_method" value="moov_money">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                                                Payer {{ number_format(5000, 0, ',', ' ') }} FCFA
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Wave -->
                                <div class="payment-option border border-gray-200 rounded-lg p-6 hover:border-purple-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/31/Wave_Mobile_Money_logo.png/1200px-Wave_Mobile_Money_logo.png" 
                                                 alt="Wave" class="provider-logo">
                                            <div>
                                                <h6 class="font-semibold text-gray-900">Wave</h6>
                                                <p class="text-sm text-gray-600">Paiement sécurisé via Wave</p>
                                            </div>
                                        </div>
                                        <form action="{{ route('payments.standalone.process', $citizenRequest) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="payment_method" value="wave">
                                            <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition-colors">
                                                Payer {{ number_format(5000, 0, ',', ' ') }} FCFA
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Résumé de la commande -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h5 class="text-lg font-semibold mb-4 text-gray-900">Résumé de votre demande</h5>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Référence :</span>
                                    <span class="font-medium">{{ $citizenRequest->reference_number }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type :</span>
                                    <span class="font-medium">{{ $citizenRequest->description }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date :</span>
                                    <span class="font-medium">{{ $citizenRequest->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Statut :</span>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">
                                        En attente de paiement
                                    </span>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Frais de traitement :</span>
                                    <span class="font-medium">{{ number_format(5000, 0, ',', ' ') }} FCFA</span>
                                </div>
                                
                                <div class="flex justify-between text-lg font-bold border-t pt-4">
                                    <span>Total à payer :</span>
                                    <span class="text-primary-600">{{ number_format(5000, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-start space-x-2">
                                    <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                    <div class="text-sm text-blue-700">
                                        <p class="font-medium mb-1">Information importante :</p>
                                        <p>Après le paiement, votre demande sera traitée dans un délai de 24-48h ouvrables. Vous recevrez une notification par email une fois le document prêt.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
</body>
</html>
