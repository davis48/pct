<?php

namespace App\Services;

use App\Models\CitizenRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentGeneratorService
{
    /**
     * Générer un document PDF pour une demande approuvée
     */
    public function generateDocument(CitizenRequest $request)
    {
        // Charger les données nécessaires
        $request->load(['user', 'document']);

        // Sélectionner le template approprié
        $template = $this->getTemplateForDocumentType($request->type);
        
        // Préparer les données pour le template
        $data = $this->prepareTemplateData($request);

        // Générer le PDF
        $pdf = Pdf::loadView($template, $data);
        $pdf->setPaper('A4', 'portrait');

        // Générer le chemin de stockage
        $fileName = "{$request->id}.pdf";
        $filePath = "generated_documents/{$fileName}";

        // Sauvegarder le PDF
        Storage::put($filePath, $pdf->output());

        Log::info('Document généré avec succès', [
            'request_id' => $request->id,
            'template' => $template,
            'file_path' => $filePath
        ]);

        return $filePath;
    }

    /**
     * Obtenir le template approprié pour le type de document
     */
    protected function getTemplateForDocumentType($type)
    {        $templates = [
            'attestation' => 'documents.templates.attestation-domicile',
            'attestation-domicile' => 'documents.templates.attestation-domicile',
            'legalisation' => 'documents.templates.legalisation',
            'mariage' => 'documents.templates.certificat-mariage',
            'certificat-mariage' => 'documents.templates.certificat-mariage',
            'extrait-acte' => 'documents.templates.extrait-naissance',
            'extrait-naissance' => 'documents.templates.extrait-naissance',
            'declaration-naissance' => 'documents.templates.declaration-naissance',
            'certificat' => 'documents.templates.certificat-celibat',
            'certificat-celibat' => 'documents.templates.certificat-celibat',
            'certificat-deces' => 'documents.templates.certificat-deces',
            'deces' => 'documents.templates.certificat-deces',
            'information' => 'documents.templates.document-information',
            'autre' => 'documents.templates.document-general',
        ];

        return $templates[$type] ?? 'documents.templates.document-general';
    }    /**
     * Préparer les données pour le template
     */    protected function prepareTemplateData(CitizenRequest $request)
    {
        // Décoder les données additionnelles
        $additionalData = [];
        if ($request->additional_data) {
            $decodedData = json_decode($request->additional_data, true);
            $additionalData = $decodedData['form_data'] ?? [];
        }

        return [
            'request' => $request,
            'user' => $request->user,
            'document' => $request->document,
            'form_data' => $additionalData,
            'generated_at' => now(),
            'date_generation' => now(),
            'reference_number' => $request->reference_number,
            'qr_code_data' => $this->generateQRCodeData($request),
            'municipality' => config('app.municipality', 'Mairie de Yamoussoukro'),
            'mayor_name' => config('app.mayor_name', 'Le Maire'),
            'document_number' => $this->generateDocumentNumber($request),
        ];
    }

    /**
     * Générer les données pour le QR code
     */
    protected function generateQRCodeData(CitizenRequest $request)
    {
        return json_encode([
            'ref' => $request->reference_number,
            'type' => $request->type,
            'user' => $request->user->nom . ' ' . $request->user->prenoms,
            'date' => now()->format('Y-m-d'),
            'verify_url' => route('documents.verify', $request->reference_number)
        ]);
    }

    /**
     * Générer un numéro de document unique
     */
    protected function generateDocumentNumber(CitizenRequest $request)
    {
        $year = now()->year;
        $month = now()->format('m');
        $typeCode = $this->getDocumentTypeCode($request->type);
        
        return sprintf('%s/%s/%s/%04d', $typeCode, $month, $year, $request->id);
    }

    /**
     * Obtenir le code du type de document
     */
    protected function getDocumentTypeCode($type)
    {
        $codes = [
            'attestation' => 'AD',
            'legalisation' => 'LD',
            'mariage' => 'CM',
            'extrait-acte' => 'EAN',
            'declaration-naissance' => 'DN',
            'certificat' => 'CC',
            'information' => 'DI',
            'autre' => 'DO',
        ];

        return $codes[$type] ?? 'DO';
    }

    /**
     * Régénérer un document existant
     */
    public function regenerateDocument(CitizenRequest $request)
    {
        // Supprimer l'ancien document s'il existe
        $oldPath = "generated_documents/{$request->id}.pdf";
        if (Storage::exists($oldPath)) {
            Storage::delete($oldPath);
        }

        // Générer un nouveau document
        return $this->generateDocument($request);
    }

    /**
     * Vérifier si un document existe
     */
    public function documentExists(CitizenRequest $request)
    {
        $filePath = "generated_documents/{$request->id}.pdf";
        return Storage::exists($filePath);
    }
}
