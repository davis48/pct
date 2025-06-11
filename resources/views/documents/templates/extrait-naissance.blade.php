<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Extrait d'Acte de Naissance</title>
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
    <div class="watermark">{{ $municipality ?? 'MAIRIE' }}</div>

    <div class="header">
        <div class="logo">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
        <div>Union - Travail - Progrès</div>
        <div class="commune">{{ $municipality ?? 'Mairie d\'Abidjan' }}</div>
        <div>ÉTAT CIVIL</div>
    </div>

    <div class="document-title">EXTRAIT D'ACTE DE NAISSANCE</div>

    <div class="content">
        <p>Nous, Maire de la {{ $municipality ?? 'Mairie' }}, Officier de l'État Civil, certifions que sur les registres de l'État Civil de cette commune, il a été trouvé l'acte de naissance suivant :</p>

        <div class="info-section">
            <div><span class="info-label">Nom et Prénoms :</span> {{ ($form_data['name'] ?? $name ?? '') . ' ' . ($form_data['first_names'] ?? $first_names ?? '') }}</div>
            <div><span class="info-label">Sexe :</span> {{ $form_data['gender'] ?? $gender ?? '' }}</div>
            <div><span class="info-label">Date de naissance :</span> {{ isset($form_data['date_of_birth']) ? \Carbon\Carbon::parse($form_data['date_of_birth'])->format('d/m/Y') : (isset($date_of_birth) ? \Carbon\Carbon::parse($date_of_birth)->format('d/m/Y') : '') }}</div>
            <div><span class="info-label">Heure de naissance :</span> {{ $form_data['birth_time'] ?? $birth_time ?? '' }}</div>
            <div><span class="info-label">Lieu de naissance :</span> {{ $form_data['place_of_birth'] ?? $place_of_birth ?? '' }}</div>
            <div><span class="info-label">Nationalité :</span> {{ $form_data['nationality'] ?? $nationality ?? '' }}</div>
            
            <div style="margin-top: 15px;"><strong>FILIATION PATERNELLE :</strong></div>
            <div><span class="info-label">Nom du père :</span> {{ ($form_data['father_name'] ?? $father_name ?? '') . ' ' . ($form_data['prenoms_pere'] ?? $prenoms_pere ?? '') }}</div>
            <div><span class="info-label">Âge du père :</span> {{ $form_data['age_pere'] ?? $age_pere ?? '' }}{{ ($form_data['age_pere'] ?? $age_pere ?? '') ? ' ans' : '' }}</div>
            <div><span class="info-label">Profession du père :</span> {{ $form_data['profession_pere'] ?? $form_data['father_profession'] ?? $profession_pere ?? $father_profession ?? '' }}</div>
            <div><span class="info-label">Domicile du père :</span> {{ $form_data['domicile_pere'] ?? $domicile_pere ?? '' }}</div>
            <div><span class="info-label">Lieu de naissance du père :</span> {{ $form_data['lieu_naissance_pere'] ?? $lieu_naissance_pere ?? '' }}</div>
            
            <div style="margin-top: 15px;"><strong>FILIATION MATERNELLE :</strong></div>
            <div><span class="info-label">Nom de la mère :</span> {{ ($form_data['mother_name'] ?? $mother_name ?? '') . ' ' . ($form_data['prenoms_mere'] ?? $prenoms_mere ?? '') }}</div>
            <div><span class="info-label">Âge de la mère :</span> {{ $form_data['age_mere'] ?? $age_mere ?? '' }}{{ ($form_data['age_mere'] ?? $age_mere ?? '') ? ' ans' : '' }}</div>
            <div><span class="info-label">Profession de la mère :</span> {{ $form_data['profession_mere'] ?? $form_data['mother_profession'] ?? $profession_mere ?? $mother_profession ?? '' }}</div>
            <div><span class="info-label">Domicile de la mère :</span> {{ $form_data['domicile_mere'] ?? $domicile_mere ?? '' }}</div>
            <div><span class="info-label">Lieu de naissance de la mère :</span> {{ $form_data['lieu_naissance_mere'] ?? $lieu_naissance_mere ?? '' }}</div>
            
            <div style="margin-top: 15px;"><strong>INFORMATIONS D'ENREGISTREMENT :</strong></div>
            <div><span class="info-label">Centre d'état civil :</span> {{ $form_data['centre_etat_civil'] ?? $centre_etat_civil ?? '' }}</div>
            <div><span class="info-label">Numéro d'acte :</span> {{ $form_data['numero_acte'] ?? $form_data['registry_number'] ?? $numero_acte ?? $registry_number ?? '' }}</div>
            <div><span class="info-label">Date de déclaration :</span> {{ isset($form_data['date_declaration']) ? \Carbon\Carbon::parse($form_data['date_declaration'])->format('d/m/Y') : (isset($form_data['registration_date']) ? \Carbon\Carbon::parse($form_data['registration_date'])->format('d/m/Y') : (isset($date_declaration) ? \Carbon\Carbon::parse($date_declaration)->format('d/m/Y') : (isset($registration_date) ? \Carbon\Carbon::parse($registration_date)->format('d/m/Y') : $date_generation->format('d/m/Y')))) }}</div>
            <div><span class="info-label">Année de registre :</span> {{ $form_data['annee_registre'] ?? $annee_registre ?? '' }}</div>
            <div><span class="info-label">Déclarant :</span> {{ $form_data['declarant_name'] ?? $declarant_name ?? '' }}</div>
        </div>

        <p>Le présent extrait est délivré pour servir et valoir ce que de droit.</p>

        <div style="margin-top: 30px;">
            <div><span class="info-label">Numéro de référence :</span> {{ $reference_number }}</div>
            <div><span class="info-label">Date de délivrance :</span> {{ $date_generation->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="signature-section">
        <p>{{ $municipality ?? 'Mairie' }}, le {{ $date_generation->format('d/m/Y') }}</p>
        <p><strong>Le Maire</strong></p>
        <div style="height: 80px;"></div>
        <p><strong>{{ $mayor_name ?? 'Le Maire' }}</strong></p>
        <p style="font-size: 12px;">Cachet et signature</p>
    </div>

    <div class="footer">
        <p>Document généré électroniquement - Référence: {{ $reference_number }} - {{ $date_generation->format('d/m/Y à H:i') }}</p>
        <p>{{ $municipality ?? 'Mairie' }} - Service d'État Civil - Téléphone: +225 XX XX XX XX</p>
    </div>
</body>
</html>
