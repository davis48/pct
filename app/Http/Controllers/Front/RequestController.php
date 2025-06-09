<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        return view('front.requests.index', compact('requests'));
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

        return view('front.requests.create', compact('documents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'type' => 'required|string',
            'description' => 'required|string|max:1000',
            'attachments.*' => 'nullable|file|max:10240' // Max 10MB par fichier
        ]);

        // Gérer les pièces jointes
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/attachments', $filename);
                $attachments[] = [
                    'name' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }

        // Créer la demande
        $citizenRequest = CitizenRequest::create([
            'user_id' => Auth::id(),
            'document_id' => $request->document_id,
            'type' => $request->type,
            'description' => $request->description,
            'attachments' => $attachments,
            'status' => 'pending', // Changé de 'draft' à 'pending'
            'payment_status' => 'unpaid',
            'payment_required' => true, // Changé à true pour forcer le paiement
        ]);

        // Rediriger vers la page de paiement
        return redirect()->route('payments.show', $citizenRequest)
            ->with('success', 'Votre demande a été créée. Veuillez procéder au paiement pour finaliser votre demande.');
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

        return view('front.requests.show', compact('request'));
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
