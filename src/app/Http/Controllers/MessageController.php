<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()
            ->sentMessages()
            ->with('receiver')
            ->latest()
            ->get()
            ->merge(
                auth()->user()->receivedMessages()->with('sender')->latest()->get()
            )
            ->unique(function ($message) {
                $userId = $message->sender_id === auth()->id()
                    ? $message->receiver_id
                    : $message->sender_id;
                return $userId;
            })
            ->sortByDesc('created_at');

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        // Mark as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'message' => $validated['message'],
        ]);

        if ($request->ajax()) {
            return response()->json([
                'message' => $message->load('sender'),
                'success' => true
            ]);
        }

        return back();
    }

    public function fetch(User $user)
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->where('created_at', '>', now()->subSeconds(5))
            ->orderBy('created_at', 'asc')
            ->with('sender')
            ->get();

        return response()->json($messages);
    }
}
