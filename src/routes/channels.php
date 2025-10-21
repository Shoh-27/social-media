<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('post.{postId}', function ($user, $postId) {
    return true; // Public channel
});

Broadcast::channel('posts', function ($user) {
    return true; // Public channel
});
