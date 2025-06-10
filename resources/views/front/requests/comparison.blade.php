@extends('layouts.front.app')

@push('styles')
<style>
    .comparison-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .comparison-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .old-version {
        border-top: 5px solid #dc3545;
    }
    
    .new-version {
        border-top: 5px solid #28a745;
    }
    
    .version-header {
        padding: 1.5rem;
        font-weight: bold;
        color: white;
    }
    
    .old-version .version-header {
        background: #dc3545;
    }
    
    .new-version .version-header {
        background: #28a745;
    }
    
    .form-preview {
        padding: 2rem;
        height: 600px;
        overflow-y: auto;
    }
    
    .feature-list {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin: 1rem 0;
    }
    
    .feature-missing {
        opacity: 0.5;
        text-decoration: line-through;
    }
    
    .feature-new {
        background: #d4edda;
        color: #155724;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: bold;
    }
    
    .side-by-side {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin: 2rem 0;
    }
    
    .stats-comparison {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin: 2rem 0;
    }
    
    .metric {
        text-align: center;
        padding: 1rem;
    }
    
    .metric-value {
        font-size: 2rem;
        font-weight: bold;
        display: block;
    }
    
    .metric-old {
        color: #dc3545;
    }
    
    .metric-new {
        color: #28a745;
    }
    
    @media (max-width: 768px) {
        .side-by-side {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('title', 'Comparaison des Formulaires - Avant vs Après')

@section('content')
<div class="comparison-container">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4 mb-4">
                <i class="fas fa-balance-scale me-3"></i>
                Comparaison des Formulaires
            </h1>
            <p class="lead">
                Découvrez l'évolution spectaculaire de notre formulaire de création de demandes
            </p>
        </div>

        <!-- Statistiques de comparaison -->
        <div class="stats-comparison">
            <h3 class="text-center mb-4">📊 Métriques de Comparaison</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="metric">
                        <span class="metric-value metric-old">4</span>
                        <small>Champs (Ancien)</small>
                    </div>
                    <div class="metric">
                        <span class="metric-value metric-new">15+</span>
                        <small>Champs (Nouveau)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric">
                        <span class="metric-value metric-old">0</span>
                        <small>Validation temps réel</small>
                    </div>
                    <div class="metric">
                        <span class="metric-value metric-new">100%</span>
                        <small>Validation temps réel</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric">
                        <span class="metric-value metric-old">Basique</span>
                        <small>Upload de fichiers</small>
                    </div>
                    <div class="metric">
                        <span class="metric-value metric-new">Avancé</span>
                        <small>Upload de fichiers</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric">
                        <span class="metric-value metric-old">0</span>
                        <small>Étapes guidées</small>
                    </div>
                    <div class="metric">
                        <span class="metric-value metric-new">4</span>
                        <small>Étapes guidées</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparaison côte à côte -->
        <div class="side-by-side">
            <!-- Ancien formulaire -->
            <div class="comparison-card old-version">
                <div class="version-header">
                    <i class="fas fa-times-circle me-2"></i>
                    Ancien Formulaire (Version 1.0)
                </div>
                <div class="form-preview">
                    <h5>Interface Basique</h5>
                    <div class="mb-3">
                        <label class="form-label">Type de demande</label>
                        <select class="form-select" disabled>
                            <option>Sélectionnez un type de demande</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document associé</label>
                        <select class="form-select" disabled>
                            <option>Sélectionnez un document</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" disabled placeholder="Description basique..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pièces jointes</label>
                        <input type="file" class="form-control" disabled>
                    </div>
                    
                    <div class="feature-list">
                        <h6>Limitations de l'ancien formulaire :</h6>
                        <ul>
                            <li class="feature-missing">❌ Aucune validation en temps réel</li>
                            <li class="feature-missing">❌ Informations insuffisantes pour PDF</li>
                            <li class="feature-missing">❌ Interface peu intuitive</li>
                            <li class="feature-missing">❌ Aucune progression visible</li>
                            <li class="feature-missing">❌ Gestion de fichiers basique</li>
                            <li class="feature-missing">❌ Pas de sauvegarde automatique</li>
                            <li class="feature-missing">❌ Messages d'erreur génériques</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Nouveau formulaire -->
            <div class="comparison-card new-version">
                <div class="version-header">
                    <i class="fas fa-check-circle me-2"></i>
                    Nouveau Formulaire (Version 2.0)
                </div>
                <div class="form-preview">
                    <h5>Interface Révolutionnée</h5>
                    
                    <!-- Tracker de progression -->
                    <div class="d-flex justify-content-between mb-3">
                        <div class="text-center">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">1</div>
                            <small class="d-block">Type</small>
                        </div>
                        <div class="text-center">
                            <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">2</div>
                            <small class="d-block">Infos</small>
                        </div>
                        <div class="text-center">
                            <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">3</div>
                            <small class="d-block">Docs</small>
                        </div>
                        <div class="text-center">
                            <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">4</div>
                            <small class="d-block">Valid</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">📄 Type de demande <span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option>📄 Attestation de domicile</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">🏙️ Lieu de naissance <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Ex: Abidjan, Côte d'Ivoire">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">💼 Profession <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Ex: Étudiant, Commerçant">
                        </div>
                    </div>
                    
                    <div class="feature-list">
                        <h6>✨ Nouvelles fonctionnalités :</h6>
                        <ul>
                            <li>✅ <span class="feature-new">NOUVEAU</span> Validation en temps réel</li>
                            <li>✅ <span class="feature-new">NOUVEAU</span> 15+ champs pour PDF complets</li>
                            <li>✅ <span class="feature-new">NOUVEAU</span> Interface progressive en 4 étapes</li>
                            <li>✅ <span class="feature-new">NOUVEAU</span> Prévisualisation des fichiers</li>
                            <li>✅ <span class="feature-new">NOUVEAU</span> Sauvegarde automatique</li>
                            <li>✅ <span class="feature-new">NOUVEAU</span> Messages contextuels</li>
                            <li>✅ <span class="feature-new">NOUVEAU</span> Design responsive</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des améliorations détaillées -->
        <div class="stats-comparison">
            <h3 class="text-center mb-4">🚀 Améliorations Détaillées</h3>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-danger">❌ Problèmes de l'ancien formulaire</h5>
                    <ul>
                        <li>Informations manquantes pour générer les PDF</li>
                        <li>Processus de validation post-soumission</li>
                        <li>Retours d'erreur tardifs</li>
                        <li>Interface non intuitive</li>
                        <li>Aucune indication de progression</li>
                        <li>Perte de données en cas de problème</li>
                        <li>Expérience utilisateur frustrante</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="text-success">✅ Solutions du nouveau formulaire</h5>
                    <ul>
                        <li>Collecte complète des informations PDF</li>
                        <li>Validation immédiate et contextuelle</li>
                        <li>Feedback en temps réel</li>
                        <li>Navigation guidée et intuitive</li>
                        <li>Tracker de progression visuel</li>
                        <li>Sauvegarde automatique des données</li>
                        <li>Expérience utilisateur optimisée</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="text-center mt-5">
            <h3 class="mb-4">Prêt à découvrir la différence ?</h3>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('demo.form') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-rocket me-2"></i>Voir la Démonstration Complète
                </a>
                <a href="{{ route('requests.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-edit me-2"></i>Tester le Nouveau Formulaire
                </a>
                <a href="{{ route('old.form') }}" class="btn btn-outline-danger btn-lg">
                    <i class="fas fa-eye me-2"></i>Voir l'Ancien Formulaire
                </a>
            </div>
        </div>

        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-clock me-1"></i>
                Comparaison mise à jour le {{ date('d/m/Y à H:i') }}
            </small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des métriques
    document.querySelectorAll('.metric-value').forEach(metric => {
        const text = metric.textContent;
        if (!isNaN(parseInt(text))) {
            const target = parseInt(text);
            let current = 0;
            const increment = target / 20;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    metric.textContent = target;
                    clearInterval(timer);
                } else {
                    metric.textContent = Math.floor(current);
                }
            }, 100);
        }
    });

    // Animation d'apparition des cartes
    const cards = document.querySelectorAll('.comparison-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        card.style.transition = 'all 0.8s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>
@endpush
