<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NavbarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $unreadNotifications = 0;
        
        if (Auth::check()) {
            $unreadNotifications = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
        
        $view->with('unreadNotifications', $unreadNotifications);
    }
}
