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
    </div>    <div class="document-info">
        <strong>Référence :</strong> {{ $reference_number }}<br>
        <strong>Date :</strong> {{ $date_generation->format('d/m/Y') }}<br>
        <strong>Heure :</strong> {{ $date_generation->format('H:i') }}
    </div>

    <div class="document-title">
        ATTESTATION DE DOMICILE
    </div>

    <div class="content">
        <p>Je soussigné(e), <strong>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</strong>, certifie que :</p>

        <div class="citizen-info">
            <strong>Nom et Prénoms :</strong> {{ ($form_data['nom'] ?? '') . ' ' . ($form_data['prenoms'] ?? '') }}<br>
            <strong>Date de naissance :</strong> {{ isset($form_data['date_naissance']) ? \Carbon\Carbon::parse($form_data['date_naissance'])->format('d/m/Y') : 'Non renseignée' }}<br>
            <strong>Lieu de naissance :</strong> {{ $form_data['lieu_naissance'] ?? 'Non renseigné' }}<br>
            <strong>Nationalité :</strong> {{ $form_data['nationalite'] ?? 'Non renseignée' }}<br>
            <strong>Profession :</strong> {{ $form_data['profession'] ?? 'Non renseignée' }}<br>
            <strong>Numéro CNI :</strong> {{ $form_data['cin_number'] ?? 'Non renseigné' }}<br>
            <strong>Téléphone :</strong> {{ $form_data['telephone'] ?? 'Non renseigné' }}<br>
        </div>

        <p>Demeure effectivement à l'adresse suivante :</p>
        
        <div class="citizen-info">
            <strong>Adresse complète :</strong> {{ $form_data['adresse_complete'] ?? 'Adresse non renseignée' }}<br>
            <strong>Commune/Ville :</strong> {{ $form_data['commune'] ?? 'Non renseignée' }}<br>
            <strong>Quartier :</strong> {{ $form_data['quartier'] ?? 'Non renseigné' }}<br>
            <strong>Date d'installation :</strong> {{ isset($form_data['date_installation']) ? \Carbon\Carbon::parse($form_data['date_installation'])->format('d/m/Y') : 'Non renseignée' }}<br>
            <strong>Statut du logement :</strong> {{ $form_data['statut_logement'] ?? 'Non renseigné' }}<br>
        </div>

        @if(isset($form_data['nom_temoin']) && $form_data['nom_temoin'])
        <p><strong>Attesté par le témoin :</strong></p>
        <div class="citizen-info">
            <strong>Nom et Prénoms du témoin :</strong> {{ ($form_data['nom_temoin'] ?? '') . ' ' . ($form_data['prenoms_temoin'] ?? '') }}<br>
            <strong>Profession du témoin :</strong> {{ $form_data['profession_temoin'] ?? 'Non renseignée' }}<br>
            <strong>Téléphone du témoin :</strong> {{ $form_data['telephone_temoin'] ?? 'Non renseigné' }}<br>
        </div>
        @endif

        <p>Cette attestation est délivrée pour servir et valoir ce que de droit.</p>

        <p><strong>Motif de la demande :</strong> {{ $form_data['motif'] ?? $request->reason ?? 'Usage administratif' }}</p>
        <p><strong>Lieu de délivrance :</strong> {{ $form_data['lieu_delivrance'] ?? 'Abidjan' }}</p>
    </div>

    <div class="signature-section">        <div class="signature-box">
            <p><strong>Le Demandeur</strong></p>
            <div style="height: 60px;"></div>
            <p>{{ ($form_data['nom'] ?? '') . ' ' . ($form_data['prenoms'] ?? '') }}</p>
        </div>
        
        <div class="signature-box">
            <p><strong>Le Maire</strong></p>
            <div style="height: 60px;"></div>
            <p>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</p>
            <p><em>Cachet et signature</em></p>
        </div>
    </div>

    <div class="footer">
        <p>Document généré électroniquement le {{ $date_generation->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Service des Affaires Civiles - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $reference_number }}</p>
    </div>
</body>
</html>
