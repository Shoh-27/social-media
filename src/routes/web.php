<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HashtagController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Feed
    Route::get('/feed', [PostController::class, 'index'])->name('posts.index');

    // Posts
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Likes
    Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('posts.like');
    Route::delete('/posts/{post}/like', [LikeController::class, 'destroy'])->name('posts.unlike');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Follow/Unfollow
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('users.unfollow');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'unreadCount'])->name('notifications.unread');

    // Messages (Chat)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{user}/fetch', [MessageController::class, 'fetch'])->name('messages.fetch');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::get('/hashtag/{name}', [HashtagController::class, 'show'])->name('hashtags.show');
    Route::get('/trending', [HashtagController::class, 'trending'])->name('hashtags.trending');

    // User Preferences
    Route::post('/preferences/theme', [UserPreferenceController::class, 'updateTheme'])->name('preferences.theme');
    Route::get('/preferences', [UserPreferenceController::class, 'getPreferences'])->name('preferences.get');
});


require __DIR__.'/auth.php';
