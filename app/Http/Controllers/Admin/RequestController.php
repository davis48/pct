<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\User;

class RequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Nous appliquons déjà ce middleware au niveau des routes
        // donc nous n'avons pas besoin de l'appliquer ici
        // $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Liste toutes les demandes pour l'admin avec pagination
        $query = CitizenRequest::with(['user', 'document'])->latest();

        // Filtrer par statut si présent dans la requête
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Recherche si présente dans la requête
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQuery) use ($search) {
                      $subQuery->where('nom', 'like', "%{$search}%")
                              ->orWhere('prenoms', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('document', function($subQuery) use ($search) {
                      $subQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Paginer les résultats (15 par page)
        $requests = $query->paginate(15);

        return view('admin.requests.index', compact('requests'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Récupérer et afficher une demande spécifique
        $request = CitizenRequest::with(['user', 'document'])->findOrFail($id);
        return view('admin.requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Récupérer et afficher le formulaire d'édition d'une demande
        $request = CitizenRequest::with(['user', 'document'])->findOrFail($id);
        return view('admin.requests.edit', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation des données
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
            'admin_comments' => 'nullable|string',
        ]);

        // Récupérer la demande
        $citizenRequest = CitizenRequest::findOrFail($id);

        // Mettre à jour les champs
        $citizenRequest->status = $request->status;
        $citizenRequest->admin_comments = $request->admin_comments;
        $citizenRequest->save();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Demande mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Récupérer la demande
        $citizenRequest = CitizenRequest::findOrFail($id);

        // Supprimer la demande
        $citizenRequest->delete();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Demande supprimée avec succès.');
    }
}
