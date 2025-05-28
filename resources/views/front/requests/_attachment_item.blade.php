{{-- This is a partial view for rendering a single attachment item --}}
<li class="list-group-item d-flex justify-content-between align-items-center">
    @if(is_string($attachment))
        <span>{{ basename($attachment) }}</span>
        <a href="{{ asset('storage/' . $attachment) }}" class="btn btn-sm btn-outline-primary" target="_blank">
            <i class="fas fa-download me-1"></i>Télécharger
        </a>
    @elseif(is_array($attachment) && isset($attachment['name']))
        <span>{{ $attachment['name'] }}</span>
        <a href="{{ asset('storage/' . ($attachment['path'] ?? '')) }}" class="btn btn-sm btn-outline-primary" target="_blank">
            <i class="fas fa-download me-1"></i>Télécharger
        </a>
    @else
        <span>Pièce jointe #{{ $index + 1 }}</span>
        <a href="#" class="btn btn-sm btn-outline-secondary disabled">
            <i class="fas fa-exclamation-circle me-1"></i>Format non supporté
        </a>
    @endif
</li>
