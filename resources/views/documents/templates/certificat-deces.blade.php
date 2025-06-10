<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificat de Décès</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
        }
        .commune {
            font-size: 18px;
            color: #666;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            text-transform: uppercase;
            color: #2c5aa0;
        }
        .content {
            margin: 30px 0;
            text-align: justify;
        }
        .info-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #2c5aa0;
        }
        .info-label {
            font-weight: bold;
            color: #2c5aa0;
        }
        .signature-section {
            margin-top: 50px;
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(44, 90, 160, 0.1);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ $commune }}</div>
    
    <div class="header">
        <div class="logo">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
        <div>Union - Travail - Progrès</div>
        <div class="commune">{{ $commune }}</div>
        <div>ÉTAT CIVIL</div>
    </div>

    <div class="document-title">{{ $document_title }}</div>    <div class="content">
        <p>Nous, Maire de la {{ $commune }}, Officier de l'État Civil, certifions que sur les registres de l'État Civil de cette commune, il a été trouvé l'acte de décès suivant :</p>

        <div class="info-section">
            <div><span class="info-label">DÉFUNT(E) :</span></div>
            <div><span class="info-label">Nom(s) :</span> {{ $form_data['deceased_last_name'] ?? '[À remplir selon l\'acte de décès]' }}</div>
            <div><span class="info-label">Prénom(s) :</span> {{ $form_data['deceased_first_name'] ?? '[À remplir selon l\'acte de décès]' }}</div>
            <div><span class="info-label">Né(e) le :</span> {{ isset($form_data['deceased_birth_date']) ? \Carbon\Carbon::parse($form_data['deceased_birth_date'])->format('d/m/Y') : '[À remplir selon l\'acte de décès]' }}</div>
            <div><span class="info-label">À :</span> {{ $form_data['deceased_birth_place'] ?? '[À remplir selon l\'acte de décès]' }}</div>
            <div><span class="info-label">Décédé(e) le :</span> {{ isset($form_data['death_date']) ? \Carbon\Carbon::parse($form_data['death_date'])->format('d/m/Y') : '[Date du décès]' }}</div>
            <div><span class="info-label">À :</span> {{ $form_data['death_place'] ?? '[Lieu du décès]' }}</div>
        </div>

        <div class="info-section">
            <div><span class="info-label">DÉCLARANT :</span></div>
            <div><span class="info-label">Nom(s) et Prénom(s) :</span> {{ $form_data['declarant_name'] ?? strtoupper($user->nom ?? '') . ' ' . ucfirst($user->prenoms ?? '') }}</div>
            <div><span class="info-label">Né(e) le :</span> {{ isset($form_data['declarant_birth_date']) ? \Carbon\Carbon::parse($form_data['declarant_birth_date'])->format('d/m/Y') : (isset($user->date_naissance) ? \Carbon\Carbon::parse($user->date_naissance)->format('d/m/Y') : '') }}</div>
            <div><span class="info-label">Profession :</span> {{ $form_data['declarant_profession'] ?? $user->profession ?? 'Non spécifiée' }}</div>
            <div><span class="info-label">Domicile :</span> {{ $form_data['declarant_address'] ?? $user->address ?? '' }}</div>
            <div><span class="info-label">Lien avec le défunt :</span> {{ $form_data['relationship_to_deceased'] ?? '[À préciser]' }}</div>
        </div>

        @if(isset($form_data['purpose']))
        <div class="info-section">
            <div><span class="info-label">MOTIF DE LA DEMANDE :</span> {{ $form_data['purpose'] }}</div>
            @if(isset($form_data['notes']) && !empty($form_data['notes']))
            <div><span class="info-label">Remarques :</span> {{ $form_data['notes'] }}</div>
            @endif
        </div>
        @endif

        <p>Le présent certificat est délivré pour servir et valoir ce que de droit.</p>

        <div style="margin-top: 30px;">
            <div><span class="info-label">Numéro de référence :</span> {{ $reference_number }}</div>
            <div><span class="info-label">Date de délivrance :</span> {{ $date_generation }}</div>
        </div>
    </div>

    <div class="signature-section">
        <p>{{ $commune }}, le {{ $date_generation }}</p>
        <p><strong>Le Maire</strong></p>
        <div style="height: 80px;"></div>
        <p><strong>[Nom du Maire]</strong></p>
        <p style="font-size: 12px;">Cachet et signature</p>
    </div>

    <div class="footer">
        <p>Document généré électroniquement - Référence: {{ $reference_number }} - {{ $date_generation }}</p>
        <p>{{ $commune }} - Adresse de la mairie - Téléphone: [numéro] - Email: [email]</p>
    </div>
</body>
</html>
