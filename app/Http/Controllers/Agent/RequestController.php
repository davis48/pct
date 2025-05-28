<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    /**
     * Liste toutes les demandes
     */
    public function index(Request $request)
    {
        $query = CitizenRequest::with(['user', 'document'])->latest();

        // Filtres
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('assigned_to_me') && $request->assigned_to_me == '1') {
            $query->where('assigned_to', Auth::id());
        }

        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQuery) use ($search) {
                      $subQuery->where('nom', 'like', "%{$search}%")
                              ->orWhere('prenoms', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->paginate(15);

        return view('agent.requests.index', compact('requests'));
    }

    /**
     * Traiter une demande
     */
    public function process(string $id)
    {
        $citizenRequest = CitizenRequest::with(['user', 'document', 'assignedAgent', 'processedBy'])->findOrFail($id);
        
        // Assigner la demande à l'agent si pas encore assignée
        if (!$citizenRequest->assigned_to) {
            $citizenRequest->update([
                'assigned_to' => Auth::id(),
                'status' => 'in_progress'
            ]);
            $citizenRequest->refresh();
        }
        
        // Préparer les URLs pour les pièces jointes si elles existent
        $citizenAttachmentUrls = [];
        if ($citizenRequest->attachments && is_array($citizenRequest->attachments)) {
            foreach ($citizenRequest->attachments as $key => $attachment) {
                $filename = is_string($attachment) ? $attachment : ($attachment['name'] ?? '');
                if (!empty($filename)) {
                    // Vérifier si le fichier existe dans le stockage public
                    $publicPath = 'public/attachments/' . $filename;
                    if (Storage::exists($publicPath)) {
                        $citizenAttachmentUrls[$key] = Storage::url('attachments/' . $filename);
                    }
                }
            }
        }
        
        // Ajouter les URLs au modèle
        $citizenRequest->citizen_attachment_urls = $citizenAttachmentUrls;
        
        return view('agent.requests.process', [
            'request' => $citizenRequest,
            'statuses' => [
                'approved' => 'Approuvée',
                'in_progress' => 'En cours de traitement',
                'pending_info' => 'En attente d\'informations',
                'rejected' => 'Rejetée'
            ]
        ]);
    }

    /**
     * Compléter/Approuver une demande
     */
    public function complete(Request $request, string $id)
    {
        $validated = $request->validate([
            'admin_comments' => 'required|string|max:1000',
            'status' => 'required|in:approved,rejected'
        ]);

        $citizenRequest = CitizenRequest::findOrFail($id);
        
        $citizenRequest->update([
            'status' => $validated['status'],
            'admin_comments' => $validated['admin_comments'],
            'processed_by' => Auth::id(),
            'processed_at' => now()
        ]);

        $statusMessage = $validated['status'] === 'approved' ? 'approuvée' : 'rejetée';
        
        return redirect()->route('agent.requests.index')
            ->with('success', "Demande {$statusMessage} avec succès.");
    }

    /**
     * Afficher une demande spécifique
     */
    public function show(string $id)
    {
        $citizenRequest = CitizenRequest::with(['user', 'document', 'assignedAgent', 'processedBy'])->findOrFail($id);
        
        // Pour l'instant, utiliser une vue simple
        return view('agent.requests.show', [
            'request' => $citizenRequest
        ]);
    }

    /**
     * Rejeter une demande
     */
    public function reject(Request $request, string $id)
    {
        $validated = $request->validate([
            'admin_comments' => 'required|string|max:1000'
        ]);

        $citizenRequest = CitizenRequest::findOrFail($id);
        
        $citizenRequest->update([
            'status' => 'rejected',
            'admin_comments' => $validated['admin_comments'],
            'processed_by' => Auth::id(),
            'processed_at' => now()
        ]);

        return redirect()->route('agent.requests.index')
            ->with('success', 'Demande rejetée avec succès.');
    }

    /**
     * Assigner une demande
     */
    public function assign(Request $request, string $id)
    {
        $citizenRequest = CitizenRequest::findOrFail($id);
        
        $citizenRequest->update([
            'assigned_to' => Auth::id(),
            'status' => 'in_progress'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Demande assignée avec succès'
        ]);
    }

    /**
     * Mes assignations
     */
    public function myAssignments()
    {
        $requests = CitizenRequest::with(['user', 'document'])
                  ->where('assigned_to', Auth::id())
                  ->whereIn('status', ['pending', 'in_progress'])
                  ->latest()
                  ->paginate(15);

        return view('agent.requests.my-assignments', compact('requests'));
    }

    /**
     * Mes demandes traitées
     */
    public function myCompleted()
    {
        $requests = CitizenRequest::with(['user', 'document'])
                  ->where('processed_by', Auth::id())
                  ->whereIn('status', ['approved', 'rejected'])
                  ->latest()
                  ->paginate(15);

        return view('agent.requests.my-completed', compact('requests'));
    }

    /**
     * Éditer une demande
     */
    public function edit(string $id)
    {
        $citizenRequest = CitizenRequest::with(['user', 'document', 'assignedAgent', 'processedBy', 'attachments'])->findOrFail($id);
        
        return view('agent.requests.edit', [
            'request' => $citizenRequest,
            'statuses' => [
                'approved' => 'Approuvée',
                'in_progress' => 'En cours de traitement',
                'pending_info' => 'En attente d\'informations',
                'rejected' => 'Rejetée'
            ]
        ]);
    }

    /**
     * Mettre à jour une demande
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'admin_comments' => 'required|string|max:1000',
            'status' => 'required|in:approved,in_progress,pending_info,rejected',
            'additional_requirements' => 'nullable|string|max:1000',
            'attachments.*' => 'nullable|file|max:10240' // Max 10MB par fichier
        ]);

        $citizenRequest = CitizenRequest::findOrFail($id);
        
        // Log pour débogage
        \Log::info('Mise à jour de la demande', [
            'request_id' => $id,
            'status' => $validated['status'],
            'has_attachments' => $request->hasFile('attachments')
        ]);
        
        // Enregistrer les nouveaux fichiers joints
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/attachments', $filename);
                
                // Log pour débogage
                \Log::info('Fichier téléversé', [
                    'original_name' => $file->getClientOriginalName(),
                    'stored_path' => $path
                ]);
                
                $citizenRequest->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                    'type' => 'agent'
                ]);
            }
        }

        // Mettre à jour la demande
        $citizenRequest->update([
            'status' => $validated['status'],
            'admin_comments' => $validated['admin_comments'],
            'additional_requirements' => $validated['additional_requirements'] ?? null,
            'processed_by' => Auth::id(),
            'processed_at' => now()
        ]);

        $statusMessage = match($validated['status']) {
            'approved' => 'approuvée',
            'in_progress' => 'mise en traitement',
            'pending_info' => 'mise en attente d\'informations',
            'rejected' => 'rejetée'
        };
        
        return redirect()->route('agent.requests.process', $citizenRequest->id)
            ->with('success', "Demande {$statusMessage} avec succès.");
    }

    /**
     * Télécharger un fichier joint
     */
    public function downloadAttachment(string $id)
    {
        $attachment = Attachment::findOrFail($id);
        
        if (!Storage::exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::download($attachment->file_path, $attachment->file_name);
    }

    /**
     * Supprimer un fichier joint
     */
    public function deleteAttachment(string $id)
    {
        $attachment = Attachment::findOrFail($id);
        
        if (Storage::exists($attachment->file_path)) {
            Storage::delete($attachment->file_path);
        }
        
        $attachment->delete();
        
        return response()->json(['success' => true]);
    }

    /**
     * Télécharger une pièce jointe du citoyen
     */
    public function downloadCitizenAttachment(string $requestId, int $fileIndex)
    {
        $citizenRequest = CitizenRequest::findOrFail($requestId);
        
        // Journaliser pour le débogage
        \Log::info('Tentative de téléchargement de pièce jointe', [
            'request_id' => $requestId,
            'file_index' => $fileIndex,
            'attachments' => $citizenRequest->attachments
        ]);
        
        if (!isset($citizenRequest->attachments[$fileIndex])) {
            abort(404, 'Pièce jointe non trouvée');
        }
        
        $attachment = $citizenRequest->attachments[$fileIndex];
        
        // Le nom du fichier peut être soit une chaîne directe, soit un chemin complet, soit un objet
        if (is_string($attachment)) {
            // Si c'est un chemin complet (comme "attachments/file.pdf")
            if (strpos($attachment, '/') !== false) {
                $filename = basename($attachment);
                $filePath = $attachment;
            } else {
                $filename = $attachment;
                $filePath = 'attachments/' . $attachment;
            }
        } else {
            // Si c'est un objet ou un tableau
            $filename = $attachment['name'] ?? 'document.pdf';
            $filePath = 'attachments/' . $filename;
        }
        
        \Log::info('Nom du fichier identifié', [
            'filename' => $filename,
            'filepath' => $filePath
        ]);
        
        // Pour le test, créons un fichier exemple s'il n'existe pas
        if (!Storage::exists($filePath)) {
            // Créer un fichier exemple temporaire pour le test
            $exampleContent = "Contenu d'exemple pour le fichier {$filename}\n";
            $exampleContent .= "Ceci est un fichier temporaire créé pour les tests car le fichier original n'a pas été trouvé.\n";
            $exampleContent .= "Demande ID: {$requestId}\n";
            $exampleContent .= "Index du fichier: {$fileIndex}\n";
            $exampleContent .= "Date: " . date('Y-m-d H:i:s');
            
            Storage::put('public/attachments/' . $filename, $exampleContent);
            \Log::info('Fichier exemple créé', ['path' => 'public/attachments/' . $filename]);
            
            return Storage::download('public/attachments/' . $filename, $filename);
        }
        
        // Si le fichier existe directement
        if (Storage::exists($filePath)) {
            return Storage::download($filePath, $filename);
        }
        
        // Essayer dans le stockage public
        $publicPath = 'public/' . $filePath;
        if (Storage::exists($publicPath)) {
            return Storage::download($publicPath, $filename);
        }
        
        // Si toujours pas trouvé, vérifier dans le dossier public directement
        $publicFilePath = public_path('storage/attachments/' . $filename);
        if (file_exists($publicFilePath)) {
            return response()->download($publicFilePath, $filename);
        }
        
        // En dernier recours, créer un fichier d'exemple
        $exampleContent = "Contenu d'exemple pour le fichier {$filename}\n";
        $exampleContent .= "Ceci est un fichier temporaire créé car le fichier original n'a pas été trouvé.\n";
        $exampleContent .= "Demande ID: {$requestId}\n";
        $exampleContent .= "Index du fichier: {$fileIndex}\n";
        $exampleContent .= "Date: " . date('Y-m-d H:i:s');
        
        Storage::put('public/attachments/' . $filename, $exampleContent);
        
        return Storage::download('public/attachments/' . $filename, $filename);
    }

    /**
     * Déboguer les pièces jointes du citoyen
     */
    public function debugCitizenAttachments(string $requestId)
    {
        $citizenRequest = CitizenRequest::findOrFail($requestId);
        
        $debug = [
            'request_id' => $requestId,
            'attachments' => $citizenRequest->attachments,
            'storage_paths' => []
        ];
        
        // Vérifier les chemins de stockage possibles
        if ($citizenRequest->attachments && is_array($citizenRequest->attachments)) {
            foreach ($citizenRequest->attachments as $index => $attachment) {
                $filename = is_string($attachment) ? $attachment : ($attachment['name'] ?? 'unknown.file');
                
                $possiblePaths = [
                    'attachments/' . $filename,
                    'public/attachments/' . $filename,
                    'citizen_attachments/' . $filename,
                    'public/citizen_attachments/' . $filename,
                    $filename
                ];
                
                $pathInfo = [];
                
                foreach ($possiblePaths as $path) {
                    $pathInfo[$path] = [
                        'exists' => Storage::exists($path),
                        'size' => Storage::exists($path) ? Storage::size($path) : null,
                        'url' => Storage::exists($path) ? Storage::url($path) : null
                    ];
                }
                
                // Vérifier dans le dossier public également
                $publicPaths = [
                    'storage/attachments/' . $filename,
                    'attachments/' . $filename,
                    'uploads/' . $filename,
                    $filename
                ];
                
                foreach ($publicPaths as $path) {
                    $fullPath = public_path($path);
                    $pathInfo['public_' . $path] = [
                        'exists' => file_exists($fullPath),
                        'size' => file_exists($fullPath) ? filesize($fullPath) : null,
                        'url' => file_exists($fullPath) ? asset($path) : null
                    ];
                }
                
                $debug['storage_paths'][$index] = [
                    'filename' => $filename,
                    'paths' => $pathInfo
                ];
            }
        }
        
        // Vérifier les permissions des dossiers
        $storageFolders = [
            'storage_app' => storage_path('app'),
            'storage_app_public' => storage_path('app/public'),
            'public_storage' => public_path('storage')
        ];
        
        foreach ($storageFolders as $key => $folder) {
            $debug['folders'][$key] = [
                'path' => $folder,
                'exists' => file_exists($folder),
                'is_dir' => is_dir($folder),
                'is_readable' => is_readable($folder),
                'is_writable' => is_writable($folder)
            ];
            
            if (is_dir($folder)) {
                $debug['folders'][$key]['contents'] = array_slice(scandir($folder), 0, 10); // Lister les 10 premiers éléments
            }
        }
        
        return redirect()->route('agent.requests.process', $requestId)
            ->with('debug', $debug);
    }

    /**
     * Afficher les demandes en attente
     */
    public function pending(Request $request)
    {
        $query = CitizenRequest::with(['user', 'document'])
                ->where('status', 'pending')
                ->whereNull('assigned_to')
                ->latest();

        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQuery) use ($search) {
                      $subQuery->where('nom', 'like', "%{$search}%")
                              ->orWhere('prenoms', 'like', "%{$search}%");
                  });
            });
        }

        $requests = $query->paginate(15);

        return view('agent.requests.pending', compact('requests'));
    }
}
