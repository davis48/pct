<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur - PCT UVCI</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .btn-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-admin:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6b4190 100%);
            color: white;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body class="admin-login-bg d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-shield text-primary fs-1"></i>
                            </div>
                            <h2 class="fw-bold text-dark mb-2">Accès Administrateur</h2>
                            <p class="text-muted">Interface réservée aux administrateurs système</p>
                        </div>
                        <form action="{{ route('admin.login.post') }}" method="POST">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2"></i>Adresse email
                                </label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="admin@pct-uvci.ci"
                                    required
                                    autocomplete="email"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2"></i>Mot de passe
                                </label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="current-password"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="remember"
                                        name="remember"
                                    >
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                            </div>

                            <!-- Lien mot de passe oublié -->
                            <div class="text-end mb-3">
                                <a href="{{ route('password.request') }}" class="text-decoration-none">
                                    <i class="fas fa-key me-1"></i>Mot de passe oublié ?
                                </a>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-admin btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                </button>
                            </div>                            <div class="text-center">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
                                </a>
                            </div>
                        </form>                        <!-- Informations de connexion pour le développement -->
                        @if(config('app.debug'))
                        <div class="alert alert-warning mt-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">Environnement de développement</h6>
                                    <p class="mb-1"><strong>Email :</strong> admin@pct-uvci.ci</p>
                                    <p class="mb-0"><strong>Mot de passe :</strong> admin123456</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
