<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;

class DocumentController extends Controller
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
        // Liste tous les documents publics disponibles pour le citoyen
        $documents = Document::where('is_public', true)
                           ->where('status', 'active')
                           ->get();

        return view('front.documents.index', compact('documents'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Récupérer et afficher un document spécifique
        $document = Document::where('id', $id)
                          ->where('is_public', true)
                          ->where('status', 'active')
                          ->firstOrFail();

        return view('front.documents.show', compact('document'));
    }
}
