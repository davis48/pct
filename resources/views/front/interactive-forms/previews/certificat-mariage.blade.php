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
        CERTIFICAT DE MARIAGE
    </div>

    <!-- Registry Info -->
    <div style="background-color: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 20px 0;">
        <strong>Registre de Mariage :</strong> Année {{ now()->year }}<br>
        <strong>Acte N° :</strong> {{ $data['reference_number'] ?? 'REF-' . now()->format('YmdHis') }}<br>
        <strong>Centre d'État Civil :</strong> Mairie d'Abidjan
    </div>

    <!-- Main Content -->
    <div style="margin: 40px 0; text-align: justify;">
        <p>Je soussigné(e), <strong>Maire d'Abidjan</strong>, Officier d'État Civil de la Commune d'Abidjan, certifie que le mariage a été célébré entre :</p>

        <!-- Spouses Info -->
        <div style="display: flex; justify-content: space-between; margin: 20px 0; gap: 20px;">
            <!-- Époux -->
            <div style="width: 48%; background-color: #f8f9fa; padding: 15px; border-left: 4px solid #dc3545;">
                <h4 style="margin-top: 0; color: #dc3545;">L'ÉPOUX</h4>
                <strong>Nom et Prénoms :</strong> {{ $data['name'] ?? 'Non renseigné' }}<br>
                <strong>Date de naissance :</strong> {{ $data['date_of_birth'] ?? 'Non renseignée' }}<br>
                <strong>Lieu de naissance :</strong> {{ $data['place_of_birth'] ?? 'Non renseigné' }}<br>
                <strong>Profession :</strong> {{ $data['profession'] ?? 'Non renseignée' }}<br>
                <strong>Domicile :</strong> {{ $data['address'] ?? 'Non renseigné' }}<br>
                <strong>Nationalité :</strong> {{ $data['nationality'] ?? 'Ivoirienne' }}<br>
                <strong>Père :</strong> {{ $data['father_name'] ?? 'Non renseigné' }}<br>
                <strong>Mère :</strong> {{ $data['mother_name'] ?? 'Non renseigné' }}
            </div>
            
            <!-- Épouse -->
            <div style="width: 48%; background-color: #f8f9fa; padding: 15px; border-left: 4px solid #dc3545;">
                <h4 style="margin-top: 0; color: #dc3545;">L'ÉPOUSE</h4>
                <strong>Nom et Prénoms :</strong> {{ $data['spouse_name'] ?? 'Non renseigné' }}<br>
                <strong>Date de naissance :</strong> {{ $data['spouse_birth_date'] ?? 'Non renseignée' }}<br>
                <strong>Lieu de naissance :</strong> {{ $data['spouse_birth_place'] ?? 'Non renseigné' }}<br>
                <strong>Profession :</strong> {{ $data['spouse_profession'] ?? 'Non renseignée' }}<br>
                <strong>Domicile :</strong> {{ $data['spouse_address'] ?? 'Non renseigné' }}<br>
                <strong>Nationalité :</strong> {{ $data['spouse_nationality'] ?? 'Ivoirienne' }}<br>
                <strong>Père :</strong> {{ $data['spouse_father_name'] ?? 'Non renseigné' }}<br>
                <strong>Mère :</strong> {{ $data['spouse_mother_name'] ?? 'Non renseigné' }}
            </div>
        </div>

        <!-- Ceremony Details -->
        <div style="background-color: #fff3cd; padding: 20px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #ffc107;">DÉTAILS DE LA CÉRÉMONIE</h3>
            <strong>Date de mariage :</strong> {{ isset($data['marriage_date']) ? date('d/m/Y', strtotime($data['marriage_date'])) : 'Non renseignée' }}<br>
            <strong>Lieu de célébration :</strong> Mairie d'Abidjan<br>
            <strong>Heure de la cérémonie :</strong> {{ $data['marriage_time'] ?? '10h00' }}<br>
            <strong>Régime matrimonial :</strong> {{ $data['matrimonial_regime'] ?? 'Communauté de biens réduite aux acquêts' }}<br>
            <strong>Officiant :</strong> Maire d'Abidjan
        </div>

        <!-- Witnesses -->
        <div style="background-color: #f8f9fa; padding: 20px; border-left: 4px solid #0066cc; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #0066cc;">TÉMOINS</h3>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 48%;">
                    <strong>Témoin de l'époux :</strong><br>
                    {{ $data['witness1_name'] ?? 'Non renseigné' }}<br>
                    {{ $data['witness1_profession'] ?? '' }}
                </div>
                <div style="width: 48%;">
                    <strong>Témoin de l'épouse :</strong><br>
                    {{ $data['witness2_name'] ?? 'Non renseigné' }}<br>
                    {{ $data['witness2_profession'] ?? '' }}
                </div>
            </div>
        </div>

        <p><strong>Mentions marginales :</strong> {{ $data['marginal_notes'] ?? 'Néant' }}</p>

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
