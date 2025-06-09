<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Administratif</title>
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
        .request-details {
            background-color: #fff3cd;
            padding: 20px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
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
        <div class="subtitle">SERVICES ADMINISTRATIFS</div>
    </div>

    <div class="document-info">
        <strong>Référence :</strong> {{ $reference }}<br>
        <strong>Date :</strong> {{ $date_generation->format('d/m/Y') }}<br>
        <strong>Heure :</strong> {{ $date_generation->format('H:i') }}
    </div>

    <div class="document-title">
        {{ strtoupper($document->title ?? 'DOCUMENT ADMINISTRATIF') }}
    </div>

    <div class="content">
        <p>Je soussigné(e), <strong>{{ $request->processed_by ?? 'Responsable des Services Administratifs' }}</strong>, certifie que la demande ci-dessous a été traitée et approuvée :</p>

        <div class="citizen-info">
            <h3 style="margin-top: 0; color: #0066cc;">DEMANDEUR</h3>
            <strong>Nom et Prénoms :</strong> {{ $user->name }}<br>
            <strong>Date de naissance :</strong> {{ $user->date_of_birth ?? 'Non renseignée' }}<br>
            <strong>Lieu de naissance :</strong> {{ $user->place_of_birth ?? 'Non renseigné' }}<br>
            <strong>Profession :</strong> {{ $user->profession ?? 'Non renseignée' }}<br>
            <strong>Domicile :</strong> {{ $user->address ?? 'Non renseigné' }}<br>
            <strong>Numéro CNI :</strong> {{ $user->cin_number ?? 'Non renseigné' }}<br>
            <strong>Contact :</strong> {{ $user->phone ?? $user->email ?? 'Non renseigné' }}
        </div>

        <div class="request-details">
            <h3 style="margin-top: 0; color: #ffc107;">DÉTAILS DE LA DEMANDE</h3>
            <strong>Type de demande :</strong> {{ $document->title ?? 'Document administratif' }}<br>
            <strong>Date de demande :</strong> {{ $request->created_at->format('d/m/Y à H:i') }}<br>
            <strong>Statut :</strong> <span style="color: #28a745; font-weight: bold;">{{ strtoupper($request->status) }}</span><br>
            <strong>Date d'approbation :</strong> {{ $request->updated_at->format('d/m/Y à H:i') }}<br>
            @if($request->reason)
            <strong>Motif :</strong> {{ $request->reason }}<br>
            @endif
            @if($request->notes)
            <strong>Observations :</strong> {{ $request->notes }}<br>
            @endif
        </div>

        <p>La présente certification atteste que toutes les conditions requises ont été remplies et que la demande a été dûment approuvée par les services compétents.</p>

        <div class="legal-notice">
            <h4 style="margin-top: 0; color: #17a2b8;">CONDITIONS D'UTILISATION</h4>
            <p>Ce document est délivré conformément aux dispositions réglementaires en vigueur.</p>
            <p>Il ne peut être utilisé que pour les fins pour lesquelles il a été demandé.</p>
            <p>Sa validité est de trois (3) mois à compter de sa date de délivrance, sauf dispositions contraires spécifiques.</p>
        </div>

        <p>Le présent document est délivré pour servir et valoir ce que de droit.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p><strong>L'Autorité Compétente</strong></p>
            <div style="height: 60px;"></div>
            <p>{{ $request->processed_by ?? 'Responsable des Services' }}</p>
            <p><em>Cachet et signature</em></p>
        </div>
    </div>

    <div class="footer">
        <p>Document généré électroniquement le {{ $date_generation->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Services Administratifs - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $reference }}</p>
    </div>
</body>
</html>
