<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the citizen dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les demandes du citoyen avec relations
        $requests = CitizenRequest::where('user_id', $user->id)
            ->with(['document', 'assignedAgent', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques du citoyen
        $stats = [
            'total_requests' => $requests->count(),
            'pending_requests' => $requests->where('status', 'pending')->count(),
            'in_progress_requests' => $requests->where('status', 'in_progress')->count(),
            'approved_requests' => $requests->where('status', 'approved')->count(),
            'rejected_requests' => $requests->where('status', 'rejected')->count(),
        ];

        // Notifications non lues
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('citizen.dashboard', compact('requests', 'stats', 'notifications'));
    }

    /**
     * Get real-time status updates for citizen requests.
     */
    public function getRequestUpdates()
    {
        $user = Auth::user();
        
        $requests = CitizenRequest::where('user_id', $user->id)
            ->with(['document', 'assignedAgent', 'processedBy'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'requests' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'document_name' => $request->document->name ?? 'Document non spécifié',
                    'status' => $request->status,
                    'status_label' => $this->getStatusLabel($request->status),
                    'created_at' => $request->created_at->format('d/m/Y H:i'),
                    'updated_at' => $request->updated_at->format('d/m/Y H:i'),
                    'assigned_agent' => $request->assignedAgent->name ?? 'Non assigné',
                    'processed_by' => $request->processedBy->name ?? null,
                    'processed_at' => $request->processed_at ? $request->processed_at->format('d/m/Y H:i') : null,
                    'rejection_reason' => $request->rejection_reason,
                ];
            })
        ]);
    }

    /**
     * Get unread notifications for the citizen.
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'created_at' => $notification->created_at->format('d/m/Y H:i'),
                ];
            }),
            'count' => $notifications->count()
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get status label in French.
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'En attente',
            'in_progress' => 'En cours de traitement',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Show request details.
     */
    public function showRequest($id)
    {
        $user = Auth::user();
        
        $request = CitizenRequest::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['document', 'assignedAgent', 'processedBy'])
            ->firstOrFail();

        // Marquer les notifications liées à cette demande comme lues
        Notification::where('user_id', $user->id)
            ->where('data->request_id', $id)
            ->update(['is_read' => true]);

        return view('citizen.request-detail', compact('request'));
    }
}
