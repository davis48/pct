<?php

namespace App\Services;

use App\Models\CitizenRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentPdfGeneratorService
{    public function generateDocument(CitizenRequest $request)
    {
        $user = $request->user;

        if (!$user) {
            throw new \Exception('Utilisateur manquant');
        }

        // Récupérer les données supplémentaires du formulaire interactif si disponibles
        $additionalData = [];
        if ($request->additional_data) {
            $additionalData = json_decode($request->additional_data, true);
        }

        // Déterminer le type de document à générer
        $documentType = $this->determineDocumentType($request, $additionalData);

        // Choisir le template approprié selon le type de document
        switch ($documentType) {
            case 'extrait-naissance':
                return $this->generateExtraitNaissance($request, $user, $additionalData);
            case 'certificat-mariage':
                return $this->generateCertificatMariage($request, $user, $additionalData);
            case 'declaration-naissance':
                return $this->generateDeclarationNaissance($request, $user, $additionalData);
            case 'certificat-celibat':
                return $this->generateCertificatCelibat($request, $user, $additionalData);
            case 'certificat-deces':
                return $this->generateCertificatDeces($request, $user, $additionalData);
            case 'attestation-domicile':
                return $this->generateAttestationDomicile($request, $user, $additionalData);
            case 'legalisation':
                return $this->generateLegalisation($request, $user, $additionalData);
            default:
                throw new \Exception("Type de document non supporté : {$documentType}");
        }
    }

    /**
     * Déterminer le type de document à générer
     */
    private function determineDocumentType(CitizenRequest $request, array $additionalData = [])
    {
        // Si c'est un formulaire interactif, utiliser le form_type
        if (isset($additionalData['form_type'])) {
            return $additionalData['form_type'];
        }

        // Si c'est un formulaire interactif avec generated_via
        if (isset($additionalData['generated_via']) && $additionalData['generated_via'] === 'interactive_form') {
            // Mapper le type de request au type de document
            $typeMapping = [
                'extrait-acte' => 'extrait-naissance',
                'mariage' => 'certificat-mariage',
                'certificat' => 'certificat-celibat',
                'attestation' => 'attestation-domicile',
                'legalisation' => 'legalisation'
            ];

            return $typeMapping[$request->type] ?? $request->type;
        }

        // Sinon, utiliser le document associé (formulaires traditionnels)
        if ($request->document) {
            $documentNameMapping = [
                'Extrait d\'acte de naissance' => 'extrait-naissance',
                'Certificat de mariage' => 'certificat-mariage',
                'Déclaration de naissance' => 'declaration-naissance',
                'Certificat de célibat' => 'certificat-celibat',
                'Certificat de décès' => 'certificat-deces'
            ];

            return $documentNameMapping[$request->document->name] ?? 'extrait-naissance';
        }

        // Par défaut
        return 'extrait-naissance';
    }    private function generateExtraitNaissance(CitizenRequest $request, User $user, array $additionalData = [])
    {
        // Utiliser les données du formulaire interactif si disponibles
        $formData = $additionalData['form_data'] ?? [];

        $data = array_merge([
            'request' => $request,
            'user' => $user,
            'document_title' => 'EXTRAIT D\'ACTE DE NAISSANCE',
            'reference_number' => $request->reference_number,
            'date_generation' => now(),
            'commune' => 'Commune de [Nom de votre commune]',
        ], $formData, $this->getOfficialDocumentData());

        // Log pour tracer les données transmises au template
        Log::info('Données transmises au template de l\'extrait de naissance', $data);

        $pdf = Pdf::loadView('documents.templates.extrait-naissance', $data);
        return $pdf->download('extrait-naissance-' . $request->reference_number . '.pdf');
    }    private function generateCertificatMariage(CitizenRequest $request, User $user, array $additionalData = [])
    {
        $formData = $additionalData['form_data'] ?? [];

        $data = [
            'request' => $request,
            'user' => $user,
            'form_data' => $formData,
            'document_title' => 'CERTIFICAT DE MARIAGE',
            'reference_number' => $request->reference_number,
            'date_generation' => now(),
            'commune' => 'Commune de [Nom de votre commune]',
            // Données spécifiques du mariage
            'spouse_name' => $formData['spouse_name'] ?? '',
            'spouse_birth_date' => $formData['spouse_birth_date'] ?? '',
            'spouse_birth_place' => $formData['spouse_birth_place'] ?? '',
            'marriage_date' => $formData['marriage_date'] ?? '',
            'marriage_time' => $formData['marriage_time'] ?? '',
            'witness1_name' => $formData['witness1_name'] ?? '',
            'witness2_name' => $formData['witness2_name'] ?? '',
            'full_name' => $formData['name'] ?? $user->name,
            'date_of_birth' => $formData['date_of_birth'] ?? $user->date_of_birth,
            'place_of_birth' => $formData['place_of_birth'] ?? $user->place_of_birth,
            'nationality' => $formData['nationality'] ?? $user->nationality
        ];

        $data = array_merge($data, $this->getOfficialDocumentData());

        $pdf = Pdf::loadView('documents.templates.certificat-mariage', $data);
        return $pdf->download('certificat-mariage-' . $request->reference_number . '.pdf');
    }    private function generateDeclarationNaissance(CitizenRequest $request, User $user, array $additionalData = [])
    {
        $formData = $additionalData['form_data'] ?? [];

        $data = [
            'request' => $request,
            'user' => $user,
            'form_data' => $formData,
            'document_title' => 'DÉCLARATION DE NAISSANCE',
            'reference_number' => $request->reference_number,
            'date_generation' => now(),
            'commune' => 'Commune de [Nom de votre commune]',

            'full_name' => $formData['name'] ?? $user->name,
            'date_of_birth' => $formData['date_of_birth'] ?? $user->date_of_birth,
            'place_of_birth' => $formData['place_of_birth'] ?? $user->place_of_birth,
            'father_name' => $formData['father_name'] ?? $user->father_name,
            'mother_name' => $formData['mother_name'] ?? $user->mother_name
        ];

        $data = array_merge($data, $this->getOfficialDocumentData());

        $pdf = Pdf::loadView('documents.templates.declaration-naissance', $data);
        return $pdf->download('declaration-naissance-' . $request->reference_number . '.pdf');
    }    private function generateCertificatCelibat(CitizenRequest $request, User $user, array $additionalData = [])
    {
        $formData = $additionalData['form_data'] ?? [];

        $data = [
            'request' => $request,
            'user' => $user,
            'form_data' => $formData,
            'document_title' => 'CERTIFICAT DE CÉLIBAT',
            'reference_number' => $request->reference_number,
            'date_generation' => now(),
            'commune' => 'Commune de [Nom de votre commune]',

            'full_name' => $formData['name'] ?? $user->name,
            'date_of_birth' => $formData['date_of_birth'] ?? $user->date_of_birth,
            'place_of_birth' => $formData['place_of_birth'] ?? $user->place_of_birth,
            'nationality' => $formData['nationality'] ?? $user->nationality,
            'profession' => $formData['profession'] ?? $user->profession,
            'address' => $formData['address'] ?? $user->address
        ];

        $data = array_merge($data, $this->getOfficialDocumentData());

        $pdf = Pdf::loadView('documents.templates.certificat-celibat', $data);
        return $pdf->download('certificat-celibat-' . $request->reference_number . '.pdf');
    }    private function generateCertificatDeces(CitizenRequest $request, User $user, array $additionalData = [])
    {
        $formData = $additionalData['form_data'] ?? [];

        $data = [
            'request' => $request,
            'user' => $user,
            'form_data' => $formData,
            'document_title' => 'CERTIFICAT DE DÉCÈS',
            'reference_number' => $request->reference_number,
            'date_generation' => now(),
            'commune' => 'Commune de [Nom de votre commune]',

            'deceased_name' => $formData['deceased_name'] ?? '',
            'death_date' => $formData['death_date'] ?? '',
            'death_place' => $formData['death_place'] ?? '',
            'cause_of_death' => $formData['cause_of_death'] ?? '',
            'full_name' => $formData['name'] ?? $user->name,
            'date_of_birth' => $formData['date_of_birth'] ?? $user->date_of_birth,
            'place_of_birth' => $formData['place_of_birth'] ?? $user->place_of_birth,
            'nationality' => $formData['nationality'] ?? $user->nationality
        ];

        $data = array_merge($data, $this->getOfficialDocumentData());

        $pdf = Pdf::loadView('documents.templates.certificat-deces', $data);
        return $pdf->download('certificat-deces-' . $request->reference_number . '.pdf');
    }

    private function generateAttestationDomicile(CitizenRequest $request, User $user, array $additionalData = [])
    {
        $formData = $additionalData['form_data'] ?? [];

        $data = [
            'request' => $request,
            'user' => $user,
            'form_data' => $formData,
            'document_title' => 'ATTESTATION DE DOMICILE',
            'reference_number' => $request->reference_number,
            'date_generation' => now(),
            'commune' => 'Commune de [Nom de votre commune]',

            'full_name' => $formData['name'] ?? $user->name,
            'date_of_birth' => $formData['date_of_birth'] ?? $user->date_of_birth,
            'place_of_birth' => $formData['place_of_birth'] ?? $user->place_of_birth,
            'nationality' => $formData['nationality'] ?? $user->nationality,
            'address' => $formData['address'] ?? $user->address,
            'district' => $formData['district'] ?? '',
            'residence_duration' => $formData['residence_duration'] ?? ''
        ];

        $data = array_merge($data, $this->getOfficialDocumentData());

        $pdf = Pdf::loadView('documents.templates.attestation-domicile', $data);
        return $pdf->download('attestation-domicile-' . $request->reference_number . '.pdf');
    }

    private function generateLegalisation(CitizenRequest $request, User $user, array $additionalData = [])
    {
        $formData = $additionalData['form_data'] ?? [];

        $data = [
            'request' => $request,
            'user' => $user,
            'form_data' => $formData,
            'document_title' => 'LÉGALISATION DE DOCUMENT',
            'reference_number' => $request->reference_number,
            'date_generation' => now(),
            'commune' => 'Commune de [Nom de votre commune]',

            'full_name' => $formData['name'] ?? $user->name,
            'date_of_birth' => $formData['date_of_birth'] ?? $user->date_of_birth,
            'place_of_birth' => $formData['place_of_birth'] ?? $user->place_of_birth,
            'nationality' => $formData['nationality'] ?? $user->nationality,
            'document_type' => $formData['document_type'] ?? '',
            'document_date' => $formData['document_date'] ?? '',
            'issuing_authority' => $formData['issuing_authority'] ?? ''
        ];

        $data = array_merge($data, $this->getOfficialDocumentData());

        $data = array_merge($data, $this->getOfficialDocumentData());

        $pdf = Pdf::loadView('documents.templates.legalisation', $data);
        return $pdf->download('legalisation-' . $request->reference_number . '.pdf');
    }

    /**
     * Obtenir le chemin de la signature du maire
     */
    private function getMayorSignaturePath()
    {
        $signaturePath = public_path('images/official/signature_maire.png');
        return file_exists($signaturePath) ? $signaturePath : null;
    }

    /**
     * Obtenir le chemin du cachet officiel
     */
    private function getOfficialSealPath()
    {
        $sealPath = public_path('images/official/cachet_officiel.png');
        return file_exists($sealPath) ? $sealPath : null;
    }

    /**
     * Obtenir les données communes pour la signature et le cachet
     */
    private function getOfficialDocumentData()
    {
        $electronicSealData = $this->getElectronicSealData();
        
        return [
            'mayor_signature' => $this->getMayorSignaturePath(),
            'official_seal' => $this->getOfficialSealPath(),
            'electronic_seal' => $electronicSealData,
            'electronic_seal_image' => $this->generateElectronicSeal($electronicSealData),
            'mayor_name' => 'PCT_MAYOR',
            'municipality' => config('app.municipality_name', 'Mairie de Cocody'),
        ];
    }

    /**
     * Générer les données du cachet électronique
     */
    private function getElectronicSealData()
    {
        return [
            'seal_number' => 'SEAL-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'seal_date' => now()->format('d/m/Y H:i:s'),
            'seal_authority' => 'MAIRIE DE COCODY',
            'verification_code' => strtoupper(substr(md5(uniqid()), 0, 8)),
        ];
    }

    /**
     * Générer le cachet électronique avec les données dynamiques
     */
    private function generateElectronicSeal($sealData)
    {
        $templatePath = public_path('images/official/cachet_electronique_v2.svg');
        
        // Fallback vers le template original si le v2 n'existe pas
        if (!file_exists($templatePath)) {
            $templatePath = public_path('images/official/cachet_electronique_template.svg');
        }
        
        if (!file_exists($templatePath)) {
            return null;
        }
        
        $svgContent = file_get_contents($templatePath);
        
        // Remplacer les placeholders par les vraies données
        $svgContent = str_replace('{DATE}', $sealData['seal_date'], $svgContent);
        $svgContent = str_replace('{VERIFICATION_CODE}', $sealData['verification_code'], $svgContent);
        
        // Créer un fichier temporaire avec les données
        $tempFile = storage_path('app/temp/electronic_seal_' . uniqid() . '.svg');
        
        // Créer le répertoire temp s'il n'existe pas
        $tempDir = dirname($tempFile);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        file_put_contents($tempFile, $svgContent);
        
        return $tempFile;
    }
}
