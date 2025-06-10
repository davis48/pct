<div class="certificate-preview" style="max-width: 21cm; margin: 0 auto; background: white; padding: 2cm; font-family: 'Times New Roman', serif; line-height: 1.6; color: #333;">
    
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 40px; border-bottom: 3px solid #0066cc; padding-bottom: 20px;">
        <div style="width: 80px; height: 80px; background-color: #0066cc; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px; margin: 0 auto 15px;">
            UVCI
        </div>
        <div style="font-size: 24px; font-weight: bold; color: #0066cc; margin: 10px 0;">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
        <div style="font-size: 16px; color: #666;">Union - Travail - Progrès</div>
        <div style="font-size: 16px; color: #666;">MAIRIE D'ABIDJAN</div>
        <div style="font-size: 16px; color: #666;">SERVICE D'ÉTAT CIVIL</div>
    </div>

    <!-- Document Info -->
    <div style="text-align: right; margin-bottom: 30px; font-size: 12px;">
        <strong>Référence :</strong> {{ $data['reference_number'] ?? 'REF-' . now()->format('YmdHis') }}<br>
        <strong>Date :</strong> {{ now()->format('d/m/Y') }}<br>
        <strong>Heure :</strong> {{ now()->format('H:i') }}
    </div>

    <!-- Document Title -->
    <div style="text-align: center; font-size: 20px; font-weight: bold; text-decoration: underline; margin: 30px 0; color: #0066cc;">
        CERTIFICAT DE CÉLIBAT
    </div>

    <!-- Main Content -->
    <div style="margin: 40px 0; text-align: justify;">
        <p>Je soussigné(e), <strong>Maire d'Abidjan</strong>, Officier d'État Civil de la Commune d'Abidjan, certifie que :</p>

        <!-- Personal Info -->
        <div style="background-color: #f8f9fa; padding: 20px; border-left: 4px solid #0066cc; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #0066cc;">INFORMATIONS PERSONNELLES</h3>
            <p><strong>Nom et Prénoms :</strong> {{ $data['name'] ?? 'Non renseigné' }}</p>
            <p><strong>Date de naissance :</strong> {{ isset($data['date_of_birth']) ? date('d/m/Y', strtotime($data['date_of_birth'])) : 'Non renseignée' }}</p>
            <p><strong>Lieu de naissance :</strong> {{ $data['place_of_birth'] ?? 'Non renseigné' }}</p>
            <p><strong>Nationalité :</strong> {{ $data['nationality'] ?? 'Ivoirienne' }}</p>
            <p><strong>Profession :</strong> {{ $data['profession'] ?? 'Non renseignée' }}</p>
            <p><strong>Domicile :</strong> {{ $data['address'] ?? 'Non renseigné' }}</p>
            @if(isset($data['father_name']) && $data['father_name'])
                <p><strong>Père :</strong> {{ $data['father_name'] }}</p>
            @endif
            @if(isset($data['mother_name']) && $data['mother_name'])
                <p><strong>Mère :</strong> {{ $data['mother_name'] }}</p>
            @endif
        </div>

        <!-- Certification Statement -->
        <div style="background-color: #d4edda; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0; text-align: center;">
            <h3 style="margin-top: 0; color: #28a745;">CERTIFICATION</h3>
            <p style="font-size: 18px; font-weight: bold;">
                La personne susmentionnée est CÉLIBATAIRE au jour de la délivrance du présent certificat.
            </p>
        </div>

        <p>Après vérification dans les registres de l'état civil, il ressort qu'aucun acte de mariage n'a été dressé au nom de l'intéressé(e).</p>

        <p>Le présent certificat est conforme aux registres de l'état civil et est délivré pour servir et valoir ce que de droit.</p>

        <!-- Validity -->
        <div style="background-color: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 20px 0;">
            <p><strong>VALIDITÉ :</strong> Ce certificat est valable pendant une durée de trois (3) mois à compter de sa date de délivrance.</p>
            <p><strong>USAGE :</strong> Ce document ne peut être utilisé que pour les fins pour lesquelles il a été demandé.</p>
        </div>
    </div>

    <!-- Signature Section -->
    <div style="margin-top: 60px; text-align: right;">
        <div style="display: inline-block; text-align: center; border-top: 1px solid #ccc; padding-top: 10px; width: 250px;">
            <p><strong>L'Officier d'État Civil</strong></p>
            <div style="height: 60px;"></div>
            <p>Maire d'Abidjan</p>
            <p><em>Cachet et signature</em></p>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 40px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ccc; padding-top: 10px;">
        <p>Document généré électroniquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Service d'État Civil - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $data['reference_number'] ?? 'REF-' . now()->format('YmdHis') }}</p>
    </div>

    <!-- Watermark -->
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 100px; color: rgba(0, 102, 204, 0.1); z-index: -1; font-weight: bold; pointer-events: none;">
        OFFICIEL
    </div>
</div>
