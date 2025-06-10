@extends('layouts.front.app')

@section('title', 'Mes Demandes | Plateforme Administrative')

@push('styles')
<style>
    /* Reset et isolation des styles */
    .requests-page-container * {
        -webkit-text-fill-color: initial !important;
        background-clip: initial !important;
        -webkit-background-clip: initial !important;
    }
    
    .requests-page-container {
        background: #f8fafc;
        padding: 2rem 0;
        min-height: calc(100vh - 64px);
        position: relative;
        z-index: 1;
    }
    
    .page-header {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    @media (min-width: 1024px) {
        .page-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }
    
    .page-header-content h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .page-header-icon {
        color: #3b82f6;
        font-size: 1.5rem;
    }
    
    .page-header-content p {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .requests-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        position: relative;
        z-index: 2;
    }
    
    .requests-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 2rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .requests-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .requests-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .requests-meta {
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .requests-content {
        padding: 0;
    }
    
    .request-card {
        padding: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    
    .request-card:last-child {
        border-bottom: none;
    }
    
    .request-card:hover {
        background: #f8fafc;
    }
    
    .request-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .request-ref {
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
    }
    
    .request-date {
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .status-en_attente {
        background: #fef3c7;
        color: #d97706;
    }
    
    .status-en_cours {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .status-approved {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .status-rejected {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .request-details {
        margin-bottom: 1rem;
    }
    
    .request-title {
        font-weight: 500;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }
    
    .request-description {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .request-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        color: white;
    }
    
    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #374151;
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
        color: white;
    }
    
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #d1d5db;
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }
    
    .table-view {
        display: none;
    }
    
    @media (min-width: 1024px) {
        .card-view {
            display: none;
        }
        
        .table-view {
            display: block;
        }
        
        .requests-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .requests-table th {
            background: #f9fafb;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .requests-table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        
        .requests-table tr:hover {
            background: #f8fafc;
        }
    }
    
    .create-button {
        background: #3b82f6;
        color: white;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    
    .create-button:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        color: white;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #16a34a;
    }
    
    .alert-icon {
        font-size: 1.25rem;
    }
    
    .alert-message {
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="requests-page-container">
    <div class="max-w-6xl mx-auto px-4">
        <!-- En-tête -->
        <div class="page-header">
            <div class="page-header-content">
                <h1>
                    <i class="fas fa-folder-open page-header-icon"></i>
                    Mes demandes
                </h1>
                <p>Suivez le statut de toutes vos demandes de documents</p>
            </div>
            <a href="{{ route('requests.create') }}" class="create-button">
                <i class="fas fa-plus-circle"></i>
                Nouvelle demande
            </a>
        </div>

        <!-- Messages de succès -->
        @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle alert-icon"></i>
            <span class="alert-message">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Contenu principal -->
        <div class="requests-container">
            @if(count($requests) > 0)
                <div class="requests-header">
                    <div class="requests-header-content">
                        <h2 class="requests-title">
                            {{ count($requests) }} demande{{ count($requests) > 1 ? 's' : '' }} trouvée{{ count($requests) > 1 ? 's' : '' }}
                        </h2>
                        <div class="requests-meta">
                            Dernière mise à jour : {{ now()->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Vue Desktop : Tableau -->
                <div class="table-view">
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Type</th>
                                <th>Document</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td class="font-medium text-gray-900">#{{ $request->reference_number }}</td>
                                <td class="text-gray-600">{{ ucfirst($request->type) }}</td>
                                <td class="text-gray-600">{{ $request->document->name ?? 'N/A' }}</td>
                                <td class="text-gray-500 text-sm">{{ $request->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="status-badge status-{{ $request->status }}">
                                        @switch($request->status)
                                            @case('en_attente')
                                                En attente
                                                @break
                                            @case('en_cours')
                                                En cours
                                                @break
                                            @case('approved')
                                                Approuvée
                                                @break
                                            @case('rejected')
                                                Rejetée
                                                @break
                                            @default
                                                {{ $request->status }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('requests.show', $request) }}" class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                            Voir
                                        </a>
                                        
                                        @if($request->status === 'approved' && $request->document_path)
                                            <a href="{{ route('documents.download', $request) }}" class="btn btn-success">
                                                <i class="fas fa-download"></i>
                                                Télécharger
                                            </a>
                                        @endif
                                        
                                        @if($request->status === 'en_attente')
                                            <form action="{{ route('requests.destroy', $request) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times"></i>
                                                    Annuler
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Vue Mobile : Cartes -->
                <div class="card-view requests-content">
                    @foreach($requests as $request)
                    <div class="request-card">
                        <div class="request-meta">
                            <span class="request-ref">#{{ $request->reference_number }}</span>
                            <span class="request-date">{{ $request->created_at->format('d/m/Y à H:i') }}</span>
                            <span class="status-badge status-{{ $request->status }}">
                                @switch($request->status)
                                    @case('en_attente')
                                        En attente
                                        @break
                                    @case('en_cours')
                                        En cours
                                        @break
                                    @case('approved')
                                        Approuvée
                                        @break
                                    @case('rejected')
                                        Rejetée
                                        @break
                                    @default
                                        {{ $request->status }}
                                @endswitch
                            </span>
                        </div>
                        
                        <div class="request-details">
                            <div class="request-title">
                                {{ $request->document->name ?? 'Document non spécifié' }} ({{ ucfirst($request->type) }})
                            </div>
                            <div class="request-description">
                                {{ Str::limit($request->description, 100) }}
                            </div>
                        </div>
                        
                        <div class="request-actions">
                            <a href="{{ route('requests.show', $request) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                                Voir détails
                            </a>
                            
                            @if($request->status === 'approved' && $request->document_path)
                                <a href="{{ route('documents.download', $request) }}" class="btn btn-success">
                                    <i class="fas fa-download"></i>
                                    Télécharger
                                </a>
                            @endif
                            
                            @if($request->status === 'en_attente')
                                <form action="{{ route('requests.destroy', $request) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i>
                                        Annuler
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- État vide -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3>Aucune demande trouvée</h3>
                    <p>Vous n'avez pas encore soumis de demande de document.</p>
                    <a href="{{ route('requests.create') }}" class="create-button">
                        <i class="fas fa-plus-circle"></i>
                        Créer votre première demande
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
