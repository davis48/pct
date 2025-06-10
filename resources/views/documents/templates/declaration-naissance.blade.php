<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Déclaration de Naissance</title>
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
        .parents-info {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .pere, .mere {
            width: 48%;
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

    <div class="document-title">{{ $document_title }}</div>

    <div class="content">
        <p>Nous, Maire de la {{ $commune }}, Officier de l'État Civil, enregistrons la naissance suivante :</p>

        <div class="info-section">
            <div><span class="info-label">ENFANT :</span></div>
            <div><span class="info-label">Nom(s) :</span> [À remplir lors de la déclaration]</div>
            <div><span class="info-label">Prénom(s) :</span> [À remplir lors de la déclaration]</div>
            <div><span class="info-label">Né(e) le :</span> [À remplir]</div>
            <div><span class="info-label">À :</span> [Lieu de naissance]</div>
            <div><span class="info-label">Sexe :</span> [À remplir]</div>
        </div>

        <div class="info-section">
            <div><span class="info-label">DÉCLARANT :</span></div>
            <div><span class="info-label">Nom(s) :</span> {{ strtoupper($user->nom) }}</div>
            <div><span class="info-label">Prénom(s) :</span> {{ ucfirst($user->prenoms) }}</div>
            <div><span class="info-label">Né(e) le :</span> {{ \Carbon\Carbon::parse($user->date_naissance)->format('d/m/Y') }}</div>
            <div><span class="info-label">Profession :</span> {{ $user->profession ?? 'Non spécifiée' }}</div>
            <div><span class="info-label">Domicile :</span> {{ $user->address }}</div>
            <div><span class="info-label">Lien avec l'enfant :</span> [À préciser]</div>
        </div>

        <div class="info-section">
            <div class="parents-info">
                <div class="pere">
                    <strong>PÈRE :</strong><br>
                    <span class="info-label">Nom :</span> {{ $user->father_name ?? '[À remplir]' }}<br>
                    <span class="info-label">Profession :</span> {{ $user->father_profession ?? '[À remplir]' }}<br>
                    <span class="info-label">Domicile :</span> {{ $user->father_address ?? '[À remplir]' }}
                </div>
                <div class="mere">
                    <strong>MÈRE :</strong><br>
                    <span class="info-label">Nom :</span> {{ $user->mother_name ?? '[À remplir]' }}<br>
                    <span class="info-label">Profession :</span> {{ $user->mother_profession ?? '[À remplir]' }}<br>
                    <span class="info-label">Domicile :</span> {{ $user->mother_address ?? '[À remplir]' }}
                </div>
            </div>
        </div>

        <p>Déclaration faite conformément aux dispositions légales en vigueur.</p>

        <div style="margin-top: 30px;">
            <div><span class="info-label">Numéro de référence :</span> {{ $reference_number }}</div>
            <div><span class="info-label">Date de déclaration :</span> {{ $date_generation }}</div>
        </div>
    </div>

    <div class="signature-section">
        <p>{{ $commune }}, le {{ $date_generation }}</p>
        <div style="display: flex; justify-content: space-between; margin-top: 30px;">
            <div style="text-align: center;">
                <p><strong>Le Déclarant</strong></p>
                <div style="height: 60px;"></div>
                <p>{{ strtoupper($user->nom) }} {{ ucfirst($user->prenoms) }}</p>
            </div>
            <div style="text-align: center;">
                <p><strong>Le Maire</strong></p>
                <div style="height: 60px;"></div>
                <p><strong>[Nom du Maire]</strong></p>
                <p style="font-size: 12px;">Cachet et signature</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Document généré électroniquement - Référence: {{ $reference_number }} - {{ $date_generation }}</p>
        <p>{{ $commune }} - Adresse de la mairie - Téléphone: [numéro] - Email: [email]</p>
    </div>
</body>
</html>
