@extends('layouts.app')

@section('title', 'Prévisualisation du Document')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('interactive-forms.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-eye text-blue-600 mr-2"></i>
                            Prévisualisation du Document
                        </h1>
                        <p class="text-gray-600">Votre {{ str_replace('-', ' ', $formType) }} a été généré avec succès</p>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('interactive-forms.download', [$formType, $requestId]) }}" 
                       class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Télécharger PDF
                    </a>
                    <button onclick="printDocument()" 
                            class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        <i class="fas fa-print mr-2"></i>
                        Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Document généré avec succès</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Votre document a été créé et est prêt au téléchargement. Référence: <strong>{{ $requestId }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Preview -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Preview Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Aperçu du document</h2>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Ceci est un aperçu. Le document PDF final peut légèrement différer.
                    </div>
                </div>
            </div>

            <!-- Document Content -->
            <div class="p-8" id="documentPreview">
                @if($formType === 'certificat-mariage')
                    @include('front.interactive-forms.previews.certificat-mariage', ['data' => $data])
                @elseif($formType === 'certificat-celibat')
                    @include('front.interactive-forms.previews.certificat-celibat', ['data' => $data])
                @elseif($formType === 'extrait-naissance')
                    @include('front.interactive-forms.previews.extrait-naissance', ['data' => $data])
                @elseif($formType === 'certificat-deces')
                    @include('front.interactive-forms.previews.certificat-deces', ['data' => $data])
                @elseif($formType === 'attestation-domicile')
                    @include('front.interactive-forms.previews.attestation-domicile', ['data' => $data])
                @elseif($formType === 'legalisation')
                    @include('front.interactive-forms.previews.legalisation', ['data' => $data])
                @endif
            </div>
        </div>

        <!-- Actions Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Download Options -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-download text-blue-600 mr-2"></i>
                    Options de téléchargement
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('interactive-forms.download', [$formType, $requestId]) }}" 
                       class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-file-pdf mr-2"></i>
                        PDF Original
                    </a>
                    <button onclick="saveAsImage()" 
                            class="block w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i class="fas fa-image mr-2"></i>
                        Image PNG
                    </button>
                </div>
            </div>

            <!-- Share Options -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-share text-green-600 mr-2"></i>
                    Partager
                </h3>
                <div class="space-y-3">
                    <button onclick="shareByEmail()" 
                            class="block w-full bg-green-100 text-green-700 text-center py-2 px-4 rounded-lg hover:bg-green-200 transition-colors duration-200">
                        <i class="fas fa-envelope mr-2"></i>
                        Par email
                    </button>
                    <button onclick="copyLink()" 
                            class="block w-full bg-blue-100 text-blue-700 text-center py-2 px-4 rounded-lg hover:bg-blue-200 transition-colors duration-200">
                        <i class="fas fa-link mr-2"></i>
                        Copier le lien
                    </button>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                    Prochaines étapes
                </h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <p><i class="fas fa-check text-green-500 mr-2"></i>Document généré</p>
                    <p><i class="fas fa-download text-blue-500 mr-2"></i>Télécharger le PDF</p>
                    <p><i class="fas fa-print text-gray-500 mr-2"></i>Imprimer si nécessaire</p>
                    <p><i class="fas fa-archive text-purple-500 mr-2"></i>Conserver précieusement</p>
                </div>
            </div>
        </div>

        <!-- Important Notice -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Important</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Ce document a la même valeur légale qu'un document obtenu en mairie</li>
                            <li>Conservez la référence <strong>{{ $requestId }}</strong> pour toute vérification</li>
                            <li>Le document est valable pendant 3 mois à compter de sa date de génération</li>
                            <li>En cas de perte, vous pouvez le retélécharger avec cette référence</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generate Another Document -->
        <div class="mt-8 text-center">
            <a href="{{ route('interactive-forms.index') }}" 
               class="inline-flex items-center px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Générer un autre document
            </a>
        </div>
    </div>
</div>

<script>
function printDocument() {
    window.print();
}

function saveAsImage() {
    // Functionality to convert preview to image
    alert('Fonctionnalité de sauvegarde en image à implémenter');
}

function shareByEmail() {
    const subject = encodeURIComponent('Document {{ str_replace("-", " ", $formType) }}');
    const body = encodeURIComponent('Voici mon document généré. Référence: {{ $requestId }}');
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
}

function copyLink() {
    const link = window.location.href;
    navigator.clipboard.writeText(link).then(function() {
        // Show success message
        const originalText = event.target.innerHTML;
        event.target.innerHTML = '<i class="fas fa-check mr-2"></i>Copié!';
        event.target.classList.add('bg-green-100', 'text-green-700');
        
        setTimeout(() => {
            event.target.innerHTML = originalText;
            event.target.classList.remove('bg-green-100', 'text-green-700');
            event.target.classList.add('bg-blue-100', 'text-blue-700');
        }, 2000);
    });
}

// Auto-focus on first download button for accessibility
document.addEventListener('DOMContentLoaded', function() {
    const firstDownloadButton = document.querySelector('a[href*="download"]');
    if (firstDownloadButton) {
        firstDownloadButton.focus();
    }
});
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #documentPreview, #documentPreview * {
        visibility: visible;
    }
    #documentPreview {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endsection
