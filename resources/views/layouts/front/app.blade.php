<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Administrative</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Optimized Application CSS -->
    <link rel="stylesheet" href="{{ asset('css/app-optimized.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Navigation -->

    @include('layouts.front.header')
    <!-- Hero Section -->

    @yield('content')
    <!-- Footer -->

    @include('layouts.front.footer')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/front.js') }}" defer></script>
    @stack('scripts')
</body>

</html>
