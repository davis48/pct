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
        EXTRAIT D'ACTE DE NAISSANCE
    </div>    <!-- Registry Info -->
    <div style="background-color: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 20px 0;">
        <strong>Registre des Naissances :</strong> Année {{ isset($data['date_of_birth']) ? date('Y', strtotime($data['date_of_birth'])) : now()->year }}<br>
        <strong>Numéro de registre :</strong> {{ $data['registry_number'] ?? 'Non renseigné' }}<br>
        <strong>Acte N° :</strong> {{ $data['registration_number'] ?? 'REF-' . now()->format('YmdHis') }}<br>
        <strong>Date d'enregistrement :</strong> {{ isset($data['registration_date']) ? date('d/m/Y', strtotime($data['registration_date'])) : 'Non renseignée' }}<br>
        <strong>Centre d'État Civil :</strong> Mairie d'Abidjan
    </div>

    <!-- Main Content -->
    <div style="margin: 40px 0; text-align: justify;">
        <p>Je soussigné(e), <strong>Maire d'Abidjan</strong>, Officier d'État Civil de la Commune d'Abidjan, certifie qu'il a été dressé dans les registres de l'état civil de cette commune l'acte de naissance suivant :</p>

        <!-- Birth Information -->
        <div style="background-color: #f8f9fa; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #28a745;">INFORMATIONS DE NAISSANCE</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div>
                    <p><strong>Nom et Prénoms :</strong><br>{{ $data['name'] ?? 'Non renseigné' }}</p>
                    <p><strong>Sexe :</strong><br>{{ $data['gender'] ?? 'Non renseigné' }}</p>
                    <p><strong>Date de naissance :</strong><br>{{ isset($data['date_of_birth']) ? date('d/m/Y', strtotime($data['date_of_birth'])) : 'Non renseignée' }}</p>
                    @if(isset($data['birth_time']) && $data['birth_time'])
                    <p><strong>Heure de naissance :</strong><br>{{ $data['birth_time'] }}</p>
                    @endif
                </div>
                <div>
                    <p><strong>Lieu de naissance :</strong><br>{{ $data['place_of_birth'] ?? 'Non renseigné' }}</p>
                    <p><strong>Nationalité :</strong><br>{{ $data['nationality'] ?? 'Ivoirienne' }}</p>                    @if(isset($data['declarant_name']) && $data['declarant_name'])
                    <p><strong>Déclarant :</strong><br>{{ $data['declarant_name'] }}</p>
                    @endif
                    <p><strong>Date d'enregistrement :</strong><br>{{ isset($data['registration_date']) ? date('d/m/Y', strtotime($data['registration_date'])) : 'Non renseignée' }}</p>
                </div>
            </div>
        </div>

        <!-- Parents Information -->
        <div style="background-color: #fff3cd; padding: 20px; border-left: 4px solid #ffc107; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #ffc107;">FILIATION</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 15px;">
                <div style="background-color: rgba(0, 123, 255, 0.1); padding: 15px; border-radius: 5px;">
                    <h4 style="color: #007bff; margin-top: 0;">PÈRE</h4>
                    <p><strong>Nom et Prénoms :</strong><br>{{ $data['father_name'] ?? 'Non renseigné' }}</p>
                    @if(isset($data['father_profession']) && $data['father_profession'])
                    <p><strong>Profession :</strong><br>{{ $data['father_profession'] }}</p>
                    @endif
                </div>
                
                <div style="background-color: rgba(220, 53, 69, 0.1); padding: 15px; border-radius: 5px;">
                    <h4 style="color: #dc3545; margin-top: 0;">MÈRE</h4>
                    <p><strong>Nom et Prénoms :</strong><br>{{ $data['mother_name'] ?? 'Non renseigné' }}</p>
                    @if(isset($data['mother_profession']) && $data['mother_profession'])
                    <p><strong>Profession :</strong><br>{{ $data['mother_profession'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <p>Le présent extrait est conforme aux registres de l'état civil et est délivré pour servir et valoir ce que de droit.</p>

        <!-- Validity -->
        <div style="background-color: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 20px 0;">
            <p><strong>VALIDITÉ :</strong> Cet extrait est valable pendant une durée de trois (3) mois à compter de sa date de délivrance.</p>
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
