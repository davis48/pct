{{-- Template réutilisable pour le cachet électronique - Aligné à droite sous le nom du maire --}}
<div class="electronic-seal-container" style="text-align: right; margin-top: 20px;">
    @if($electronic_seal_image)
        <img src="{{ $electronic_seal_image }}" alt="Cachet électronique" 
             style="max-width: 200px; max-height: 120px; border: 1px solid #ddd; background: white;">
    @else
        <div class="electronic-seal-fallback" style="display: inline-block; margin: 0;">
            <div class="seal-header">
                <strong>CACHET ÉLECTRONIQUE</strong>
            </div>
            <div class="seal-body">
                <p>Document certifié conforme</p>
                <p>Autorité: {{ $municipality ?? 'MAIRIE DE COCODY' }}</p>
                <p>Signé par: {{ $mayor_name ?? 'PCT_MAYOR' }}</p>
                <p>Date: {{ $date_generation->format('d/m/Y H:i:s') }}</p>
                @if(isset($electronic_seal['verification_code']))
                    <div class="verification-code">
                        <strong>Code: {{ $electronic_seal['verification_code'] }}</strong>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<style>
    .electronic-seal-container {
        margin: 20px 0;
        text-align: center;
    }
    
    .electronic-seal-fallback {
        border: 2px dashed #2c5aa0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 15px;
        border-radius: 8px;
        margin: 10px auto;
        max-width: 220px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .seal-header {
        font-size: 12px;
        color: #2c5aa0;
        font-weight: bold;
        margin-bottom: 10px;
        border-bottom: 1px solid #2c5aa0;
        padding-bottom: 5px;
    }
    
    .seal-body p {
        margin: 3px 0;
        font-size: 10px;
        color: #666;
        line-height: 1.2;
    }
    
    .verification-code {
        margin-top: 8px;
        padding: 5px;
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
        font-size: 9px;
        color: #d32f2f;
    }
</style>
