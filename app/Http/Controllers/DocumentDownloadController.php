<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DocumentDownloadController extends Controller
{
    /**
     * Télécharger le document généré pour une demande approuvée
     */
    public function downloadApprovedDocument(CitizenRequest $request)
    {
        // Vérifier que l'utilisateur peut accéder à cette demande
        if (Auth::user()->role === 'citizen' && $request->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce document.');
        }

        // Vérifier que la demande est approuvée
        if ($request->status !== 'approved') {
            abort(404, 'Document non disponible. La demande doit être approuvée.');
        }

        // Générer le document selon le type de demande
        return $this->generateDocument($request);
    }

    /**
     * Générer le document selon le type de demande
     */
    private function generateDocument(CitizenRequest $request)
    {
        $user = $request->user;
        $document = $request->document;
        
        // Données communes à tous les documents
        $data = [
            'request' => $request,
            'user' => $user,
            'document' => $document,
            'date_generation' => Carbon::now(),
            'reference' => $request->reference_number,
        ];

        switch ($request->type) {
            case 'attestation':
                return $this->generateAttestationDomicile($data);
            
            case 'extrait-acte':
                return $this->generateExtraitActeNaissance($data);
            
            case 'certificat':
                return $this->generateCertificatCelibat($data);
            
            case 'mariage':
                return $this->generateCertificatMariage($data);
            
            case 'legalisation':
                return $this->generateLegalisation($data);
            
            default:
                return $this->generateDocumentGenerique($data);
        }
    }

    /**
     * Générer une attestation de domicile
     */
    private function generateAttestationDomicile($data)
    {
        $pdf = Pdf::loadView('documents.templates.attestation-domicile', $data);
        $filename = "Attestation_Domicile_{$data['reference']}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Générer un extrait d'acte de naissance
     */
    private function generateExtraitActeNaissance($data)
    {
        $pdf = Pdf::loadView('documents.templates.extrait-acte-naissance', $data);
        $filename = "Extrait_Acte_Naissance_{$data['reference']}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Générer un certificat de célibat
     */
    private function generateCertificatCelibat($data)
    {
        $pdf = Pdf::loadView('documents.templates.certificat-celibat', $data);
        $filename = "Certificat_Celibat_{$data['reference']}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Générer un certificat de mariage
     */
    private function generateCertificatMariage($data)
    {
        $pdf = Pdf::loadView('documents.templates.certificat-mariage', $data);
        $filename = "Certificat_Mariage_{$data['reference']}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Générer une légalisation
     */
    private function generateLegalisation($data)
    {
        $pdf = Pdf::loadView('documents.templates.legalisation', $data);
        $filename = "Legalisation_{$data['reference']}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Générer un document générique
     */
    private function generateDocumentGenerique($data)
    {
        $pdf = Pdf::loadView('documents.templates.document-generique', $data);
        $filename = "Document_{$data['reference']}.pdf";
        
        return $pdf->download($filename);
    }

    /**
     * Prévisualiser le document avant téléchargement
     */
    public function previewDocument(CitizenRequest $request)
    {
        // Vérifier que l'utilisateur peut accéder à cette demande
        if (Auth::user()->role === 'citizen' && $request->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce document.');
        }

        // Vérifier que la demande est approuvée
        if ($request->status !== 'approved') {
            abort(404, 'Document non disponible. La demande doit être approuvée.');
        }

        $user = $request->user;
        $document = $request->document;
        
        // Données communes à tous les documents
        $data = [
            'request' => $request,
            'user' => $user,
            'document' => $document,
            'date_generation' => Carbon::now(),
            'reference' => $request->reference_number,
        ];

        switch ($request->type) {
            case 'attestation':
                $pdf = Pdf::loadView('documents.templates.attestation-domicile', $data);
                break;
            
            case 'extrait-acte':
                $pdf = Pdf::loadView('documents.templates.extrait-acte-naissance', $data);
                break;
            
            case 'certificat':
                $pdf = Pdf::loadView('documents.templates.certificat-celibat', $data);
                break;
            
            case 'mariage':
                $pdf = Pdf::loadView('documents.templates.certificat-mariage', $data);
                break;
            
            case 'legalisation':
                $pdf = Pdf::loadView('documents.templates.legalisation', $data);
                break;
            
            default:
                $pdf = Pdf::loadView('documents.templates.document-generique', $data);
                break;
        }

        return $pdf->stream();
    }
}
