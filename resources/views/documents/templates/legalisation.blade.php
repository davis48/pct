<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Légalisation de Document</title>
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
        .legalization-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #0066cc;
            margin: 20px 0;
        }
        .document-details {
            background-color: #fff3cd;
            padding: 20px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
        .certification-box {
            background-color: #d4edda;
            padding: 20px;
            border: 2px solid #28a745;
            border-radius: 5px;
            margin: 30px 0;
            text-align: center;
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
        .stamp-area {
            border: 2px dashed #ccc;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="watermark">LÉGALISÉ</div>
    
    <div class="header">
        <div class="logo">
            <div style="width: 80px; height: 80px; background-color: #0066cc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                UVCI
            </div>
        </div>
        <div class="title">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
        <div class="subtitle">Union - Travail - Progrès</div>
        <div class="subtitle">MAIRIE D'ABIDJAN</div>
        <div class="subtitle">SERVICE DE LÉGALISATION</div>
    </div>    <div class="document-info">
        <strong>Référence :</strong> {{ $reference_number }}<br>
        <strong>Date :</strong> {{ $date_generation->format('d/m/Y') }}<br>
        <strong>Heure :</strong> {{ $date_generation->format('H:i') }}
    </div>

    <div class="document-title">
        LÉGALISATION DE DOCUMENT
    </div>

    <div class="content">
        <div class="legalization-info">
            <h3 style="margin-top: 0; color: #0066cc;">DEMANDEUR</h3>
            <strong>Nom et Prénoms :</strong> {{ $form_data['nom'] ?? 'Non renseigné' }}<br>
            <strong>Date de naissance :</strong> {{ isset($form_data['date_naissance']) ? \Carbon\Carbon::parse($form_data['date_naissance'])->format('d/m/Y') : 'Non renseignée' }}<br>
            <strong>Lieu de naissance :</strong> {{ $form_data['lieu_naissance'] ?? 'Non renseigné' }}<br>
            <strong>Profession :</strong> {{ $form_data['profession'] ?? 'Non renseignée' }}<br>
            <strong>Domicile :</strong> {{ $form_data['adresse'] ?? 'Non renseigné' }}<br>
            <strong>Numéro CNI :</strong> {{ $form_data['numero_cni'] ?? 'Non renseigné' }}
        </div>

        <div class="document-details">
            <h3 style="margin-top: 0; color: #ffc107;">DOCUMENT À LÉGALISER</h3>
            <strong>Type de document :</strong> {{ $form_data['document_type'] ?? 'Document administratif' }}<br>
            <strong>Date du document original :</strong> {{ isset($form_data['document_date']) ? \Carbon\Carbon::parse($form_data['document_date'])->format('d/m/Y') : 'Non renseignée' }}<br>
            <strong>Autorité émettrice :</strong> {{ $form_data['issuing_authority'] ?? 'Administration compétente' }}<br>
            <strong>Numéro du document :</strong> {{ $form_data['document_number'] ?? 'Non renseigné' }}<br>
            <strong>Motif de la demande :</strong> {{ $form_data['motif_demande'] ?? 'Usage administratif' }}<br>
            <strong>Destination :</strong> {{ $form_data['destination'] ?? 'Non précisée' }}
        </div>

        <div class="certification-box">
            <h3 style="margin-top: 0; color: #28a745;">CERTIFICATION DE LÉGALISATION</h3>
            <p>Je soussigné(e), <strong>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</strong>, Maire de la Commune d'Abidjan, certifie que :</p>
            <p><strong>LA SIGNATURE ET LA QUALITÉ</strong> de la personne qui a signé le document ci-dessus mentionné sont conformes aux spécimens déposés en Mairie.</p>
            <p><strong>LE CACHET OU SCEAU</strong> apposé sur ledit document est conforme à celui utilisé par l'autorité compétente.</p>
        </div>

        <div class="legal-notice">
            <h4 style="margin-top: 0; color: #17a2b8;">CONDITIONS DE LÉGALISATION</h4>
            <p>Cette légalisation ne porte que sur l'authenticité de la signature et du cachet ou sceau. Elle ne préjuge en rien du contenu du document légalisé.</p>
            <p>La présente légalisation a été effectuée conformément aux dispositions légales en vigueur.</p>
        </div>        <p><strong>Motif de la demande :</strong> {{ $data['motif_demande'] ?? 'Usage administratif' }}</p>

        <p><strong>Destination :</strong> {{ $data['destination'] ?? 'Administration publique' }}</p>

        <div class="stamp-area">
            <p><strong>CACHET DE LÉGALISATION</strong></p>
            <p>MAIRIE D'ABIDJAN</p>
            <p>LÉGALISÉ LE {{ is_string($date_generation) ? $date_generation : $date_generation->format('d/m/Y') }}</p>
            <p>N° {{ $reference_number }}</p>
            <div style="height: 40px; border: 1px solid #ccc; margin: 10px auto; width: 120px; display: flex; align-items: center; justify-content: center;">
                CACHET OFFICIEL
            </div>
        </div>

        <div class="legal-notice">
            <p><strong>VALIDITÉ :</strong> Cette légalisation est valable selon les dispositions légales du pays de destination.</p>
            <p><strong>APOSTILLE :</strong> Pour une utilisation à l'étranger, une apostille peut être nécessaire auprès du Ministère de la Justice.</p>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p><strong>Le Maire</strong></p>
            <div style="height: 60px;"></div>
            <p>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</p>
            <p><em>Cachet et signature</em></p>
        </div>
    </div>

    <div class="footer">
        <p>Document généré électroniquement le {{ is_string($date_generation) ? $date_generation . ' à ' . date('H:i') : $date_generation->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Service de Légalisation - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $reference_number }}</p>
    </div>
</body>
</html>
