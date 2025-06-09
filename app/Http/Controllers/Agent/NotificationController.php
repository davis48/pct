<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitizenRequest;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Afficher les notifications
     */
    public function index()
    {
        $notifications = CitizenRequest::with(['user', 'document'])
            ->where('status', CitizenRequest::STATUS_PENDING)
            ->orWhere(function($query) {
                $query->where('assigned_to', Auth::id())
                      ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS]);
            })
            ->latest()
            ->paginate(10);

        return view('agent.notifications.index', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $request = CitizenRequest::findOrFail($id);
        $request->is_read = true;
        $request->save();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        CitizenRequest::where('status', CitizenRequest::STATUS_PENDING)
            ->orWhere(function($query) {
                $query->where('assigned_to', Auth::id())
                      ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS]);
            })
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Obtenir le nombre de notifications non lues
     */
    public function getUnreadCount()
    {
        $count = CitizenRequest::where(function($query) {
                $query->where('status', CitizenRequest::STATUS_PENDING)
                      ->where('is_read', false);
            })
            ->orWhere(function($query) {
                $query->where('assigned_to', Auth::id())
                      ->whereIn('status', [CitizenRequest::STATUS_PENDING, CitizenRequest::STATUS_IN_PROGRESS])
                      ->where('is_read', false);
            })
            ->count();

        return response()->json(['count' => $count]);
    }
}
