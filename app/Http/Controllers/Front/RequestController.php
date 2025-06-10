<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Liste toutes les demandes de l'utilisateur
        $requests = CitizenRequest::where('user_id', Auth::id())->latest()->get();
        return view('front.requests.index_standalone', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Liste les documents disponibles pour la demande
        $documents = Document::where('is_public', true)
                           ->where('status', 'active')
                           ->get();

        return view('front.requests.create_standalone', compact('documents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation étendue des données
        $validated = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'type' => 'required|string',
            'description' => 'required|string|max:1000',
            'reason' => 'required|string|max:500',
            'urgency' => 'nullable|string|in:normal,urgent,very_urgent',
            'contact_preference' => 'nullable|string|in:email,phone,both',
            
            // Informations personnelles pour le document
            'place_of_birth' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'cin_number' => 'required|string|regex:/^[A-Z]{2}[0-9]{10}$/',
            'nationality' => 'required|string|max:100',
            'complete_address' => 'required|string|max:500',
            
            // Validation et conditions
            'confirm_accuracy' => 'required|accepted',
            'accept_terms' => 'required|accepted',
            
            // Fichiers joints
            'attachments.*' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png' // Max 5MB par fichier
        ], [
            'cin_number.regex' => 'Le numéro CNI doit suivre le format: 2 lettres suivies de 10 chiffres (ex: CI0123456789)',
            'attachments.required' => 'Vous devez joindre au moins un document à votre demande',
            'attachments.*.mimes' => 'Les fichiers doivent être au format PDF, JPG, JPEG ou PNG',
            'attachments.*.max' => 'Chaque fichier ne doit pas dépasser 5MB',
            'confirm_accuracy.accepted' => 'Vous devez certifier que les informations sont exactes',
            'accept_terms.accepted' => 'Vous devez accepter les conditions générales'
        ]);

        try {
            // Mettre à jour les informations personnelles de l'utilisateur
            $user = Auth::user();
            $user->update([
                'place_of_birth' => $validated['place_of_birth'],
                'profession' => $validated['profession'],
                'cin_number' => $validated['cin_number'],
                'nationality' => $validated['nationality'],
                'address' => $validated['complete_address'],
            ]);

            // Créer la demande avec les nouvelles informations
            $citizenRequest = CitizenRequest::create([
                'user_id' => Auth::id(),
                'document_id' => $validated['document_id'],
                'type' => $validated['type'],
                'description' => $validated['description'],
                'reason' => $validated['reason'],
                'urgency' => $validated['urgency'] ?? 'normal',
                'contact_preference' => $validated['contact_preference'] ?? 'email',
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_required' => true,
                'additional_data' => json_encode([
                    'place_of_birth' => $validated['place_of_birth'],
                    'profession' => $validated['profession'],
                    'cin_number' => $validated['cin_number'],
                    'nationality' => $validated['nationality'],
                    'complete_address' => $validated['complete_address'],
                    'form_version' => '2.0',
                    'submitted_at' => now()->toISOString()
                ])
            ]);

            // Gérer les pièces jointes et les enregistrer dans la table attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('attachments', $filename, 'public');
                    
                    // Créer l'enregistrement dans la table attachments
                    $citizenRequest->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => Auth::id(),
                        'type' => 'citizen'
                    ]);
                }
            }

            // Supprimer les données sauvegardées du localStorage (sera géré côté client)
            
            // Rediriger vers la page de paiement avec un message de succès détaillé
            return redirect()->route('payments.show', $citizenRequest)
                ->with('success', '🎉 Votre demande a été créée avec succès ! Référence: ' . $citizenRequest->reference_number . '. Veuillez procéder au paiement pour finaliser votre demande.');
                
        } catch (\Exception $e) {
            // Log de l'erreur
            Log::error('Erreur lors de la création de la demande', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de votre demande. Veuillez réessayer ou contacter le support.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Récupérer la demande avec l'utilisateur et le document associés
        $request = CitizenRequest::with(['user', 'document'])
                   ->where('id', $id)
                   ->where('user_id', Auth::id())
                   ->firstOrFail();

        return view('front.requests.show_standalone', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Récupérer la demande avec vérification de propriété
        $request = CitizenRequest::where('id', $id)
                   ->where('user_id', Auth::id())
                   ->firstOrFail();

        // Vérifier que la demande est encore au stade brouillon
        if ($request->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une demande qui a déjà été soumise.');
        }

        // Supprimer les fichiers associés si nécessaire
        if ($request->attachments && is_array($request->attachments)) {
            foreach ($request->attachments as $attachment) {
                $filePath = is_string($attachment) ? $attachment : ($attachment['path'] ?? null);
                if ($filePath && Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }
        }

        // Supprimer la demande
        $request->delete();

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Votre demande en brouillon a été supprimée avec succès.');
    }

    /**
     * Confirm and submit a draft request
     */
    public function confirm(string $id)
    {
        // Récupérer la demande avec vérification de propriété
        $request = CitizenRequest::where('id', $id)
                   ->where('user_id', Auth::id())
                   ->firstOrFail();

        // Vérifier que la demande est encore au stade brouillon
        if ($request->status !== \App\Models\CitizenRequest::STATUS_DRAFT) {
            return redirect()->back()
                ->with('error', 'Cette demande a déjà été soumise.');
        }

        // Confirmer la soumission
        $request->update([
            'status' => \App\Models\CitizenRequest::STATUS_PENDING,
            'payment_status' => \App\Models\CitizenRequest::PAYMENT_STATUS_PAID,
            'payment_required' => false
        ]);

        return redirect()->route('citizen.dashboard')
            ->with('success', '🎉 Votre demande a été soumise avec succès ! Référence: ' . $request->reference_number . '. Elle est maintenant en attente de traitement par nos agents.');
    }
}
