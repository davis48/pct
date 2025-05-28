<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\CitizenRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:agent']);
    }

    /**
     * Afficher la liste des documents
     */
    public function index(Request $request)
    {
        $query = CitizenRequest::with(['document', 'user']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('document', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($subQ) use ($search) {
                      $subQ->where('nom', 'like', "%{$search}%")
                           ->orWhere('prenoms', 'like', "%{$search}%");
                  });
            });
        }

        // Filtrage par type de document
        if ($request->filled('document_type')) {
            $query->where('document_id', $request->document_type);
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $requests = $query->latest()->paginate(15);

        // Statistiques pour la page - format standardisé pour le sidebar agent
        $stats = [
            'users' => User::where('role', 'citizen')->count(),
            'documents' => Document::count(),
            'requests' => CitizenRequest::count(),
            'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
            'myAssignedRequests' => CitizenRequest::where('assigned_to', Auth::id())->count(),
            'myCompletedRequests' => CitizenRequest::where('processed_by', Auth::id())
                ->whereIn('status', ['approved', 'complete', 'rejetee'])
                ->count(),
        ];

        // Types de documents disponibles
        $documentTypes = Document::all();

        return view('agent.documents.index', compact('requests', 'stats', 'documentTypes'));
    }

    /**
     * Afficher un document spécifique
     */
    public function show(CitizenRequest $request)
    {
        $request->load(['user', 'document']);

        // Statistiques pour le sidebar agent
        $stats = [
            'users' => User::where('role', 'citizen')->count(),
            'documents' => Document::count(),
            'requests' => CitizenRequest::count(),
            'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
            'myAssignedRequests' => CitizenRequest::where('assigned_to', Auth::id())->count(),
            'myCompletedRequests' => CitizenRequest::where('processed_by', Auth::id())
                ->whereIn('status', ['approved', 'complete', 'rejetee'])
                ->count(),
        ];

        return view('agent.documents.show', compact('request', 'stats'));
    }

    /**
     * Obtenir les statistiques des documents
     */
    public function getStats()
    {
        $stats = [
            'users' => User::where('role', 'citizen')->count(),
            'documents' => Document::count(),
            'requests' => CitizenRequest::count(),
            'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
            'myAssignedRequests' => CitizenRequest::where('assigned_to', Auth::id())->count(),
            'myCompletedRequests' => CitizenRequest::where('processed_by', Auth::id())
                ->whereIn('status', ['approved', 'complete', 'rejetee'])
                ->count(),
        ];

        // Statistiques par mois (12 derniers mois)
        $monthlyStats = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'requests' => CitizenRequest::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        // Statistiques par type de document
        $documentStats = Document::withCount('citizenRequests')->get();

        return response()->json([
            'general' => $stats,
            'monthly' => $monthlyStats,
            'by_document_type' => $documentStats,
        ]);
    }

    /**
     * Obtenir les métriques en temps réel
     */
    public function getRealTimeMetrics()
    {
        $today = now()->format('Y-m-d');

        return response()->json([
            'today' => [
                'new_requests' => CitizenRequest::whereDate('created_at', $today)->count(),
                'processed_requests' => CitizenRequest::whereDate('updated_at', $today)
                    ->where('status', 'approved')->count(),
            ],
            'queue' => [
                'pendingRequests' => CitizenRequest::where('status', 'pending')->count(),
                'myAssignedRequests' => CitizenRequest::where('assigned_to', Auth::id())->count(),
            ],
            'attachments' => [
                'total_files' => CitizenRequest::whereNotNull('attachments')->count(),
            ],
        ]);
    }

    /**
     * Télécharger une pièce jointe
     */
    public function downloadAttachment(CitizenRequest $request, $filename)
    {
        $attachments = $request->attachments ?? [];

        foreach ($attachments as $attachment) {
            if (basename($attachment) === $filename) {
                $filePath = storage_path('app/public/' . $attachment);

                if (file_exists($filePath)) {
                    return response()->download($filePath);
                }
            }
        }

        return abort(404, 'Fichier non trouvé');
    }

    /**
     * Prévisualiser une pièce jointe
     */
    public function previewAttachment(CitizenRequest $request, $filename)
    {
        $attachments = $request->attachments ?? [];

        foreach ($attachments as $attachment) {
            if (basename($attachment) === $filename) {
                $filePath = storage_path('app/public/' . $attachment);

                if (file_exists($filePath)) {
                    $mimeType = mime_content_type($filePath);
                    return response()->file($filePath, [
                        'Content-Type' => $mimeType,
                    ]);
                }
            }
        }

        return abort(404, 'Fichier non trouvé');
    }

    /**
     * Exporter les données
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');

        $query = CitizenRequest::with(['document', 'user']);

        // Appliquer les mêmes filtres que dans index()
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('document', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->get();

        switch ($format) {
            case 'csv':
                return $this->exportToCsv($data);
            case 'excel':
                return $this->exportToExcel($data);
            case 'pdf':
                return $this->exportToPdf($data);
            default:
                return $this->exportToCsv($data);
        }
    }

    private function exportToCsv($data)
    {
        $filename = 'documents_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'Référence',
                'Type de document',
                'Demandeur',
                'Statut',
                'Date de création',
                'Description'
            ]);

            // Données
            foreach ($data as $request) {
                fputcsv($file, [
                    $request->reference_number,
                    $request->document->title ?? 'N/A',
                    ($request->user->nom ?? '') . ' ' . ($request->user->prenoms ?? ''),
                    $request->status,
                    $request->created_at->format('Y-m-d H:i:s'),
                    $request->description
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($data)
    {
        // Implementation avec PhpSpreadsheet si nécessaire
        return $this->exportToCsv($data);
    }

    private function exportToPdf($data)
    {
        // Implementation avec DomPDF si nécessaire
        return $this->exportToCsv($data);
    }
}
