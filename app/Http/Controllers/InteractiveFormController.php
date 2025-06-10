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

    public function generate(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            // Sauvegarder les données en session pour les récupérer après connexion
            session(['pending_form_data' => $request->all()]);
            return redirect()->route('login')->with('message', 'Vous devez vous connecter pour soumettre votre demande.');
        }

        // Validation des données
        $validated = $request->validate([
            'type_document' => 'required|string',
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'nom_pere' => 'nullable|string|max:255',
            'nom_mere' => 'nullable|string|max:255',
            'declarant' => 'nullable|string|max:255',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Créer la demande
        $citizenRequest = CitizenRequest::create([
            'user_id' => Auth::id(),
            'type_document' => $validated['type_document'],
            'nom' => $validated['nom'],
            'prenoms' => $validated['prenoms'],
            'date_naissance' => $validated['date_naissance'] ?? null,
            'lieu_naissance' => $validated['lieu_naissance'] ?? null,
            'nom_pere' => $validated['nom_pere'] ?? null,
            'nom_mere' => $validated['nom_mere'] ?? null,
            'declarant' => $validated['declarant'] ?? null,
            'status' => 'pending',
            'reference' => 'REQ-' . strtoupper(Str::random(8)),
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

        // Rediriger vers la page de paiement
        return redirect()->route('payments.show', $citizenRequest)
            ->with('success', 'Votre demande a été créée avec succès. Veuillez procéder au paiement.');
    }

    public function processPendingSubmission()
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Récupérer les données en session
        $formData = session('pending_form_data');
        if (!$formData) {
            return redirect()->route('interactive-forms.index')
                ->with('error', 'Aucune donnée de formulaire en attente.');
        }

        // Effacer les données de la session
        session()->forget('pending_form_data');

        // Créer une requête factice avec les données sauvegardées
        $request = new Request($formData);

        // Traiter la soumission
        return $this->generate($request);
    }
}
