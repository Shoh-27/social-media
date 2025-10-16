<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
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

        // Create notification
        if ($post->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => auth()->id(),
                'type' => 'comment',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id,
                'message' => auth()->user()->name . ' commented on your post',
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
