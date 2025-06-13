<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\CitizenRequest;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InteractiveFormController extends Controller
{
    public function index()
    {
        return view('front.interactive-forms.index');
    }

    public function extraitNaissanceStandalone()
    {
        return view('front.interactive-forms.extrait-naissance_standalone');
    }

    public function attestationDomicileStandalone()
    {
        return view('front.interactive-forms.attestation-domicile_standalone');
    }

    public function certificatMariageStandalone()
    {
        return view('front.interactive-forms.certificat-mariage_standalone');
    }

    public function certificatCelibatStandalone()
    {
        return view('front.interactive-forms.certificat-celibat_standalone');
    }

    public function certificatDecesStandalone()
    {
        return view('front.interactive-forms.certificat-deces_standalone');
    }

    public function legalisationStandalone()
    {
        return view('front.interactive-forms.legalisation_standalone');
    }    
    public function generate(Request $request, $formType = null)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            // Sauvegarder les données en session pour les récupérer après connexion
            session(['pending_form_data' => $request->all(), 'pending_form_type' => $formType]);
            return redirect()->route('login.standalone')->with('message', 'Vous devez vous connecter pour soumettre votre demande.');
        }

        // Déterminer le type de formulaire si non fourni
        if (!$formType) {
            $formType = $request->input('type_document', 'certificat-mariage');
        }

        // Validation des données selon le type de formulaire
        $validated = $this->validateFormData($request, $formType);

        // Créer la demande
        $citizenRequest = CitizenRequest::create([
            'user_id' => Auth::id(),
            'type' => $formType,
            'status' => 'pending',
            'reference_number' => 'REQ-' . strtoupper(Str::random(8)),
            'additional_data' => json_encode([
                'form_data' => $validated,
                'form_type' => $formType
            ])
        ]);

        // Traiter les documents uploadés
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                // Générer un nom unique pour le fichier
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Stocker le fichier
                $path = $file->storeAs('documents', $filename, 'public');
                
                // Créer l'enregistrement de l'attachement
                Attachment::create([
                    'citizen_request_id' => $citizenRequest->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Rediriger vers la page de paiement standalone
        return redirect()->route('payments.standalone.show', $citizenRequest)
            ->with('success', 'Votre demande a été créée avec succès. Veuillez procéder au paiement.');
    }

    private function validateFormData(Request $request, $formType)
    {
        switch ($formType) {
            case 'certificat-mariage':
                return $request->validate([
                    'nom_epoux' => 'required|string|max:255',
                    'prenoms_epoux' => 'required|string|max:255',
                    'date_naissance_epoux' => 'nullable|date',
                    'lieu_naissance_epoux' => 'nullable|string|max:255',
                    'profession_epoux' => 'nullable|string|max:255',
                    'domicile_epoux' => 'nullable|string|max:255',
                    'nom_epouse' => 'required|string|max:255',
                    'prenoms_epouse' => 'required|string|max:255',
                    'date_naissance_epouse' => 'nullable|date',
                    'lieu_naissance_epouse' => 'nullable|string|max:255',
                    'profession_epouse' => 'nullable|string|max:255',
                    'domicile_epouse' => 'nullable|string|max:255',
                    'date_mariage' => 'required|date',
                    'lieu_mariage' => 'required|string|max:255',
                    'regime_matrimonial' => 'nullable|string|max:255',
                    'officiant' => 'nullable|string|max:255',
                    'documents' => 'nullable|array',
                    'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
                ]);
            
            case 'extrait-naissance':
                return $request->validate([
                    'nom' => 'required|string|max:255',
                    'prenoms' => 'required|string|max:255',
                    'date_naissance' => 'nullable|date',
                    'lieu_naissance' => 'nullable|string|max:255',
                    'nom_pere' => 'nullable|string|max:255',
                    'nom_mere' => 'nullable|string|max:255',
                    'declarant' => 'nullable|string|max:255',
                    'documents' => 'nullable|array',
                    'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);
              default:
                return $request->validate([
                    'documents' => 'nullable|array',
                    'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
                ]);
        }
    }

    public function processPendingSubmission()
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login.standalone');
        }

        // Récupérer les données en session
        $formData = session('pending_form_data');
        $formType = session('pending_form_type');
        
        if (!$formData) {
            return redirect()->route('interactive-forms.index')
                ->with('error', 'Aucune donnée de formulaire en attente.');
        }

        // Effacer les données de la session
        session()->forget(['pending_form_data', 'pending_form_type']);

        // Créer une requête factice avec les données sauvegardées
        $request = new Request($formData);

        // Traiter la soumission
        return $this->generate($request, $formType);
    }

    public function show($formType)
    {
        $viewName = "front.interactive-forms.{$formType}";
        
        if (!view()->exists($viewName)) {
            abort(404, "Formulaire '{$formType}' non trouvé");
        }
        
        return view($viewName);
    }

    public function download($formType, $requestId)
    {
        $request = CitizenRequest::findOrFail($requestId);
        
        // Vérifier que l'utilisateur a le droit de télécharger ce document
        if (Auth::id() !== $request->user_id) {
            abort(403, 'Accès non autorisé');
        }
        
        // Générer et télécharger le document
        $documentService = app(\App\Services\DocumentPdfGeneratorService::class);
        
        try {
            return $documentService->generateDocument($request);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la génération du document: ' . $e->getMessage());
        }
    }
}
