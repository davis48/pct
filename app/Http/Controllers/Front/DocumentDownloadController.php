<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CitizenRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Services\DocumentGeneratorService;

class DocumentDownloadController extends Controller
{
    protected $documentGenerator;

    public function __construct(DocumentGeneratorService $documentGenerator)
    {
        $this->middleware('auth');
        $this->documentGenerator = $documentGenerator;
    }

    /**
     * Télécharger le document généré pour une demande approuvée
     */
    public function downloadApprovedDocument(CitizenRequest $request)
    {
        // Vérifier que la demande appartient à l'utilisateur connecté
        if ($request->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce document.');
        }

        // Vérifier que la demande est approuvée
        if ($request->status !== CitizenRequest::STATUS_APPROVED) {
            abort(404, 'Ce document n\'est pas encore disponible. La demande doit être approuvée.');
        }

        // Vérifier si le document a déjà été généré
        $documentPath = $this->getOrGenerateDocument($request);
        
        if (!$documentPath || !Storage::exists($documentPath)) {
            abort(404, 'Document non trouvé.');
        }

        // Générer un nom de fichier convivial
        $fileName = $this->generateFileName($request);

        // Télécharger le document
        return Storage::download($documentPath, $fileName);
    }

    /**
     * Obtenir ou générer le document pour la demande
     */
    protected function getOrGenerateDocument(CitizenRequest $request)
    {
        // Chemin du document dans le stockage
        $documentPath = "generated_documents/{$request->id}.pdf";

        // Si le document existe déjà, le retourner
        if (Storage::exists($documentPath)) {
            return $documentPath;
        }

        // Sinon, générer le document
        try {
            return $this->documentGenerator->generateDocument($request);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la génération du document', [
                'request_id' => $request->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Générer un nom de fichier convivial
     */
    protected function generateFileName(CitizenRequest $request)
    {
        $documentType = $this->getDocumentTypeName($request->type);
        $date = now()->format('Y-m-d');
        $reference = $request->reference_number;
        
        return "{$documentType}_{$reference}_{$date}.pdf";
    }

    /**
     * Obtenir le nom convivial du type de document
     */
    protected function getDocumentTypeName($type)
    {
        $types = [
            'attestation' => 'Attestation_de_domicile',
            'legalisation' => 'Legalisation_de_document',
            'mariage' => 'Certificat_de_mariage',
            'extrait-acte' => 'Extrait_acte_naissance',
            'declaration-naissance' => 'Declaration_de_naissance',
            'certificat' => 'Certificat_de_celibat',
            'information' => 'Document_information',
            'autre' => 'Document_officiel',
        ];

        return $types[$type] ?? 'Document_officiel';
    }

    /**
     * Prévisualiser le document (optionnel)
     */
    public function previewApprovedDocument(CitizenRequest $request)
    {
        // Vérifier que la demande appartient à l'utilisateur connecté
        if ($request->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce document.');
        }

        // Vérifier que la demande est approuvée
        if ($request->status !== CitizenRequest::STATUS_APPROVED) {
            abort(404, 'Ce document n\'est pas encore disponible.');
        }

        $documentPath = $this->getOrGenerateDocument($request);
        
        if (!$documentPath || !Storage::exists($documentPath)) {
            abort(404, 'Document non trouvé.');
        }

        // Retourner le contenu du fichier pour prévisualisation
        $content = Storage::get($documentPath);
        return Response::make($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"'
        ]);
    }

    /**
     * Vérifier si un document est disponible pour téléchargement
     */
    public function checkDocumentAvailability(CitizenRequest $request)
    {
        if ($request->user_id !== Auth::id()) {
            return response()->json(['available' => false, 'message' => 'Accès non autorisé']);
        }

        if ($request->status !== CitizenRequest::STATUS_APPROVED) {
            return response()->json(['available' => false, 'message' => 'Demande non approuvée']);
        }

        $documentPath = "generated_documents/{$request->id}.pdf";
        $exists = Storage::exists($documentPath);

        return response()->json([
            'available' => $exists,
            'message' => $exists ? 'Document disponible' : 'Document en cours de génération'
        ]);
    }
}
