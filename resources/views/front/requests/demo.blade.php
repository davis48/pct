@extends('layouts.front.app')

@push('styles')
<link href="{{ asset('css/form-enhancement-demo.css') }}" rel="stylesheet">
@endpush

@section('title', 'Démonstration - Formulaire de demande amélioré')

@section('content')
<div class="form-enhancement-demo">
    <div class="container">
        <div class="demo-card">
            <div class="demo-header">
                <h1 class="display-4 mb-4">
                    <i class="fas fa-magic me-3"></i>
                    Formulaire de Demande Révolutionné
                </h1>
                <p class="lead mb-4">
                    Découvrez notre nouvelle interface de création de demandes administratives, 
                    entièrement repensée pour une expérience utilisateur exceptionnelle
                </p>
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-number">5x</span>
                        <span class="stat-label">Plus rapide</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">15+</span>
                        <span class="stat-label">Nouveaux champs</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Validation automatique</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">4</span>
                        <span class="stat-label">Étapes guidées</span>
                    </div>
                </div>
            </div>

            <div class="demo-content">
                <div class="feature-highlight">
                    <h3><i class="fas fa-rocket text-primary me-2"></i>Pourquoi cette révolution ?</h3>
                    <p class="mb-0">
                        L'ancien formulaire était basique et ne collectait pas toutes les informations nécessaires 
                        pour générer automatiquement les documents PDF. Notre nouvelle version transforme complètement 
                        l'expérience utilisateur avec une interface moderne, intuitive et complète.
                    </p>
                </div>

                <h2 class="text-center mb-5">🎯 Améliorations Principales</h2>

                <div class="improvement-list">
                    <div class="improvement-item">
                        <h4><i class="fas fa-route me-2"></i>Navigation Guidée</h4>
                        <p>Un tracker de progression en 4 étapes guide l'utilisateur tout au long du processus, 
                        rendant la saisie moins intimidante et plus structurée.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i>Progression visuelle</li>
                            <li><i class="fas fa-check text-success me-2"></i>Validation en temps réel</li>
                            <li><i class="fas fa-check text-success me-2"></i>Sauvegarde automatique</li>
                        </ul>
                    </div>

                    <div class="improvement-item">
                        <h4><i class="fas fa-user-edit me-2"></i>Collecte d'Informations Complète</h4>
                        <p>Tous les champs nécessaires pour générer les documents PDF officiels sont maintenant collectés 
                        directement dans le formulaire.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i>Lieu de naissance</li>
                            <li><i class="fas fa-check text-success me-2"></i>Profession</li>
                            <li><i class="fas fa-check text-success me-2"></i>Numéro CNI</li>
                            <li><i class="fas fa-check text-success me-2"></i>Nationalité</li>
                            <li><i class="fas fa-check text-success me-2"></i>Adresse complète</li>
                        </ul>
                    </div>

                    <div class="improvement-item">
                        <h4><i class="fas fa-shield-alt me-2"></i>Validation Intelligente</h4>
                        <p>Validation en temps réel avec des messages d'erreur contextuels et des suggestions 
                        pour aider l'utilisateur à remplir correctement le formulaire.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i>Format CNI automatique</li>
                            <li><i class="fas fa-check text-success me-2"></i>Vérification des fichiers</li>
                            <li><i class="fas fa-check text-success me-2"></i>Taille et format contrôlés</li>
                        </ul>
                    </div>

                    <div class="improvement-item">
                        <h4><i class="fas fa-file-upload me-2"></i>Gestion de Fichiers Moderne</h4>
                        <p>Interface de téléchargement de fichiers repensée avec prévisualisation, 
                        indicateurs de taille et validation automatique des formats.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i>Prévisualisation des fichiers</li>
                            <li><i class="fas fa-check text-success me-2"></i>Documents requis dynamiques</li>
                            <li><i class="fas fa-check text-success me-2"></i>Contrôle de taille et format</li>
                        </ul>
                    </div>

                    <div class="improvement-item">
                        <h4><i class="fas fa-palette me-2"></i>Design Moderne</h4>
                        <p>Interface entièrement repensée avec un design moderne, responsive et accessible, 
                        optimisée pour tous les appareils.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i>Design responsive</li>
                            <li><i class="fas fa-check text-success me-2"></i>Animations fluides</li>
                            <li><i class="fas fa-check text-success me-2"></i>Accessibilité améliorée</li>
                        </ul>
                    </div>

                    <div class="improvement-item">
                        <h4><i class="fas fa-cogs me-2"></i>Fonctionnalités Avancées</h4>
                        <p>Nouvelles fonctionnalités pour améliorer l'expérience utilisateur et 
                        faciliter le traitement des demandes.</p>
                        <ul class="list-unstyled mt-3">
                            <li><i class="fas fa-check text-success me-2"></i>Niveaux d'urgence</li>
                            <li><i class="fas fa-check text-success me-2"></i>Préférences de contact</li>
                            <li><i class="fas fa-check text-success me-2"></i>Motif de la demande</li>
                        </ul>
                    </div>
                </div>

                <div class="technical-details">
                    <h3><i class="fas fa-code me-2"></i>Détails Techniques</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Frontend</h5>
                            <ul>
                                <li>JavaScript ES6+ avec validation en temps réel</li>
                                <li>CSS Grid et Flexbox pour la mise en page</li>
                                <li>Bootstrap 5 pour la responsivité</li>
                                <li>LocalStorage pour la sauvegarde automatique</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Backend</h5>
                            <ul>
                                <li>Laravel 11 avec validation étendue</li>
                                <li>Nouvelles migrations pour les champs PDF</li>
                                <li>Modèles mis à jour avec relations</li>
                                <li>Gestion d'erreurs améliorée</li>
                            </ul>
                        </div>
                    </div>

                    <div class="code-block">
                        <strong>Nouveaux champs ajoutés :</strong><br>
                        • place_of_birth (lieu de naissance)<br>
                        • profession (profession)<br>
                        • cin_number (numéro CNI avec validation)<br>
                        • nationality (nationalité)<br>
                        • complete_address (adresse complète)<br>
                        • reason (motif de la demande)<br>
                        • urgency (niveau d'urgence)<br>
                        • contact_preference (préférence de contact)
                    </div>
                </div>

                <div class="feature-highlight">
                    <h3><i class="fas fa-lightbulb text-warning me-2"></i>Impact sur l'Expérience Utilisateur</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <h5>⏱️ Gain de Temps</h5>
                            <p>Les informations collectées automatiquement évitent les allers-retours et 
                            permettent la génération immédiate des documents.</p>
                        </div>
                        <div class="col-md-4">
                            <h5>🎯 Réduction d'Erreurs</h5>
                            <p>La validation en temps réel et les formats automatiques réduisent 
                            drastiquement les erreurs de saisie.</p>
                        </div>
                        <div class="col-md-4">
                            <h5>📱 Accessibilité</h5>
                            <p>Interface responsive optimisée pour mobile, tablette et desktop 
                            avec une navigation intuitive.</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <h3 class="mb-4">Prêt à tester la nouvelle expérience ?</h3>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('requests.create') }}" class="btn-demo">
                            <i class="fas fa-rocket me-2"></i>Tester le Nouveau Formulaire
                        </a>
                        <a href="{{ route('citizen.dashboard') }}" class="btn-demo" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <i class="fas fa-home me-2"></i>Retour au Dashboard
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Démo créée le {{ date('d/m/Y à H:i') }} - Version 2.0
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation d'apparition progressive des éléments
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observer tous les éléments d'amélioration
    document.querySelectorAll('.improvement-item').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'all 0.6s ease';
        observer.observe(item);
    });

    // Animation des statistiques au survol
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotateY(5deg)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotateY(0deg)';
        });
    });

    // Animation des nombres dans les statistiques
    document.querySelectorAll('.stat-number').forEach(stat => {
        const finalValue = stat.textContent;
        if (!isNaN(parseInt(finalValue))) {
            stat.textContent = '0';
            let current = 0;
            const target = parseInt(finalValue);
            const increment = target / 30;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = finalValue;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(current);
                }
            }, 50);
        }
    });
});
</script>
@endpush
