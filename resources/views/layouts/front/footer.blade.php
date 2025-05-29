  <footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-landmark fa-2x me-2"></i>
                    <h5 class="mb-0 fw-bold">Plateforme d'Actes Civils</h5>
                </div>
                <p class="mb-3 text-light">Simplifiez vos démarches administratives en ligne. Notre plateforme vous permet de demander, suivre et recevoir vos documents officiels en toute sécurité.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white" title="Facebook" data-bs-toggle="tooltip">
                        <i class="fab fa-facebook-f fa-lg"></i>
                    </a>
                    <a href="#" class="text-white" title="Twitter" data-bs-toggle="tooltip">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-white" title="Instagram" data-bs-toggle="tooltip">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="#" class="text-white" title="LinkedIn" data-bs-toggle="tooltip">
                        <i class="fab fa-linkedin-in fa-lg"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold mb-4">Liens rapides</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="{{ url('/') }}" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Accueil
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>À propos
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>FAQ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-2 small"></i>Nous contacter
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold mb-4">Contact et assistance</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="d-flex">
                            <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                            <span>123 Avenue de la République<br>75001 Paris, France</span>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex">
                            <i class="fas fa-envelope me-3 text-primary"></i>
                            <span>contact@actes-civils.gouv.fr</span>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex">
                            <i class="fas fa-phone-alt me-3 text-primary"></i>
                            <span>+33 (0)1 23 45 67 89</span>
                        </div>
                    </li>
                    <li>
                        <div class="d-flex">
                            <i class="fas fa-clock me-3 text-primary"></i>
                            <span>Lun-Ven: 9h-17h</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="my-4 bg-light">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; {{ date('Y') }} Plateforme d'Actes Civils. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="#" class="text-light text-decoration-none small">Politique de confidentialité</a>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted mx-2">|</span>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-light text-decoration-none small">Conditions d'utilisation</a>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted mx-2">|</span>
                    </li>
                    <li class="list-inline-item">
                        <a href="{{ route('admin.login') }}" class="text-light text-decoration-none small opacity-50">Administration</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
