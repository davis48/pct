<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 13px;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            background-color: #0d6efd;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            font-weight: bold;
        }
        .info {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin: 20px 0;
        }
        .success {
            border-left-color: #28a745;
        }
        .warning {
            border-left-color: #ffc107;
        }
        .error {
            border-left-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $appName }}</h1>
            <p>Plateforme de Citoyenneté Transparente</p>
        </div>
        <div class="content">
            <h2>Bonjour {{ $user->nom }} {{ $user->prenoms }},</h2>
            
            <p>{{ $message }}</p>
            
            @if($request)
            <div class="info {{ $title === 'Demande approuvée' ? 'success' : ($title === 'Demande rejetée' ? 'error' : '') }}">
                <h3>Détails de votre demande</h3>
                <p><strong>Référence:</strong> {{ $request->reference_number }}</p>
                <p><strong>Type de document:</strong> {{ $request->document?->title ?? ucfirst(str_replace('_', ' ', $request->type)) }}</p>
                <p><strong>Statut actuel:</strong> {{ $request->status }}</p>
                <p><strong>Date de mise à jour:</strong> {{ $date }}</p>
            </div>
            @endif
            
            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">Consulter les détails</a>
            </div>
            
            <p style="margin-top: 30px;">Merci d'utiliser notre plateforme,<br>L'équipe {{ $appName }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $appName }}. Tous droits réservés.</p>
            <p>Ce message est envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
