{{-- Template réutilisable pour la signature et le cachet officiel --}}
<div class="official-signature-section">
    <p>{{ $municipality ?? 'Mairie' }}, le {{ $date_generation->format('d/m/Y') }}</p>
    <p><strong>{{ $signature_title ?? 'Le Maire' }}</strong></p>
    
    <div class="signature-images">
        @if($official_seal)
            <div class="seal-container">
                <img src="{{ $official_seal }}" alt="Cachet officiel" style="max-width: 120px; max-height: 120px;">
            </div>
        @endif
        
        @if($mayor_signature)
            <div class="signature-container">
                <img src="{{ $mayor_signature }}" alt="Signature du maire" style="max-width: 150px; max-height: 80px;">
            </div>
        @endif
    </div>
    
    <p><strong>{{ $mayor_name ?? 'Le Maire' }}</strong></p>
    @if(!$mayor_signature || !$official_seal)
        <p style="font-size: 12px; color: #666;">Cachet et signature</p>
    @endif
</div>

<style>
    .official-signature-section {
        margin-top: 50px;
        text-align: right;
        page-break-inside: avoid;
    }
    .official-signature-section img {
        max-width: 100%;
        height: auto;
    }
    .signature-images {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0;
        gap: 20px;
    }
    .seal-container, .signature-container {
        text-align: center;
        flex: 1;
    }
</style>
