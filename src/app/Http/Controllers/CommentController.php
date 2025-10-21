<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Events\CommentCreated;
use App\Events\NotificationCreated;

class CommentController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        $post->increment('comments_count');

        // Broadcast event
        broadcast(new CommentCreated($comment));

        // Create notification
        if ($post->user_id !== auth()->id()) {
            $notification = Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => auth()->id(),
                'type' => 'comment',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id,
                'message' => auth()->user()->name . ' commented on your post',
            ]);

            broadcast(new NotificationCreated($notification));
        }

        if ($request->ajax()) {
            return response()->json([
                'comment' => $comment->load('user'),
                'success' => true
            ]);
        }

        return back()->with('success', 'Comment added!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->post->decrement('comments_count');
        $comment->delete();

        return back()->with('success', 'Comment deleted!');
    }
}
