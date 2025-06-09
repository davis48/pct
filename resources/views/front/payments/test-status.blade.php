@extends('layouts.front.app')

@section('title', 'TEST - Statut du paiement | Plateforme Administrative')

@push('styles')
<style>
    .success-alert {
        animation: pulse 2s infinite;
        border: 3px solid #198754 !important;
        background-color: #d1eddd !important;
    }
    
    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.02); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    .test-info {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">TEST - Statut du paiement</h3>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Message de test -->
                    <div class="test-info">
                        <h5><i class="fas fa-flask me-2"></i>Page de test</h5>
                        <p class="mb-0">Cette page teste l'affichage des messages de succès après paiement.</p>
                    </div>
                    
                    <!-- Message de succès FORCÉ pour test -->
                    <div class="alert alert-success alert-dismissible fade show mb-4 success-alert" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>🎉 TEST FORCÉ - Félicitations !</strong> Paiement effectué avec succès!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    
                    <!-- Message de session réel -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 success-alert" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>🎉 SESSION RÉELLE - Félicitations !</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Aucun message de session 'success' trouvé</strong>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Erreur :</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- Informations de débogage -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5><i class="fas fa-bug me-2"></i>Informations de débogage</h5>
                        </div>
                        <div class="card-body">
                            <h6>Variables de session:</h6>
                            <ul>
                                <li><strong>success:</strong> {{ session('success') ?? 'Non défini' }}</li>
                                <li><strong>error:</strong> {{ session('error') ?? 'Non défini' }}</li>
                                <li><strong>status:</strong> {{ session('status') ?? 'Non défini' }}</li>
                                <li><strong>message:</strong> {{ session('message') ?? 'Non défini' }}</li>
                            </ul>
                            
                            <h6 class="mt-3">Toutes les variables de session flash:</h6>
                            <pre style="font-size: 12px; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">{{ json_encode(session()->all(), JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="text-center mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Retour au dashboard
                        </a>
                        <button onclick="location.reload()" class="btn btn-secondary">
                            <i class="fas fa-sync me-2"></i>Recharger
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    console.log('=== PAGE DE TEST DE PAIEMENT ===');
    console.log('Messages de session disponibles:');
    console.log('- success:', @json(session('success')));
    console.log('- error:', @json(session('error')));
    console.log('- status:', @json(session('status')));
    
    // Vérifier si Bootstrap est chargé
    if (typeof bootstrap !== 'undefined') {
        console.log('✅ Bootstrap JS est chargé');
    } else {
        console.log('❌ Bootstrap JS non chargé');
    }
    
    // Vérifier les alertes
    const alerts = document.querySelectorAll('.alert');
    console.log('Nombre d\'alertes trouvées:', alerts.length);
    
    alerts.forEach((alert, index) => {
        console.log(`Alerte ${index + 1}:`, alert.textContent.trim());
    });
</script>
@endpush
@endsection
