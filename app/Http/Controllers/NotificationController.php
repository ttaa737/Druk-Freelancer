<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Paginated list of all notifications. */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /** Mark a single notification as read. */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    /** Mark all notifications as read. */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'All notifications marked as read.');
    }

    /** Delete a notification. */
    public function destroy(string $id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();

        return back()->with('success', 'Notification removed.');
    }

    /** Return unread count (for AJAX badge updates). */
    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}

