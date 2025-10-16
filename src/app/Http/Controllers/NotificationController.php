<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->with('sender')
            ->latest()
            ->paginate(20);

        // Mark all as read
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
        $count = auth()->user()->notifications()->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
}
