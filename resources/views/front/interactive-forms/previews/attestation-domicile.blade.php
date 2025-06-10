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
        ATTESTATION DE DOMICILE
    </div>

    <!-- Main Content -->
    <div style="margin: 40px 0; text-align: justify;">
        <p>Je soussigné(e), <strong>Maire d'Abidjan</strong>, Maire de la Commune d'Abidjan, certifie que :</p>

        <!-- Personal Information -->
        <div style="background-color: #f8f9fa; padding: 20px; border-left: 4px solid #0066cc; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #0066cc;">IDENTITÉ DU DEMANDEUR</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                <div>
                    <p><strong>Nom et Prénoms :</strong><br>{{ $data['name'] ?? 'Non renseigné' }}</p>
                    <p><strong>Date de naissance :</strong><br>{{ isset($data['date_of_birth']) ? date('d/m/Y', strtotime($data['date_of_birth'])) : 'Non renseignée' }}</p>
                    <p><strong>Lieu de naissance :</strong><br>{{ $data['place_of_birth'] ?? 'Non renseigné' }}</p>
                </div>
                <div>
                    <p><strong>Nationalité :</strong><br>{{ $data['nationality'] ?? 'Ivoirienne' }}</p>
                    @if(isset($data['profession']) && $data['profession'])
                    <p><strong>Profession :</strong><br>{{ $data['profession'] }}</p>
                    @endif
                    @if(isset($data['id_number']) && $data['id_number'])
                    <p><strong>Pièce d'identité :</strong><br>{{ $data['id_number'] }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Residence Information -->
        <div style="background-color: #d4edda; padding: 20px; border-left: 4px solid #28a745; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #28a745;">DOMICILE</h3>
            
            <div style="margin-top: 15px;">
                <p><strong>Adresse :</strong><br>{{ $data['address'] ?? 'Non renseignée' }}</p>
                <p><strong>Commune/District :</strong> {{ $data['district'] ?? 'Non renseigné' }}</p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div>
                        <p><strong>Durée de résidence :</strong><br>{{ $data['residence_duration'] ?? 'Non renseignée' }}</p>
                    </div>
                    @if(isset($data['housing_type']) && $data['housing_type'])
                    <div>
                        <p><strong>Type de logement :</strong><br>{{ $data['housing_type'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Certification Statement -->
        <div style="background-color: #fff3cd; padding: 20px; border-left: 4px solid #ffc107; margin: 20px 0; text-align: center;">
            <h3 style="margin-top: 0; color: #ffc107;">CERTIFICATION</h3>
            <p style="font-size: 18px; font-weight: bold;">
                La personne susmentionnée a son domicile à l'adresse indiquée ci-dessus.
            </p>
        </div>

        @if(isset($data['purpose']) && $data['purpose'])
        <p><strong>Motif de la demande :</strong> {{ $data['purpose'] }}</p>
        @endif

        @if(isset($data['additional_info']) && $data['additional_info'])
        <div style="background-color: #e2e3e5; padding: 15px; border-left: 4px solid #6c757d; margin: 20px 0;">
            <p><strong>Informations complémentaires :</strong></p>
            <p>{{ $data['additional_info'] }}</p>
        </div>
        @endif

        <p>La présente attestation est délivrée pour servir et valoir ce que de droit.</p>

        <!-- Validity -->
        <div style="background-color: #d1ecf1; padding: 15px; border-left: 4px solid #17a2b8; margin: 20px 0;">
            <p><strong>VALIDITÉ :</strong> Cette attestation est valable pendant une durée de trois (3) mois à compter de sa date de délivrance.</p>
            <p><strong>USAGE :</strong> Ce document ne peut être utilisé que pour les fins pour lesquelles il a été demandé.</p>
        </div>
    </div>

    <!-- Signature Section -->
    <div style="margin-top: 60px; text-align: right;">
        <div style="display: inline-block; text-align: center; border-top: 1px solid #ccc; padding-top: 10px; width: 250px;">
            <p><strong>Le Maire</strong></p>
            <div style="height: 60px;"></div>
            <p>Maire d'Abidjan</p>
            <p><em>Cachet et signature</em></p>
        </div>
    </div>

    <!-- Footer -->
    <div style="margin-top: 40px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ccc; padding-top: 10px;">
        <p>Document généré électroniquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Mairie d'Abidjan - Service des Affaires Générales - Tél: +225 XX XX XX XX</p>
        <p>Ce document est authentique et vérifiable avec la référence: {{ $data['reference_number'] ?? 'REF-' . now()->format('YmdHis') }}</p>
    </div>

    <!-- Watermark -->
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 100px; color: rgba(0, 102, 204, 0.1); z-index: -1; font-weight: bold; pointer-events: none;">
        OFFICIEL
    </div>
</div>
