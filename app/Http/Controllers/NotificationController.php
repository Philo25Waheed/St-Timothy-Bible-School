<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id === Auth::id()) {
            $notification->update(['is_read' => true, 'read_at' => now()]);
        }
        return back()->with('success', 'تمت القراءة.');
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }
}
