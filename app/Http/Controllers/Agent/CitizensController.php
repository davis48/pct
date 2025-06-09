<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CitizenRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitizensController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:agent']);
    }

    /**
     * Afficher la liste des citoyens
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'citizen')
            ->with(['requests' => function($q) {
                $q->latest()->take(5);
            }]);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('requests', function($q) {
                    $q->whereIn('status', ['en_attente', 'processing']);
                });
            } elseif ($request->status === 'inactive') {
                $query->whereDoesntHave('requests', function($q) {
                    $q->whereIn('status', ['en_attente', 'processing']);
                });
            }
        }
        
        // Statistiques pour le tableau de bord
        $stats = [
            'total' => User::where('role', 'citizen')->count(),
            'active' => User::where('role', 'citizen')
                ->whereHas('requests', function($q) {
                    $q->whereIn('status', ['en_attente', 'in_progress']);
                })->count(),
            'requests' => CitizenRequest::count(),
            'newToday' => User::where('role', 'citizen')
                ->whereDate('created_at', today())->count()
        ];

        $citizens = $query->paginate(15);
        return view('agent.citizens.index', compact('citizens', 'stats'));

        $citizens = $query->paginate(20);

        // Statistiques
        $stats = [
            'users' => User::where('role', 'citizen')->count(),
            'documents' => \App\Models\Document::count(),
            'requests' => CitizenRequest::count(),
            'pendingRequests' => CitizenRequest::where('status', 'en_attente')->count(),
            'myAssignedRequests' => CitizenRequest::where('assigned_to', auth()->id())->count(),
            'myCompletedRequests' => CitizenRequest::where('processed_by', auth()->id())
                ->whereIn('status', ['approved', 'complete', 'rejetee'])
                ->count(),
            'total' => User::where('role', 'citizen')->count(),
            'active' => User::where('role', 'citizen')
                ->whereHas('requests', function($q) {
                    $q->whereIn('status', ['en_attente', 'processing']);
                })->count(),
            'new_this_month' => User::where('role', 'citizen')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('agent.citizens.index', compact('citizens', 'stats'));
    }

    /**
     * Afficher les détails d'un citoyen
     */
    public function show(User $citizen)
    {
        if ($citizen->role !== 'citizen') {
            abort(404);
        }

        $citizen->load([
            'requests.document',
            'requests' => function($q) {
                $q->latest();
            }
        ]);

        // Statistiques du citoyen
        $stats = [
            'total_requests' => $citizen->requests->count(),
            'pending_requests' => $citizen->requests->where('status', 'en_attente')->count(),
            'processing_requests' => $citizen->requests->where('status', 'processing')->count(),
            'completed_requests' => $citizen->requests->where('status', 'completed')->count(),
            'rejected_requests' => $citizen->requests->where('status', 'rejected')->count(),
        ];

        return view('agent.citizens.show', compact('citizen', 'stats'));
    }

    /**
     * Obtenir les données d'un citoyen en AJAX
     */
    public function getData(User $citizen)
    {
        if ($citizen->role !== 'citizen') {
            return response()->json(['error' => 'Citoyen non trouvé'], 404);
        }

        $citizen->load([
            'requests.document',
            'requests' => function($q) {
                $q->latest()->take(10);
            }
        ]);

        return response()->json([
            'citizen' => $citizen,
            'stats' => [
                'total_requests' => $citizen->requests->count(),
                'pending_requests' => $citizen->requests->where('status', 'en_attente')->count(),
                'processing_requests' => $citizen->requests->where('status', 'processing')->count(),
                'completed_requests' => $citizen->requests->where('status', 'completed')->count(),
            ]
        ]);
    }

    /**
     * Exporter la liste des citoyens
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');

        $citizens = User::where('role', 'citizen')
            ->with('requests')
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($citizens);
        }

        return redirect()->back()->with('error', 'Format d\'export non supporté');
    }

    private function exportToCsv($citizens)
    {
        $filename = 'citoyens_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($citizens) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Date d\'inscription',
                'Nombre de demandes',
                'Demandes en attente',
                'Demandes traitées'
            ]);

            // Données
            foreach ($citizens as $citizen) {
                fputcsv($file, [
                    $citizen->id,
                    $citizen->nom,
                    $citizen->prenoms,
                    $citizen->email,
                    $citizen->telephone,
                    $citizen->created_at->format('d/m/Y'),
                    $citizen->requests->count(),
                    $citizen->requests->where('status', 'en_attente')->count(),
                    $citizen->requests->where('status', 'completed')->count(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
