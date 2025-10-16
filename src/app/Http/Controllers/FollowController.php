<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return response()->json(['message' => 'Cannot follow yourself'], 400);
        }

        if ($currentUser->isFollowing($user)) {
            return response()->json(['message' => 'Already following'], 400);
        }

        $currentUser->follow($user);

        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => $currentUser->id,
            'type' => 'follow',
            'notifiable_type' => User::class,
            'notifiable_id' => $currentUser->id,
            'message' => $currentUser->name . ' started following you',
        ]);

        return response()->json(['message' => 'User followed successfully']);
    }

    public function destroy(User $user)
    {
        auth()->user()->unfollow($user);

        return response()->json(['message' => 'User unfollowed successfully']);
    }
}
