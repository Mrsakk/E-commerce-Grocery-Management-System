<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = AppNotification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        try {
            $notification = AppNotification::where('user_id', Auth::id())->findOrFail($id);
            $notification->update(['is_read' => true]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark notification as read.');
        }

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        try {
            AppNotification::where('user_id', Auth::id())->where('is_read', false)
                ->update(['is_read' => true]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark notifications as read.');
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function unreadCount()
    {
        try {
            $count = AppNotification::where('user_id', Auth::id())->where('is_read', false)->count();
        } catch (\Exception $e) {
            $count = 0;
        }

        return response()->json(['count' => $count]);
    }
}
