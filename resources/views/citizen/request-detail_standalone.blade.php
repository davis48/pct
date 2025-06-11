<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la demande | PCT UVCI</title>
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
        /* Styles pour les toggles de notification */
        input[type="checkbox"] + label {
            transition: background-color 0.3s ease;
            position: relative;
        }
        
        input[type="checkbox"] + label:before {
            content: "";
            position: absolute;
            top: 0.125rem;
            left: 0.125rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background-color: white;
            transition: transform 0.3s ease;
        }
        
        input[type="checkbox"]:checked + label:before {
            transform: translateX(1rem);
        }
        
        /* Animation des notifications */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
        
        .notification-pulse {
            animation: pulse 2s infinite;
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Barre de navigation -->
    <nav class="navbar">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="{{ route('home') }}" class="navbar-brand">
                    PCT UVCI
                </a>                <div class="flex space-x-6">
                    <a href="{{ route('home') }}" class="nav-link">Accueil</a>
                    @auth
                        <a href="{{ route('citizen.dashboard') }}" class="nav-link">
                            <i class="fas fa-tachometer-alt mr-1"></i> Tableau de bord
                        </a>
                        <a href="{{ route('requests.index') }}" class="nav-link">
                            <i class="fas fa-file-alt mr-1"></i> Mes demandes
                        </a>
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

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Détails de la demande</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header de la demande -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $request->description }}
                            </h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Référence: {{ $request->reference_number }} • Soumise le {{ $request->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end">
                            @switch($request->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-2"></i> En attente
                                    </span>
                                    @break
                                @case('in_progress')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-cog mr-2"></i> En cours de traitement
                                    </span>
                                    @break
                                @case('approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-2"></i> Approuvé
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i> Terminé
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-2"></i> Rejeté
                                    </span>
                                    @break
                                @case('draft')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-edit mr-2"></i> Brouillon
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($request->status) }}
                                    </span>
                            @endswitch
                            
                            @if($request->payment_status)
                                @switch($request->payment_status)
                                    @case('paid')
                                        <span class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Payé
                                        </span>
                                        @break
                                    @case('unpaid')
                                        <span class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-credit-card mr-1"></i> Non payé
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Paiement en cours
                                        </span>
                                        @break
                                @endswitch
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Actions</h3>                    <div class="flex flex-wrap gap-3">
                        @if($request->status === 'completed' && $request->payments()->where('status', 'completed')->exists())
                            <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger le document
                            </a>
                        @endif
                        
                        <button type="button" onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-print mr-2"></i>
                            Imprimer
                        </button>
                        
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informations de la demande -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Détails de la demande -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                            Informations de la demande
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Type de demande</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->description }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Référence</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <code class="bg-gray-100 px-2 py-1 rounded">{{ $request->reference_number }}</code>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Date de soumission</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $request->created_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Urgence</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @switch($request->urgency)
                                        @case('urgent')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Urgent
                                            </span>
                                            @break
                                        @case('normal')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-clock mr-1"></i> Normal
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($request->urgency) }}
                                            </span>
                                    @endswitch
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Paiement -->
                @if($request->payment_required)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-credit-card mr-2 text-green-600"></i>
                            Informations de paiement
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Statut du paiement</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @switch($request->payment_status)
                                        @case('paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Payé
                                            </span>
                                            @break
                                        @case('unpaid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Non payé
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> En cours
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($request->payment_status) }}
                                            </span>
                                    @endswitch
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Montant</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="font-semibold">5 000 FCFA</span>
                                </dd>
                            </div>
                            @if($request->payments()->where('status', 'completed')->exists())
                                @php $payment = $request->payments()->where('status', 'completed')->first(); @endphp
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Méthode de paiement</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($payment->payment_method ?? 'N/A') }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Date de paiement</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $payment->completed_at ? $payment->completed_at->format('d/m/Y à H:i') : 'N/A' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">ID Transaction</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <code class="bg-gray-100 px-2 py-1 rounded">{{ $payment->transaction_id ?? 'N/A' }}</code>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
                @endif
            </div>

            <!-- Données du formulaire -->
            @if($request->additional_data)
                @php
                    $additionalData = json_decode($request->additional_data, true);
                    $formData = $additionalData['form_data'] ?? [];
                @endphp
                @if(!empty($formData))
                <div class="mt-6 bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            <i class="fas fa-file-alt mr-2 text-purple-600"></i>
                            Informations saisies
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            @foreach($formData as $key => $value)
                                @if(!is_array($value) && !empty($value))
                                    <div class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $value }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                </div>
                @endif
            @endif

            <!-- Pièces jointes -->
            @if($request->attachments && $request->attachments->count() > 0)
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <i class="fas fa-paperclip mr-2 text-yellow-600"></i>
                        Pièces jointes
                    </h3>
                </div>
                <div class="border-t border-gray-200">
                    <ul class="divide-y divide-gray-200">
                        @foreach($request->attachments as $attachment)
                        <li class="px-4 py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file mr-3 text-gray-400"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $attachment->file_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $attachment->file_type }} • {{ number_format($attachment->file_size / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" 
                               class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Voir
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p>&copy; 2025 PCT UVCI. Tous droits réservés.</p>
                <p class="text-gray-400 text-sm mt-2">Suivi de vos demandes en temps réel</p>
            </div>
        </div>
    </footer>
</body>
</html>
