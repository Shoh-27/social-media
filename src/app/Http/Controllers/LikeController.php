<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Notification;
use App\Events\PostLiked;
use App\Events\NotificationCreated;
use Illuminate\Http\Request;
use App\Models\User;

class LikeController extends Controller
{

    public function store(Post $post)
    {
        $user = auth()->user();

        if ($post->isLikedBy($user)) {
            return response()->json(['message' => 'Already liked'], 400);
        }

        $post->like($user);

        // Broadcast event
        broadcast(new PostLiked($post, $user));

        // Create notification
        if ($post->user_id !== $user->id) {
            $notification = Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => $user->id,
                'type' => 'like',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id,
                'message' => $user->name . ' liked your post',
            ]);

            broadcast(new NotificationCreated($notification));
        }

        return response()->json([
            'likes_count' => $post->likes_count,
            'message' => 'Post liked'
        ]);
    }

    public function destroy(Post $post)
    {
        $user = auth()->user();
        $post->unlike($user);

        // Postni yangilab olish
        $post->refresh();

        return response()->json([
            'likes_count' => $post->likes()->count(),
            'message' => 'Post unliked'
        ]);
    }

}
