<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user          = Auth::user();
        $unreadCount   = $user->appNotifications()->whereNull('read_at')->count();
        $notifications = $user->appNotifications()->latest()->paginate(20);

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markRead(AppNotification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);
        $notification->update(['read_at' => now()]);
        return back();
    }

    public function markAllRead()
    {
        Auth::user()->appNotifications()->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }
}
