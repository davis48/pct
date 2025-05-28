<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

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
            'description' => 'required|string',
            'attachments' => 'required|array|min:1',
            'attachments.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'document_id.required' => 'Veuillez sélectionner un document associé à votre demande.',
            'attachments.required' => 'Veuillez joindre au moins un document à votre demande.',
            'attachments.min' => 'Veuillez joindre au moins un document à votre demande.',
            'attachments.*.required' => 'Le document joint est obligatoire.',
            'attachments.*.mimes' => 'Le fichier doit être au format PDF, JPG ou PNG.',
            'attachments.*.max' => 'La taille du fichier ne doit pas dépasser 2 Mo.',
        ]);

        // Gestion des pièces jointes
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_at' => now()->toDateTimeString()
                ];
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors(['attachments' => 'Veuillez joindre au moins un document à votre demande.']);
        }

        // Création de la demande
        CitizenRequest::create([
            'user_id' => Auth::id(),
            'document_id' => $request->document_id,
            'type' => $request->type,
            'description' => $request->description,
            'attachments' => $attachments,
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Votre demande a été soumise avec succès avec ' . count($attachments) . ' pièce(s) jointe(s).');
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
        //
    }
}
