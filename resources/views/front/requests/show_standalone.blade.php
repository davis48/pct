<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la demande #{{ $request->reference_number }} | PCT UVCI</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #1f2937;
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        .navbar-icon {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
        }
        
        .navbar-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-link {
            text-decoration: none;
            color: #6b7280;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: #1976d2;
        }
        
        .main-content {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #1976d2;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: #1d4ed8;
        }
        
        .request-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .request-header {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .request-title {
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-en_attente {
            background: rgba(255, 255, 255, 0.2);
            color: #fbbf24;
        }
          .status-en_cours, .status-in_progress {
            background: rgba(255, 255, 255, 0.2);
            color: #60a5fa;
        }
        
        .status-approved, .status-processed, .status-ready, .status-completed {
            background: rgba(255, 255, 255, 0.2);
            color: #34d399;
        }
        
        .status-rejected {
            background: rgba(255, 255, 255, 0.2);
            color: #f87171;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
        }
        
        .status-dot.pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .request-body {
            padding: 2rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        @media (min-width: 768px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .info-icon {
            color: #1976d2;
            font-size: 1.125rem;
            margin-top: 0.125rem;
            flex-shrink: 0;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
        }
        
        .section {
            border-top: 1px solid #f3f4f6;
            padding-top: 2rem;
            margin-top: 2rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-icon {
            color: #1976d2;
        }
        
        .description-text {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
            margin-bottom: 1rem;
        }
        
        .attachments-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        @media (min-width: 640px) {
            .attachments-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .attachment-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .attachment-icon {
            color: #1976d2;
            font-size: 1.5rem;
        }
        
        .attachment-info {
            flex: 1;
        }
        
        .attachment-name {
            font-weight: 500;
            color: #1f2937;
            font-size: 0.875rem;
        }
        
        .attachment-size {
            color: #6b7280;
            font-size: 0.75rem;
        }
        
        .actions-section {
            background: #f8fafc;
            padding: 2rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .actions-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        @media (min-width: 640px) {
            .actions-grid {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-family: inherit;
            justify-content: center;
        }
        
        .btn-primary {
            background: #1976d2;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            color: white;
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
        
        .btn-secondary {
            background: white;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }
        
        .btn-secondary:hover {
            background: #f9fafb;
            color: #374151;
        }
        
        .actions-left, .actions-right {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.25rem;
            top: 0.25rem;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #1976d2;
        }
        
        .timeline-date {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        
        .timeline-event {
            font-weight: 500;
            color: #1f2937;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="{{ route('home') }}" class="navbar-brand">
                <div class="navbar-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                PCT UVCI
            </a>
            
            <div class="navbar-nav">
                <a href="{{ route('citizen.dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Mon Espace
                </a>
                <a href="{{ route('requests.index') }}" class="nav-link">
                    <i class="fas fa-file-alt mr-2"></i>
                    Mes Demandes
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="main-content">
        <div class="container">            <!-- Lien de retour -->
            <a href="{{ route('requests.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Retour aux demandes
            </a>

            <!-- Notification de statut -->
            @if(in_array($request->status, ['approved', 'processed', 'ready', 'completed']) || ($request->status == 'in_progress' && $request->processed_by))
            <div style="background: #dcfce7; border: 1px solid #86efac; color: #16a34a; padding: 1rem; border-radius: 8px; margin: 1rem 0; display: flex; align-items: center;">
                <i class="fas fa-check-circle" style="margin-right: 12px; font-size: 20px;"></i>
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">🎉 Votre document est prêt !</div>
                    <div style="font-size: 14px;">Votre demande a été traitée avec succès. Vous pouvez maintenant télécharger votre document.</div>
                </div>
            </div>
            @elseif($request->status == 'in_progress')
            <div style="background: #dbeafe; border: 1px solid #93c5fd; color: #2563eb; padding: 1rem; border-radius: 8px; margin: 1rem 0; display: flex; align-items: center;">
                <i class="fas fa-clock" style="margin-right: 12px; font-size: 20px;"></i>
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">Traitement en cours</div>
                    <div style="font-size: 14px;">Votre demande est actuellement en cours de traitement par nos équipes.</div>
                </div>
            </div>
            @elseif($request->status == 'pending' || $request->status == 'en_attente')
            <div style="background: #fef3c7; border: 1px solid #fcd34d; color: #d97706; padding: 1rem; border-radius: 8px; margin: 1rem 0; display: flex; align-items: center;">
                <i class="fas fa-hourglass-half" style="margin-right: 12px; font-size: 20px;"></i>
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">En attente de traitement</div>
                    <div style="font-size: 14px;">Votre demande a été reçue et sera traitée dans les plus brefs délais.</div>
                </div>
            </div>
            @endif

            <!-- Carte principale de la demande -->
            <div class="request-card">
                <div class="request-header">
                    <h1 class="request-title">Demande #{{ $request->reference_number ?? $request->id }}</h1>
                    <div class="status-badge status-{{ $request->status }}">
                        <div class="status-dot {{ $request->status == 'en_attente' ? 'pulse' : '' }}"></div>                        @switch($request->status)
                            @case('en_attente')
                            @case('pending')
                                En attente
                                @break
                            @case('en_cours')
                            @case('in_progress')
                                En cours
                                @break
                            @case('approved')
                                Approuvée
                                @break
                            @case('processed')
                            @case('ready')
                                Document prêt
                                @break
                            @case('completed')
                                Terminée
                                @break
                            @case('rejected')
                                Rejetée
                                @break
                            @default
                                {{ ucfirst($request->status) }}
                        @endswitch
                    </div>
                </div>

                <div class="request-body">
                    <!-- Informations principales -->
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-file-alt info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Type de demande</div>
                                <div class="info-value">{{ ucfirst($request->type) }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-calendar info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Date de demande</div>
                                <div class="info-value">{{ $request->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-document info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Document demandé</div>
                                <div class="info-value">{{ $request->document->name ?? 'Non spécifié' }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-clock info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Urgence</div>
                                <div class="info-value">
                                    @switch($request->urgency ?? 'normal')
                                        @case('urgent')
                                            Urgent (3-5 jours)
                                            @break
                                        @case('very_urgent')
                                            Très urgent (24-48h)
                                            @break
                                        @default
                                            Normal (7-10 jours)
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        @if($request->document && $request->document->price)
                        <div class="info-item">
                            <i class="fas fa-euro-sign info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Coût</div>
                                <div class="info-value">{{ $request->document->price }}€</div>
                            </div>
                        </div>
                        @endif

                        <div class="info-item">
                            <i class="fas fa-envelope info-icon"></i>
                            <div class="info-content">
                                <div class="info-label">Contact préféré</div>
                                <div class="info-value">
                                    @switch($request->contact_preference ?? 'email')
                                        @case('phone')
                                            Téléphone
                                            @break
                                        @case('both')
                                            Email et téléphone
                                            @break
                                        @default
                                            Email
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($request->description)
                    <div class="section">
                        <h3 class="section-title">
                            <i class="fas fa-clipboard-list section-icon"></i>
                            Description de la demande
                        </h3>
                        <div class="description-text">
                            {{ $request->description }}
                        </div>
                    </div>
                    @endif

                    <!-- Motif -->
                    @if($request->reason)
                    <div class="section">
                        <h3 class="section-title">
                            <i class="fas fa-question-circle section-icon"></i>
                            Motif de la demande
                        </h3>
                        <div class="description-text">
                            {{ $request->reason }}
                        </div>
                    </div>
                    @endif

                    <!-- Documents joints -->
                    @if($request->attachments && count($request->attachments) > 0)
                    <div class="section">
                        <h3 class="section-title">
                            <i class="fas fa-paperclip section-icon"></i>
                            Documents joints ({{ count($request->attachments) }})
                        </h3>
                        <div class="attachments-grid">
                            @foreach($request->attachments as $attachment)
                            <div class="attachment-item">
                                <i class="fas fa-file-pdf attachment-icon"></i>
                                <div class="attachment-info">
                                    <div class="attachment-name">{{ $attachment['name'] ?? 'Document' }}</div>
                                    <div class="attachment-size">{{ $attachment['size'] ?? 'Taille inconnue' }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Historique -->
                    @if($request->created_at)
                    <div class="section">
                        <h3 class="section-title">
                            <i class="fas fa-history section-icon"></i>
                            Historique
                        </h3>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $request->created_at->format('d/m/Y à H:i') }}</div>
                                <div class="timeline-event">Demande soumise</div>
                            </div>
                            @if($request->updated_at && $request->updated_at != $request->created_at)
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $request->updated_at->format('d/m/Y à H:i') }}</div>
                                <div class="timeline-event">                                    @switch($request->status)
                                        @case('en_cours')
                                        @case('in_progress')
                                            Demande en cours de traitement
                                            @break
                                        @case('approved')
                                            Demande approuvée
                                            @break
                                        @case('processed')
                                        @case('ready')
                                            Document prêt
                                            @break
                                        @case('completed')
                                            Demande terminée
                                            @break
                                        @case('rejected')
                                            Demande rejetée
                                            @break
                                        @default
                                            Statut mis à jour
                                    @endswitch
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="actions-section">
                    <div class="actions-grid">
                        <div class="actions-left">
                            <a href="{{ route('requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Retour à la liste
                            </a>
                        </div>
                        
                        <div class="actions-right">                            @if(in_array($request->status, ['approved', 'processed', 'ready', 'completed']) || ($request->status == 'in_progress' && $request->processed_by))
                                <a href="{{ route('documents.download', $request) }}" class="btn btn-success">
                                    <i class="fas fa-download"></i>
                                    Télécharger le document
                                </a>
                            @endif
                            
                            @if($request->status === 'en_attente')
                                <form action="{{ route('requests.destroy', $request) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i>
                                        Annuler la demande
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
