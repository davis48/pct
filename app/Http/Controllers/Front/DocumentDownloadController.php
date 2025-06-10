<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CitizenRequest;
use App\Services\DocumentPdfGeneratorService;
use Illuminate\Support\Facades\Auth;

class DocumentDownloadController extends Controller
{
    protected $pdfGenerator;

    public function __construct(DocumentPdfGeneratorService $pdfGenerator)
    {
        $this->middleware('auth');
        $this->pdfGenerator = $pdfGenerator;
    }

    /**
     * Télécharger le document généré pour une demande approuvée
     */
    public function downloadApprovedDocument(CitizenRequest $request)
    {
        // Vérifier que la demande appartient à l'utilisateur connecté
        if ($request->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à ce document.');
        }        // Vérifier que la demande est dans un état permettant le téléchargement
        $downloadableStatuses = ['approved', 'processed', 'ready', 'completed'];
        $canDownload = in_array($request->status, $downloadableStatuses) || 
                      ($request->status == 'in_progress' && $request->processed_by);
        
        if (!$canDownload) {
            abort(404, 'Ce document n\'est pas encore disponible. Veuillez attendre que votre demande soit traitée par nos services.');
        }

        try {
            return $this->pdfGenerator->generateDocument($request);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du document : ' . $e->getMessage());
        }
    }

    /**
     * Prévisualiser le document (pour les administrateurs et agents)
     */
    public function previewDocument(CitizenRequest $request)
    {
        // Seuls les admins et agents peuvent prévisualiser
        if (!in_array(Auth::user()->role, ['admin', 'agent'])) {
            abort(403, 'Vous n\'êtes pas autorisé à prévisualiser ce document.');
        }

        try {
            return $this->pdfGenerator->generateDocument($request);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du document : ' . $e->getMessage());
        }
    }
}
