<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Plateforme Administrative')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Optimized Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/app-optimized.css') }}">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        main {
            flex: 1 0 auto;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .dropdown-menu {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    @include('layouts.front.header')
    
    <!-- Flash Messages -->
    @if(session()->has('payment_success') || session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
        <div id="flash-messages" class="position-fixed top-0 start-50 translate-middle-x" style="z-index: 9999; margin-top: 80px;">
            @if(session('payment_success'))
                <div class="alert alert-success alert-dismissible fade show shadow-lg animate__animated animate__slideInDown" role="alert" style="min-width: 400px;">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Paiement réussi !</strong><br>
                    {{ session('payment_success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-lg animate__animated animate__slideInDown" role="alert" style="min-width: 400px;">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Succès !</strong><br>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-lg animate__animated animate__slideInDown" role="alert" style="min-width: 400px;">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Erreur !</strong><br>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show shadow-lg animate__animated animate__slideInDown" role="alert" style="min-width: 400px;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong><br>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show shadow-lg animate__animated animate__slideInDown" role="alert" style="min-width: 400px;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Information !</strong><br>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.front.footer')
    
    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (si nécessaire) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/front.js') }}" defer></script>
    <script>
        // Activations des tooltips et popovers Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            // Activation des tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            
            // Activation des popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })
            
            // Auto-hide flash messages after 8 seconds (for payment success, longer to read)
            setTimeout(function() {
                const flashMessages = document.querySelectorAll('#flash-messages .alert');
                flashMessages.forEach(function(alert) {
                    if (alert.classList.contains('alert-success') && alert.textContent.includes('Paiement')) {
                        // Longer time for payment success messages
                        setTimeout(function() {
                            alert.classList.add('animate__fadeOutUp');
                            setTimeout(function() {
                                alert.remove();
                            }, 500);
                        }, 8000);
                    } else {
                        // Standard time for other messages
                        setTimeout(function() {
                            alert.classList.add('animate__fadeOutUp');
                            setTimeout(function() {
                                alert.remove();
                            }, 500);
                        }, 5000);
                    }
                });
            }, 100);
        });
    </script>
    @stack('scripts')
</body>

</html>
