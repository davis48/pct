<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Célibat</title>
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
        .certificate-content {
            background-color: #fff3cd;
            padding: 20px;
            border: 2px solid #ffc107;
            border-radius: 5px;
            margin: 30px 0;
            text-align: center;
            font-size: 16px;
        }
        .signature-section {
            margin-top: 60px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            width: 250px;
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
        .legal-notice {
            background-color: #d1ecf1;
            padding: 15px;
            border-left: 4px solid #17a2b8;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="watermark">OFFICIEL</div>
    
    <div class="header">
        <div class="logo">
            <div style="width: 80px; height: 80px; background-color: #0066cc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                UVCI
            </div>
        </div>
        <div class="title">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
        <div class="subtitle">Union - Travail - Progrès</div>
        <div class="subtitle">MAIRIE D'ABIDJAN</div>
        <div class="subtitle">SERVICE D'ÉTAT CIVIL</div>
    </div>    <div class="document-info">
        <strong>Référence :</strong> {{ $reference_number }}<br>
        <strong>Date :</strong> {{ $date_generation->format('d/m/Y') }}<br>
        <strong>Heure :</strong> {{ $date_generation->format('H:i') }}
    </div>

    <div class="document-title">
        CERTIFICAT DE CÉLIBAT
    </div>

    <div class="content">
        <p>Je soussigné(e), <strong>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</strong>, Officier d'État Civil de la Commune d'Abidjan, certifie que :</p>

        <div class="citizen-info">
            <h3 style="margin-top: 0; color: #0066cc;">IDENTITÉ DU DEMANDEUR</h3>
            <strong>Nom et Prénoms :</strong> {{ ($form_data['nom'] ?? '') . ' ' . ($form_data['prenoms'] ?? '') }}<br>
            <strong>Date de naissance :</strong> {{ isset($form_data['date_naissance']) ? \Carbon\Carbon::parse($form_data['date_naissance'])->format('d/m/Y') : 'Non renseignée' }}<br>
            <strong>Lieu de naissance :</strong> {{ $form_data['lieu_naissance'] ?? 'Non renseigné' }}<br>
            <strong>Nationalité :</strong> {{ $form_data['nationalite'] ?? 'Ivoirienne' }}<br>
            <strong>Profession :</strong> {{ $form_data['profession'] ?? 'Non renseignée' }}<br>
            <strong>Domicile :</strong> {{ $form_data['domicile'] ?? 'Non renseigné' }}<br>
        </div>

        @if(isset($form_data['nom_pere']) && $form_data['nom_pere'])
        <div class="citizen-info">
            <h3 style="margin-top: 0; color: #0066cc;">FILIATION PATERNELLE</h3>
            <strong>Nom du père :</strong> {{ $form_data['nom_pere'] }}<br>
            <strong>Profession du père :</strong> {{ $form_data['profession_pere'] ?? 'Non renseignée' }}<br>
            <strong>Domicile du père :</strong> {{ $form_data['domicile_pere'] ?? 'Non renseigné' }}<br>
        </div>
        @endif

        @if(isset($form_data['nom_mere']) && $form_data['nom_mere'])
        <div class="citizen-info">
            <h3 style="margin-top: 0; color: #0066cc;">FILIATION MATERNELLE</h3>
            <strong>Nom de la mère :</strong> {{ $form_data['nom_mere'] }}<br>
            <strong>Profession de la mère :</strong> {{ $form_data['profession_mere'] ?? 'Non renseignée' }}<br>
            <strong>Domicile de la mère :</strong> {{ $form_data['domicile_mere'] ?? 'Non renseigné' }}<br>
        </div>
        @endif

        <div class="certificate-content">
            <p><strong>CERTIFIE QUE</strong></p>
            <p>Monsieur/Madame <strong>{{ ($form_data['nom'] ?? '') . ' ' . ($form_data['prenoms'] ?? '') }}</strong></p>
            <p><strong>N'EST ACTUELLEMENT ENGAGÉ(E) PAR AUCUN LIEN DE MARIAGE</strong></p>
            <p>selon les registres d'état civil tenus dans cette commune.</p>
        </div>        <div class="legal-notice">
            <h4 style="margin-top: 0; color: #17a2b8;">CONDITIONS DE VÉRIFICATION</h4>
            <p>Cette certification a été établie après vérification des registres d'état civil de la commune d'Abidjan pour la période de {{ $date_generation->copy()->subYears(10)->format('Y') }} à {{ $date_generation->format('Y') }}.</p>
            <p>Cette vérification ne couvre que les actes de mariage enregistrés dans cette commune.</p>
        </div>

        <p><strong>Motif de la demande :</strong> {{ $form_data['motif'] ?? $request->reason ?? 'Usage administratif' }}</p>

        <p>Le présent certificat est délivré pour servir et valoir ce que de droit.</p>

        <div class="legal-notice">
            <p><strong>VALIDITÉ :</strong> Ce certificat est valable pendant une durée de trois (3) mois à compter de sa date de délivrance.</p>
            <p><strong>USAGE :</strong> Ce document ne peut être utilisé que pour les fins pour lesquelles il a été demandé.</p>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p><strong>L'Officier d'État Civil</strong></p>
            <div style="height: 60px;"></div>
            <p>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</p>
            <p><em>Cachet et signature</em></p>
        </div>
    </div>

    <div class="footer">
        <p>Document généré électroniquement le {{ is_string($date_generation) ? $date_generation . ' à ' . date('H:i') : $date_generation->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Service d'État Civil - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $reference_number }}</p>
    </div>
</body>
</html>
