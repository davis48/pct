<?php

namespace App\Http\Controllers;

use App\Models\CitizenRequest;
use App\Services\DocumentPdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentDownloadController extends Controller
{
    protected $pdfGenerator;

    public function __construct(DocumentPdfGeneratorService $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
        $this->middleware('auth');
    }

    /**
     * Télécharger le document PDF d'une demande approuvée
     */
    public function download(CitizenRequest $request)
    {
        // Vérifier que l'utilisateur peut télécharger ce document
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'agent' && $request->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à télécharger ce document.');
        }

        // Vérifier que la demande est dans un état téléchargeable
        if (!in_array($request->status, ['approved', 'processed', 'ready', 'completed'])) {
            abort(403, 'Le document ne peut être téléchargé que si la demande est approuvée ou traitée.');
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
    public function preview(CitizenRequest $request)
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
