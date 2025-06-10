<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }    /**
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

        // Séparer les demandes selon leur statut de paiement et leur état
        $submittedRequests = $requests->where('payment_status', 'paid');
        $draftRequests = $requests->where('status', 'draft');
          // Calculer les statistiques exactes basées sur la réalité de la base de données (status français)
        $stats = [
            'total_requests' => $submittedRequests->count(),
            'pending_requests' => $submittedRequests->where('status', 'en_attente')->count(),
            'in_progress_requests' => $submittedRequests->where('status', 'en_cours')->count(),
            'approved_requests' => $submittedRequests->where('status', 'approved')->count(),
            'rejected_requests' => $submittedRequests->where('status', 'rejected')->count(),
            'completed_requests' => $submittedRequests->where('status', 'completed')->count(),
            'draft_requests' => $draftRequests->count(), // Demandes en attente de paiement
        ];        // Notifications non lues avec comptage exact - limite à 3 pour le dashboard
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        // Compter toutes les notifications non lues pour l'indicateur
        $unreadNotificationsCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Ajouter des données supplémentaires pour le dashboard
        $additionalData = [
            'recent_activity' => $this->getRecentActivity($user->id),
            'payment_summary' => $this->getPaymentSummary($user->id),
            'last_login' => $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : null,
        ];

        return view('citizen.dashboard', compact('requests', 'stats', 'notifications', 'unreadNotificationsCount', 'additionalData'));
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
            ->get();        return response()->json([
            'requests' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'document_name' => $request->document->title ?? 'Document non spécifié',
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
     * Get notifications for AJAX dropdown.
     */
    public function getNotificationsAjax()
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => Str::limit($notification->message, 80),
                    'type' => $notification->type,
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notification->type),
                ];
            }),
            'count' => $notifications->count()
        ]);
    }

    /**
     * Get notification icon based on type.
     */
    private function getNotificationIcon($type)
    {
        $icons = [
            'success' => 'fas fa-check-circle text-green-500',
            'info' => 'fas fa-info-circle text-blue-500',
            'warning' => 'fas fa-exclamation-triangle text-yellow-500',
            'error' => 'fas fa-times-circle text-red-500',
            'payment' => 'fas fa-credit-card text-purple-500',
            'request' => 'fas fa-file-alt text-gray-500',
            'message' => 'fas fa-envelope text-blue-500',
        ];
        
        return $icons[$type] ?? 'fas fa-bell text-gray-500';
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
     * Save notification preferences for the citizen.
     */
    public function saveNotificationPreferences(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validated = $request->validate([
            'sound_enabled' => 'boolean',
            'desktop_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'email_enabled' => 'boolean',
        ]);
        
        // Save to user preferences
        $preferences = $user->notification_preferences ?? [];
        $preferences = array_merge($preferences, [
            'sound_enabled' => $validated['sound_enabled'] ?? true,
            'desktop_enabled' => $validated['desktop_enabled'] ?? true,
            'sms_enabled' => $validated['sms_enabled'] ?? true,
            'email_enabled' => $validated['email_enabled'] ?? true,
        ]);
        
        $user->notification_preferences = $preferences;
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Préférences de notification enregistrées'
        ]);
    }

    /**
     * Show notification preferences page.
     */
    public function notificationPreferences()
    {
        return view('citizen.notification-preferences');
    }
    
    /**
     * Get current notification preferences.
     */
    public function getNotificationPreferences()
    {
        $user = Auth::user();
        $preferences = $user->notification_preferences ?? [];
        
        // Set default values if not set
        $defaults = [
            'status_notifications' => true,
            'assignment_notifications' => true,
            'submission_confirmations' => true,
            'processing_notifications' => false,
            'intermediate_notifications' => false,
            'reminder_notifications' => false,
            'payment_notifications' => true,
            'welcome_notifications' => true,
            'sms_enabled' => true,
            'email_enabled' => true,
        ];
        
        return response()->json(array_merge($defaults, $preferences));
    }
    
    /**
     * Update notification preferences.
     */
    public function updateNotificationPreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'status_notifications' => 'boolean',
            'assignment_notifications' => 'boolean',
            'submission_confirmations' => 'boolean',
            'processing_notifications' => 'boolean',
            'intermediate_notifications' => 'boolean',
            'reminder_notifications' => 'boolean',
            'payment_notifications' => 'boolean',
            'welcome_notifications' => 'boolean',
            'sms_enabled' => 'boolean',
            'email_enabled' => 'boolean',
        ]);
        
        // Force critical notifications to always be enabled
        $validated['payment_notifications'] = true; // Always enable payment notifications
        
        $user->notification_preferences = $validated;
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Préférences de notification mises à jour avec succès'
        ]);
    }
    
    /**
     * Send a test notification.
     */
    public function sendTestNotification()
    {
        $user = Auth::user();
        
        // Use the smart notification system
        $notificationService = app(\App\Services\NotificationService::class);
        
        $title = 'Notification de test';
        $message = "Ceci est une notification de test pour vérifier vos préférences. Envoyée le " . now()->format('d/m/Y à H:i');
        
        $notificationService->sendSmartNotification($user, $title, $message, 'info', [
            'type' => 'test',
            'sent_at' => now()->toISOString(),
            'priority' => \App\Services\NotificationService::PRIORITY_LOW,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notification de test envoyée'
        ]);
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
     */    public function showRequest($id)
    {
        // Rediriger vers la version standalone pour éviter les problèmes de couches
        return redirect()->route('citizen.request.standalone.show', $id);
    }

    /**
     * Show request details standalone version.
     */
    public function showRequestStandalone($id)
    {
        $user = Auth::user();
        
        $request = CitizenRequest::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['attachments', 'payments'])
            ->firstOrFail();

        // Marquer les notifications liées à cette demande comme lues
        Notification::where('user_id', $user->id)
            ->where('data->request_id', $id)
            ->update(['is_read' => true]);

        return view('citizen.request-detail_standalone', compact('request'));
    }

    /**
     * Get real-time statistics for the citizen dashboard.
     */
    public function getStats()
    {
        $user = Auth::user();
        
        // Récupérer toutes les demandes du citoyen
        $allRequests = CitizenRequest::where('user_id', $user->id)->get();
        
        // Séparer les demandes en brouillon (non payées) et soumises (payées)
        $submittedRequests = $allRequests->where('payment_status', 'paid');
        $draftRequests = $allRequests->where('status', 'draft');
          // Calculer les statistiques en temps réel (avec les status français utilisés en DB)
        $stats = [
            'total_requests' => $submittedRequests->count(),
            'pending_requests' => $submittedRequests->where('status', 'en_attente')->count(),
            'in_progress_requests' => $submittedRequests->where('status', 'en_cours')->count(),
            'approved_requests' => $submittedRequests->where('status', 'approved')->count(),
            'rejected_requests' => $submittedRequests->where('status', 'rejected')->count(),
            'completed_requests' => $submittedRequests->where('status', 'completed')->count(),
            'draft_requests' => $draftRequests->count(),
        ];
        
        // Ajouter des détails supplémentaires
        $additionalData = [
            'last_updated' => now()->format('Y-m-d H:i:s'),
            'recent_activity' => $this->getRecentActivity($user->id),
            'payment_summary' => $this->getPaymentSummary($user->id),
        ];
        
        return response()->json([
            'stats' => $stats,
            'data' => $additionalData,
            'success' => true
        ]);
    }

    /**
     * Get recent activity for the citizen.
     */
    private function getRecentActivity($userId)
    {
        $recentRequests = CitizenRequest::where('user_id', $userId)
            ->where('updated_at', '>=', now()->subDays(7))
            ->with(['document'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
            
        return $recentRequests->map(function ($request) {
            return [
                'id' => $request->id,
                'document_name' => $request->document->title ?? 'Document',
                'status' => $request->status,
                'status_label' => $this->getStatusLabel($request->status),
                'updated_at' => $request->updated_at->format('d/m/Y H:i'),
                'updated_ago' => $request->updated_at->diffForHumans(),
            ];
        });
    }

    /**
     * Get payment summary for the citizen.
     */
    private function getPaymentSummary($userId)
    {
        $payments = \App\Models\Payment::whereHas('citizenRequest', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        
        return [
            'total_paid' => $payments->where('status', 'completed')->sum('amount'),
            'total_pending' => $payments->where('status', 'pending')->sum('amount'),
            'payment_count' => $payments->where('status', 'completed')->count(),
            'average_amount' => $payments->where('status', 'completed')->avg('amount') ?? 0,
        ];
    }

    /**
     * Centre de notifications - Afficher toutes les notifications du citoyen
     */
    public function notificationCenter()
    {
        $user = Auth::user();
        
        // Récupérer toutes les notifications avec pagination
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Statistiques des notifications
        $notificationStats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            'read' => Notification::where('user_id', $user->id)->where('is_read', true)->count(),
            'today' => Notification::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'this_week' => Notification::where('user_id', $user->id)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];
        
        return view('citizen.notifications-center', compact('notifications', 'notificationStats'));
    }

    /**
     * Supprimer une notification
     */
    public function deleteNotification($id)
    {
        $user = Auth::user();
        
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();
        
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification non trouvée'], 404);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Notification supprimée avec succès'
        ]);
    }

    /**
     * Supprimer toutes les notifications lues
     */
    public function deleteReadNotifications()
    {
        $user = Auth::user();
        
        $deletedCount = Notification::where('user_id', $user->id)
            ->where('is_read', true)
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => "$deletedCount notifications supprimées",
            'count' => $deletedCount
        ]);
    }

    /**
     * Supprimer toutes les notifications
     */
    public function deleteAllNotifications()
    {
        $user = Auth::user();
        
        $deletedCount = Notification::where('user_id', $user->id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Toutes les notifications ont été supprimées ($deletedCount)",
            'count' => $deletedCount
        ]);
    }

    /**
     * Filtrer les notifications
     */
    public function filterNotifications(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');
        $page = $request->get('page', 1);
        
        $query = Notification::where('user_id', $user->id);
        
        // Apply filters
        switch ($type) {
            case 'unread':
                $query->where('is_read', false);
                break;
            case 'success':
            case 'info':
            case 'warning':
            case 'error':
            case 'payment':
            case 'request':
            case 'message':
                $query->where('type', $type);
                break;
            case 'all':
            default:
                // No additional filter
                break;
        }
        
        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page', $page);
        
        return response()->json([
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
            ]
        ]);
    }

    // ...existing code...
}
