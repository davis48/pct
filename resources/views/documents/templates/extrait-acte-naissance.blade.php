<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrait d'Acte de Naissance</title>
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
        .birth-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #0066cc;
            margin: 20px 0;
        }
        .parents-info {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .parent-box {
            width: 48%;
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #28a745;
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
        .registry-info {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
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
    </div>

    <div class="document-info">
        <strong>Référence :</strong> {{ $reference }}<br>
        <strong>Date :</strong> {{ $date_generation->format('d/m/Y') }}<br>
        <strong>Heure :</strong> {{ $date_generation->format('H:i') }}
    </div>

    <div class="document-title">
        EXTRAIT D'ACTE DE NAISSANCE
    </div>

    <div class="content">
        <div class="registry-info">
            <strong>Registre d'État Civil :</strong> Année {{ $date_generation->year }}<br>
            <strong>Acte N° :</strong> {{ $reference }}<br>
            <strong>Centre d'État Civil :</strong> Mairie d'Abidjan
        </div>

        <p>Le présent extrait est conforme aux registres de l'état civil de la Mairie d'Abidjan.</p>

        <div class="birth-info">
            <h3 style="margin-top: 0; color: #0066cc;">INFORMATIONS SUR L'ENFANT</h3>
            <strong>Nom et Prénoms :</strong> {{ $user->name }}<br>
            <strong>Sexe :</strong> {{ $user->gender ?? 'Non renseigné' }}<br>
            <strong>Date de naissance :</strong> {{ $user->date_of_birth ?? 'Non renseignée' }}<br>
            <strong>Lieu de naissance :</strong> {{ $user->place_of_birth ?? 'Abidjan, Côte d\'Ivoire' }}<br>
            <strong>Heure de naissance :</strong> {{ $user->birth_time ?? 'Non renseignée' }}
        </div>

        <h3 style="color: #0066cc;">FILIATION</h3>
        <div class="parents-info">
            <div class="parent-box">
                <h4 style="margin-top: 0; color: #28a745;">PÈRE</h4>
                <strong>Nom et Prénoms :</strong> {{ $user->father_name ?? 'Non renseigné' }}<br>
                <strong>Date de naissance :</strong> {{ $user->father_birth_date ?? 'Non renseignée' }}<br>
                <strong>Lieu de naissance :</strong> {{ $user->father_birth_place ?? 'Non renseigné' }}<br>
                <strong>Profession :</strong> {{ $user->father_profession ?? 'Non renseignée' }}<br>
                <strong>Domicile :</strong> {{ $user->father_address ?? 'Non renseigné' }}
            </div>
            
            <div class="parent-box">
                <h4 style="margin-top: 0; color: #28a745;">MÈRE</h4>
                <strong>Nom et Prénoms :</strong> {{ $user->mother_name ?? 'Non renseigné' }}<br>
                <strong>Date de naissance :</strong> {{ $user->mother_birth_date ?? 'Non renseignée' }}<br>
                <strong>Lieu de naissance :</strong> {{ $user->mother_birth_place ?? 'Non renseigné' }}<br>
                <strong>Profession :</strong> {{ $user->mother_profession ?? 'Non renseignée' }}<br>
                <strong>Domicile :</strong> {{ $user->mother_address ?? 'Non renseigné' }}
            </div>
        </div>

        <div class="registry-info">
            <strong>Déclarant :</strong> {{ $user->declarant_name ?? 'Non renseigné' }}<br>
            <strong>Qualité du déclarant :</strong> {{ $user->declarant_relationship ?? 'Père' }}<br>
            <strong>Date de déclaration :</strong> {{ $user->declaration_date ?? $user->date_of_birth ?? 'Non renseignée' }}
        </div>

        <p><strong>Mentions marginales :</strong> {{ $user->marginal_notes ?? 'Néant' }}</p>

        <p>Le présent extrait ne peut servir qu'aux usages pour lesquels il a été demandé et pendant une durée de trois (3) mois à compter de sa délivrance.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p><strong>L'Officier d'État Civil</strong></p>
            <div style="height: 60px;"></div>
            <p>{{ $request->processed_by ?? 'Officier d\'État Civil' }}</p>
            <p><em>Cachet et signature</em></p>
        </div>
    </div>

    <div class="footer">
        <p>Document généré électroniquement le {{ $date_generation->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Service d'État Civil - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $reference }}</p>
    </div>
</body>
</html>
