<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\CitizenRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RequestController extends Controller
{
    /**
     * Liste toutes les demandes
     */
    public function index(Request $request)
    {
        $query = CitizenRequest::with(['user', 'document'])->latest();

        // Exclure les demandes en brouillon (pas encore soumises)
        $query->where('status', '!=', \App\Models\CitizenRequest::STATUS_DRAFT);

        // Filtres
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('assigned_to_me') && $request->assigned_to_me == '1') {
            $query->where('assigned_to', Auth::id());
        }

        // Filtre pour les demandes traitables (payées ou ne nécessitant pas de paiement)
        if ($request->has('processable_only') && $request->processable_only == '1') {
            $query->where(function($q) {
                $q->where('payment_status', \App\Models\CitizenRequest::PAYMENT_STATUS_PAID)
                  ->orWhere('payment_required', false)
                  ->orWhereNull('payment_required');
            });
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

        // Statistiques pour affichage
        $stats = [
            'total' => CitizenRequest::where('status', '!=', \App\Models\CitizenRequest::STATUS_DRAFT)->count(),
            'pending' => CitizenRequest::where('status', \App\Models\CitizenRequest::STATUS_PENDING)->count(),
            'in_progress' => CitizenRequest::where('status', \App\Models\CitizenRequest::STATUS_IN_PROGRESS)->count(),
            'processable' => CitizenRequest::where('status', '!=', \App\Models\CitizenRequest::STATUS_DRAFT)
                ->where(function($q) {
                    $q->where('payment_status', \App\Models\CitizenRequest::PAYMENT_STATUS_PAID)
                      ->orWhere('payment_required', false)
                      ->orWhereNull('payment_required');
                })->count(),
            'unpaid' => CitizenRequest::where('status', '!=', \App\Models\CitizenRequest::STATUS_DRAFT)
                ->where('payment_status', '!=', \App\Models\CitizenRequest::PAYMENT_STATUS_PAID)
                ->where('payment_required', true)
                ->count(),
        ];

        return view('agent.requests.index', compact('requests', 'stats'));
    }

    /**
     * Prendre en charge une demande
     */
    public function take(string $id)
    {
        $request = CitizenRequest::with(['user', 'document'])->findOrFail($id);

        // Vérifier que la demande peut être traitée
        if (!$request->canBeProcessed()) {
            $errorMessage = $request->requiresPayment() && $request->payment_status !== \App\Models\CitizenRequest::PAYMENT_STATUS_PAID
                ? 'Cette demande ne peut pas être traitée car le paiement n\'a pas été effectué.'
                : 'Cette demande ne peut pas être traitée dans son état actuel.';
            
            return redirect()->back()
                ->with('error', $errorMessage);
        }

        try {
            // Mettre à jour le statut de la demande
            $request->update([
                'status' => CitizenRequest::STATUS_IN_PROGRESS,
                'assigned_to' => Auth::id()
            ]);

            // Créer une notification pour le citoyen
            \App\Models\Notification::create([
                'user_id' => $request->user_id,
                'title' => 'Votre demande est en cours de traitement',
                'message' => "Votre demande de {$request->type} (Référence: {$request->reference_number}) est maintenant en cours de traitement par un agent.",
                'type' => 'info',
                'data' => [
                    'request_id' => $request->id,
                    'reference_number' => $request->reference_number,
                    'request_status' => CitizenRequest::STATUS_IN_PROGRESS,
                    'assigned_to' => Auth::id()
                ],
                'is_read' => false,
            ]);

            return redirect()->route('agent.requests.show', $request)
                ->with('success', 'La demande a été prise en charge avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la prise en charge de la demande', [
                'error' => $e->getMessage(),
                'request_id' => $id,
                'agent_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la prise en charge de la demande.');
        }
    }

    /**
     * Afficher la page de traitement d'une demande
     */
    public function process(string $id)
    {
        $citizenRequest = CitizenRequest::with(['user', 'document', 'assignedAgent', 'processedBy', 'attachments'])
            ->findOrFail($id);

        // Vérifier que la demande peut être traitée SEULEMENT si elle n'est pas encore terminée
        $isFinished = in_array($citizenRequest->status, ['approved', 'rejected']);
        
        if (!$isFinished && !$citizenRequest->canBeProcessed()) {
            $errorMessage = $citizenRequest->requiresPayment() && $citizenRequest->payment_status !== \App\Models\CitizenRequest::PAYMENT_STATUS_PAID
                ? 'Cette demande ne peut pas être traitée car le paiement n\'a pas été effectué.'
                : 'Cette demande ne peut pas être traitée dans son état actuel.';
            
            return redirect()->route('agent.requests.index')
                ->with('error', $errorMessage);
        }

        // Si la demande n'est pas encore assignée, l'assigner automatiquement à l'agent courant
        if (!$citizenRequest->assigned_to) {
            $citizenRequest->update([
                'assigned_to' => Auth::id(),
                'status' => CitizenRequest::STATUS_IN_PROGRESS
            ]);

            // Créer une notification pour le citoyen
            \App\Models\Notification::create([
                'user_id' => $citizenRequest->user_id,
                'title' => 'Votre demande est en cours de traitement',
                'message' => "Votre demande de {$citizenRequest->type} (Référence: {$citizenRequest->reference_number}) est maintenant en cours de traitement par un agent.",
                'type' => 'info',
                'data' => [
                    'request_id' => $citizenRequest->id,
                    'reference_number' => $citizenRequest->reference_number,
                    'request_status' => CitizenRequest::STATUS_IN_PROGRESS,
                    'assigned_to' => Auth::id()
                ],
                'is_read' => false,
            ]);
        }

        return view('agent.requests.process', [
            'request' => $citizenRequest,
            'statuses' => [
                CitizenRequest::STATUS_APPROVED => 'Approuvée',
                CitizenRequest::STATUS_IN_PROGRESS => 'En cours de traitement',
                'pending_info' => 'En attente d\'informations',
                CitizenRequest::STATUS_REJECTED => 'Rejetée'
            ]
        ]);
    }

    /**
     * Approuver une demande
     */
    public function approve(string $id)
    {
        $request = CitizenRequest::with(['user', 'document'])->findOrFail($id);

        try {
            // Mettre à jour le statut de la demande
            $request->update([
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'processed_at' => now()
            ]);

            // Créer une notification pour le citoyen
            \App\Models\Notification::create([
                'user_id' => $request->user_id,
                'title' => 'Votre demande a été approuvée',
                'message' => "Votre demande de {$request->type} (Référence: {$request->reference_number}) a été approuvée. Vous pouvez venir retirer votre document.",
                'type' => 'success',
                'data' => [
                    'request_id' => $request->id,
                    'reference_number' => $request->reference_number,
                    'request_status' => 'approved',
                    'processed_by' => Auth::id()
                ],
                'is_read' => false,
            ]);

            return redirect()->route('agent.requests.show', $request)
                ->with('success', 'La demande a été approuvée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'approbation de la demande', [
                'error' => $e->getMessage(),
                'request_id' => $id,
                'agent_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'approbation de la demande.');
        }
    }

    /**
     * Rejeter une demande
     */
    public function reject(Request $request, string $id)
    {
        $citizenRequest = CitizenRequest::with(['user', 'document'])->findOrFail($id);

        $validated = $request->validate([
            'admin_comments' => 'required|string|max:1000'
        ]);

        try {
            // Mettre à jour le statut de la demande
            $citizenRequest->update([
                'status' => 'rejected',
                'admin_comments' => $validated['admin_comments'],
                'processed_by' => Auth::id(),
                'processed_at' => now()
            ]);

            // Créer une notification pour le citoyen
            \App\Models\Notification::create([
                'user_id' => $citizenRequest->user_id,
                'title' => 'Votre demande a été rejetée',
                'message' => "Votre demande de {$citizenRequest->type} (Référence: {$citizenRequest->reference_number}) a été rejetée. Raison: {$validated['admin_comments']}",
                'type' => 'danger',
                'data' => [
                    'request_id' => $citizenRequest->id,
                    'reference_number' => $citizenRequest->reference_number,
                    'request_status' => 'rejected',
                    'processed_by' => Auth::id(),
                    'admin_comments' => $validated['admin_comments']
                ],
                'is_read' => false,
            ]);

            return redirect()->route('agent.requests.show', $citizenRequest)
                ->with('success', 'La demande a été rejetée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du rejet de la demande', [
                'error' => $e->getMessage(),
                'request_id' => $id,
                'agent_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du rejet de la demande.');
        }
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
     * Assigner une demande
     */
    public function assign(Request $request, string $id)
    {
        $citizenRequest = CitizenRequest::findOrFail($id);
        $oldStatus = $citizenRequest->status;

        $citizenRequest->update([
            'assigned_to' => Auth::id(),
            'status' => 'in_progress'
        ]);

        // Envoyer une notification d'assignation au citoyen
        $notificationService = new NotificationService();
        $notificationService->sendAssignmentNotification($citizenRequest, Auth::user());

        // Envoyer aussi une notification de changement de statut
        $notificationService->sendStatusChangeNotification($citizenRequest, $oldStatus, 'in_progress');

        // Rediriger vers la page de traitement au lieu de retourner un JSON
        return redirect()->route('agent.requests.process', $citizenRequest->id)
            ->with('success', 'Demande assignée avec succès. Vous pouvez maintenant la traiter.');
    }

    /**
     * Mes assignations
     */
    public function myAssignments()
    {
        $requests = CitizenRequest::with(['user', 'document'])
                  ->where('assigned_to', Auth::id())
                  ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
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
                ->where('status', CitizenRequest::STATUS_PENDING)
                ->where('payment_status', CitizenRequest::PAYMENT_STATUS_PAID)
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

    /**
     * Liste les demandes en cours
     */
    public function inProgress()
    {
        $requests = CitizenRequest::with(['user', 'document', 'assignedAgent'])
                                ->where('status', CitizenRequest::STATUS_IN_PROGRESS)
                                ->latest()
                                ->paginate(15);

        return view('agent.requests.in_progress', compact('requests'));
    }

    /**
     * Liste les demandes nécessitant un rappel
     */
    public function reminders()
    {
        $requests = CitizenRequest::with(['user', 'document'])
                                ->where('status', CitizenRequest::STATUS_PENDING)
                                ->where('created_at', '<=', now()->subDays(3))
                                ->latest()
                                ->paginate(15);

        return view('agent.requests.reminders', compact('requests'));
    }

    /**
     * Liste des demandes qui peuvent être traitées
     */
    public function listProcessableRequests()
    {
        $requests = CitizenRequest::with(['user', 'document'])
            ->where('payment_status', CitizenRequest::PAYMENT_STATUS_PAID)
            ->whereIn('status', [
                CitizenRequest::STATUS_PENDING,
                CitizenRequest::STATUS_IN_PROGRESS
            ])
            ->whereNull('assigned_to')
            ->latest()
            ->paginate(15);

        return view('agent.requests.processable', compact('requests'));
    }
}
