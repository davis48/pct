<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PCT-UVCI - Administration</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Optimized Application CSS -->
    <link href="{{ asset('css/app-optimized.css') }}" rel="stylesheet">

    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.admin.sidebar')

        <div class="main-content">
            <!-- Header -->
            @include('layouts.admin.header')

            <!-- Main Content -->
            <main>
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="footer py-3 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-between small">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <div class="text-muted">Copyright &copy; PCT-UVCI {{ date('Y') }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="text-muted">Plateforme Citoyenne Technique - UVCI</div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>

    @yield('scripts')
</body>
</html>
