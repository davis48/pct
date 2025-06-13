<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de Mariage</title>
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
        .marriage-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #0066cc;
            margin: 20px 0;
        }
        .spouses-info {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .spouse-box {
            width: 48%;
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #dc3545;
        }
        .ceremony-details {
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
        .registry-info {
            background-color: #d1ecf1;
            padding: 15px;
            border-left: 4px solid #17a2b8;
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
    </div>    <div class="document-info">
        <strong>Référence :</strong> {{ $reference_number }}<br>
        <strong>Date :</strong> {{ $date_generation->format('d/m/Y') }}<br>
        <strong>Heure :</strong> {{ $date_generation->format('H:i') }}
    </div>

    <div class="document-title">
        CERTIFICAT DE MARIAGE
    </div>

    <div class="content">
        <div class="registry-info">
            <strong>Registre de Mariage :</strong> Année {{ $date_generation->year }}<br>
            <strong>Acte N° :</strong> {{ $reference_number }}<br>
            <strong>Centre d'État Civil :</strong> Mairie d'Abidjan
        </div>

        <p>Je soussigné(e), <strong>{{ $request->processed_by ?? 'Maire d\'Abidjan' }}</strong>, Officier d'État Civil de la Commune d'Abidjan, certifie que le mariage a été célébré entre :</p>

        <div class="spouses-info">
            <div class="spouse-box">
                <h4 style="margin-top: 0; color: #dc3545;">L'ÉPOUX</h4>
                <strong>Nom et Prénoms :</strong> {{ ($form_data['nom_epoux'] ?? '') . ' ' . ($form_data['prenoms_epoux'] ?? '') }}<br>
                <strong>Date de naissance :</strong> {{ isset($form_data['date_naissance_epoux']) ? \Carbon\Carbon::parse($form_data['date_naissance_epoux'])->format('d/m/Y') : 'Non renseignée' }}<br>
                <strong>Lieu de naissance :</strong> {{ $form_data['lieu_naissance_epoux'] ?? 'Non renseigné' }}<br>
                <strong>Profession :</strong> {{ $form_data['profession_epoux'] ?? 'Non renseignée' }}<br>
                <strong>Domicile :</strong> {{ $form_data['domicile_epoux'] ?? 'Non renseigné' }}
            </div>
            
            <div class="spouse-box">
                <h4 style="margin-top: 0; color: #dc3545;">L'ÉPOUSE</h4>
                <strong>Nom et Prénoms :</strong> {{ ($form_data['nom_epouse'] ?? '') . ' ' . ($form_data['prenoms_epouse'] ?? '') }}<br>
                <strong>Date de naissance :</strong> {{ isset($form_data['date_naissance_epouse']) ? \Carbon\Carbon::parse($form_data['date_naissance_epouse'])->format('d/m/Y') : 'Non renseignée' }}<br>
                <strong>Lieu de naissance :</strong> {{ $form_data['lieu_naissance_epouse'] ?? 'Non renseigné' }}<br>
                <strong>Profession :</strong> {{ $form_data['profession_epouse'] ?? 'Non renseignée' }}<br>
                <strong>Domicile :</strong> {{ $form_data['domicile_epouse'] ?? 'Non renseigné' }}
            </div>
        </div>

        <div class="ceremony-details">
            <h4 style="margin-top: 0; color: #ffc107;">DÉTAILS DE LA CÉRÉMONIE</h4>
            <strong>Date du mariage :</strong> {{ isset($form_data['date_mariage']) ? \Carbon\Carbon::parse($form_data['date_mariage'])->format('d/m/Y') : 'Non renseignée' }}<br>
            <strong>Lieu du mariage :</strong> {{ $form_data['lieu_mariage'] ?? 'Non renseigné' }}<br>
            <strong>Régime matrimonial :</strong> {{ $form_data['regime_matrimonial'] ?? 'Non renseigné' }}<br>
            <strong>Officiant :</strong> {{ $form_data['officiant'] ?? $request->processed_by ?? 'Maire d\'Abidjan' }}
        </div>
                <strong>Date de naissance :</strong> {{ $user->date_of_birth ?? 'Non renseignée' }}<br>
                <strong>Lieu de naissance :</strong> {{ $user->place_of_birth ?? 'Non renseigné' }}<br>
                <strong>Profession :</strong> {{ $user->profession ?? 'Non renseignée' }}<br>
                <strong>Domicile :</strong> {{ $user->address ?? 'Non renseigné' }}<br>
                <strong>Nationalité :</strong> {{ $user->nationality ?? 'Ivoirienne' }}<br>
                <strong>Père :</strong> {{ $user->father_name ?? 'Non renseigné' }}<br>
                <strong>Mère :</strong> {{ $user->mother_name ?? 'Non renseigné' }}
            </div>
            
            <div class="spouse-box">
                <h4 style="margin-top: 0; color: #dc3545;">L'ÉPOUSE</h4>
                <strong>Nom et Prénoms :</strong> {{ $user->spouse_name ?? 'Non renseigné' }}<br>
                <strong>Date de naissance :</strong> {{ $user->spouse_birth_date ?? 'Non renseignée' }}<br>
                <strong>Lieu de naissance :</strong> {{ $user->spouse_birth_place ?? 'Non renseigné' }}<br>
                <strong>Profession :</strong> {{ $user->spouse_profession ?? 'Non renseignée' }}<br>
                <strong>Domicile :</strong> {{ $user->spouse_address ?? 'Non renseigné' }}<br>
                <strong>Nationalité :</strong> {{ $user->spouse_nationality ?? 'Ivoirienne' }}<br>
                <strong>Père :</strong> {{ $user->spouse_father_name ?? 'Non renseigné' }}<br>
                <strong>Mère :</strong> {{ $user->spouse_mother_name ?? 'Non renseigné' }}
            </div>
        </div>

        <div class="ceremony-details">
            <h3 style="margin-top: 0; color: #ffc107;">DÉTAILS DE LA CÉRÉMONIE</h3>
            <strong>Date de mariage :</strong> {{ $user->marriage_date ?? $request->created_at->format('d/m/Y') }}<br>
            <strong>Lieu de célébration :</strong> Mairie d'Abidjan<br>
            <strong>Heure de la cérémonie :</strong> {{ $user->marriage_time ?? '10h00' }}<br>
            <strong>Régime matrimonial :</strong> {{ $user->matrimonial_regime ?? 'Communauté de biens réduite aux acquêts' }}<br>
            <strong>Officiant :</strong> {{ $request->processed_by ?? 'Maire d\'Abidjan' }}
        </div>

        <div class="marriage-info">
            <h3 style="margin-top: 0; color: #0066cc;">TÉMOINS</h3>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <strong>Témoin de l'époux :</strong><br>
                    {{ $user->witness1_name ?? 'Non renseigné' }}<br>
                    {{ $user->witness1_profession ?? '' }}
                </div>
                <div style="width: 48%;">
                    <strong>Témoin de l'épouse :</strong><br>
                    {{ $user->witness2_name ?? 'Non renseigné' }}<br>
                    {{ $user->witness2_profession ?? '' }}
                </div>
            </div>
        </div>

        <p><strong>Mentions marginales :</strong> {{ $user->marginal_notes ?? 'Néant' }}</p>

        <p>Le présent certificat est conforme aux registres de l'état civil et est délivré pour servir et valoir ce que de droit.</p>

        <div class="registry-info">
            <p><strong>VALIDITÉ :</strong> Ce certificat est valable pendant une durée de trois (3) mois à compter de sa date de délivrance.</p>
            <p><strong>USAGE :</strong> Ce document ne peut être utilisé que pour les fins pour lesquelles il a été demandé.</p>
        </div>
    </div>    <div class="signature-section">
        <div class="signature-box">
            <p><strong>L'Officier d'État Civil</strong></p>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin: 20px 0;">
                @if($official_seal)
                    <div style="text-align: center;">
                        <img src="{{ $official_seal }}" alt="Cachet officiel" style="max-width: 120px; max-height: 120px;">
                    </div>
                @endif
                
                @if($mayor_signature)
                    <div style="text-align: center;">
                        <img src="{{ $mayor_signature }}" alt="Signature du maire" style="max-width: 150px; max-height: 80px;">
                    </div>
                @endif
            </div>
            
            <p><strong>PCT_MAYOR</strong></p>
            @if(!$mayor_signature || !$official_seal)
                <p><em>Cachet et signature</em></p>
            @endif
            
            {{-- Cachet électronique déplacé sous le nom du maire, aligné à droite --}}
            <div style="text-align: right; margin-top: 20px;">
                @if($electronic_seal_image)
                    <img src="{{ $electronic_seal_image }}" alt="Cachet électronique" style="max-width: 200px; max-height: 120px; border: 1px solid #ddd;">
                @else
                    <div style="display: inline-block; text-align: center; margin: 0; padding: 12px; border: 2px dashed #0066cc; background-color: #f8f9fa; border-radius: 6px; max-width: 220px;">
                        <p style="margin: 0; font-size: 11px; color: #0066cc;"><strong>CACHET ÉLECTRONIQUE</strong></p>
                        <p style="margin: 3px 0; font-size: 9px; color: #666;">Document certifié conforme</p>
                        <p style="margin: 3px 0; font-size: 9px; color: #666;">Signé par: PCT_MAYOR</p>
                        <p style="margin: 3px 0; font-size: 9px; color: #666;">Date: {{ $date_generation->format('d/m/Y H:i') }}</p>
                        @if(isset($electronic_seal['verification_code']))
                            <p style="margin: 5px 0; font-size: 8px; color: #d32f2f; font-family: monospace;"><strong>Code: {{ $electronic_seal['verification_code'] }}</strong></p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div><div class="footer">
        <p>Document généré électroniquement le {{ is_string($date_generation) ? $date_generation . ' à ' . date('H:i') : $date_generation->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Service d'État Civil - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $reference_number }}</p>
    </div>
</body>
</html>
