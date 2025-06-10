<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation de Domicile</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0066cc;
            margin: 10px 0;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
        }
        .document-info {
            text-align: right;
            margin-bottom: 30px;
            font-size: 12px;
        }
        .content {
            margin: 40px 0;
            text-align: justify;
        }
        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            margin: 30px 0;
            color: #0066cc;
        }
        .citizen-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #0066cc;
            margin: 20px 0;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 102, 204, 0.1);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">OFFICIEL</div>
    
    <div class="header">
        <div class="logo">
            <!-- Logo de la mairie/commune -->
            <div style="width: 80px; height: 80px; background-color: #0066cc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                UVCI
            </div>
        </div>
        <div class="title">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
        <div class="subtitle">Union - Travail - Progrès</div>
        <div class="subtitle">MAIRIE D'ABIDJAN</div>
        <div class="subtitle">SERVICE DES AFFAIRES CIVILES</div>
    </div>

    <div class="document-info">
        <strong>Référence :</strong> {{ $reference_number }}<br>        <strong>Date :</strong> {{ is_string($date_generation) ? $date_generation : $date_generation->format('d/m/Y') }}<br>
        <strong>Heure :</strong> {{ is_string($date_generation) ? date('H:i') : $date_generation->format('H:i') }}
    </div>

    <div class="document-title">
        ATTESTATION DE DOMICILE
    </div>

    <div class="content">
        <p>Je soussigné(e), <strong>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</strong>, certifie que :</p>

        <div class="citizen-info">
            <strong>Nom et Prénoms :</strong> {{ $user->name }}<br>
            <strong>Date de naissance :</strong> {{ $user->date_of_birth ?? 'Non renseignée' }}<br>
            <strong>Lieu de naissance :</strong> {{ $user->place_of_birth ?? 'Non renseigné' }}<br>
            <strong>Profession :</strong> {{ $user->profession ?? 'Non renseignée' }}<br>
            <strong>Numéro CNI :</strong> {{ $user->cin_number ?? 'Non renseigné' }}<br>
        </div>

        <p>Demeure effectivement à l'adresse suivante :</p>
        
        <div class="citizen-info">
            <strong>Adresse complète :</strong> {{ $user->address ?? 'Adresse non renseignée' }}<br>
            <strong>Commune/Ville :</strong> {{ $user->city ?? 'Abidjan' }}<br>
            <strong>Depuis le :</strong> {{ $request->created_at->format('d/m/Y') }}
        </p>

        <p>Cette attestation est délivrée pour servir et valoir ce que de droit.</p>

        <p><strong>Motif de la demande :</strong> {{ $request->reason ?? 'Usage administratif' }}</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p><strong>Le Demandeur</strong></p>
            <div style="height: 60px;"></div>
            <p>{{ $user->name }}</p>
        </div>
        
        <div class="signature-box">
            <p><strong>Le Maire</strong></p>
            <div style="height: 60px;"></div>
            <p>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</p>
            <p><em>Cachet et signature</em></p>
        </div>
    </div>

    <div class="footer">
        <p>Document généré électroniquement le {{ is_string($date_generation) ? $date_generation . ' à ' . date('H:i') : $date_generation->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Service des Affaires Civiles - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $reference_number }}</p>
    </div>
</body>
</html>
