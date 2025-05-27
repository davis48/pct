<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentController extends Controller
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
    public function index()
    {
        // Liste tous les documents pour l'admin avec pagination
        $documents = Document::latest()->paginate(15);
        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Affiche le formulaire de création d'un document
        return view('admin.documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation renforcée des données
        $validated = $request->validate([
            'title' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ0-9\s\-\.\_\(\)]+$/',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|in:identite,certificat,permis,autre',
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:2048',
                function ($attribute, $value, $fail) {
                    // Vérification du type MIME réel
                    $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                    if (!in_array($value->getMimeType(), $allowedMimes)) {
                        $fail('Le type de fichier n\'est pas autorisé.');
                    }

                    // Vérification de l'extension réelle
                    $allowedExtensions = ['pdf', 'doc', 'docx'];
                    if (!in_array(strtolower($value->getClientOriginalExtension()), $allowedExtensions)) {
                        $fail('L\'extension du fichier n\'est pas autorisée.');
                    }
                }
            ],
            'status' => 'required|in:active,inactive',
            'is_public' => 'sometimes|boolean',
        ], [
            'title.regex' => 'Le titre ne peut contenir que des lettres, chiffres, espaces et caractères de base.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'file.mimes' => 'Le fichier doit être au format PDF, DOC ou DOCX.',
            'file.max' => 'Le fichier ne peut pas dépasser 2 Mo.',
        ]);

        // Gestion sécurisée du fichier
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Génération d'un nom unique sécurisé
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents', $filename, 'public');
            $validated['file_path'] = $filePath;
        }

        // Gestion du champ is_public qui est une checkbox
        $validated['is_public'] = $request->has('is_public') ? true : false;

        // Création du document avec logging
        $document = Document::create($validated);

        // Log de l'action pour audit
        Log::info('Document créé', [
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'title' => $document->title,
            'category' => $document->category
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Récupérer et afficher un document spécifique
        $document = Document::findOrFail($id);
        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Récupérer et afficher le formulaire d'édition d'un document
        $document = Document::findOrFail($id);
        return view('admin.documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation renforcée des données
        $validated = $request->validate([
            'title' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ0-9\s\-\.\_\(\)]+$/',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|in:identite,certificat,permis,autre',
            'file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:2048',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Vérification du type MIME réel
                        $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                        if (!in_array($value->getMimeType(), $allowedMimes)) {
                            $fail('Le type de fichier n\'est pas autorisé.');
                        }

                        // Vérification de l'extension réelle
                        $allowedExtensions = ['pdf', 'doc', 'docx'];
                        if (!in_array(strtolower($value->getClientOriginalExtension()), $allowedExtensions)) {
                            $fail('L\'extension du fichier n\'est pas autorisée.');
                        }
                    }
                }
            ],
            'is_public' => 'boolean',
            'status' => 'required|in:active,inactive',
        ], [
            'title.regex' => 'Le titre ne peut contenir que des lettres, chiffres, espaces et caractères de base.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'file.mimes' => 'Le fichier doit être au format PDF, DOC ou DOCX.',
            'file.max' => 'Le fichier ne peut pas dépasser 2 Mo.',
        ]);

        // Récupérer le document
        $document = Document::findOrFail($id);
        $oldFilePath = $document->file_path;

        // Mettre à jour le fichier si fourni
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Génération d'un nom unique sécurisé
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('documents', $filename, 'public');

            // Supprimer l'ancien fichier de manière sécurisée
            if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }

            $document->file_path = $filePath;
        }

        // Mettre à jour les autres champs
        $document->title = $validated['title'];
        $document->description = $validated['description'];
        $document->category = $validated['category'];
        $document->is_public = $request->has('is_public');
        $document->status = $validated['status'];
        $document->save();

        // Log de l'action pour audit
        Log::info('Document mis à jour', [
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'title' => $document->title,
            'changes' => $document->getChanges()
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Récupérer le document
        $document = Document::findOrFail($id);

        // Supprimer le fichier associé
        Storage::disk('public')->delete($document->file_path);

        // Supprimer le document
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document supprimé avec succès.');
    }
}
